<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application landing page (public)
     */
    public function index()
    {
        // For MVP: you can pass dummy stats or latest 6 jobs later
        $stats = [
            'jobs_count'     => 5200,
            'companies_count'=> 840,
            'freshers_hired' => 3200,
        ];

        // Later: replace with real query
        // $latestJobs = JobPosting::latest()->take(6)->get();

        return view('home.index', compact('stats'));
    }
}