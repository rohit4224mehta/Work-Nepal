@extends('layouts.app')

@section('title', $company->name . ' - Company Profile - WorkNepal')

@section('meta_description', Str::limit(strip_tags($company->description), 160))
@section('meta_keywords', $company->industry . ', ' . $company->location . ', jobs, careers')

@push('meta_tags')
    <meta property="og:title" content="{{ $company->name }} - WorkNepal">
    <meta property="og:description" content="{{ Str::limit(strip_tags($company->description), 200) }}">
    <meta property="og:image" content="{{ $company->logo_url ?? asset('images/default-company.png') }}">
    <meta property="og:url" content="{{ route('companies.show', $company->slug) }}">
@endpush

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen">
    
    {{-- Company Header with Cover Image --}}
    <div class="relative">
        {{-- Cover Image --}}
        <div class="h-64 md:h-80 bg-gradient-to-r from-red-600 to-red-700 overflow-hidden">
            @if($company->cover_path)
                <img src="{{ Storage::url($company->cover_path) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0 bg-[url('/images/pattern.svg')] bg-repeat"></div>
                </div>
            @endif
        </div>

        {{-- Company Info Overlay --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative -mt-24 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="flex items-start gap-6">
                        {{-- Logo --}}
                        <div class="w-24 h-24 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                            @if($company->logo_path)
                                <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl font-bold text-gray-500 dark:text-gray-400 uppercase">
                                    {{ substr($company->name, 0, 1) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h1>
                                @if($company->verification_status == 'verified')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap gap-4 text-gray-600 dark:text-gray-400">
                                @if($company->industry)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                        </svg>
                                        {{ $company->industry }}
                                    </span>
                                @endif
                                
                                @if($company->location)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $company->location }}
                                    </span>
                                @endif

                                @if($company->founded_year)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Est. {{ $company->founded_year }}
                                    </span>
                                @endif

                                @if($company->size)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        {{ $company->size }} employees
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" 
                               class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                                </svg>
                                Website
                            </a>
                        @endif

                        @auth
                            @if(auth()->user()->canAccessCompany($company))
                                <a href="{{ route('employer.company.team', $company) }}" 
                                   class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Manage Company
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column - Company Info --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- About Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        About
                    </h2>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($company->description ?? 'No description provided.')) !!}
                    </div>
                </div>

                {{-- Contact Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Contact
                    </h2>

                    @if($company->contact_email)
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <a href="mailto:{{ $company->contact_email }}" class="text-red-600 hover:text-red-700">
                                    {{ $company->contact_email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($company->phone)
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Phone</p>
                                <a href="tel:{{ $company->phone }}" class="text-red-600 hover:text-red-700">
                                    {{ $company->phone }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($company->location)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Location</p>
                                <p class="text-gray-900 dark:text-white">{{ $company->location }}</p>
                                @if($company->headquarters && $company->headquarters != $company->location)
                                    <p class="text-xs text-gray-500">Headquarters: {{ $company->headquarters }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Social Links --}}
                @php
                    // Decode social links if it's a string
                    $socialLinks = is_string($company->social_links) 
                        ? json_decode($company->social_links, true) 
                        : ($company->social_links ?? []);
                @endphp
                
                @if(!empty($socialLinks) && (($socialLinks['facebook'] ?? '') || ($socialLinks['twitter'] ?? '') || ($socialLinks['linkedin'] ?? '')))
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Follow Us</h2>
                        <div class="flex gap-3">
                            @if($socialLinks['facebook'] ?? '')
                                <a href="{{ $socialLinks['facebook'] }}" target="_blank" 
                                   class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                                    </svg>
                                </a>
                            @endif

                            @if($socialLinks['twitter'] ?? '')
                                <a href="{{ $socialLinks['twitter'] }}" target="_blank" 
                                   class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500 transition transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.33-12.342c0-.213-.005-.425-.014-.636A10 10 0 0023.953 4.57z"/>
                                    </svg>
                                </a>
                            @endif

                            @if($socialLinks['linkedin'] ?? '')
                                <a href="{{ $socialLinks['linkedin'] }}" target="_blank" 
                                   class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-blue-800 transition transform hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Culture Photos --}}
                @php
                    // Decode culture images if it's a string
                    $cultureImages = is_string($company->culture_images) 
                        ? json_decode($company->culture_images, true) 
                        : ($company->culture_images ?? []);
                    
                    // Filter out empty values
                    $cultureImages = array_filter($cultureImages);
                @endphp
                
                @if(!empty($cultureImages))
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Company Culture</h2>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($cultureImages as $image)
                                @if($image)
                                    <a href="{{ Storage::url($image) }}" target="_blank" class="block aspect-square rounded-lg overflow-hidden hover:opacity-90 transition">
                                        <img src="{{ Storage::url($image) }}" alt="Company culture" class="w-full h-full object-cover" loading="lazy">
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Jobs --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Jobs Header --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Open Positions
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $company->active_jobs_count }} active jobs at {{ $company->name }}
                            </p>
                        </div>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" 
                               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm">
                                View Career Page
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Jobs List --}}
                @if($company->jobPostings->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($company->jobPostings as $job)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            <a href="{{ route('jobs.show', $job->slug ?? $job->id) }}" class="hover:text-red-600">
                                                {{ $job->title }}
                                            </a>
                                        </h3>
                                        
                                        <div class="flex flex-wrap gap-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $job->location ?? 'Nepal' }}
                                            </span>
                                            
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                            </span>
                                            
                                            @if($job->salary_range)
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $job->salary_range }}
                                                </span>
                                            @endif

                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Posted {{ $job->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('jobs.show', $job->slug ?? $job->id) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors whitespace-nowrap">
                                        View Job
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($allActiveJobsCount > 5)
                        <div class="text-center mt-8">
                            <a href="{{ route('jobs.index', ['company' => $company->id]) }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-xl transition-colors">
                                View All {{ $allActiveJobsCount }} Jobs
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Open Positions</h3>
                        <p class="text-gray-600 dark:text-gray-400">There are currently no open positions at {{ $company->name }}. Please check back later.</p>
                    </div>
                @endif

                {{-- Company Video --}}
                @if($company->video_link)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Company Video</h2>
                        <div class="aspect-video rounded-lg overflow-hidden">
                            <iframe class="w-full h-full" src="{{ $company->video_link }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Similar Companies --}}
    @if($similarCompanies->isNotEmpty())
        <div class="border-t border-gray-200 dark:border-gray-700 mt-12 pt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Similar Companies</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($similarCompanies as $similar)
                        <a href="{{ route('companies.show', $similar->slug) }}" 
                           class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center hover:shadow-lg transition">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($similar->logo_path)
                                    <img src="{{ Storage::url($similar->logo_path) }}" alt="{{ $similar->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xl font-bold text-gray-500">{{ substr($similar->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <h3 class="font-medium text-gray-900 dark:text-white text-sm mb-1 truncate">{{ $similar->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $similar->active_jobs_count ?? 0 }} jobs</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Share Company Section --}}
    <div class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Share this company:</span>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank"
                           class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank"
                           class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center text-white hover:bg-blue-800 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text=Check out {{ $company->name }} on WorkNepal" target="_blank"
                           class="w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.33-12.342c0-.213-.005-.425-.014-.636A10 10 0 0023.953 4.57z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <button onclick="window.print()" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Profile
                </button>
            </div>
        </div>
    </div>
</div>
@endsection