<?php
// app/Http/Controllers/Employer/JobController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Company;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of jobs for the employer.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Get all companies the user has access to
        $companyIds = $user->accessibleCompanyIds();
        
        if (empty($companyIds)) {
            $jobs = collect();
        } else {
            $query = JobPosting::whereIn('company_id', $companyIds)
                ->with(['company', 'applications'])
                ->withCount('applications');
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('verification_status')) {
                $query->where('verification_status', $request->verification_status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }
            
            $jobs = $query->latest()->paginate(10);
        }
        
        $stats = $this->getJobStatistics($companyIds);
        
        return view('employer.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        
        $companies = $user->accessibleCompanies()
            ->where('verification_status', 'verified')
            ->get();

        if ($companies->isEmpty()) {
            return redirect()->route('employer.company.create')
                ->with('error', 'You need a verified company to post jobs. Please create and verify your company first.');
        }
        
        $maxJobs = config('settings.max_active_jobs_per_company', 20);
        $companiesWithLimit = [];
        
        foreach ($companies as $company) {
            $activeJobsCount = JobPosting::where('company_id', $company->id)
                ->where('status', 'active')
                ->count();
            
            if ($activeJobsCount >= $maxJobs) {
                $companiesWithLimit[] = $company->name;
            }
        }
        
        $selectedCompanyId = $request->get('company_id');
        
        return view('employer.jobs.create', compact('companies', 'companiesWithLimit', 'selectedCompanyId'));
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,internship,remote',
            'salary_range' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
            'skills' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $company = Company::findOrFail($validated['company_id']);

            // Check if user has access to this company
            if (!auth()->user()->canAccessCompany($company)) {
                return back()->with('error', 'You do not have permission to post jobs for this company.')
                    ->withInput();
            }
            
            // Check if company is verified
            if ($company->verification_status !== 'verified') {
                return back()->with('error', 'Your company must be verified before posting jobs. Please wait for admin approval.')
                    ->withInput();
            }
            
            // Check job posting limit
            $maxJobs = config('settings.max_active_jobs_per_company', 20);
            $activeJobsCount = JobPosting::where('company_id', $company->id)
                ->where('status', 'active')
                ->count();
                
            if ($activeJobsCount >= $maxJobs) {
                return back()->with('error', "You have reached the maximum limit of {$maxJobs} active jobs. Please close some jobs before posting new ones.")
                    ->withInput();
            }
            
            // Create slug
            $slug = Str::slug($validated['title']) . '-' . Str::random(6);
            
            // Prepare job data
            $jobData = [
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'],
                'company_id' => $company->id,
                'location' => $validated['location'],
                'job_type' => $validated['job_type'],
                'category' => $validated['category'],
                'experience_level' => $validated['experience_level'],
                'salary_range' => $validated['salary_range'] ?? null,
                'deadline' => $validated['deadline'],
                'status' => 'pending',
                'verification_status' => 'pending',
                'is_featured' => false,
                'job_source' => 'local',
            ];
            
            // Add benefits if provided
            if ($request->filled('benefits')) {
                $jobData['benefits'] = $request->benefits;
            }
            
            // Create job posting
            $job = JobPosting::create($jobData);
            
            // Handle skills if provided
            if ($request->filled('skills')) {
                $this->attachSkills($job, $request->skills);
            }
            
            DB::commit();
            
            // Simple logging without activity()
            Log::channel('stack')->info('New job posted', [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'user_id' => auth()->id(),
            ]);
            
            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job posted successfully! It will be visible to job seekers after admin approval.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the actual error for debugging
            Log::channel('stack')->error('Job posting failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->except('_token')
            ]);
            
            // Return user-friendly error message
            return back()
                ->with('error', 'Failed to post job: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a job.
     */
    public function edit(JobPosting $job): View
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to edit this job.');
        }
        
        if ($job->status === 'closed') {
            return redirect()
                ->route('employer.jobs.index')
                ->with('error', 'Cannot edit closed jobs.');
        }
        
        if ($job->deadline && $job->deadline < now()->format('Y-m-d')) {
            return redirect()
                ->route('employer.jobs.index')
                ->with('error', 'Cannot edit expired jobs.');
        }
        
        $companies = auth()->user()->accessibleCompanies()
            ->where('verification_status', 'verified')
            ->get();
        
        return view('employer.jobs.edit', compact('job', 'companies'));
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, JobPosting $job)
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to update this job.');
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,internship,remote',
            'salary_range' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
            'skills' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $newCompany = Company::findOrFail($validated['company_id']);
            
            if (!auth()->user()->canAccessCompany($newCompany)) {
                return back()->with('error', 'You do not have permission to move this job to another company.')
                    ->withInput();
            }
            
            if ($newCompany->verification_status !== 'verified') {
                return back()->with('error', 'Cannot move job to unverified company.')
                    ->withInput();
            }
            
            // Update slug if title changed
            if ($job->title !== $validated['title']) {
                $slug = Str::slug($validated['title']) . '-' . Str::random(6);
                $job->slug = $slug;
            }
            
            // Update job fields
            $job->title = $validated['title'];
            $job->description = $validated['description'];
            $job->company_id = $validated['company_id'];
            $job->location = $validated['location'];
            $job->job_type = $validated['job_type'];
            $job->category = $validated['category'];
            $job->experience_level = $validated['experience_level'];
            $job->salary_range = $validated['salary_range'] ?? null;
            $job->deadline = $validated['deadline'];
            $job->status = 'pending';
            $job->verification_status = 'pending';
            
            if ($request->filled('benefits')) {
                $job->benefits = $request->benefits;
            }
            
            $job->save();
            
            // Update skills if provided
            if ($request->filled('skills')) {
                $this->attachSkills($job, $request->skills);
            }
            
            DB::commit();

            Log::channel('stack')->info('Job updated', [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'user_id' => auth()->id(),
            ]);

            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job updated successfully and sent for re-approval.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('stack')->error('Job update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update job. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified job.
     */
    public function destroy(JobPosting $job)
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to delete this job.');
        }
        
        try {
            $jobTitle = $job->title;
            $job->delete();
            
            Log::channel('stack')->info('Job deleted', [
                'job_id' => $job->id,
                'job_title' => $jobTitle,
                'user_id' => auth()->id(),
            ]);
            
            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job deleted successfully.');
                
        } catch (\Exception $e) {
            Log::channel('stack')->error('Job deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete job. Please try again.');
        }
    }

    /**
     * Show applications for a specific job.
     */
    public function applications(JobPosting $job, Request $request): View
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to view applications for this job.');
        }
        
        $query = $job->applications()
            ->with(['applicant' => function($q) {
                $q->select('id', 'name', 'email', 'profile_photo_path', 'resume_path', 'headline');
            }]);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $applications = $query->latest()->paginate(15);
        
        $stats = [
            'total' => $job->applications()->count(),
            'applied' => $job->applications()->where('status', 'applied')->count(),
            'shortlisted' => $job->applications()->where('status', 'shortlisted')->count(),
            'rejected' => $job->applications()->where('status', 'rejected')->count(),
            'hired' => $job->applications()->where('status', 'hired')->count(),
        ];
        
        return view('employer.jobs.applications', compact('job', 'applications', 'stats'));
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, JobPosting $job, $applicationId)
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:applied,shortlisted,rejected,hired'
        ]);
        
        $application = $job->applications()->findOrFail($applicationId);
        $oldStatus = $application->status;
        
        $application->update([
            'status' => $request->status,
            'status_updated_at' => now(),
        ]);
        
        // Send notification to applicant
        if ($request->status === 'shortlisted') {
            NotificationService::applicationShortlisted($application);
        } elseif ($request->status === 'rejected') {
            NotificationService::applicationRejected($application);
        } elseif ($request->status === 'hired') {
            NotificationService::applicationHired($application);
        }
        
        Log::channel('stack')->info('Application status updated', [
            'application_id' => $application->id,
            'job_title' => $job->title,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Application status updated successfully.',
                'status' => $request->status
            ]);
        }
        
        return redirect()
            ->route('employer.jobs.applications', $job)
            ->with('success', 'Application status updated successfully.');
    }

    /**
     * Close a job posting
     */
    public function close(JobPosting $job)
    {
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to close this job.');
        }
        
        $job->update(['status' => 'closed']);
        
        Log::channel('stack')->info('Job closed', [
            'job_id' => $job->id,
            'job_title' => $job->title,
        ]);
        
        return redirect()
            ->route('employer.jobs.index')
            ->with('success', 'Job closed successfully.');
    }

    /**
     * Get job statistics for the employer
     */
    protected function getJobStatistics($companyIds)
    {
        if (empty($companyIds)) {
            return [
                'total' => 0,
                'active' => 0,
                'pending' => 0,
                'closed' => 0,
                'expired' => 0,
                'total_applications' => 0,
            ];
        }
        
        return [
            'total' => JobPosting::whereIn('company_id', $companyIds)->count(),
            'active' => JobPosting::whereIn('company_id', $companyIds)
                ->where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                })
                ->count(),
            'pending' => JobPosting::whereIn('company_id', $companyIds)
                ->where('status', 'pending')
                ->count(),
            'closed' => JobPosting::whereIn('company_id', $companyIds)
                ->where('status', 'closed')
                ->count(),
            'expired' => JobPosting::whereIn('company_id', $companyIds)
                ->whereNotNull('deadline')
                ->where('deadline', '<', now()->format('Y-m-d'))
                ->where('status', '!=', 'closed')
                ->count(),
            'total_applications' => JobPosting::whereIn('company_id', $companyIds)
                ->withCount('applications')
                ->get()
                ->sum('applications_count'),
        ];
    }

    /**
     * Attach skills to job posting
     */
    protected function attachSkills($job, $skillsString)
    {
        // Skip if no skills table or method doesn't exist
        if (!method_exists($job, 'skills')) {
            return $this;
        }
        
        $skillsArray = array_map('trim', explode(',', $skillsString));
        $skillIds = [];
        
        foreach ($skillsArray as $skillName) {
            if (empty($skillName)) continue;
            
            $skill = \App\Models\Skill::firstOrCreate(
                ['name' => $skillName],
                ['slug' => Str::slug($skillName)]
            );
            $skillIds[] = $skill->id;
        }
        
        if (!empty($skillIds)) {
            $job->skills()->sync($skillIds);
        }
        
        return $this;
    }
}