@extends('layouts.app')

@section('title', 'Find Jobs in Nepal - WorkNepal')

@section('meta_description', 'Search thousands of verified jobs in Nepal. Filter by category, location, job type, and experience level. Find your dream job today.')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Find Your Dream Job in Nepal</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Browse {{ number_format($jobs->total()) }}+ verified jobs from top companies
                </p>
            </div>

            {{-- Quick Search Form --}}
            <div class="mt-8 max-w-4xl mx-auto">
                <form action="{{ route('jobs.index') }}" method="GET" class="bg-white rounded-2xl shadow-xl p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Job title, skills, or company" 
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900">
                        </div>
                        <div class="flex gap-2">
                            <select name="location" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 bg-white">
                                <option value="">All Nepal</option>
                                @foreach($filterOptions['locations'] ?? [] as $location => $count)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                        {{ $location }} ({{ number_format($count) }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-8 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors whitespace-nowrap">
                                Search Jobs
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- Sidebar Filters --}}
            <div class="lg:w-80 flex-shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-24">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Filters</h3>
                        
                        <form action="{{ route('jobs.index') }}" method="GET" id="filter-form">
                            {{-- Preserve search query --}}
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            {{-- Categories --}}
                            @if(!empty($filterOptions['categories']))
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Category</h4>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @foreach($filterOptions['categories'] as $category => $count)
                                        <label class="flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                            <div class="flex items-center">
                                                <input type="radio" 
                                                       name="category" 
                                                       value="{{ $category }}"
                                                       {{ request('category') == $category ? 'checked' : '' }}
                                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                                       onchange="this.form.submit()">
                                                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $category }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500">({{ number_format($count) }})</span>
                                        </label>
                                    @endforeach
                                </div>
                                
                                @if(request('category'))
                                    <a href="{{ route('jobs.index', array_merge(request()->except(['category', 'page']))) }}" 
                                       class="text-xs text-red-600 hover:text-red-700 mt-2 inline-block">
                                        Clear category
                                    </a>
                                @endif
                            </div>
                            @endif

                            {{-- Job Type --}}
                            @if(!empty($filterOptions['job_types']))
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Job Type</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['job_types'] as $value => $label)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                            <input type="checkbox" 
                                                   name="job_type[]" 
                                                   value="{{ $value }}"
                                                   {{ in_array($value, (array)request('job_type', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                                   onchange="this.form.submit()">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Experience Level --}}
                            @if(!empty($filterOptions['experience_levels']))
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Experience Level</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['experience_levels'] as $value => $label)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                            <input type="radio" 
                                                   name="experience" 
                                                   value="{{ $value }}"
                                                   {{ request('experience') == $value ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                                   onchange="this.form.submit()">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Salary Range --}}
                            @if(!empty($filterOptions['salary_ranges']))
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Salary Range (Monthly)</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['salary_ranges'] as $value => $label)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                            <input type="radio" 
                                                   name="salary" 
                                                   value="{{ $value }}"
                                                   {{ request('salary') == $value ? 'checked' : '' }}
                                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                                   onchange="this.form.submit()">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Quick Filters --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Quick Filters</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                        <input type="checkbox" 
                                               name="fresher" 
                                               value="1"
                                               {{ request()->boolean('fresher') ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                               onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Fresher Friendly</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                                        <input type="checkbox" 
                                               name="urgent" 
                                               value="1"
                                               {{ request()->boolean('urgent') ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                               onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Urgent Hiring</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Sort By</h4>
                                <select name="sort" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                        onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest Jobs</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Jobs</option>
                                    <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline (Earliest)</option>
                                </select>
                            </div>

                            {{-- Clear Filters --}}
                            @if(request()->hasAny(['category', 'job_type', 'experience', 'fresher', 'urgent', 'location', 'sort', 'salary']))
                                <a href="{{ route('jobs.index', request()->has('search') ? ['search' => request('search')] : []) }}" 
                                   class="block w-full text-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                                    Clear All Filters
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Featured Jobs Sidebar --}}
                    @if(isset($featuredJobs) && $featuredJobs->isNotEmpty())
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Featured Jobs</h4>
                            <div class="space-y-3">
                                @foreach($featuredJobs as $featured)
                                    <a href="{{ route('jobs.show', $featured->slug) }}" 
                                       class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition group">
                                        <h5 class="font-medium text-gray-900 dark:text-white text-sm mb-1 group-hover:text-red-600">
                                            {{ $featured->title }}
                                        </h5>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $featured->company->name }}</p>
                                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $featured->location }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $featured->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Statistics Sidebar --}}
                    @if(isset($statistics))
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Job Market Insights</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Total Jobs</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($statistics['total'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">New This Week</span>
                                    <span class="font-semibold text-green-600">{{ number_format($statistics['new_this_week'] ?? 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Companies Hiring</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($statistics['companies_hiring'] ?? 0) }}</span>
                                </div>
                                @if(isset($statistics['remote_jobs']))
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Remote Jobs</span>
                                    <span class="font-semibold text-blue-600">{{ number_format($statistics['remote_jobs'] ?? 0) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Jobs List --}}
            <div class="flex-1">
                {{-- Results Header --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">
                            Showing <span class="font-semibold">{{ $jobs->firstItem() }}</span> to 
                            <span class="font-semibold">{{ $jobs->lastItem() }}</span> of 
                            <span class="font-semibold">{{ number_format($jobs->total()) }}</span> jobs
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">View:</span>
                        <button id="grid-view-btn" class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" title="Grid View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button id="list-view-btn" class="p-2 bg-red-600 text-white rounded-lg" title="List View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Jobs Container --}}
                <div id="jobs-container" class="space-y-4">
                    @if($jobs->count() > 0)
                        @foreach($jobs as $job)
                            <div class="job-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        {{-- Company Logo --}}
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($job->company && $job->company->logo_path)
                                                <img src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-2xl font-bold text-gray-500 dark:text-gray-400">
                                                    {{ substr($job->company->name ?? 'C', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Job Info --}}
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                    {{-- FIXED: Use slug for route --}}
                                                    <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-red-600">
                                                        {{ $job->title }}
                                                    </a>
                                                </h3>
                                                @if($job->is_featured)
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        Featured
                                                    </span>
                                                @endif
                                                @if($job->experience_level == 'entry')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Fresher Friendly</span>
                                                @endif
                                                @if($job->deadline && $job->deadline <= now()->addDays(7))
                                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Urgent</span>
                                                @endif
                                            </div>

                                            <p class="text-gray-600 dark:text-gray-400 mb-3">
                                                {{-- FIXED: Use slug for company route --}}
                                                <a href="{{ route('companies.show', $job->company->slug) }}" class="hover:text-red-600">
                                                    {{ $job->company->name }}
                                                </a>
                                                @if($job->company->verification_status == 'verified')
                                                    <span class="inline-flex items-center ml-1 text-green-600" title="Verified Company">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </p>

                                            <div class="flex flex-wrap gap-3 text-sm">
                                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $job->location }}
                                                </span>
                                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                                </span>
                                                @if($job->salary_range)
                                                    <span class="flex items-center text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $job->salary_range }}
                                                    </span>
                                                @endif
                                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('M d, Y') }}
                                                </span>
                                            </div>

                                            {{-- Skills Tags --}}
                                            @if($job->skills)
                                                <div class="flex flex-wrap gap-2 mt-3">
                                                    @foreach(explode(',', $job->skills) as $skill)
                                                        <span class="px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 text-xs rounded-full">
                                                            {{ trim($skill) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex flex-col items-end gap-2">
                                        <div class="flex gap-2">
                                            @auth
                                                @php
                                                    $isSaved = auth()->user()->savedJobs()->where('job_posting_id', $job->id)->exists();
                                                @endphp
                                                <button onclick="toggleSave({{ $job->id }})" 
                                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                                        data-job-id="{{ $job->id }}"
                                                        id="save-btn-{{ $job->id }}"
                                                        title="{{ $isSaved ? 'Remove from saved' : 'Save job' }}">
                                                    <svg class="w-6 h-6 save-icon-{{ $job->id }}" fill="{{ $isSaved ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                    </svg>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Login to save">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                    </svg>
                                                </a>
                                            @endauth
                                            {{-- FIXED: Use slug for route --}}
                                            <a href="{{ route('jobs.show', $job->slug) }}" 
                                               class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                                View Details
                                            </a>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </span>
                                        @if($job->applications_count)
                                            <span class="text-xs text-gray-500">
                                                {{ $job->applications_count }} applicant(s)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- No Results --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Jobs Found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters or browse all jobs</p>
                            <a href="{{ route('jobs.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                                Browse All Jobs
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                @if($jobs->hasPages())
                    <div class="mt-8">
                        {{ $jobs->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Newsletter Section --}}
    <div class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Get Job Alerts in Your Inbox</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Be the first to know about new opportunities matching your profile</p>
                <form id="newsletter-form" class="max-w-md mx-auto flex gap-3">
                    <input type="email" 
                           id="newsletter-email"
                           placeholder="Enter your email" 
                           class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                           required>
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors whitespace-nowrap">
                        Subscribe
                    </button>
                </form>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                    We'll never share your email. Unsubscribe anytime.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let isLoading = false;

function toggleSave(jobId) {
    if (isLoading) return;
    isLoading = true;
    
    fetch(`/jobs/${jobId}/toggle-save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ job_id: jobId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = document.querySelector(`.save-icon-${jobId}`);
            if (icon) {
                if (data.saved) {
                    icon.setAttribute('fill', 'currentColor');
                    showNotification('Job saved successfully!', 'success');
                } else {
                    icon.setAttribute('fill', 'none');
                    showNotification('Job removed from saved', 'info');
                }
            }
        } else if (data.error) {
            showNotification(data.error, 'error');
        } else if (data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    })
    .finally(() => {
        isLoading = false;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
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
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Auto-submit filter form on radio/checkbox change
document.querySelectorAll('#filter-form input[type="radio"], #filter-form input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', () => {
        document.getElementById('filter-form').submit();
    });
});

// View toggle functionality
let isGridView = false;
const gridBtn = document.getElementById('grid-view-btn');
const listBtn = document.getElementById('list-view-btn');
const jobsContainer = document.getElementById('jobs-container');

if (gridBtn && listBtn && jobsContainer) {
    gridBtn.addEventListener('click', () => {
        jobsContainer.classList.remove('space-y-4');
        jobsContainer.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-6');
        gridBtn.classList.add('bg-red-600', 'text-white');
        gridBtn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
        listBtn.classList.remove('bg-red-600', 'text-white');
        listBtn.classList.add('bg-gray-200', 'dark:bg-gray-700');
        localStorage.setItem('jobView', 'grid');
    });
    
    listBtn.addEventListener('click', () => {
        jobsContainer.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'gap-6');
        jobsContainer.classList.add('space-y-4');
        listBtn.classList.add('bg-red-600', 'text-white');
        listBtn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
        gridBtn.classList.remove('bg-red-600', 'text-white');
        gridBtn.classList.add('bg-gray-200', 'dark:bg-gray-700');
        localStorage.setItem('jobView', 'list');
    });
    
    const savedView = localStorage.getItem('jobView');
    if (savedView === 'grid') {
        gridBtn.click();
    }
}

// Newsletter subscription
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('newsletter-email').value;
        const button = newsletterForm.querySelector('button');
        
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        try {
            // Simulate API call - replace with actual endpoint
            await new Promise(resolve => setTimeout(resolve, 1000));
            showNotification('Subscribed successfully! Check your email.', 'success');
            newsletterForm.reset();
        } catch (error) {
            showNotification('Something went wrong. Please try again.', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = 'Subscribe';
        }
    });
}
</script>
@endpush

@endsection