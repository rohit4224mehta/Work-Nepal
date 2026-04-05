@extends('layouts.app')

@section('title', 'Saved Jobs - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Saved Jobs</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                    Jobs you've saved for later
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('jobs.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse More Jobs
                </a>
            </div>
        </div>
    </div>

    {{-- Saved Jobs List --}}
    @if($savedJobs->count() > 0)
        <div class="space-y-4">
            @foreach($savedJobs as $job)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
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
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-red-600">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    @if($job->is_featured)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Featured</span>
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
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex gap-2">
                                <button onclick="removeSavedJob({{ $job->id }})" 
                                        class="p-2 text-red-600 hover:text-red-700 transition-colors"
                                        title="Remove from saved">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <a href="{{ route('jobs.show', $job->slug) }}" 
                                   class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                    View Details
                                </a>
                            </div>
                            <span class="text-xs text-gray-500">
                                Saved {{ $job->pivot->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $savedJobs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Saved Jobs Yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Start saving jobs you're interested in. They'll appear here for easy access.
            </p>
            <a href="{{ route('jobs.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Browse Jobs
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function removeSavedJob(jobId) {
    if (!confirm('Are you sure you want to remove this job from your saved list?')) {
        return;
    }
    
    fetch(`/saved-jobs/${jobId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Job removed from saved', 'success');
            // Remove the job card from DOM
            const jobCard = document.querySelector(`button[onclick="removeSavedJob(${jobId})"]`).closest('.bg-white');
            if (jobCard) {
                jobCard.remove();
            }
            // Reload if no jobs left
            if (document.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-2xl').length === 0) {
                location.reload();
            }
        } else {
            showNotification(data.message || 'Failed to remove job', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endpush

@endsection