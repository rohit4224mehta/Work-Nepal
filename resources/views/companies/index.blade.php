@extends('layouts.app')

@section('title', 'Companies - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Top Companies in Nepal</h1>
                <p class="text-xl text-red-100 max-w-3xl mx-auto">
                    Discover great places to work. Explore companies hiring now and find your dream job.
                </p>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-white">{{ number_format($stats['total_companies']) }}+</div>
                    <div class="text-red-100 mt-2">Verified Companies</div>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-white">{{ number_format($stats['total_jobs']) }}+</div>
                    <div class="text-red-100 mt-2">Active Jobs</div>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold text-white">{{ number_format($stats['active_recruiters']) }}+</div>
                    <div class="text-red-100 mt-2">Active Recruiters</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="GET" action="{{ route('companies.index') }}" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Companies</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by company name..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- Industry Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Industry</label>
                    <select name="industry" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <option value="">All Industries</option>
                        @foreach($industries as $industry)
                            <option value="{{ $industry }}" {{ request('industry') == $industry ? 'selected' : '' }}>
                                {{ $industry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Location Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                    <input type="text" 
                           name="location" 
                           value="{{ request('location') }}"
                           placeholder="City or Remote"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="flex justify-end mt-4 gap-3">
                <a href="{{ route('companies.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Clear Filters
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    {{-- Sort and Results Count --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <p class="text-gray-600 dark:text-gray-400">
                Showing <span class="font-semibold">{{ $companies->firstItem() }}</span> to 
                <span class="font-semibold">{{ $companies->lastItem() }}</span> of 
                <span class="font-semibold">{{ $companies->total() }}</span> companies
            </p>

            <div class="flex items-center gap-2 mt-4 sm:mt-0">
                <label class="text-sm text-gray-600 dark:text-gray-400">Sort by:</label>
                <select name="sort" onchange="window.location.href = '{{ route('companies.index') }}?sort=' + this.value + '&{{ http_build_query(request()->except('sort', 'page')) }}'"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="jobs" {{ request('sort') == 'jobs' ? 'selected' : '' }}>Most Jobs</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Companies Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($companies->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($companies as $company)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            {{-- Company Header --}}
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200 dark:border-gray-600">
                                    @if($company->logo_path)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-2xl font-bold text-gray-500 dark:text-gray-400 uppercase">
                                            {{ substr($company->name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white truncate">
                                        <a href="{{ route('companies.show', $company->slug) }}" class="hover:text-red-600">
                                            {{ $company->name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $company->verification_badge }}">
                                            {{ ucfirst($company->verification_status) }}
                                        </span>
                                        @if($company->industry)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $company->industry }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Company Details --}}
                            <div class="mt-4 space-y-2">
                                @if($company->location)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $company->location }}</span>
                                    </div>
                                @endif

                                @if($company->website)
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                                        </svg>
                                        <a href="{{ $company->website }}" target="_blank" class="truncate hover:text-red-600">
                                            {{ preg_replace('#^https?://#', '', $company->website) }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Description Preview --}}
                            @if($company->description)
                                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ Str::limit(strip_tags($company->description), 100) }}
                                </p>
                            @endif

                            {{-- Job Count and CTA --}}
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $company->job_postings_count }} active jobs
                                    </span>
                                </div>
                                <a href="{{ route('companies.show', $company->slug) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    View Profile
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $companies->links() }}
            </div>
        @else
            {{-- No Results --}}
            <div class="text-center py-16">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Companies Found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters</p>
                <a href="{{ route('companies.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700">
                    Clear All Filters
                </a>
            </div>
        @endif
    </div>

    {{-- Call to Action --}}
    <div class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Is your company hiring?</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    Join WorkNepal today and connect with thousands of talented professionals in Nepal.
                </p>
                <a href="{{ route('register') }}?role=employer" 
                   class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold text-lg rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Register as Employer
                </a>
            </div>
        </div>
    </div>
</div>
@endsection