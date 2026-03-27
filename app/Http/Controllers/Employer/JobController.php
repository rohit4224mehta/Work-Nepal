<?php
// app/Http/Controllers/Employer/JobController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $jobs = collect(); // Return empty collection if no companies
        } else {
            $query = JobPosting::whereIn('company_id', $companyIds)
                ->with(['company', 'applications'])
                ->withCount('applications');
            
            // Apply status filter if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Apply verification filter if provided
            if ($request->filled('verification_status')) {
                $query->where('verification_status', $request->verification_status);
            }
            
            // Apply search filter
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
        
        // Get statistics for dashboard
        $stats = $this->getJobStatistics($companyIds);
        
        return view('employer.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        
        // Get companies the user has access to
        $companies = $user->accessibleCompanies()
            ->where('verification_status', 'verified')
            ->get();

        if ($companies->isEmpty()) {
            return redirect()->route('employer.company.create')
                ->with('error', 'You need a verified company to post jobs. Please create and verify your company first.');
        }
        
        // Check if any company has reached job limit
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
                abort(403, 'You do not have permission to post jobs for this company.');
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
            
            // Create job posting
            $job = JobPosting::create([
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
            ]);
            
            // Store skills if provided (if you have a skills table)
            if ($request->filled('skills')) {
                $this->attachSkills($job, $request->skills);
            }
            
            // Store benefits if provided
            if ($request->filled('benefits')) {
                $job->update(['benefits' => $request->benefits]);
            }
            
            DB::commit();
            
            // Log the activity
            activity()
                ->performedOn($job)
                ->causedBy(auth()->user())
                ->withProperties([
                    'company' => $company->name,
                    'title' => $job->title,
                    'ip' => request()->ip(),
                ])
                ->log('Job posted');
            
            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job posted successfully! It will be visible to job seekers after admin approval.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job posting failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to post job. Please try again.')->withInput();
        }
    }

    /**
     * Show the form for editing a job.
     */
    public function edit(JobPosting $job): View
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to edit this job.');
        }
        
        // Check if job can be edited (not closed or expired)
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
        // Check if user has access to this job's company
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
            
            // Check if user has access to the new company
            if (!auth()->user()->canAccessCompany($newCompany)) {
                abort(403, 'You do not have permission to move this job to another company.');
            }
            
            // Check if new company is verified
            if ($newCompany->verification_status !== 'verified') {
                return back()->with('error', 'Cannot move job to unverified company.')->withInput();
            }
            
            // Update slug if title changed
            $slug = $job->title !== $validated['title'] 
                ? Str::slug($validated['title']) . '-' . Str::random(6)
                : $job->slug;
            
            // Update job
            $job->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'],
                'company_id' => $validated['company_id'],
                'location' => $validated['location'],
                'job_type' => $validated['job_type'],
                'category' => $validated['category'],
                'experience_level' => $validated['experience_level'],
                'salary_range' => $validated['salary_range'] ?? null,
                'deadline' => $validated['deadline'],
                // Reset status to pending for re-approval if significant changes
                'status' => 'pending',
                'verification_status' => 'pending',
            ]);
            
            // Update skills if provided
            if ($request->filled('skills')) {
                $this->attachSkills($job, $request->skills);
            }
            
            // Update benefits if provided
            if ($request->filled('benefits')) {
                $job->update(['benefits' => $request->benefits]);
            }
            
            DB::commit();

            // REMOVE OR COMMENT OUT THE ACTIVITY LOGGING
            // activity()
            //     ->performedOn($job)
            //     ->causedBy(auth()->user())
            //     ->withProperties([
            //         'changes' => array_keys($validated),
            //         'ip' => request()->ip(),
            //     ])
            //     ->log('Job updated');

            // Instead, use Laravel's built-in logging if needed
            Log::info('Job updated', [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'user_id' => auth()->id(),
                'company_id' => $job->company_id,
                'changes' => array_keys($validated),
                'ip' => request()->ip(),
            ]);

            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job updated successfully and sent for re-approval.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job update failed: ' . $e->getMessage(), [
                'job_id' => $job->id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update job. Please try again.')->withInput();
        }
    }

    /**
     * Remove the specified job.
     */
    public function destroy(JobPosting $job)
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to delete this job.');
        }
        
        try {
            $jobTitle = $job->title;
            $job->delete();
            
            // Log the activity
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'job_title' => $jobTitle,
                    'company' => $job->company->name,
                    'ip' => request()->ip(),
                ])
                ->log('Job deleted');
            
            return redirect()
                ->route('employer.jobs.index')
                ->with('success', 'Job deleted successfully.');
                
        } catch (\Exception $e) {
            Log::error('Job deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete job. Please try again.');
        }
    }

    /**
     * Show applications for a specific job.
     */
    public function applications(JobPosting $job, Request $request): View
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to view applications for this job.');
        }
        
        $query = $job->applications()
            ->with(['applicant' => function($q) {
                $q->select('id', 'name', 'email', 'profile_photo_path', 'resume_path', 'headline');
            }])
            ->withCount('applicant');
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $applications = $query->latest()->paginate(15);
        
        // Get statistics for applications
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
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:applied,shortlisted,rejected,hired'
        ]);
        
        $application = $job->applications()->findOrFail($applicationId);
        $application->update([
            'status' => $request->status,
            'status_updated_at' => now(),
        ]);
        
        // Log the activity
        activity()
            ->performedOn($application)
            ->causedBy(auth()->user())
            ->withProperties([
                'job_title' => $job->title,
                'applicant' => $application->applicant->name,
                'old_status' => $application->getOriginal('status'),
                'new_status' => $request->status,
            ])
            ->log('Application status updated');
        
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
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to close this job.');
        }
        
        $job->update([
            'status' => 'closed',
        ]);
        
        // Log the activity
        activity()
            ->performedOn($job)
            ->causedBy(auth()->user())
            ->withProperties([
                'job_title' => $job->title,
                'company' => $job->company->name,
            ])
            ->log('Job closed');
        
        return redirect()
            ->route('employer.jobs.index')
            ->with('success', 'Job closed successfully. No new applications will be accepted.');
    }

    /**
     * Reopen a closed job posting
     */
    public function reopen(JobPosting $job)
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to reopen this job.');
        }
        
        // Check if job is expired
        if ($job->deadline && $job->deadline < now()->format('Y-m-d')) {
            return redirect()
                ->route('employer.jobs.index')
                ->with('error', 'Cannot reopen expired jobs. Please extend the deadline first.');
        }
        
        $job->update([
            'status' => 'active',
            'verification_status' => 'pending', // Needs re-approval
        ]);
        
        // Log the activity
        activity()
            ->performedOn($job)
            ->causedBy(auth()->user())
            ->log('Job reopened');
        
        return redirect()
            ->route('employer.jobs.index')
            ->with('success', 'Job reopened and sent for re-approval.');
    }

    /**
     * Extend job deadline
     */
    public function extendDeadline(Request $request, JobPosting $job)
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to extend this job.');
        }
        
        $request->validate([
            'new_deadline' => 'required|date|after:' . now()->format('Y-m-d')
        ]);
        
        $job->update([
            'deadline' => $request->new_deadline,
        ]);
        
        return redirect()
            ->route('employer.jobs.applications', $job)
            ->with('success', 'Job deadline extended successfully.');
    }

    /**
     * Duplicate a job posting
     */
    public function duplicate(JobPosting $job)
    {
        // Check if user has access to this job's company
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403, 'You do not have permission to duplicate this job.');
        }
        
        $newJob = $job->replicate();
        $newJob->title = $job->title . ' (Copy)';
        $newJob->slug = Str::slug($job->title . '-copy-' . uniqid());
        $newJob->status = 'pending';
        $newJob->verification_status = 'pending';
        $newJob->created_at = now();
        $newJob->updated_at = now();
        $newJob->save();
        
        return redirect()
            ->route('employer.jobs.edit', $newJob)
            ->with('success', 'Job duplicated successfully. You can now edit the copy.');
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
        
        $stats = [
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
        
        return $stats;
    }

    /**
     * Attach skills to job posting
     */
    protected function attachSkills($job, $skillsString)
    {
        // Split skills by comma
        $skillsArray = array_map('trim', explode(',', $skillsString));
        
        // If you have a skills table, you can sync here
        // This is a placeholder - implement based on your skills table structure
        if (method_exists($job, 'skills')) {
            $skillIds = [];
            foreach ($skillsArray as $skillName) {
                $skill = \App\Models\Skill::firstOrCreate(
                    ['name' => $skillName],
                    ['slug' => Str::slug($skillName)]
                );
                $skillIds[] = $skill->id;
            }
            $job->skills()->sync($skillIds);
        }
        
        return $this;
    }
}