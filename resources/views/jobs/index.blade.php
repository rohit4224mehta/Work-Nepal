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
                                   name="q" 
                                   value="{{ request('q') }}"
                                   placeholder="Job title, skills, or company" 
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900">
                        </div>
                        <div class="flex gap-2">
                            <select name="location" class="px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-gray-900 bg-white">
                                <option value="">All Nepal</option>
                                @foreach($filterOptions['locations'] as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
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
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif

                            {{-- Categories --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Category</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['categories'] as $category => $count)
                                        <label class="flex items-center justify-between">
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
                                    
                                    @if(request('category'))
                                        <a href="{{ route('jobs.index', array_merge(request()->except(['category', 'page']))) }}" 
                                           class="text-xs text-red-600 hover:text-red-700 mt-2 inline-block">
                                            Clear category
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Job Type --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Job Type</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['job_types'] as $value => $label)
                                        <label class="flex items-center">
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

                            {{-- Experience Level --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Experience Level</h4>
                                <div class="space-y-2">
                                    @foreach($filterOptions['experience_levels'] as $value => $label)
                                        <label class="flex items-center">
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

                            {{-- Quick Filters --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Quick Filters</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="fresher" 
                                               value="1"
                                               {{ request()->boolean('fresher') ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                               onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Fresher Friendly</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="foreign" 
                                               value="1"
                                               {{ request()->boolean('foreign') ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                                               onchange="this.form.submit()">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Foreign Jobs</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">Sort By</h4>
                                <select name="sort" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                        onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                    <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline (Earliest)</option>
                                </select>
                            </div>

                            {{-- Clear Filters --}}
                            @if(request()->hasAny(['category', 'job_type', 'experience', 'fresher', 'foreign', 'location', 'sort']))
                                <a href="{{ route('jobs.index', ['q' => request('q')]) }}" 
                                   class="block w-full text-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                                    Clear All Filters
                                </a>
                            @endif
                        </form>
                    </div>

                    {{-- Featured Jobs Sidebar --}}
                    @if($featuredJobs->isNotEmpty())
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">Featured Jobs</h4>
                            <div class="space-y-3">
                                @foreach($featuredJobs as $featured)
                                    <a href="{{ route('jobs.show', $featured->slug) }}" 
                                       class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:shadow-md transition">
                                        <h5 class="font-medium text-gray-900 dark:text-white text-sm mb-1">{{ $featured->title }}</h5>
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
                        <button class="p-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" title="Grid View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                        <button class="p-2 bg-red-600 text-white rounded-lg" title="List View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Jobs Grid --}}
                @if($jobs->count() > 0)
                    <div class="space-y-4">
                        @foreach($jobs as $job)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        {{-- Company Logo --}}
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($job->company && $job->company->logo_path)
                                                <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
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
                                                    <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-red-600">
                                                        {{ $job->title }}
                                                    </a>
                                                </h3>
                                                @if($job->is_featured)
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Featured</span>
                                                @endif
                                                @if($job->experience_level == 'entry')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Fresher</span>
                                                @endif
                                            </div>

                                            <p class="text-gray-600 dark:text-gray-400 mb-3">
                                                <a href="{{ route('companies.show', $job->company->slug) }}" class="hover:text-red-600">
                                                    {{ $job->company->name }}
                                                </a>
                                            </p>

                                            <div class="flex flex-wrap gap-3 text-sm">
                                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
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
                                                <button onclick="toggleSave({{ $job->id }})" 
                                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors"
                                                        title="{{ auth()->user()->savedJobs()->where('job_posting_id', $job->id)->exists() ? 'Remove from saved' : 'Save job' }}">
                                                    <svg class="w-6 h-6" fill="{{ auth()->user()->savedJobs()->where('job_posting_id', $job->id)->exists() ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
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
                                            <a href="{{ route('jobs.show', $job->slug) }}" 
                                               class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                                View Details
                                            </a>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @else
                    {{-- No Results --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Jobs Found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your search filters</p>
                        <a href="{{ route('jobs.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700">
                            Clear All Filters
                        </a>
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
                <p class="text-gray-600 dark:text-gray-400 mb-6">Be the first to know about new opportunities</p>
                <form class="max-w-md mx-auto flex gap-3">
                    <input type="email" 
                           placeholder="Enter your email" 
                           class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors whitespace-nowrap">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
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
            showNotification('success', data.message);
            // Reload to update UI
            setTimeout(() => location.reload(), 1000);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        }
    });
}

function showNotification(type, message) {
    // Your notification logic
    alert(message);
}
</script>
@endpush
@endsection