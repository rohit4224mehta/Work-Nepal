@extends('layouts.app')

@section('title', $company->name . ' - Company Profile - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Company Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
        
        {{-- Cover Image --}}
        @if($company->cover_path)
            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $company->cover_url }}')"></div>
        @else
            <div class="h-48 bg-gradient-to-r from-red-600 to-red-400"></div>
        @endif

        <div class="relative px-6 pb-6">
            {{-- Logo --}}
            <div class="absolute -top-16 left-6">
                <div class="w-32 h-32 rounded-xl bg-white dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-lg overflow-hidden">
                    @if($company->logo_path)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-600">
                            <span class="text-3xl font-bold text-gray-500 dark:text-gray-400">
                                {{ substr($company->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Company Info --}}
            <div class="pt-20">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h1>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-gray-600 dark:text-gray-400">
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
                                    </svg>
                                    {{ $company->location }}
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($company->verification_status == 'verified') bg-green-100 text-green-800
                                @elseif($company->verification_status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($company->verification_status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if(auth()->id() === $company->owner_id)
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('employer.dashboard') }}" 
                               class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                                Go to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Company Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column - Company Info --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- About Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">About</h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ $company->description ?? 'No description provided.' }}
                </p>
                
                @if($company->founded_year || $company->size || $company->website)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                        @if($company->founded_year)
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Founded: {{ $company->founded_year }}</span>
                            </div>
                        @endif
                        
                        @if($company->size)
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Size: {{ $company->size }}</span>
                            </div>
                        @endif
                        
                        @if($company->website)
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                                </svg>
                                <a href="{{ $company->website }}" target="_blank" class="text-red-600 hover:text-red-700">
                                    {{ $company->website }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Contact Card --}}
            @if($company->contact_email || $company->phone)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Contact</h2>
                    <div class="space-y-3">
                        @if($company->contact_email)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $company->contact_email }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600">
                                    {{ $company->contact_email }}
                                </a>
                            </div>
                        @endif
                        
                        @if($company->phone)
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $company->phone }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600">
                                    {{ $company->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Social Links --}}
            @if($company->social_links && (($company->social_links['facebook'] ?? '') || ($company->social_links['twitter'] ?? '') || ($company->social_links['linkedin'] ?? '')))
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Follow Us</h2>
                    <div class="flex gap-4">
                        @if($company->social_links['facebook'] ?? '')
                            <a href="{{ $company->social_links['facebook'] }}" target="_blank" class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </a>
                        @endif
                        
                        @if($company->social_links['twitter'] ?? '')
                            <a href="{{ $company->social_links['twitter'] }}" target="_blank" class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white hover:bg-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.33-12.342c0-.213-.005-.425-.014-.636A10 10 0 0023.953 4.57z"/>
                                </svg>
                            </a>
                        @endif
                        
                        @if($company->social_links['linkedin'] ?? '')
                            <a href="{{ $company->social_links['linkedin'] }}" target="_blank" class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white hover:bg-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column - Jobs & Culture --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Active Jobs --}}
            @if($company->jobPostings->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Open Positions</h2>
                    <div class="space-y-4">
                        @foreach($company->jobPostings as $job)
                            <div class="border-b border-gray-200 dark:border-gray-700 last:border-0 pb-4 last:pb-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-red-600">
                                                {{ $job->title }}
                                            </a>
                                        </h3>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 text-xs rounded-full">
                                                {{ ucfirst($job->job_type) }}
                                            </span>
                                            @if($job->location)
                                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                                                    {{ $job->location }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ $job->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Culture Photos --}}
            @if($company->culture_images && count(array_filter($company->culture_images)) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Company Culture</h2>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($company->culture_images as $image)
                            @if($image)
                                <div class="aspect-square rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Culture" class="w-full h-full object-cover">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Company Video --}}
            @if($company->video_link)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Company Video</h2>
                    <div class="aspect-video rounded-lg overflow-hidden">
                        <iframe class="w-full h-full" src="{{ $company->video_link }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Back to Dashboard Button --}}
    <div class="mt-8 text-center">
        <a href="{{ route('employer.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection