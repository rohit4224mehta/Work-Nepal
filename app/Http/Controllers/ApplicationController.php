<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $applications = auth()->user()
            ->jobApplications()
            ->with(['job.company'])
            ->latest()
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }
}