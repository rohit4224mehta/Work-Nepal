@extends('layouts.guest')

@section('title', 'WorkNepal – Nepal\'s #1 Job Search & Hiring Platform')

@section('meta_description', 'Find verified jobs in Nepal. ' . number_format($stats['active_jobs'] ?? 0) . '+ active jobs, ' . number_format($stats['companies_count'] ?? 0) . '+ companies, fresher friendly, foreign employment with safety info. Join Nepal\'s fastest growing job platform.')

@section('meta_keywords', 'jobs in nepal, career, employment, fresher jobs, foreign employment, hiring, recruitment, kathmandu jobs, remote jobs nepal')

@section('meta_og_title', 'WorkNepal - Find Your Dream Job in Nepal')
@section('meta_og_description', number_format($stats['active_jobs'] ?? 0) . '+ verified jobs • ' . number_format($stats['companies_count'] ?? 0) . '+ companies • Fresher friendly • Safe foreign employment')
@section('meta_og_image', asset('images/og-homepage.jpg'))

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    
    .counter-number {
        transition: all 0.3s ease;
    }
    
    .hover-scale {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    @keyframes pulse-ring {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
        }
    }
    
    .pulse-ring {
        animation: pulse-ring 2s infinite;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #dc2626;
        border-radius: 5px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #b91c1c;
    }
</style>
@endpush

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-red-600 via-red-500 to-orange-500 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-[url('/images/pattern.svg')] bg-repeat animate-pulse"></div>
    </div>
    
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 py-24 md:py-32 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-lg rounded-full mb-8 animate-fadeInUp">
                <svg class="w-4 h-4 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">Trusted by {{ number_format($stats['total_users'] ?? 0) }}+ Job Seekers & {{ number_format($stats['companies_count'] ?? 0) }}+ Companies</span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight animate-fadeInUp">
                Find Your <span class="text-yellow-300">Dream Job</span> in Nepal
            </h1>
            
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto font-light opacity-90 animate-fadeInUp delay-100">
                {{ number_format($stats['active_jobs'] ?? 0) }}+ verified jobs • {{ number_format($stats['fresher_jobs'] ?? 0) }}+ fresher friendly • {{ number_format($stats['foreign_jobs'] ?? 0) }}+ foreign jobs with safety info
            </p>

            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-4 md:p-6 animate-fadeInUp delay-200">
                <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Job title, skills, or company..." 
                            class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 text-lg"
                            value="{{ request('search') }}"
                        >
                    </div>
                    <div class="flex gap-2">
                        <select name="location" class="px-4 py-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 bg-white">
                            <option value="">All Nepal</option>
                            @foreach($locations ?? [] as $location)
                                <option value="{{ $location['name'] }}" {{ request('location') == $location['name'] ? 'selected' : '' }}>
                                    {{ $location['name'] }} ({{ number_format($location['count']) }} jobs)
                                </option>
                            @endforeach
                        </select>
                        <button 
                            type="submit" 
                            class="px-8 py-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl text-lg whitespace-nowrap"
                        >
                            Search Jobs
                        </button>
                    </div>
                </form>

                @if(!empty($popularSearches))
                <div class="flex flex-wrap items-center gap-3 mt-4 text-sm">
                    <span class="text-gray-500 font-medium">Popular:</span>
                    @foreach($popularSearches as $search)
                        <a href="{{ route('jobs.index', ['search' => $search]) }}" 
                           class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full transition">
                            {{ $search }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Trust Signals Banner --}}
<section class="bg-gray-50 border-y border-gray-200 py-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center items-center gap-8 text-sm text-gray-600">
            <span class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Government Compliant</span>
            <span class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Verified Recruiters</span>
            <span class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Free for Job Seekers</span>
            <span class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Privacy Protected</span>
        </div>
    </div>
</section>

{{-- Quick Stats with Counters --}}
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center group hover:transform hover:-translate-y-1 transition">
                <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2 counter-number" 
                     data-target="{{ $stats['active_jobs'] ?? 0 }}"
                     data-suffix="+">
                    0
                </div>
                <p class="text-gray-600 font-medium">Active Jobs</p>
                @if(($stats['active_jobs'] ?? 0) > 1000)
                    <span class="text-xs text-green-500 mt-1 inline-block">🔥 {{ number_format(($stats['active_jobs'] ?? 0) / 1000, 1) }}K+ opportunities</span>
                @endif
            </div>
            <div class="text-center group hover:transform hover:-translate-y-1 transition">
                <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2 counter-number" 
                     data-target="{{ $stats['companies_count'] ?? 0 }}"
                     data-suffix="+">
                    0
                </div>
                <p class="text-gray-600 font-medium">Companies</p>
                @if(($stats['companies_count'] ?? 0) > 500)
                    <span class="text-xs text-green-500 mt-1 inline-block">⭐ Top employers hiring</span>
                @endif
            </div>
            <div class="text-center group hover:transform hover:-translate-y-1 transition">
                <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2 counter-number" 
                     data-target="{{ $stats['freshers_hired'] ?? 0 }}"
                     data-suffix="+">
                    0
                </div>
                <p class="text-gray-600 font-medium">Freshers Hired</p>
                @if(($stats['freshers_hired'] ?? 0) > 500)
                    <span class="text-xs text-green-500 mt-1 inline-block">🎓 Start your career</span>
                @endif
            </div>
            <div class="text-center group hover:transform hover:-translate-y-1 transition">
                <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2 counter-number" 
                     data-target="{{ $stats['total_users'] ?? 0 }}"
                     data-suffix="+">
                    0
                </div>
                <p class="text-gray-600 font-medium">Job Seekers</p>
                @if(($stats['total_users'] ?? 0) > 10000)
                    <span class="text-xs text-green-500 mt-1 inline-block">👥 Join the community</span>
                @endif
            </div>
        </div>

        <div class="mt-8 text-center">
            <div class="inline-flex items-center gap-6 text-sm text-gray-500">
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +{{ number_format(($stats['jobs_added_this_month'] ?? 0), 0) }} jobs added this month
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ number_format(($stats['jobs_added_this_week'] ?? 0), 0) }} new jobs this week
                </span>
            </div>
        </div>
    </div>
</section>

{{-- Browse by Category Section --}}
@if(!empty($categories) && count($categories) > 0)
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Browse Jobs by Category</h2>
            <p class="text-xl text-gray-600">Find your perfect role from {{ number_format(count($categories)) }} categories with {{ number_format(array_sum(array_column($categories, 'count'))) }} opportunities</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('jobs.index', ['category' => $category['slug']]) }}" 
                   class="group bg-white rounded-xl p-6 text-center hover:shadow-lg transition border border-gray-200 hover-scale">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl {{ $category['bg_color'] }} flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-8 h-8 {{ $category['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category['icon_path'] }}" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $category['name'] }}</h3>
                    <p class="text-sm text-gray-500">{{ number_format($category['count']) }} jobs</p>
                    @if($category['count'] > 100)
                        <span class="inline-block mt-1 text-xs text-green-600 font-medium">🔥 Trending</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('jobs.index') }}" class="text-red-600 hover:text-red-700 font-semibold inline-flex items-center group">
                Browse All {{ number_format(count($categories)) }} Categories
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Jobs by Location Section --}}
@if(!empty($locations))
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Jobs by Location</h2>
            <p class="text-xl text-gray-600">Find opportunities near you or work remotely</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach(array_slice($locations, 0, 12) as $location)
                <a href="{{ route('jobs.index', ['location' => $location['name']]) }}" 
                   class="group bg-gray-50 rounded-xl p-6 text-center hover:shadow-lg transition border border-gray-200 hover-scale">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-red-100 flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $location['name'] }}</h3>
                    <p class="text-sm text-gray-500">{{ number_format($location['count']) }} jobs</p>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('jobs.index') }}" class="text-red-600 hover:text-red-700 font-semibold inline-flex items-center group">
                Browse All Locations
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Featured Jobs Section --}}
@if(isset($featuredJobs) && $featuredJobs->isNotEmpty())
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Jobs</h2>
                <p class="text-xl text-gray-600">Hand-picked opportunities from top companies</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="mt-4 md:mt-0 text-red-600 hover:text-red-700 font-semibold flex items-center group">
                View All Jobs
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredJobs as $job)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-200 overflow-hidden group hover-scale">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden">
                                @if($job->company && $job->company->logo_path)
                                    <img src="{{ Storage::url($job->company->logo_path) }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <span class="text-xl font-bold text-gray-500">{{ substr($job->company->name ?? 'C', 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg mb-1 line-clamp-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-red-600 transition">
                                        {{ $job->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm">{{ $job->company->name ?? 'Unknown Company' }}</p>
                            </div>
                            @if($job->is_featured)
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full whitespace-nowrap">Featured</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                            </span>
                            @if($job->location)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $job->location }}
                                </span>
                            @endif
                            @if($job->salary_range)
                                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">
                                    {{ $job->salary_range }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            @if($job->experience_level)
                                <span class="text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst($job->experience_level) }}
                                </span>
                            @endif
                            <span class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <a href="{{ route('jobs.show', $job->slug) }}" 
                           class="block w-full text-center px-4 py-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition font-medium">
                            Apply Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Features Grid --}}
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose WorkNepal?</h2>
            <p class="text-xl text-gray-600">Nepal's most trusted job platform with features designed for you</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($features as $feature)
                <div class="group text-center hover-scale">
                    <div class="w-20 h-20 {{ $feature['bg_color'] }} rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 {{ $feature['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon_path'] }}" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Employers Section --}}
@if(isset($featuredEmployers) && $featuredEmployers->isNotEmpty())
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Employers</h2>
                <p class="text-xl text-gray-600">Top companies hiring on WorkNepal</p>
            </div>
            <a href="{{ route('companies.index') }}" class="mt-4 md:mt-0 text-red-600 hover:text-red-700 font-semibold flex items-center group">
                View All Companies
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($featuredEmployers as $employer)
                <a href="{{ route('companies.show', $employer->slug) }}" 
                   class="group bg-white rounded-xl p-6 text-center hover:shadow-lg transition border border-gray-200 hover-scale">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden group-hover:scale-110 transition">
                        @if($employer->logo_path)
                            <img src="{{ Storage::url($employer->logo_path) }}" alt="{{ $employer->name }}" class="w-full h-full object-cover" loading="lazy">
                        @else
                            <span class="text-2xl font-bold text-gray-500">{{ substr($employer->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm mb-1 truncate">{{ $employer->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $employer->job_postings_count ?? 0 }} open jobs</p>
                    @if($employer->verification_status == 'verified')
                        <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                            ✓ Verified
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Success Stories / Testimonials --}}
@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Success Stories</h2>
            <p class="text-xl text-gray-600">Real people, real jobs, real success</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
                <div class="bg-gray-50 rounded-2xl p-8 relative hover-scale">
                    <svg class="w-10 h-10 text-red-200 absolute top-6 right-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    
                    <p class="text-gray-700 mb-6 relative z-10 line-clamp-4">{{ Str::limit($testimonial->content, 150) }}</p>
                    
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-600 to-red-400 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($testimonial->user->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-gray-900">{{ $testimonial->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $testimonial->user->headline ?? $testimonial->job_title ?? 'Job Seeker' }}</p>
                        </div>
                    </div>

                    @if($testimonial->rating)
                        <div class="flex mt-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Foreign Jobs Safety Banner --}}
<section class="py-12 bg-gradient-to-r from-red-600 to-orange-600">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center gap-6 mb-6 md:mb-0">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center pulse-ring">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white mb-2">Planning to work abroad?</h3>
                    <p class="text-white/90">Read our comprehensive safety guide for foreign employment</p>
                </div>
            </div>
            <a href="{{ route('pages.foreign-safety') }}" 
               class="px-8 py-4 bg-white text-red-600 font-bold rounded-xl hover:bg-gray-100 transition transform hover:-translate-y-0.5 shadow-lg whitespace-nowrap">
                Read Safety Guide →
            </a>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gray-900 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Start Your Career Journey?</h2>
        <p class="text-xl text-gray-300 mb-10 max-w-3xl mx-auto">Join {{ number_format($stats['total_users'] ?? 0) }}+ job seekers who found their dream jobs through WorkNepal</p>
        
        <div class="flex flex-wrap justify-center gap-4">
            @guest
                <a href="{{ route('register') }}" class="px-8 py-4 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition transform hover:-translate-y-0.5 shadow-xl">
                    Create Free Account
                </a>
            @endguest
            <a href="{{ route('employer.company.create') }}" class="px-8 py-4 border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-gray-900 transition transform hover:-translate-y-0.5">
                Post a Job
            </a>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mt-12 opacity-70">
            <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Verified Jobs</span>
            <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Free for Job Seekers</span>
            <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Privacy Protected</span>
            <span class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Government Compliant</span>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation with suffix support
    const counters = document.querySelectorAll('.counter-number');
    
    if (counters.length === 0) return;

    const formatNumber = (num) => {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-target'));
        const suffix = counter.getAttribute('data-suffix') || '';
        
        if (isNaN(target)) return;
        
        const duration = 2000;
        const startTime = performance.now();
        const startValue = 0;

        const updateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOutQuart = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.floor(easeOutQuart * target);
            counter.innerText = formatNumber(currentValue) + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = formatNumber(target) + suffix;
            }
        };

        requestAnimationFrame(updateCounter);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
    
    // Lazy loading for images
    if ('loading' in HTMLImageElement.prototype) {
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
    } else {
        // Fallback for older browsers
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});
</script>
@endpush