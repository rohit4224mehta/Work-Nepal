<?php
// app/Http/Controllers/JobController.php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    /**
     * Display a listing of jobs with advanced filtering
     */
    public function index(Request $request): View
    {
        $query = JobPosting::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereDate('deadline', '>=', now())
            ->with(['company' => function ($q) {
                $q->select('id', 'name', 'slug', 'logo_path', 'verification_status');
            }]);

        // Search by keyword (title, description, company name)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('company', function ($companyQuery) use ($searchTerm) {
                      $companyQuery->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }

        // Filter by experience level
        if ($request->filled('experience')) {
            $query->where('experience_level', $request->experience);
        }

        // Filter by salary range
        if ($request->filled('salary_min')) {
            // This is simplified - in production you'd need proper salary parsing
            $query->where('salary_range', 'LIKE', "%{$request->salary_min}%");
        }

        // Filter by fresher friendly
        if ($request->boolean('fresher')) {
            $query->where('experience_level', 'entry');
        }

        // Filter by foreign jobs
        if ($request->boolean('foreign')) {
            $foreignKeywords = ['foreign', 'abroad', 'international', 'overseas', 'uae', 'qatar', 'saudi', 'dubai', 'malaysia', 'kuwait'];
            $query->where(function ($q) use ($foreignKeywords) {
                foreach ($foreignKeywords as $keyword) {
                    $q->orWhere('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('location', 'LIKE', "%{$keyword}%");
                }
            });
        }

        // Sort options
        switch ($request->get('sort', 'latest')) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'salary_high':
                // Simplified - would need proper salary parsing
                $query->latest();
                break;
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            default:
                $query->latest();
        }

        // Get jobs with pagination
        $jobs = $query->paginate(12)->withQueryString();

        // Get filter options for sidebar (cached for performance)
        $filterOptions = Cache::remember('job_filter_options', 3600, function () {
            return [
                'categories' => JobPosting::where('status', 'active')
                    ->whereNotNull('category')
                    ->distinct('category')
                    ->pluck('category')
                    ->filter()
                    ->values()
                    ->mapWithKeys(function ($item) {
                        return [$item => JobPosting::where('category', $item)->count()];
                    }),
                'job_types' => [
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'remote' => 'Remote',
                    'freelance' => 'Freelance',
                ],
                'experience_levels' => [
                    'entry' => 'Entry Level',
                    'mid' => 'Mid Level',
                    'senior' => 'Senior Level',
                    'lead' => 'Lead / Manager',
                    'executive' => 'Executive',
                ],
                'locations' => JobPosting::where('status', 'active')
                    ->whereNotNull('location')
                    ->distinct('location')
                    ->pluck('location')
                    ->filter()
                    ->values()
                    ->take(10),
            ];
        });

        // Get featured jobs for sidebar
        $featuredJobs = Cache::remember('featured_jobs_sidebar', 1800, function () {
            return JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereDate('deadline', '>=', now())
                ->with('company')
                ->inRandomOrder()
                ->limit(5)
                ->get();
        });

        return view('jobs.index', compact(
            'jobs', 
            'filterOptions', 
            'featuredJobs',
            'request'
        ));
    }

    /**
     * Display the specified job.
     */
    public function show(JobPosting $job): View
    {
        // Check if job is active and verified
        if ($job->status !== 'active' || $job->verification_status !== 'verified') {
            abort(404);
        }

        // Load relationships
        $job->load(['company' => function ($q) {
            $q->withCount(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }]);
        }]);

        // Get similar jobs
        $similarJobs = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('id', '!=', $job->id)
            ->whereDate('deadline', '>=', now())
            ->where(function ($q) use ($job) {
                $q->where('category', $job->category)
                  ->orWhere('job_type', $job->job_type)
                  ->orWhere('experience_level', $job->experience_level);
            })
            ->with('company')
            ->latest()
            ->limit(4)
            ->get();

        // Check if user has already applied
        $hasApplied = false;
        if (auth()->check()) {
            $hasApplied = auth()->user()->jobApplications()
                ->where('job_posting_id', $job->id)
                ->exists();
        }

        // Check if job is saved by user
        $isSaved = false;
        if (auth()->check()) {
            $isSaved = auth()->user()->savedJobs()
                ->where('job_posting_id', $job->id)
                ->exists();
        }

        return view('jobs.show', compact('job', 'similarJobs', 'hasApplied', 'isSaved'));
    }

    /**
     * Quick apply to job (AJAX)
     */
    public function quickApply(Request $request, JobPosting $job)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to apply',
                'redirect' => route('login')
            ], 401);
        }

        $user = auth()->user();

        // Check if already applied
        if ($user->jobApplications()->where('job_posting_id', $job->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied to this job'
            ], 400);
        }

        // Check if user has resume
        if (!$user->resume_path) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload your resume first',
                'redirect' => route('profile.edit') . '#resume'
            ], 400);
        }

        // Create application
        $application = $user->jobApplications()->create([
            'job_posting_id' => $job->id,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        // Send notification to employer
        // $job->company->notify(new NewApplicationNotification($application));

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully!'
        ]);
    }

    /**
     * Save/unsave job (AJAX)
     */
    public function toggleSave(JobPosting $job)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to save jobs',
                'redirect' => route('login')
            ], 401);
        }

        $user = auth()->user();
        
        if ($user->savedJobs()->where('job_posting_id', $job->id)->exists()) {
            $user->savedJobs()->detach($job->id);
            $saved = false;
            $message = 'Job removed from saved';
        } else {
            $user->savedJobs()->attach($job->id);
            $saved = true;
            $message = 'Job saved successfully';
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
            'message' => $message
        ]);
    }

    /**
     * Get job suggestions for search (AJAX)
     */
    public function suggestions(Request $request)
    {
        $search = $request->get('q');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $jobs = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereDate('deadline', '>=', now())
            ->where('title', 'LIKE', "%{$search}%")
            ->with('company:id,name,slug')
            ->select('id', 'title', 'slug', 'company_id', 'location', 'job_type')
            ->limit(10)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'company' => $job->company->name,
                    'location' => $job->location,
                    'type' => $job->job_type,
                    'url' => route('jobs.show', $job->slug),
                ];
            });

        return response()->json($jobs);
    }
}