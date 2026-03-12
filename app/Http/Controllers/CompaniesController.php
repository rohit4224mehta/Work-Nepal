<?php
// app/Http/Controllers/CompaniesController.php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompaniesController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request): View
    {
        $query = Company::query()
            ->where('verification_status', 'verified')
            ->withCount(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }]);

        // Search by company name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by industry
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // Sort options
        switch ($request->get('sort', 'featured')) {
            case 'newest':
                $query->latest();
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'jobs':
                $query->orderBy('job_postings_count', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('verification_status', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        $companies = $query->paginate(12)->withQueryString();

        // Get unique industries for filter dropdown
        $industries = Company::where('verification_status', 'verified')
            ->whereNotNull('industry')
            ->distinct('industry')
            ->pluck('industry')
            ->filter()
            ->values();

        // Get stats
        $stats = [
            'total_companies' => Company::where('verification_status', 'verified')->count(),
            'total_jobs' => JobPosting::where('status', 'active')
                            ->whereDate('deadline', '>=', now())
                            ->count(),
            'active_recruiters' => Company::where('verification_status', 'verified')
                                ->has('users')
                                ->count(),
        ];

        return view('companies.index', compact('companies', 'industries', 'stats', 'request'));
    }

    /**
     * Display the specified company.
     */
    public function show($slug): View
    {
        $company = Company::where('slug', $slug)
            ->where('verification_status', 'verified')
            ->withCount(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now());
            }])
            ->with(['jobPostings' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('deadline', '>=', now())
                      ->latest()
                      ->limit(5);
            }])
            ->firstOrFail();

        // Get similar companies in same industry
        $similarCompanies = Company::where('verification_status', 'verified')
            ->where('id', '!=', $company->id)
            ->where('industry', $company->industry)
            ->withCount('jobPostings')
            ->limit(4)
            ->get();

        return view('companies.show', compact('company', 'similarCompanies'));
    }

    /**
     * Get company suggestions for AJAX search.
     */
    public function suggestions(Request $request)
    {
        $search = $request->get('q');
        
        $companies = Company::where('verification_status', 'verified')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->select('id', 'name', 'slug', 'logo_path', 'industry', 'location')
            ->withCount('jobPostings')
            ->limit(10)
            ->get();

        return response()->json($companies);
    }

    /**
     * Display companies by industry.
     */
    public function byIndustry($industry): View
    {
        $companies = Company::where('verification_status', 'verified')
            ->where('industry', $industry)
            ->withCount('jobPostings')
            ->paginate(12);

        $industryName = $industry;

        return view('companies.industry', compact('companies', 'industryName'));
    }
}