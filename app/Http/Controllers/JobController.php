<?php
// app/Http/Controllers/JobController.php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // ✅ ADD THIS LINE


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
            ->where(function($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->format('Y-m-d'));
            })
            ->with(['company' => function ($q) {
                $q->select('id', 'name', 'slug', 'logo_path', 'verification_status');
            }])
            ->withCount('applications');

        // Search by keyword (title, description, company name)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('company', function ($companyQuery) use ($searchTerm) {
                      $companyQuery->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by job type (multiple selection)
        if ($request->filled('job_type')) {
            $jobTypes = is_array($request->job_type) ? $request->job_type : [$request->job_type];
            $query->whereIn('job_type', $jobTypes);
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
        if ($request->filled('salary')) {
            $salary = $request->salary;
            $query->where(function($q) use ($salary) {
                if ($salary === '0-25000') {
                    $q->where('salary_range', 'LIKE', '%0%')
                      ->orWhere('salary_range', 'LIKE', '%up to%')
                      ->orWhere('salary_range', 'LIKE', '%less than%');
                } elseif ($salary === '25000-50000') {
                    $q->where('salary_range', 'LIKE', '%25000%')
                      ->orWhere('salary_range', 'LIKE', '%30000%')
                      ->orWhere('salary_range', 'LIKE', '%40000%');
                } elseif ($salary === '50000-100000') {
                    $q->where('salary_range', 'LIKE', '%50000%')
                      ->orWhere('salary_range', 'LIKE', '%60000%')
                      ->orWhere('salary_range', 'LIKE', '%70000%')
                      ->orWhere('salary_range', 'LIKE', '%80000%')
                      ->orWhere('salary_range', 'LIKE', '%90000%');
                } elseif ($salary === '100000+') {
                    $q->where('salary_range', 'LIKE', '%100000%')
                      ->orWhere('salary_range', 'LIKE', '%1,00,000%')
                      ->orWhere('salary_range', 'LIKE', '%lakh%');
                }
            });
        }

        // Filter by fresher friendly
        if ($request->boolean('fresher')) {
            $query->where('experience_level', 'entry');
        }

        // Filter by urgent hiring (deadline within 7 days)
        if ($request->boolean('urgent')) {
            $query->where('deadline', '<=', now()->addDays(7)->format('Y-m-d'))
                  ->where('deadline', '>=', now()->format('Y-m-d'));
        }

        // Filter by foreign jobs
        if ($request->boolean('foreign')) {
            $foreignKeywords = ['foreign', 'abroad', 'international', 'overseas', 'uae', 'qatar', 'saudi', 'dubai', 'malaysia', 'kuwait', 'oman', 'bahrain'];
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
                $query->latest('created_at');
                break;
            case 'oldest':
                $query->oldest('created_at');
                break;
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            case 'salary_desc':
                $query->orderBy('salary_range', 'desc');
                break;
            case 'salary_asc':
                $query->orderBy('salary_range', 'asc');
                break;
            default:
                $query->latest('created_at');
        }

        // Get jobs with pagination
        $jobs = $query->paginate(12)->withQueryString();

        // Get filter options for sidebar (cached for performance)
        $filterOptions = Cache::remember('job_filter_options', 3600, function () {
            // Get categories with counts
            $categories = JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereNotNull('category')
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->pluck('count', 'category')
                ->toArray();

            // Get locations with counts
            $locations = JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereNotNull('location')
                ->select('location', DB::raw('count(*) as count'))
                ->groupBy('location')
                ->orderBy('count', 'desc')
                ->limit(15)
                ->pluck('count', 'location')
                ->toArray();

            return [
                'categories' => $categories,
                'locations' => $locations,
                'job_types' => [
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'remote' => 'Remote',
                    'freelance' => 'Freelance',
                ],
                'experience_levels' => [
                    'entry' => 'Entry Level (0-1 years)',
                    'mid' => 'Mid Level (2-5 years)',
                    'senior' => 'Senior Level (5-8 years)',
                    'lead' => 'Lead / Manager (8+ years)',
                    'executive' => 'Executive / Director',
                ],
                'salary_ranges' => [
                    '0-25000' => 'Up to NPR 25,000',
                    '25000-50000' => 'NPR 25,000 - 50,000',
                    '50000-100000' => 'NPR 50,000 - 100,000',
                    '100000+' => 'NPR 100,000+',
                ],
            ];
        });

        // Get featured jobs for sidebar
        $featuredJobs = Cache::remember('featured_jobs_sidebar', 1800, function () {
            return JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_featured', true)
                ->where(function($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                })
                ->with('company')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // Get job market statistics
        $statistics = Cache::remember('job_market_statistics', 3600, function () {
            return [
                'total' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->count(),
                'new_this_week' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('created_at', '>=', now()->subWeek())
                    ->count(),
                'companies_hiring' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->distinct('company_id')
                    ->count('company_id'),
                'remote_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('job_type', 'remote')
                    ->count(),
            ];
        });

        return view('jobs.index', compact(
            'jobs', 
            'filterOptions', 
            'featuredJobs',
            'statistics'
        ));
    }

    /**
     * Display the specified job.
     */
    public function show($slug): View
    {
        $job = JobPosting::where('slug', $slug)
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->with(['company' => function ($q) {
                $q->withCount(['jobPostings' => function ($query) {
                    $query->where('status', 'active')
                          ->where('verification_status', 'verified')
                          ->where(function($q) {
                              $q->whereNull('deadline')
                                ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                          });
                }]);
            }])
            ->withCount('applications')
            ->firstOrFail();

        // Check if job is expired
        if ($job->deadline && $job->deadline < now()->format('Y-m-d')) {
            abort(404, 'This job posting has expired.');
        }

        // Increment view count (if you have a views column)
        // $job->increment('views');

        // Get similar jobs
        $similarJobs = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where('id', '!=', $job->id)
            ->where(function($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->format('Y-m-d'));
            })
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
    /**
     * Apply for a job (AJAX)
     */
    public function apply(Request $request, JobPosting $job)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to apply',
                'redirect' => route('login')
            ], 401);
        }

        $user = Auth::user();

        // ✅ NEW: Check if user is the company owner
        if ($job->company->owner_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot apply to jobs posted by your own company.'
            ], 403);
        }

        // ✅ NEW: Check if user is a team member of the company
        if ($user->companies()->where('company_id', $job->company->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot apply to jobs posted by your own company.'
            ], 403);
        }

        // Check if job is still active
        if ($job->status !== 'active' || $job->verification_status !== 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'This job is no longer available'
            ], 400);
        }

        // Check if job is expired
        if ($job->deadline && $job->deadline < now()->format('Y-m-d')) {
            return response()->json([
                'success' => false,
                'message' => 'Application deadline has passed'
            ], 400);
        }

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

        try {
            DB::beginTransaction();

            // Create application
            $application = $user->jobApplications()->create([
                'job_posting_id' => $job->id,
                'status' => 'applied',
                'applied_at' => now(),
            ]);

            DB::commit();

            // Log the activity
            Log::info('Job application submitted', [
                'user_id' => $user->id,
                'job_id' => $job->id,
                'job_title' => $job->title,
                'company_id' => $job->company->id,
                'company_name' => $job->company->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Job application failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'job_id' => $job->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application. Please try again.'
            ], 500);
        }
    }

/**
 * Save/unsave a job (AJAX)
 */
public function toggleSave(Request $request, JobPosting $job)
{
    // $job is already found by slug due to route model binding
    
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Please login to save jobs',
            'redirect' => route('login')
        ], 401);
    }

    $user = Auth::user();
    
    try {
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

    } catch (\Exception $e) {
        Log::error('Job save toggle failed: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to save job. Please try again.'
        ], 500);
    }
}


    public function report(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:job_postings,id',
            'reason' => 'required|in:spam,inappropriate,scam,expired,other',
            'description' => 'nullable|string|max:500'
        ]);
        
        try {
            $job = JobPosting::findOrFail($request->job_id);
            
            // Create report
            Report::create([
                'reporter_id' => auth()->id(),
                'reported_entity_type' => 'job',
                'reported_entity_id' => $job->id,
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => 'pending',
                'priority' => 'medium',
            ]);
            
            // Log the report
            Log::info('Job reported', [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'reporter_id' => auth()->id(),
                'reason' => $request->reason,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you for reporting. We will review this job.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Job report failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report. Please try again.'
            ], 500);
        }
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
            ->where(function($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->format('Y-m-d'));
            })
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
                    'type' => ucfirst(str_replace('-', ' ', $job->job_type)),
                    'url' => route('jobs.show', $job->slug),
                ];
            });

        return response()->json($jobs);
    }

    /**
     * Get filter options for the jobs page (AJAX)
     */
    public function getFilterOptions()
    {
        $filterOptions = Cache::remember('job_filter_options_api', 3600, function () {
            return [
                'categories' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->whereNotNull('category')
                    ->select('category', DB::raw('count(*) as count'))
                    ->groupBy('category')
                    ->orderBy('count', 'desc')
                    ->get(),
                'locations' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->whereNotNull('location')
                    ->select('location', DB::raw('count(*) as count'))
                    ->groupBy('location')
                    ->orderBy('count', 'desc')
                    ->limit(20)
                    ->get(),
            ];
        });

        return response()->json($filterOptions);
    }

    /**
     * Get job statistics (AJAX)
     */
    public function getStatistics()
    {
        $statistics = [
            'total' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->count(),
            'new_today' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereDate('created_at', now()->format('Y-m-d'))
                ->count(),
            'new_this_week' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('created_at', '>=', now()->subWeek())
                ->count(),
            'expiring_soon' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('deadline', '<=', now()->addDays(7))
                ->where('deadline', '>=', now()->format('Y-m-d'))
                ->count(),
            'companies_hiring' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->distinct('company_id')
                ->count('company_id'),
            'remote_jobs' => JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('job_type', 'remote')
                ->count(),
        ];

        return response()->json($statistics);
    }

    /**
     * Clear job cache (for admin use)
     */
    public function clearCache()
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        Cache::forget('job_filter_options');
        Cache::forget('featured_jobs_sidebar');
        Cache::forget('job_market_statistics');
        Cache::forget('job_filter_options_api');

        return back()->with('success', 'Job cache cleared successfully.');
    }

    /**
     * Export jobs to CSV (for admin use)
     */
    public function export(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $query = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->with('company');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        $jobs = $query->get();

        $filename = 'jobs_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');

        // Add headers
        fputcsv($handle, [
            'ID', 'Title', 'Company', 'Location', 'Job Type', 
            'Category', 'Experience Level', 'Salary Range', 
            'Deadline', 'Posted Date', 'Status'
        ]);

        // Add data
        foreach ($jobs as $job) {
            fputcsv($handle, [
                $job->id,
                $job->title,
                $job->company->name,
                $job->location,
                $job->job_type,
                $job->category,
                $job->experience_level,
                $job->salary_range,
                $job->deadline ? $job->deadline->format('Y-m-d') : 'No deadline',
                $job->created_at->format('Y-m-d'),
                $job->status,
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->withHeaders([
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
    }
}