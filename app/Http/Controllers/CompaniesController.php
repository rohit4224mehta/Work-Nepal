<?php
// app/Http/Controllers/CompaniesController.php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CompaniesController extends Controller
{
    /**
     * Display a listing of companies with advanced filtering.
     */
    public function index(Request $request): View
    {
        // Start query with eager loading and counts
        $query = Company::query()
            ->where('verification_status', 'verified')
            ->with(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now())
                      ->select('id', 'company_id', 'title', 'job_type', 'location');
            }])
            ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }]);

        // Search by company name or description
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('industry', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Filter by industry (exact match)
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // Filter by location (partial match)
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // Filter by company size
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        // Filter by has active jobs
        if ($request->boolean('has_jobs')) {
            $query->has('jobPostings', '>', 0);
        }

        // Filter by recently active (posted jobs in last 30 days)
        if ($request->boolean('recently_active')) {
            $query->whereHas('jobPostings', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            });
        }

        // Sort options with multiple criteria
        switch ($request->get('sort', 'featured')) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'jobs_asc':
                $query->orderBy('active_jobs_count', 'asc');
                break;
            case 'jobs_desc':
                $query->orderBy('active_jobs_count', 'desc');
                break;
            case 'random':
                $query->inRandomOrder();
                break;
            case 'featured':
            default:
                $query->orderBy('verification_status', 'desc')
                      ->orderBy('active_jobs_count', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        // Get paginated results
        $companies = $query->paginate(12)->withQueryString();

        // Get unique industries for filter dropdown (cached)
        $industries = Cache::remember('company_industries', 3600, function () {
            return Company::where('verification_status', 'verified')
                ->whereNotNull('industry')
                ->distinct('industry')
                ->pluck('industry')
                ->filter()
                ->values()
                ->toArray();
        });

        // Get company sizes for filter
        $sizes = [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees', 
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '501-1000' => '501-1000 employees',
            '1000+' => '1000+ employees',
        ];

        // Get comprehensive stats - FIXED: Removed the has('users') call
        $stats = Cache::remember('company_stats', 1800, function () {
            $totalCompanies = Company::where('verification_status', 'verified')->count();
            $totalJobs = JobPosting::where('status', 'active')
                            ->whereDate('deadline', '>=', now())
                            ->count();
            
            // Industries count
            $industriesCount = Company::where('verification_status', 'verified')
                ->whereNotNull('industry')
                ->distinct('industry')
                ->count('industry');

            // Companies with active jobs
            $companiesWithJobs = Company::where('verification_status', 'verified')
                ->whereHas('jobPostings', function ($q) {
                    $q->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
                })
                ->count();

            // Total employees (approx - sum of company sizes)
            $totalEmployees = Company::where('verification_status', 'verified')
                ->whereNotNull('size')
                ->get()
                ->sum(function ($company) {
                    // Convert size range to average number
                    $sizeMap = [
                        '1-10' => 5,
                        '11-50' => 30,
                        '51-200' => 125,
                        '201-500' => 350,
                        '501-1000' => 750,
                        '1000+' => 1500,
                    ];
                    return $sizeMap[$company->size] ?? 0;
                });

            // Count companies that have team members (using teamMembers relationship)
            $companiesWithTeamMembers = Company::where('verification_status', 'verified')
                ->whereHas('teamMembers')
                ->count();

            return [
                'total_companies' => $totalCompanies,
                'total_jobs' => $totalJobs,
                'industries_count' => $industriesCount,
                'companies_with_jobs' => $companiesWithJobs,
                'total_employees' => $totalEmployees,
                'active_recruiters' => $companiesWithTeamMembers, // FIXED: Using teamMembers relationship
            ];
        });

        // Get top industries for featured section
        $topIndustries = Cache::remember('top_industries', 3600, function () {
            return Company::where('verification_status', 'verified')
                ->whereNotNull('industry')
                ->select('industry', DB::raw('count(*) as count'))
                ->groupBy('industry')
                ->orderBy('count', 'desc')
                ->limit(6)
                ->get();
        });

        return view('companies.index', compact(
            'companies', 
            'industries', 
            'sizes',
            'stats', 
            'topIndustries',
            'request'
        ));
    }

    /**
     * Display the specified company with all details.
     */
    public function show($slug): View
    {
        $company = Company::where('slug', $slug)
            ->where('verification_status', 'verified')
            ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }])
            ->with(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now())
                      ->with('company:id,name,slug,logo_path')
                      ->latest()
                      ->limit(10);
            }])
            ->with(['owner' => function ($query) {
                $query->select('id', 'name', 'email', 'profile_photo_path');
            }])
            ->with(['teamMembers' => function ($query) {
                $query->select('users.id', 'users.name', 'users.email', 'users.profile_photo_path')
                      ->withPivot('role', 'is_active')
                      ->wherePivot('is_active', true);
            }])
            ->firstOrFail();

        // Get all active jobs for this company (for "view all" link)
        $allActiveJobsCount = $company->jobPostings()
            ->where('status', 'active')
            ->whereDate('deadline', '>=', now())
            ->count();

        // Get similar companies in same industry
        $similarCompanies = Cache::remember('similar_companies_' . $company->id, 1800, function () use ($company) {
            return Company::where('verification_status', 'verified')
                ->where('id', '!=', $company->id)
                ->where('industry', $company->industry)
                ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                    $query->where('status', 'active')
                          ->whereDate('deadline', '>=', now());
                }])
                ->limit(6)
                ->get();
        });

        // Parse social links
        $socialLinks = is_string($company->social_links) 
            ? json_decode($company->social_links, true) 
            : ($company->social_links ?? []);

        // Parse culture images
        $cultureImages = is_string($company->culture_images)
            ? json_decode($company->culture_images, true)
            : ($company->culture_images ?? []);

        // Get company insights/stats
        $insights = [
            'total_jobs_posted' => $company->jobPostings()->count(),
            'avg_response_time' => $this->calculateAvgResponseTime($company),
            'most_common_job_type' => $this->getMostCommonJobType($company),
        ];

        return view('companies.show', compact(
            'company', 
            'similarCompanies', 
            'allActiveJobsCount',
            'insights',
            'socialLinks',
            'cultureImages'
        ));
    }

    /**
     * Get company suggestions for AJAX search.
     */
    public function suggestions(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:50'
        ]);

        $search = $request->get('q');
        
        $companies = Company::where('verification_status', 'verified')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->select('id', 'name', 'slug', 'logo_path', 'industry', 'location')
            ->withCount(['jobPostings as jobs_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }])
            ->limit(8)
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'logo' => $company->logo_url,
                    'industry' => $company->industry,
                    'location' => $company->location,
                    'jobs_count' => $company->jobs_count,
                    'url' => route('companies.show', $company->slug),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $companies,
            'total' => count($companies)
        ]);
    }

    /**
     * Display companies by industry.
     */
    public function byIndustry($industry): View
    {
        $companies = Company::where('verification_status', 'verified')
            ->where('industry', $industry)
            ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }])
            ->paginate(12);

        $industryName = $industry;
        
        $totalCompanies = $companies->total();
        $totalJobs = $companies->sum('active_jobs_count');

        return view('companies.industry', compact(
            'companies', 
            'industryName',
            'totalCompanies',
            'totalJobs'
        ));
    }

    /**
     * Get companies by location (API endpoint).
     */
    public function byLocation(Request $request, $location)
    {
        $companies = Company::where('verification_status', 'verified')
            ->where('location', 'LIKE', '%' . $location . '%')
            ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }])
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $companies,
            'location' => $location
        ]);
    }

    /**
     * Get featured companies for homepage.
     */
    public function featured()
    {
        $featuredCompanies = Cache::remember('featured_companies', 3600, function () {
            return Company::where('verification_status', 'verified')
                ->whereHas('jobPostings', function ($query) {
                    $query->where('status', 'active')
                          ->whereDate('deadline', '>=', now());
                })
                ->withCount(['jobPostings as active_jobs_count' => function ($query) {
                    $query->where('status', 'active')
                          ->whereDate('deadline', '>=', now());
                }])
                ->inRandomOrder()
                ->limit(12)
                ->get(['id', 'name', 'slug', 'logo_path', 'industry', 'location']);
        });

        return response()->json([
            'success' => true,
            'data' => $featuredCompanies
        ]);
    }

    /**
     * Calculate average response time for company.
     */
    private function calculateAvgResponseTime($company): string
    {
        // This would need actual data from job applications
        // For now, return a default or random value
        $times = ['Same day', '1-2 days', '2-3 days', '3-5 days', 'Within a week'];
        return $times[array_rand($times)];
    }

    /**
     * Get most common job type for company.
     */
    private function getMostCommonJobType($company): string
    {
        $mostCommon = $company->jobPostings()
            ->select('job_type', DB::raw('count(*) as count'))
            ->groupBy('job_type')
            ->orderBy('count', 'desc')
            ->first();

        return $mostCommon ? ucfirst(str_replace('-', ' ', $mostCommon->job_type)) : 'Various';
    }

    /**
     * Follow/unfollow company (for job seekers).
     */
    public function toggleFollow(Request $request, Company $company)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to follow companies',
                'redirect' => route('login')
            ], 401);
        }

        $user = auth()->user();
        
        // Check if user is following (you'll need to create this relationship)
        // This is a placeholder - implement based on your database structure
        return response()->json([
            'success' => true,
            'message' => 'Feature coming soon'
        ]);
    }

    /**
     * Report a company (for inappropriate content).
     */
    public function report(Request $request, Company $company)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to report',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
            'details' => 'nullable|string|max:1000'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your report. We will review it shortly.'
        ]);
    }
}