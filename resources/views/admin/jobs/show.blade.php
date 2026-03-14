@extends('layouts.admin')

@section('title', $job->title . ' - Job Details - WorkNepal Admin')

@section('header', 'Job Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $job->title }}</h2>
                @if($job->verification_status == 'verified')
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Verified</span>
                @elseif($job->verification_status == 'pending')
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>
                @elseif($job->verification_status == 'rejected')
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Rejected</span>
                @endif
                @if($job->is_featured)
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">Featured</span>
                @endif
            </div>
            
            <div class="flex gap-3">
                @if($job->verification_status == 'pending')
                    <form method="POST" action="{{ route('admin.jobs.approve', $job) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Approve Job
                        </button>
                    </form>
                    
                    <button type="button" 
                            @click="showRejectModal = true"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject
                    </button>
                @endif
                
                <form method="POST" action="{{ route('admin.jobs.feature', $job) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 {{ $job->is_featured ? 'bg-yellow-600' : 'bg-purple-600' }} text-white rounded-lg hover:opacity-90 transition">
                        {{ $job->is_featured ? 'Unfeature Job' : 'Feature Job' }}
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.jobs.toggle-status', $job) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 {{ $job->status == 'active' ? 'bg-orange-600' : 'bg-green-600' }} text-white rounded-lg hover:opacity-90 transition">
                        {{ $job->status == 'active' ? 'Close Job' : 'Activate Job' }}
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" 
                      onsubmit="return confirm('Permanently delete this job? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Job
                    </button>
                </form>
            </div>
        </div>

        {{-- Job Overview Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Job Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Basic Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h4>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Job ID</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $job->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Title</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $job->title }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Category</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $job->category ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Job Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Experience Level</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($job->experience_level) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Location</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $job->location ?? 'Not specified' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Salary Range</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $job->salary_range ?? 'Not disclosed' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Company Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company</h4>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if($job->company->logo_path)
                                <img src="{{ Storage::url($job->company->logo_path) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl font-bold text-gray-500">{{ substr($job->company->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $job->company->name }}</p>
                            <p class="text-sm text-gray-500">{{ $job->company->industry ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Verification:</span>
                            @if($job->company->verification_status == 'verified')
                                <span class="text-green-600">Verified</span>
                            @else
                                <span class="text-yellow-600">Pending</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Location:</span>
                            <span>{{ $job->company->location ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.companies.show', $job->company) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View Company Profile →
                        </a>
                    </div>
                </div>

                {{-- Dates Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Important Dates</h4>
                    
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Posted:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $job->created_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Last Updated:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $job->updated_at->format('M d, Y h:i A') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Deadline:</dt>
                            <dd class="text-sm font-medium {{ $job->deadline && \Carbon\Carbon::parse($job->deadline)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('M d, Y') : 'Not set' }}
                            </dd>
                        </div>
                        @if($job->deadline)
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($job->deadline), false);
                            @endphp
                            @if($daysLeft > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Days Left:</dt>
                                    <dd class="text-sm font-medium {{ $daysLeft < 7 ? 'text-yellow-600' : 'text-green-600' }}">
                                        {{ round($daysLeft) }} days
                                    </dd>
                                </div>
                            @endif
                        @endif
                    </dl>
                </div>

                {{-- Posted By Card --}}
                @if($job->postedBy)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Posted By</h4>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold">
                                {{ substr($job->postedBy->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $job->postedBy->name }}</p>
                                <p class="text-sm text-gray-500">{{ $job->postedBy->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Detailed Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_applications'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['viewed_applications'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Viewed</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['shortlisted'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Shortlisted</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Hired</div>
                    </div>
                </div>

                {{-- Description Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Job Description</h4>
                    <div class="prose max-w-none dark:prose-invert">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                {{-- Requirements Card --}}
                @if($job->requirements)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Requirements</h4>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                @endif

                {{-- Benefits Card --}}
                @if($job->benefits)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Benefits</h4>
                        <div class="prose max-w-none dark:prose-invert">
                            {!! nl2br(e($job->benefits)) !!}
                        </div>
                    </div>
                @endif

                {{-- Skills Card --}}
                @if($job->skills)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Required Skills</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $job->skills) as $skill)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Recent Applications --}}
                @if($job->applications->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Applications</h4>
                            <a href="{{ route('admin.applications.index', ['job' => $job->id]) }}" 
                               class="text-red-600 hover:text-red-700 text-sm font-medium">
                                View All →
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($job->applications as $application)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-500 flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($application->applicant->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $application->applicant->name }}</p>
                                            <p class="text-xs text-gray-500">Applied {{ $application->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($application->status == 'applied') bg-blue-100 text-blue-800
                                        @elseif($application->status == 'shortlisted') bg-green-100 text-green-800
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div x-data="{ showRejectModal: false }" 
     x-show="showRejectModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Job</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Reject: {{ $job->title }}</p>
            <form method="POST" action="{{ route('admin.jobs.reject', $job) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Please provide a reason for rejecting this job..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showRejectModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject Job
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection