@extends('layouts.app')

@section('title', 'My Job Applications - WorkNepal')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                My Job Applications
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Track the status of all your applications in one place
            </p>
        </div>

        <a href="{{ route('jobs.index') }}"
           class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Find More Jobs
        </a>
    </div>

    <!-- Applications List -->
    @if($applications->isEmpty())
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 lg:p-12 text-center">
            <div class="mx-auto w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>

            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                No Applications Yet
            </h3>

            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                You haven't applied to any jobs. Start exploring opportunities now!
            </p>

            <a href="{{ route('jobs.index') }}"
               class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition-colors">
                Browse Jobs
            </a>
        </div>
    @else
        <!-- Applications Grid/List -->
        <div class="space-y-6">
            @foreach($applications as $application)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6 lg:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <!-- Left: Job Info -->
                        <div class="flex items-start gap-5 flex-1">
                            <!-- Company Logo -->
                            <div class="w-14 h-14 rounded-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0 overflow-hidden border border-gray-200 dark:border-gray-700">
                                @if($application->job->company->logo_path ?? false)
                                    <img src="{{ asset('storage/' . $application->job->company->logo_path) }}" alt="{{ $application->job->company->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 dark:text-gray-400 text-xl font-bold">
                                        {{ substr($application->job->company->name ?? 'C', 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Job Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $application->job->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $application->job->company->name ?? 'Company' }}
                                    • {{ $application->job->location ?? 'Remote' }}
                                </p>

                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $application->status === 'applied' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    {{ $application->status === 'viewed' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                    {{ $application->status === 'shortlisted' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                    {{ $application->status === 'hired' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : '' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Right: Date & Actions -->
                        <div class="flex flex-col sm:items-end gap-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Applied {{ $application->applied_at->diffForHumans() }}
                            </p>

                            <a href="{{ route('jobs.show', $application->job->slug) }}"
                               class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors">
                                View Job →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination (when you add it later) -->
        <div class="mt-10">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection