@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->company->name . ' - WorkNepal')

@section('meta_description', Str::limit(strip_tags($job->description), 160))
@section('meta_keywords', implode(',', array_slice(explode(' ', $job->title), 0, 5)))

@push('meta_tags')
    <meta property="og:title" content="{{ $job->title }} at {{ $job->company->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($job->description), 200) }}">
    <meta property="og:image" content="{{ $job->company->logo_path ? Storage::url($job->company->logo_path) : asset('images/default-company.png') }}">
    <meta property="og:url" content="{{ route('jobs.show', $job->slug) }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="WorkNepal">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $job->title }} at {{ $job->company->name }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($job->description), 200) }}">
    <meta name="twitter:image" content="{{ $job->company->logo_path ? Storage::url($job->company->logo_path) : asset('images/default-company.png') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('jobs.show', $job->slug) }}">
    
    {{-- JSON-LD Structured Data for SEO --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "JobPosting",
        "title": "{{ $job->title }}",
        "description": "{{ strip_tags($job->description) }}",
        "datePosted": "{{ $job->created_at->toIso8601String() }}",
        "validThrough": "{{ $job->deadline ? $job->deadline->toIso8601String() : '' }}",
        "employmentType": "{{ strtoupper(str_replace('-', '_', $job->job_type)) }}",
        "hiringOrganization": {
            "@type": "Organization",
            "name": "{{ $job->company->name }}",
            "logo": "{{ $job->company->logo_path ? Storage::url($job->company->logo_path) : asset('images/default-company.png') }}",
            "sameAs": "{{ $job->company->website }}"
        },
        "jobLocation": {
            "@type": "Place",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "{{ $job->location }}",
                "addressCountry": "NP"
            }
        },
        "baseSalary": {
            "@type": "MonetaryAmount",
            "currency": "NPR",
            "value": {
                "@type": "QuantitativeValue",
                "value": {{ preg_replace('/[^0-9]/', '', $job->salary_range) ?: 0 }},
                "unitText": "MONTH"
            }
        },
        "experienceRequirements": "{{ ucfirst($job->experience_level) }} Level",
        "employmentType": "{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}"
    }
    </script>
@endpush

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb with Schema --}}
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600 transition">
                        Home
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('jobs.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600 transition">
                        Jobs
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white font-medium truncate">
                    {{ $job->title }}
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Main Content - Job Details --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Job Header Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 lg:p-8">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div class="flex items-start gap-4">
                                {{-- Company Logo --}}
                                <div class="w-20 h-20 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600 shadow-sm">
                                    @if($job->company && $job->company->logo_path)
                                        <img src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
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
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm rounded-full flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Featured
                                            </span>
                                        @endif
                                        @if($job->verification_status == 'verified')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Verified
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <p class="text-xl text-gray-600 dark:text-gray-400">
                                        <a href="{{ route('companies.show', $job->company->slug) }}" class="hover:text-red-600 transition-colors">
                                            {{ $job->company->name }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                @auth
                                    <button onclick="toggleSave('{{ $job->slug }}')" 
        class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-red-600 hover:text-red-600 transition-all flex items-center justify-center gap-2 group"
        id="save-job-btn">
    <svg class="w-5 h-5 transition-colors group-hover:scale-110" fill="{{ $isSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
    </svg>
    <span id="save-btn-text">{{ $isSaved ? 'Saved' : 'Save Job' }}</span>
</button>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:border-red-600 hover:text-red-600 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                        Save Job
                                    </a>
                                @endauth
                                
                                @auth
                                    @if(!$hasApplied)
                                        <button onclick="applyForJob('{{ $job->slug }}')" 
        class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
        id="apply-job-btn">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Apply Now
</button>
                                    @else
                                        <button disabled 
                                                class="px-8 py-3 bg-gray-400 text-white font-semibold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Already Applied
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        Login to Apply
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Quick Info Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Location</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $job->location }}
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Job Type</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                </div>
                            </div>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Experience</div>
                                <div class="font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst($job->experience_level) }}
                                </div>
                            </div>
                            
                            @if($job->salary_range)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">
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
                            $deadline = \Carbon\Carbon::parse($job->deadline);
                            $daysLeft = \Carbon\Carbon::now()->startOfDay()->diffInDays($deadline, false);
                        @endphp
                        @if($daysLeft <= 7 && $daysLeft > 0)
                            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl animate-pulse">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <span class="text-yellow-800 dark:text-yellow-400 font-medium">
                                            <strong>Hurry!</strong> Only {{ floor($daysLeft) }} {{ floor($daysLeft) == 1 ? 'day' : 'days' }} left to apply
                                        </span>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-500 mt-1">
                                            Deadline: {{ $deadline->format('F j, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Job Description Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Job Description</h2>
                        </div>
                        
                        <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-600 dark:prose-p:text-gray-400">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                </div>

                {{-- Skills Section --}}
                @if($job->skills)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Required Skills</h2>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $job->skills) as $skill)
                                    <a href="{{ route('jobs.index', ['search' => trim($skill)]) }}" 
                                       class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900/30 transition transform hover:scale-105">
                                        {{ trim($skill) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Company Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">About {{ $job->company->name }}</h2>
                        </div>
                        
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shadow-sm">
                                @if($job->company->logo_path)
                                    <img src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-gray-500">{{ substr($job->company->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $job->company->name }}</h3>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @if($job->company->industry)
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $job->company->industry }}</span>
                                    @endif
                                    @if($job->company->location)
                                        <span class="text-sm text-gray-400">•</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $job->company->location }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $job->company->job_postings_count ?? 0 }} active jobs
                                    </span>
                                    <a href="{{ route('companies.show', $job->company->slug) }}" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1 group">
                                        View Company Profile
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($job->company->description)
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ Str::limit(strip_tags($job->company->description), 400) }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                
                {{-- Apply Card (Sticky) --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                    <div class="text-center mb-4">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Ready to Apply?</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Take the next step in your career</p>
                    </div>
                    
                    @auth
                        @if(!$hasApplied)
                            @if(auth()->user()->resume_path)
                                <button onclick="applyForJob({{ $job->id }})" 
                                        class="w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 mb-4 flex items-center justify-center gap-2"
                                        id="apply-sidebar-btn">
                                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Apply for this Position
                                </button>
                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-4">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-yellow-800 dark:text-yellow-400 font-medium">Resume Required</p>
                                            <p class="text-xs text-yellow-700 dark:text-yellow-500 mt-1">
                                                Please upload your resume first to apply for jobs.
                                            </p>
                                            <a href="{{ route('profile.edit') }}#resume" class="text-xs text-yellow-700 dark:text-yellow-500 font-medium mt-2 inline-block hover:underline">
                                                Upload Resume →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <p class="text-xs text-gray-500 text-center">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Your information is secure with us
                            </p>
                        @else
                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl mb-4">
                                <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-green-800 dark:text-green-400 font-medium">Application Submitted!</p>
                                <p class="text-xs text-green-700 dark:text-green-500 mt-1">You've already applied for this position</p>
                                <a href="{{ route('applications.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium mt-3 inline-block">
                                    View My Applications →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 inline-block mb-3">
                                Login to Apply
                            </a>
                            <p class="text-sm text-gray-500">
                                Don't have an account? 
                                <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-medium">Sign up free</a>
                            </p>
                        </div>
                    @endauth

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-2">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Posted: {{ $job->created_at->format('M d, Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $job->applications_count ?? 0 }} applicant(s)
                        </div>
                    </div>
                </div>

                {{-- Share Job Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Share this Job</h3>
                    
                    <div class="flex gap-3">
                        <button onclick="shareOnFacebook()" 
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                            Share
                        </button>
                        
                        <button onclick="shareOnLinkedIn()" 
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-blue-700 text-white rounded-xl hover:bg-blue-800 transition transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                            </svg>
                            Share
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <div class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <input type="text" id="job-url" value="{{ route('jobs.show', $job->slug) }}" readonly 
                                   class="flex-1 bg-transparent text-sm text-gray-600 dark:text-gray-400 focus:outline-none">
                            <button onclick="copyJobLink()" 
                                    class="px-3 py-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 rounded-lg text-sm transition">
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Report Job Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Report this job</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                See something wrong? Help us keep WorkNepal safe.
                            </p>
                            <button onclick="showReportModal()" 
                                    class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                                Report Issue
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Similar Jobs Section --}}
        @if($similarJobs->isNotEmpty())
            <div class="mt-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Similar Jobs You Might Like</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Based on this job posting</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                        View All Jobs
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarJobs as $similar)
                        <a href="{{ route('jobs.show', $similar->slug) }}" 
                           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-all transform hover:-translate-y-1 group">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                    @if($similar->company && $similar->company->logo_path)
                                        <img src="{{ Storage::url($similar->company->logo_path) }}" alt="{{ $similar->company->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-bold text-gray-500">{{ substr($similar->company->name ?? 'C', 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-red-600 transition line-clamp-1">
                                        {{ $similar->title }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $similar->company->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $similar->location }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

{{-- Report Modal --}}
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Report this Job</h3>
            <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="reportForm">
            @csrf
            <input type="hidden" name="job_id" value="{{ $job->id }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for reporting</label>
                <select name="reason" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" required>
                    <option value="">Select a reason</option>
                    <option value="spam">Spam or misleading</option>
                    <option value="inappropriate">Inappropriate content</option>
                    <option value="scam">Scam or fraud</option>
                    <option value="expired">Job is expired</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional details (optional)</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white" placeholder="Please provide more details..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeReportModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">Submit Report</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let isLoading = false;

function toggleSave(jobId) {
    if (isLoading) return;
    isLoading = true;
    
    const btn = document.getElementById('save-job-btn');
    const btnText = document.getElementById('save-btn-text');
    const originalText = btnText.innerText;
    
    // Show loading state
    btnText.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    btn.disabled = true;
    
    // ✅ FIX: Use the correct route with job ID
    fetch(`/jobs/${jobId}/toggle-save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ job_id: jobId })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Server error');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            const icon = btn.querySelector('svg:first-child');
            if (data.saved) {
                icon.setAttribute('fill', 'currentColor');
                btnText.innerText = 'Saved';
                showNotification('Job saved successfully!', 'success');
            } else {
                icon.setAttribute('fill', 'none');
                btnText.innerText = 'Save Job';
                showNotification('Job removed from saved', 'info');
            }
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            showNotification(data.message || 'Something went wrong', 'error');
            btnText.innerText = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Failed to save job. Please try again.', 'error');
        btnText.innerText = originalText;
    })
    .finally(() => {
        btn.disabled = false;
        isLoading = false;
    });
}

function applyForJob(jobSlug) {
    if (isLoading) return;
    isLoading = true;
    
    const btn = document.getElementById('apply-job-btn') || document.getElementById('apply-sidebar-btn');
    let originalHtml = '';
    
    if (btn) {
        originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        btn.disabled = true;
    }
    
    // ✅ FIX: Use slug instead of ID
    fetch(`/jobs/${jobSlug}/apply`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ job_slug: jobSlug })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Server error');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            showNotification(data.message || 'Failed to apply', 'error');
            if (btn) btn.innerHTML = originalHtml;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Failed to apply. Please try again.', 'error');
        if (btn) btn.innerHTML = originalHtml;
    })
    .finally(() => {
        if (btn) btn.disabled = false;
        isLoading = false;
    });
}

function toggleSave(jobSlug) {
    if (isLoading) return;
    isLoading = true;
    
    const btn = document.getElementById('save-job-btn');
    const btnText = document.getElementById('save-btn-text');
    const originalText = btnText.innerText;
    
    btnText.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    btn.disabled = true;
    
    // ✅ FIX: Use slug instead of ID
    fetch(`/jobs/${jobSlug}/toggle-save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ job_slug: jobSlug })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Server error');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            const icon = btn.querySelector('svg:first-child');
            if (data.saved) {
                icon.setAttribute('fill', 'currentColor');
                btnText.innerText = 'Saved';
                showNotification('Job saved successfully!', 'success');
            } else {
                icon.setAttribute('fill', 'none');
                btnText.innerText = 'Save Job';
                showNotification('Job removed from saved', 'info');
            }
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            showNotification(data.message || 'Something went wrong', 'error');
            btnText.innerText = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Failed to save job. Please try again.', 'error');
        btnText.innerText = originalText;
    })
    .finally(() => {
        btn.disabled = false;
        isLoading = false;
    });
}


function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=500');
}

function copyJobLink() {
    const urlInput = document.getElementById('job-url');
    urlInput.select();
    document.execCommand('copy');
    showNotification('Link copied to clipboard!', 'success');
}

function showReportModal() {
    document.getElementById('reportModal').classList.remove('hidden');
    document.getElementById('reportModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.getElementById('reportModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
    document.getElementById('reportForm').reset();
}

document.getElementById('reportForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch('{{ route("jobs.report") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            job_id: {{ $job->id }},
            reason: this.reason.value,
            description: this.description.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Report submitted successfully. Thank you for helping us!', 'success');
            closeReportModal();
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            showNotification(data.message || 'Failed to submit report', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to submit report. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="${type === 'success' ? 'M5 13l4 4L19 7' : 
                          type === 'error' ? 'M6 18L18 6M6 6l12 12' : 
                          'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'}"/>
            </svg>
            <span>${message}</span>
        </div>
    `;
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
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush

@endsection