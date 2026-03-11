@extends('layouts.app')

@section('title', 'Job Seeker Dashboard - WorkNepal')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

    <!-- LEFT COLUMN -->
    <div class="space-y-6">

        <!-- Profile Strength -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Your Profile Strength
            </h3>

            @php
                $user = auth()->user();
                $completion = $user->profileCompletionPercentage() ?? 0;
            @endphp

            <x-progress-bar :percentage="$completion" color="red" class="mb-4"/>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                {{ $completion }}% complete — finish your profile to get better job recommendations.
            </p>

            <ul class="space-y-2 text-sm">

                @unless($user->profile_photo_path)
                <li class="flex items-center text-red-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01"/>
                    </svg>
                    <a href="{{ route('profile.edit') }}" class="hover:underline">
                        Upload Profile Photo
                    </a>
                </li>
                @endunless

                @unless($user->resume_path)
                <li class="flex items-center text-red-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6"/>
                    </svg>
                    <a href="{{ route('profile.edit') }}" class="hover:underline">
                        Upload Resume
                    </a>
                </li>
                @endunless

                @if($user->skills?->isEmpty())
                <li class="flex items-center text-red-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5"/>
                    </svg>
                    <a href="{{ route('profile.edit') }}" class="hover:underline">
                        Add Skills
                    </a>
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

                    <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6"/>
                    </svg>

                    <span class="text-sm font-medium">Search Jobs</span>

                </a>

                <a href="{{ route('applications.index') }}"
                   class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">

                    <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6"/>
                    </svg>

                    <span class="text-sm font-medium">My Applications</span>

                </a>

                <a href="{{ route('saved.jobs') }}"
                   class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">

                    <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 5v14l7-5 7 5V5z"/>
                    </svg>

                    <span class="text-sm font-medium">Saved Jobs</span>

                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">

                    <svg class="w-8 h-8 text-red-600 mb-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0"/>
                    </svg>

                    <span class="text-sm font-medium">Edit Profile</span>

                </a>

            </div>

        </div>

        <!-- Become Employer -->
        @unless($user->hasRole('employer'))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Become an Employer
            </h3>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Want to hire talent? Create a company profile and start posting jobs.
            </p>

            <a href="{{ route('employer.company.create') }}"
               class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">

                Create Company

            </a>

        </div>
        @endunless

    </div>

    <!-- RIGHT COLUMN -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Recommended Jobs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">

            <div class="flex justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Recommended Jobs
                </h3>

                <a href="{{ route('jobs.index') }}"
                   class="text-red-600 text-sm hover:underline">

                    View All →

                </a>
            </div>

            @if($suggestedJobs->isEmpty())

                <p class="text-center text-gray-500 py-8">
                    Complete your profile to get job recommendations.
                </p>

            @else

                <div class="grid md:grid-cols-2 gap-6">

                    @foreach($suggestedJobs as $job)

                        <x-card-job :job="$job"/>

                    @endforeach

                </div>

            @endif

        </div>

        <!-- Recent Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">

            <div class="flex justify-between mb-6">

                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    Recent Applications
                </h3>

                <a href="{{ route('applications.index') }}"
                   class="text-red-600 text-sm hover:underline">

                    View All →

                </a>

            </div>

            @if($recentApplications->isEmpty())

                <p class="text-center text-gray-500 py-8">
                    You haven't applied to any jobs yet.
                </p>

            @else

                <div class="space-y-4">

                    @foreach($recentApplications as $application)

                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-between items-center">

                            <div>

                                <h4 class="font-medium text-gray-900 dark:text-white">
                                    {{ $application->job->title }}
                                </h4>

                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $application->job->company->name ?? 'Company' }}
                                </p>

                            </div>

                            <span class="text-xs px-3 py-1 rounded-full
                                {{ $application->status === 'applied' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $application->status === 'shortlisted' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">

                                {{ ucfirst($application->status) }}

                            </span>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</div>

<!-- Safety Banner -->
<div class="mt-12 bg-red-50 border-l-4 border-red-600 p-6 rounded-lg">

    <h4 class="text-lg font-semibold text-red-800 mb-2">
        Foreign Job Safety Notice
    </h4>

    <p class="text-red-700">
        Always verify foreign job offers before applying.
    </p>

    <a href="{{ route('pages.foreign-safety') }}"
       class="text-red-600 hover:underline mt-2 inline-block">

        Read Safety Guidelines →

    </a>

</div>

@endsection