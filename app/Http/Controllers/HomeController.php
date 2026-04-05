<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Company;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // ========== DYNAMIC STATISTICS FROM DATABASE ==========
        $stats = Cache::remember('homepage_stats', 3600, function () {
            
            // Foreign jobs keywords
            $foreignKeywords = ['foreign', 'abroad', 'international', 'overseas', 'uae', 'qatar', 'saudi', 'dubai', 'malaysia', 'kuwait', 'oman', 'bahrain'];
            
            $foreignJobsQuery = JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where(function($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                });
            
            // Add keyword search for foreign jobs
            $foreignJobsQuery->where(function($q) use ($foreignKeywords) {
                foreach ($foreignKeywords as $keyword) {
                    $q->orWhere('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('location', 'LIKE', "%{$keyword}%");
                }
            });
            
            return [
                'active_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
                'fresher_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('experience_level', 'entry')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
                'foreign_jobs' => $foreignJobsQuery->count(),
                'companies_count' => Company::where('verification_status', 'verified')->count(),
                'total_users' => User::where('account_status', 'active')->count(),
                'freshers_hired' => JobApplication::where('status', 'hired')
                    ->whereHas('jobPosting', function($q) {
                        $q->where('experience_level', 'entry');
                    })
                    ->count(),
                // Additional stats for better insights
                'jobs_added_this_month' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->count(),
                'jobs_added_this_week' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('created_at', '>=', now()->startOfWeek())
                    ->count(),
                'remote_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('job_type', 'remote')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
                'part_time_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('job_type', 'part-time')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
                'contract_jobs' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('job_type', 'contract')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
                'internships' => JobPosting::where('status', 'active')
                    ->where('verification_status', 'verified')
                    ->where('job_type', 'internship')
                    ->where(function($q) {
                        $q->whereNull('deadline')
                          ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                    })
                    ->count(),
            ];
        });

        // ========== DYNAMIC FEATURED JOBS ==========
        $featuredJobs = Cache::remember('featured_jobs', 1800, function () {
            return JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->where('is_featured', true)
                ->where(function($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                })
                ->with(['company'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        });

        // ========== DYNAMIC CATEGORIES WITH SMART ICON ASSIGNMENT ==========
        $categories = Cache::remember('homepage_categories', 86400, function () {
            // Get all categories with counts from database
            $categoryCounts = JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereNotNull('category')
                ->select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get()
                ->toArray();

            // Define icon mapping based on category name patterns
            $iconMap = [
                'IT' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'blue'],
                'Software' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'blue'],
                'Developer' => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'blue'],
                'Marketing' => ['icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055zM20.488 9H15V3.512A9.025 9.025 0 0120.488 9z', 'color' => 'green'],
                'Finance' => ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'purple'],
                'Sales' => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'yellow'],
                'HR' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'pink'],
                'Human Resources' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'pink'],
                'Engineering' => ['icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.414 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'color' => 'orange'],
                'Healthcare' => ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'color' => 'red'],
                'Education' => ['icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'color' => 'indigo'],
                'Hospitality' => ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'amber'],
                'Construction' => ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'stone'],
                'Design' => ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'teal'],
            ];

            $colorClasses = [
                'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                'pink' => ['bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
                'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
                'stone' => ['bg' => 'bg-stone-100', 'text' => 'text-stone-600'],
                'teal' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-600'],
            ];

            $categories = [];
            foreach ($categoryCounts as $categoryData) {
                $categoryName = $categoryData['category'];
                $count = $categoryData['count'];
                
                // Determine icon and color based on category name
                $matched = false;
                foreach ($iconMap as $keyword => $config) {
                    if (stripos($categoryName, $keyword) !== false) {
                        $color = $colorClasses[$config['color']];
                        $iconPath = $config['icon'];
                        $matched = true;
                        break;
                    }
                }
                
                // Default if no match found
                if (!$matched) {
                    $color = $colorClasses['blue'];
                    $iconPath = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
                }
                
                $categories[] = [
                    'name' => $categoryName,
                    'slug' => strtolower(str_replace([' ', '&', '/'], '-', $categoryName)),
                    'count' => $count,
                    'bg_color' => $color['bg'],
                    'icon_color' => $color['text'],
                    'icon_path' => $iconPath,
                ];
            }
            
            // Sort by count descending
            usort($categories, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            return $categories;
        });

        // ========== DYNAMIC LOCATIONS WITH JOB COUNTS ==========
        $locations = Cache::remember('homepage_locations', 86400, function () {
            return JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereNotNull('location')
                ->select('location', DB::raw('count(*) as count'))
                ->groupBy('location')
                ->orderBy('count', 'desc')
                ->limit(12)
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->location,
                        'count' => $item->count,
                    ];
                })
                ->toArray();
        });

        // ========== DYNAMIC POPULAR SEARCHES ==========
        $popularSearches = Cache::remember('popular_searches', 86400, function () {
            $commonTitles = JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->select('title', DB::raw('count(*) as count'))
                ->groupBy('title')
                ->orderBy('count', 'desc')
                ->limit(8)
                ->pluck('title')
                ->toArray();
            
            if (empty($commonTitles)) {
                return [
                    'Laravel Developer',
                    'React JS',
                    'Digital Marketing',
                    'Accountant',
                    'Sales Executive',
                    'Foreign Job',
                    'Remote Work',
                    'Part Time'
                ];
            }
            
            return array_slice($commonTitles, 0, 8);
        });

        // ========== DYNAMIC FEATURES (Can be made dynamic from DB if needed) ==========
        $features = [
            [
                'title' => 'Verified Jobs',
                'description' => 'Every job posting is manually reviewed to ensure authenticity',
                'bg_color' => 'bg-red-100',
                'icon_color' => 'text-red-600',
                'icon_path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
            ],
            [
                'title' => 'Fresher Friendly',
                'description' => 'Special filters and badges for entry-level positions',
                'bg_color' => 'bg-blue-100',
                'icon_color' => 'text-blue-600',
                'icon_path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
            ],
            [
                'title' => 'Foreign Jobs',
                'description' => 'Verified overseas opportunities with safety guidelines',
                'bg_color' => 'bg-green-100',
                'icon_color' => 'text-green-600',
                'icon_path' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            [
                'title' => 'Quick Apply',
                'description' => 'Apply to jobs with your saved profile in one click',
                'bg_color' => 'bg-purple-100',
                'icon_color' => 'text-purple-600',
                'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            [
                'title' => 'Smart Alerts',
                'description' => 'Get instant job alerts matching your profile',
                'bg_color' => 'bg-yellow-100',
                'icon_color' => 'text-yellow-600',
                'icon_path' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
            ],
            [
                'title' => 'Career Resources',
                'description' => 'CV tips, interview guides, and career advice',
                'bg_color' => 'bg-orange-100',
                'icon_color' => 'text-orange-600',
                'icon_path' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
            ],
        ];

        // ========== DYNAMIC FEATURED EMPLOYERS ==========
        $featuredEmployers = Cache::remember('featured_employers', 3600, function () {
            return Company::where('verification_status', 'verified')
                ->withCount(['jobPostings' => function($q) {
                    $q->where('status', 'active')
                      ->where('verification_status', 'verified')
                      ->where(function($q2) {
                          $q2->whereNull('deadline')
                            ->orWhere('deadline', '>=', now()->format('Y-m-d'));
                      });
                }])
                ->having('job_postings_count', '>', 0)
                ->orderBy('job_postings_count', 'desc')
                ->limit(12)
                ->get();
        });

        // ========== DYNAMIC TESTIMONIALS ==========
        $testimonials = Cache::remember('testimonials_home', 21600, function () {
            $testimonials = Testimonial::with('user')
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            if ($testimonials->isEmpty()) {
                return collect([
                    (object)[
                        'content' => 'WorkNepal helped me find my dream job as a Laravel Developer. The platform is easy to use and all jobs are verified!',
                        'user' => (object)[
                            'name' => 'Rahul Sharma',
                            'headline' => 'Software Engineer',
                        ],
                        'rating' => 5,
                    ],
                    (object)[
                        'content' => 'As a fresher, I was struggling to find genuine opportunities. WorkNepal made it easy with their fresher-friendly filters.',
                        'user' => (object)[
                            'name' => 'Priya Singh',
                            'headline' => 'Marketing Specialist',
                        ],
                        'rating' => 5,
                    ],
                    (object)[
                        'content' => 'The foreign job safety guide was very helpful. I found a legitimate job in UAE through this platform.',
                        'user' => (object)[
                            'name' => 'Suresh KC',
                            'headline' => 'Construction Supervisor',
                        ],
                        'rating' => 4,
                    ],
                ]);
            }
            
            return $testimonials;
        });

        // ========== DYNAMIC JOB TYPES STATS ==========
        $jobTypeStats = [
            'remote' => $stats['remote_jobs'] ?? 0,
            'part_time' => $stats['part_time_jobs'] ?? 0,
            'contract' => $stats['contract_jobs'] ?? 0,
            'internship' => $stats['internships'] ?? 0,
        ];

        // Return view with all dynamic data
        return view('home.index', compact(
            'stats',
            'featuredJobs',
            'categories',
            'locations',
            'features',
            'featuredEmployers',
            'testimonials',
            'popularSearches',
            'jobTypeStats'
        ));
    }
}