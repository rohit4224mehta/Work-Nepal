<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display the public profile page of a company
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        // For MVP / development: dummy data
        // In real version → Company::where('slug', $slug)->firstOrFail()
        $company = (object) [
            'name'              => 'Tech Innovate Nepal Pvt. Ltd.',
            'slug'              => $slug,
            'logo'              => null, // later: asset('storage/logos/' . $this->logo_path)
            'description'       => 'Leading software development company in Kathmandu specializing in web & mobile applications for Nepali and international clients.',
            'industry'          => 'Information Technology',
            'location'          => 'Kathmandu, Nepal',
            'website'           => 'https://techinnovate.com.np',
            'verified'          => true,
            'founded_year'      => 2018,
            'employees'         => '51-200',
            'rating'            => 4.7,
            'jobs_count'        => 12,
            'verification_status' => 'verified',
            'jobs'              => collect([
            (object) [
                'title' => 'Senior Laravel Developer',
                'location' => 'Kathmandu',
                'type' => 'Full Time',
                'salary' => '80,000 - 150,000',
                'posted' => '2 days ago',
                'fresher_friendly' => false,
            ],
            (object) [
                'title' => 'Junior UI/UX Designer',
                'location' => 'Pokhara',
                'type' => 'Internship',
                'salary' => 'Stipend',
                'posted' => '5 days ago',
                'fresher_friendly' => true,
            ],
            (object) [
                'title' => 'React Native Mobile Developer',
                'location' => 'Remote (Nepal)',
                'type' => 'Contract',
                'salary' => 'Negotiable',
                'posted' => '1 week ago',
                'fresher_friendly' => false,
            ],
        ]),
            
        ];

        // Later real query example:
        // $company = Company::withCount('jobPostings')
        //     ->where('slug', $slug)
        //     ->where('verification_status', 'verified')
        //     ->firstOrFail();

        return view('companies.show', compact('company'));
    }
}