<?php
// app/Http/Controllers/Employer/JobController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Display a listing of jobs.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get all companies the user has access to
        $companyIds = $user->accessibleCompanyIds();
        
        $jobs = JobPosting::whereIn('company_id', $companyIds)
            ->with(['company', 'applications'])
            ->withCount('applications')
            ->latest()
            ->paginate(10);
        
        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     */
    public function create(): View
    {
        $user = auth()->user();
        $companies = $user->accessibleCompanies()->get();

        if ($companies->isEmpty()) {
            return redirect()->route('employer.company.create')
                ->with('error', 'You need to create a company first.');
        }

        return view('employer.jobs.create', compact('companies'));
    }

    /**
     * Store a newly created job.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,internship,remote',
            'salary_range' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
        ]);

        $company = Company::findOrFail($request->company_id);

        // Check permission - FIXED: using canAccessCompany() instead of canManageCompany()
        if (!auth()->user()->canAccessCompany($company)) {
            abort(403, 'You cannot post jobs for this company');
        }

        $job = JobPosting::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title . '-' . uniqid()),
            'description' => $request->description,
            'company_id' => $company->id,
            'posted_by' => auth()->id(),
            'location' => $request->location,
            'job_type' => $request->job_type,
            'salary_range' => $request->salary_range,
            'category' => $request->category,
            'experience_level' => $request->experience_level,
            'deadline' => $request->deadline,
            'status' => 'active',
            'verification_status' => 'pending',
        ]);

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job posted successfully and pending verification.');
    }

    /**
     * Show the form for editing a job.
     */
    public function edit(JobPosting $job)
    {
        // Check permission - FIXED: using canAccessCompany()
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403);
        }
        
        $companies = auth()->user()->accessibleCompanies()->get();
        
        return view('employer.jobs.edit', compact('job', 'companies'));
    }

    /**
     * Update the specified job.
     */
    public function update(Request $request, JobPosting $job)
    {
        // Check permission - FIXED: using canAccessCompany()
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,internship,remote',
            'salary_range' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'experience_level' => 'required|string|max:100',
            'deadline' => 'required|date|after:today',
        ]);

        $job->update($request->all());

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job.
     */
    public function destroy(JobPosting $job)
    {
        // Check permission - FIXED: using canAccessCompany()
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    /**
     * Show applications for a specific job.
     */
    public function applications(JobPosting $job)
    {
        // Check permission - FIXED: using canAccessCompany()
        if (!auth()->user()->canAccessCompany($job->company)) {
            abort(403);
        }

        $applications = $job->applications()
            ->with('applicant')
            ->latest()
            ->paginate(15);

        return view('employer.jobs.applications', compact('job', 'applications'));
    }
}