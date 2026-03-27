@extends('layouts.app')

@section('title', 'Job Seeker Dashboard - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">

    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Welcome back, {{ auth()->user()->name }}! 👋
        </h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            Your personalized job search dashboard
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    {{-- Dashboard Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        <!-- LEFT COLUMN -->
        <div class="space-y-6">

            <!-- Profile Strength Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Profile Strength
                    </h3>
                    <span class="text-2xl font-bold text-red-600 dark:text-red-500" id="completion-percentage">
                        {{ $completion }}%
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                    <div class="bg-gradient-to-r from-red-600 to-red-500 h-3 rounded-full transition-all duration-500"
                         id="completion-bar"
                         style="width: {{ $completion }}%"></div>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4" id="completion-message">
                    {{ $completion < 80 ? 'Complete your profile to get better job matches.' : 'Great job! Your profile is complete.' }}
                </p>

                {{-- Missing Items --}}
                @if(!empty($missingItems) && count($missingItems) > 0)
                    <ul class="space-y-2 text-sm">
                        @foreach($missingItems as $item)
                            @if(is_array($item) && isset($item['url']) && isset($item['message']))
                                <li class="flex items-center text-amber-600 dark:text-amber-500">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <a href="{{ $item['url'] }}" class="hover:underline hover:text-amber-700 dark:hover:text-amber-400">
                                        {{ $item['message'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_applications'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-500">{{ $stats['shortlisted'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Shortlisted</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-500">{{ $stats['interviews'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Interviews</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-500">{{ $stats['saved_jobs'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Saved Jobs</div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Quick Actions
                </h3>

                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('jobs.index') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition group">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Search Jobs</span>
                    </a>

                    <a href="{{ route('applications.index') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition group">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Applications</span>
                        @if(isset($stats['pending']) && $stats['pending'] > 0)
                            <span class="mt-1 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs rounded-full">
                                {{ $stats['pending'] }} new
                            </span>
                        @endif
                    </a>

                    {{-- <a href="{{ route('saved.jobs') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition group">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Saved Jobs</span>
                    </a> --}}

                    <a href="{{ route('profile.edit') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition group">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500 mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Edit Profile</span>
                    </a>
                </div>
            </div>

            <!-- Become Employer Card -->
            @if(!auth()->user()->hasRole('employer'))
                <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Become an Employer</h3>
                        </div>
                        <p class="text-red-100 mb-4 text-sm">
                            Want to hire talent? Create a company profile and start posting jobs today.
                        </p>
                        <a href="{{ route('employer.company.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Company Profile
                        </a>
                    </div>
                </div>
            @endif

            <!-- Profile Views Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Profile Views</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['profile_views'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Last 30 days
                </p>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Recommended Jobs Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            Recommended for You
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Based on your skills and preferences
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <select id="job-type-filter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <option value="">All Types</option>
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="internship">Internship</option>
                            <option value="remote">Remote</option>
                        </select>

                        <a href="{{ route('jobs.index') }}" 
                           class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                            View All
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                @if($recommendedJobs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No recommendations yet</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Complete your profile to get personalized job matches.</p>
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Complete Profile
                        </a>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($recommendedJobs as $job)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 hover:shadow-md transition-all border border-gray-200 dark:border-gray-700">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                                        @if($job->company && $job->company->logo_path)
                                            <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-bold text-gray-400">{{ substr($job->company->name ?? 'C', 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 dark:text-white truncate">
                                            <a href="{{ route('jobs.show', $job->id) }}" class="hover:text-red-600">
                                                {{ $job->title }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $job->company->name ?? 'Unknown Company' }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded-full">
                                        {{ ucfirst($job->job_type ?? 'Full Time') }}
                                    </span>
                                    @if($job->location)
                                        <span class="px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs rounded-full">
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                    @if($job->salary_range)
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs rounded-full">
                                            {{ $job->salary_range }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="saveJob({{ $job->id }})" 
                                                class="p-1.5 text-gray-400 hover:text-red-600 transition-colors"
                                                title="Save Job">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('jobs.show', $job->id) }}" 
                                           class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors">
                                            Apply
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Applications Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            Recent Applications
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Track your job application status
                        </p>
                    </div>
                    <a href="{{ route('applications.index') }}" 
                       class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                        View All
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if($recentApplications->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications yet</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Start applying to jobs to track your progress.</p>
                        <a href="{{ route('jobs.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Browse Jobs
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentApplications as $application)
                            <div class="flex flex-wrap items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                            @if($application->jobPosting->company && $application->jobPosting->company->logo_path)
                                                <img src="{{ $application->jobPosting->company->logo_url }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-lg font-bold text-gray-400">{{ substr($application->jobPosting->company->name ?? 'C', 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('jobs.show', $application->jobPosting->id) }}" class="hover:text-red-600">
                                                    {{ $application->jobPosting->title }}
                                                </a>
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $application->jobPosting->company->name ?? 'Unknown Company' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 mt-2 sm:mt-0">
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($application->status == 'applied') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($application->status == 'viewed') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                            @elseif($application->status == 'shortlisted') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($application->status == 'hired') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Applied {{ $application->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Application Stats --}}
                    <div class="grid grid-cols-4 gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_applications'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-blue-600 dark:text-blue-500">{{ $stats['pending'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600 dark:text-green-500">{{ $stats['shortlisted'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Shortlisted</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-red-600 dark:text-red-500">{{ $stats['rejected'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Rejected</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Career Advice Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        Career Advice
                    </h3>
                    <a href="{{ route('pages.cv-tips') }}" 
                       class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                        More Articles
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <a href="{{ route('pages.cv-tips') }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition-all">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">CV Writing Tips</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Learn how to create a standout CV</p>
                    </a>

                    <a href="{{ route('pages.foreign-safety') }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition-all">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">Foreign Job Safety</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Stay safe when applying abroad</p>
                    </a>

                    <a href="{{ route('pages.help-center') }}" class="block p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition-all">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-1">Help Center</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Get answers to common questions</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Safety Banner -->
    <div class="mt-12 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border-l-4 border-red-600 p-6 rounded-lg">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-red-800 dark:text-red-400 mb-1">
                        ⚠️ Foreign Job Safety Notice
                    </h4>
                    <p class="text-red-700 dark:text-red-300 text-sm">
                        Always verify foreign job offers through official channels. Never pay for job applications.
                    </p>
                </div>
            </div>
            <a href="{{ route('pages.foreign-safety') }}"
               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium whitespace-nowrap">
                Read Safety Guide
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Save Job Function
function saveJob(jobId) {
    fetch(`/jobs/${jobId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Job saved successfully!');
        } else {
            showNotification('error', data.message || 'Failed to save job');
        }
    })
    .catch(error => {
        showNotification('error', 'Network error. Please try again.');
    });
}

// Job Type Filter
document.getElementById('job-type-filter')?.addEventListener('change', function() {
    const selectedType = this.value;
    const jobCards = document.querySelectorAll('.grid.md\\:grid-cols-2 > div');
    
    jobCards.forEach(card => {
        const jobTypeElement = card.querySelector('.bg-blue-100');
        if (jobTypeElement) {
            const jobType = jobTypeElement.textContent.trim().toLowerCase().replace(/\s+/g, '-');
            if (selectedType === '' || jobType.includes(selectedType)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
});

// Notification System
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    notification.style.animation = 'slideIn 0.3s ease-out';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection