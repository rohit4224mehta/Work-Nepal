<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        // For MVP: dummy data or later real query
        $jobs = collect(range(1, 12))->map(function ($i) {
            return (object) [
                'id' => $i,
                'title' => "Software Developer {$i}",
                'company' => "Tech Nepal Pvt. Ltd.",
                'location' => "Kathmandu",
                'type' => "Full Time",
                'salary' => "Rs. 40,000 - 80,000",
                'posted' => now()->subDays(rand(1, 30))->diffForHumans(),
                'fresher_friendly' => rand(0,1),
                'verified' => true,
            ];
        });

        return view('jobs.index', compact('jobs'));
    }

    public function show($slug)
    {
        // Later: find real job by slug
        $job = (object) [
            'title' => "Senior Laravel Developer",
            'company' => "Innovate Solutions",
            'location' => "Pokhara",
            'type' => "Full Time",
            'experience' => "3–6 years",
            'salary' => "Rs. 80,000 – 150,000",
            'description' => "We are looking for a passionate Laravel developer...",
            'requirements' => "• 3+ years Laravel\n• MySQL & API experience\n• Good English",
            'posted_at' => now()->subDays(5),
            'verified' => true,
            'fresher_friendly' => false,
        ];

        return view('jobs.show', compact('job'));
    }
}