<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\Company;
use App\Models\User;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Cache statistics for 1 hour to reduce database load
        $stats = Cache::remember('homepage_stats', 3600, function () {
            
            // Alternative approach for foreign jobs - check title or description for foreign keywords
            $foreignKeywords = ['foreign', 'abroad', 'international', 'overseas', 'uae', 'qatar', 'saudi', 'dubai', 'malaysia', 'kuwait', 'oman', 'bahrain'];
            
            $foreignJobsQuery = JobPosting::where('status', 'active')
                ->whereDate('deadline', '>=', now());
            
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
                    ->whereDate('deadline', '>=', now())
                    ->count(),
                'fresher_jobs' => JobPosting::where('status', 'active')
                    ->where('experience_level', 'entry')
                    ->whereDate('deadline', '>=', now())
                    ->count(),
                'foreign_jobs' => $foreignJobsQuery->count(),
                'companies_count' => Company::where('verification_status', 'verified')->count(),
                'total_users' => User::where('account_status', 'active')->count(),
                'freshers_hired' => JobApplication::where('status', 'hired')->count(),
            ];
        });

        // Featured Jobs
        $featuredJobs = Cache::remember('featured_jobs', 1800, function () {
            return JobPosting::where('status', 'active')
                ->where('verification_status', 'verified')
                ->whereDate('deadline', '>=', now())
                ->with('company')
                ->latest()
                ->limit(6)
                ->get();
        });

        // Job Categories with counts
        $categories = [
            [
                'name' => 'IT & Software', 
                'slug' => 'it-software', 
                'count' => JobPosting::where('category', 'IT & Software')->count(), 
                'bg_color' => 'bg-blue-100', 
                'icon_color' => 'text-blue-600', 
                'icon_path' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'
            ],
            [
                'name' => 'Marketing', 
                'slug' => 'marketing', 
                'count' => JobPosting::where('category', 'Marketing')->count(), 
                'bg_color' => 'bg-green-100', 
                'icon_color' => 'text-green-600', 
                'icon_path' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z'
            ],
            [
                'name' => 'Finance', 
                'slug' => 'finance', 
                'count' => JobPosting::where('category', 'Finance')->count(), 
                'bg_color' => 'bg-purple-100', 
                'icon_color' => 'text-purple-600', 
                'icon_path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            [
                'name' => 'Sales', 
                'slug' => 'sales', 
                'count' => JobPosting::where('category', 'Sales')->count(), 
                'bg_color' => 'bg-yellow-100', 
                'icon_color' => 'text-yellow-600', 
                'icon_path' => 'M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207'
            ],
            [
                'name' => 'Design', 
                'slug' => 'design', 
                'count' => JobPosting::where('category', 'Design')->count(), 
                'bg_color' => 'bg-pink-100', 
                'icon_color' => 'text-pink-600', 
                'icon_path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
            ],
            [
                'name' => 'HR', 
                'slug' => 'hr', 
                'count' => JobPosting::where('category', 'Human Resources')->count(), 
                'bg_color' => 'bg-indigo-100', 
                'icon_color' => 'text-indigo-600', 
                'icon_path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
            ],
        ];

        // Locations with job counts
        $locations = [
            ['name' => 'Kathmandu', 'count' => JobPosting::where('location', 'like', '%Kathmandu%')->count()],
            ['name' => 'Pokhara', 'count' => JobPosting::where('location', 'like', '%Pokhara%')->count()],
            ['name' => 'Lalitpur', 'count' => JobPosting::where('location', 'like', '%Lalitpur%')->count()],
            ['name' => 'Bhaktapur', 'count' => JobPosting::where('location', 'like', '%Bhaktapur%')->count()],
            ['name' => 'Biratnagar', 'count' => JobPosting::where('location', 'like', '%Biratnagar%')->count()],
            ['name' => 'Remote', 'count' => JobPosting::where('location', 'like', '%Remote%')->count()],
            ['name' => 'Butwal', 'count' => JobPosting::where('location', 'like', '%Butwal%')->count()],
            ['name' => 'Nepalgunj', 'count' => JobPosting::where('location', 'like', '%Nepalgunj%')->count()],
        ];

        // Features
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
        ];

        // Featured Employers
        $featuredEmployers = Cache::remember('featured_employers', 1800, function () {
            return Company::where('verification_status', 'verified')
                ->withCount(['jobPostings' => function($q) {
                    $q->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
                }])
                ->having('job_postings_count', '>', 0)
                ->orderBy('job_postings_count', 'desc')
                ->limit(12)
                ->get();
        });

        // Testimonials - Using dummy data for now
        $testimonials = collect([
            (object)[
                'content' => 'WorkNepal helped me find my dream job as a Laravel Developer. The platform is easy to use and all jobs are verified!',
                'user' => (object)[
                    'name' => 'Rahul Sharma',
                    'headline' => 'Software Engineer at Tech Company',
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

        // Popular Searches
        $popularSearches = [
            'Laravel Developer',
            'React JS',
            'Digital Marketing',
            'Accountant',
            'Sales Executive',
            'Foreign Job',
            'Remote Work',
            'Part Time'
        ];

        // FIXED: Return the correct view path
        // If your view is at resources/views/home/index.blade.php
        return view('home.index', compact(
            'stats',
            'featuredJobs',
            'categories',
            'locations',
            'features',
            'featuredEmployers',
            'testimonials',
            'popularSearches'
        ));
    }
}