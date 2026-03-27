@extends('layouts.app')

@section('title', 'Employer Dashboard - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Top Navigation Bar with Dashboard Switcher --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Employer Dashboard
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Manage your company, jobs, and applicants
            </p>
        </div>
        
        {{-- Dashboard Switcher Button --}}
        <a href="{{ route('dashboard.jobseeker') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            Switch to Job Seeker Dashboard
        </a>
    </div>

    {{-- Quick Stats Cards with Icons and Trends --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Jobs Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-right">
                    @if(isset($stats['job_trend']) && $stats['job_trend'] > 0)
                        <span class="inline-flex items-center text-xs text-green-600 dark:text-green-400">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            +{{ $stats['job_trend'] }}%
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Jobs Posted</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_jobs']) }}</p>
            </div>
        </div>

        <!-- Active Jobs Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Active Jobs</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-500">{{ number_format($stats['active_jobs']) }}</p>
            </div>
        </div>

        <!-- Total Applications Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Applications Received</p>
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-500">{{ number_format($stats['total_applications']) }}</p>
            </div>
        </div>

        <!-- Pending Applications Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pending Review</p>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-500">{{ number_format($stats['pending_applications']) }}</p>
                @if($stats['pending_applications'] > 0)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Requires attention</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Company Management Section --}}
    @if(!$company)
        {{-- No Company - Create Company State --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Create Your Company Profile</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-8">
                Start your hiring journey by creating a company profile. This will allow you to post jobs and attract top talent from Nepal and abroad.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('employer.company.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Company Profile
                </a>
                <a href="{{ route('companies.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Companies
                </a>
            </div>
            
            {{-- Quick Tips --}}
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Complete your company profile to build trust</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Add company logo and description for better visibility</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Verification badge increases candidate trust by 80%</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Company exists - Show Company Details --}}
        <div class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    {{-- Company Header with Logo and Actions --}}
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex items-start gap-5">
                            {{-- Company Logo --}}
                            <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden shadow-md">
                                @if($company->logo_path)
                                    <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl font-bold text-gray-600 dark:text-gray-400">
                                        {{ substr($company->name, 0, 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Company Info --}}
                            <div>
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h2>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($company->verification_status == 'verified') 
                                            bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($company->verification_status == 'pending') 
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($company->verification_status == 'rejected') 
                                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else 
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        @if($company->verification_status == 'verified')
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                        {{ ucfirst($company->verification_status) }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    @if($company->industry)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                            {{ $company->industry }}
                                        </span>
                                    @endif
                                    
                                    @if($company->location)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $company->location }}
                                        </span>
                                    @endif
                                    
                                    @if($company->website)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9" />
                                            </svg>
                                            <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                                {{ parse_url($company->website, PHP_URL_HOST) }}
                                            </a>
                                        </span>
                                    @endif
                                </div>
                                
                                @if($company->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 max-w-2xl">
                                        {{ Str::limit($company->description, 150) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Action Buttons for Company Management --}}
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('employer.company.edit', $company) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Company
                            </a>
                            
                            <a href="{{ route('employer.company.team', $company) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Manage Team
                            </a>
                            
                            <a href="{{ route('companies.show', $company->slug) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                View Public Profile
                            </a>
                        </div>
                    </div>
                    
                    {{-- Job Posting Limit Progress Bar --}}
                    @php
                        $maxJobs = config('settings.max_active_jobs_per_company', 20);
                        $activeJobsCount = $company->jobPostings->where('status', 'active')->count();
                        $jobPercentage = ($activeJobsCount / $maxJobs) * 100;
                        $remainingSlots = $maxJobs - $activeJobsCount;
                    @endphp
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Job Posting Limit</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    ({{ $activeJobsCount }} / {{ $maxJobs }} active jobs)
                                </span>
                            </div>
                            @if($remainingSlots > 0)
                                <a href="{{ route('employer.jobs.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Post New Job ({{ $remainingSlots }} slots left)
                                </a>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Job Limit Reached
                                </span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ min($jobPercentage, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Quick Action Cards for Job Management --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('employer.jobs.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Manage Jobs</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">View, edit, and manage all your job postings</p>
                <p class="mt-3 text-sm font-medium text-red-600 group-hover:text-red-700">View all jobs →</p>
            </a>
            
            <a href="{{ route('employer.applicants.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Review Applicants</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Review, shortlist, and manage job applications</p>
                @if($stats['pending_applications'] > 0)
                    <p class="mt-3 text-sm font-medium text-red-600">
                        {{ $stats['pending_applications'] }} pending review →
                    </p>
                @else
                    <p class="mt-3 text-sm font-medium text-red-600 group-hover:text-red-700">View applicants →</p>
                @endif
            </a>
            
            <a href="{{ route('employer.jobs.create') }}" 
               class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200 dark:border-red-800 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-red-200 dark:bg-red-900/50 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Post a New Job</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Reach thousands of job seekers in Nepal</p>
                <p class="mt-3 text-sm font-medium text-red-600 group-hover:text-red-700">Post job now →</p>
            </a>
        </div>
        
        {{-- Recent Jobs Section --}}
        @if($recentJobs->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Job Postings</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Your latest job openings
                            </p>
                        </div>
                        <a href="{{ route('employer.jobs.index') }}" 
                           class="text-sm text-red-600 hover:text-red-700 font-medium">
                            View all jobs →
                        </a>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentJobs as $job)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $job->title }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($job->status == 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($job->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $job->location ?? 'Location not specified' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ ucfirst(str_replace('-', ' ', $job->job_type ?? 'full-time')) }}
                                        </span>
                                        @if($job->deadline)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $job->applications_count }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Applications</div>
                                    </div>
                                    <a href="{{ route('employer.jobs.applications', $job) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                        Review
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Recent Applications Section --}}
        @if($recentApplications->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Applications</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Latest candidates who applied to your jobs
                            </p>
                        </div>
                        <a href="{{ route('employer.applicants.index') }}" 
                           class="text-sm text-red-600 hover:text-red-700 font-medium">
                            View all applications →
                        </a>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentApplications as $application)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">
                                            {{ strtoupper(substr($application->applicant->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $application->applicant->name }}
                                            </h4>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                @if($application->status == 'applied') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @elseif($application->status == 'shortlisted') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Applied for 
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $application->job_title }}
                                            </span>
                                        </p>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $application->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('employer.applicants.show', $application) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg transition-colors">
                                    Review Application
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
    // Auto-refresh stats every 30 seconds (optional)
    let refreshInterval = setInterval(() => {
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update stats numbers
            const statsCards = document.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-2xl');
            const newStatsCards = doc.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-2xl');
            
            statsCards.forEach((card, index) => {
                if (newStatsCards[index]) {
                    const oldNumber = card.querySelector('.text-3xl');
                    const newNumber = newStatsCards[index].querySelector('.text-3xl');
                    if (oldNumber && newNumber) {
                        oldNumber.textContent = newNumber.textContent;
                    }
                }
            });
        })
        .catch(error => console.error('Error refreshing stats:', error));
    }, 30000);
    
    // Clear interval on page unload
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
</script>
@endpush

@endsection