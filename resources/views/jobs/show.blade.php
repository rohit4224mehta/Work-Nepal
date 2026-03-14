@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->company->name . ' - WorkNepal')

@section('meta_description', Str::limit(strip_tags($job->description), 160))
@section('meta_keywords', implode(',', array_slice(explode(' ', $job->title), 0, 5)))

@push('meta_tags')
    <meta property="og:title" content="{{ $job->title }} at {{ $job->company->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($job->description), 200) }}">
    <meta property="og:image" content="{{ $job->company->logo_url ?? asset('images/default-company.png') }}">
    <meta property="og:url" content="{{ route('jobs.show', $job->slug) }}">
    <meta name="twitter:card" content="summary_large_image">
@endpush

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Back Button & Breadcrumb --}}
        <div class="mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('jobs.index') }}" 
                   class="flex items-center text-gray-600 dark:text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Jobs
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 dark:text-white font-medium">{{ $job->title }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Main Content - Job Details --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Job Header Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div class="flex items-start gap-4">
                                {{-- Company Logo --}}
                                <div class="w-20 h-20 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                                    @if($job->company && $job->company->logo_path)
                                        <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-3xl font-bold text-gray-500 dark:text-gray-400">
                                            {{ substr($job->company->name ?? 'C', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="flex flex-wrap items-center gap-3 mb-2">
                                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">{{ $job->title }}</h1>
                                        @if($job->is_featured)
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full">Featured</span>
                                        @endif
                                        @if($job->verification_status == 'verified')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Verified
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-xl text-gray-600 dark:text-gray-400">
                                        <a href="{{ route('companies.show', $job->company->slug) }}" class="hover:text-red-600">
                                            {{ $job->company->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                @auth
                                    <button onclick="toggleSave({{ $job->id }})" 
                                            class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-red-600 hover:text-red-600 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="{{ $isSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        {{ $isSaved ? 'Saved' : 'Save Job' }}
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-red-600 hover:text-red-600 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        Save Job
                                    </a>
                                @endauth
                                
                                @auth
                                    @if(!$hasApplied)
                                        <button onclick="applyForJob({{ $job->id }})" 
                                                class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                            Apply Now
                                        </button>
                                    @else
                                        <button disabled 
                                                class="px-8 py-3 bg-gray-400 text-white font-semibold rounded-xl cursor-not-allowed">
                                            Already Applied
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                        Login to Apply
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Quick Info Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Location</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $job->location }}
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Job Type</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Experience</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst($job->experience_level) }}
                                </div>
                            </div>
                            
                            @if($job->salary_range)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Salary</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $job->salary_range }}
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Deadline Alert --}}
                        @php
                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->deadline), false);
                        @endphp
                        @if($daysLeft <= 7 && $daysLeft > 0)
                            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-yellow-800 dark:text-yellow-400">
                                        <strong>Hurry!</strong> Only {{ round($daysLeft) }} days left to apply. Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('F j, Y') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Job Description Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Job Description</h2>
                        
                        <div class="prose prose-lg max-w-none dark:prose-invert">
                            {!! nl2br(e($job->description)) !!}
                        </div>

                        {{-- Requirements Section --}}
                        @if($job->requirements)
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">Requirements</h3>
                            <div class="prose prose-lg max-w-none dark:prose-invert">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        @endif

                        {{-- Benefits Section --}}
                        @if($job->benefits)
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">Benefits</h3>
                            <div class="prose prose-lg max-w-none dark:prose-invert">
                                {!! nl2br(e($job->benefits)) !!}
                            </div>
                        @endif

                        {{-- Skills Tags --}}
                        @if($job->skills)
                            <div class="mt-8">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Required Skills</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $job->skills) as $skill)
                                        <a href="{{ route('jobs.index', ['q' => trim($skill)]) }}" 
                                           class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                                            {{ trim($skill) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Company Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">About {{ $job->company->name }}</h2>
                        
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($job->company->logo_path)
                                    <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-gray-500">{{ substr($job->company->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $job->company->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ $job->company->industry }} • {{ $job->company->location }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-sm text-gray-500">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $job->company->job_postings_count }} active jobs
                                    </span>
                                    <a href="{{ route('companies.show', $job->company->slug) }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        View Company Profile →
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($job->company->description)
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ Str::limit(strip_tags($job->company->description), 300) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                
                {{-- Apply Card (Sticky) --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ready to Apply?</h3>
                    
                    @auth
                        @if(!$hasApplied)
                            <button onclick="applyForJob({{ $job->id }})" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 mb-4">
                                Apply for this Position
                            </button>
                            
                            <p class="text-sm text-gray-500 text-center">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Your information is secure
                            </p>
                        @else
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl mb-4">
                                <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-green-800 dark:text-green-400 font-medium">You've already applied for this position</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 inline-block mb-3">
                                Login to Apply
                            </a>
                            <p class="text-sm text-gray-500">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-medium">Sign up</a>
                            </p>
                        </div>
                    @endauth

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Posted: {{ $job->created_at->format('M d, Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                {{-- Share Job Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Share this Job</h3>
                    
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                            Share
                        </a>
                        
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                            </svg>
                            Share
                        </a>
                    </div>
                </div>

                {{-- Report Job Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">See something wrong?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Help us keep WorkNepal safe and trustworthy.</p>
                    <a href="#" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Report this job
                    </a>
                </div>
            </div>
        </div>

        {{-- Similar Jobs Section --}}
        @if($similarJobs->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Similar Jobs You Might Like</h2>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarJobs as $similar)
                        <a href="{{ route('jobs.show', $similar->slug) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $similar->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $similar->company->name }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $similar->location }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $similar->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleSave(jobId) {
    fetch(`/api/jobs/${jobId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        }
    });
}

function applyForJob(jobId) {
    fetch(`/jobs/${jobId}/apply`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            showNotification(data.message, 'error');
        }
    });
}

function showNotification(message, type) {
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
</script>
@endpush
@endsection