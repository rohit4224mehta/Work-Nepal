@extends('layouts.app-jobseeker')

@section('dashboard-content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

        <!-- Left Column: Profile + Progress + Quick Actions -->
        <div class="lg:col-span-1 space-y-6">

            <!-- Profile Completion Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Your Profile Strength
                </h3>

                @php
                    $completion = auth()->user()->profileCompletionPercentage() ?? 0;
                @endphp

                <x-progress-bar :percentage="$completion" color="red" class="mb-4" />

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    {{ $completion }}% complete — finish to unlock better job matches
                </p>

                <ul class="space-y-2 text-sm">
                    @if(!auth()->user()->profile_photo_path)
                        <li class="flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <a href="{{ route('profile.edit') }}" class="hover:underline">Upload Profile Photo</a>
                        </li>
                    @endif

                    @if(!auth()->user()->resume_path)
                        <li class="flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <a href="{{ route('profile.edit') }}" class="hover:underline">Upload Resume (PDF)</a>
                        </li>
                    @endif

                    @if(auth()->user()->skills->isEmpty())
                        <li class="flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <a href="{{ route('profile.edit') }}" class="hover:underline">Add Skills</a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('jobs.index') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="text-sm font-medium">Search Jobs</span>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                        <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-sm font-medium">Complete Profile</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Middle Column: Suggested Jobs + Applications -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Suggested Jobs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        Recommended Jobs for You
                    </h3>
                    <a href="{{ route('jobs.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                        View All →
                    </a>
                </div>

                @if($suggestedJobs->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        Complete your profile to see personalized job recommendations
                    </p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($suggestedJobs as $job)
                            <x-card-job :job="$job" />
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        My Recent Applications
                    </h3>
                    <a href="{{ route('applications.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                        View All →
                    </a>
                </div>

                @if($recentApplications->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        You haven't applied to any jobs yet. Start exploring!
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                        {{ $application->job->title }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $application->job->company->name ?? 'Company' }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $application->status->color() }} bg-opacity-10">
                                    {{ $application->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Nepal-Specific Trust Banner -->
    <div class="mt-12 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-600 p-6 rounded-lg">
        <h4 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">
            Important: Foreign Job Safety
        </h4>
        <p class="text-red-700 dark:text-red-200">
            Always verify foreign job offers. Read official guidelines before applying.
        </p>
        <a href="{{ route('pages.foreign-safety') }}" class="mt-3 inline-block text-red-600 hover:underline font-medium">
            Read Safety Guidelines →
        </a>
    </div>

@endsection