@extends('layouts.app')

@section('title', $user->name . ' - WorkNepal Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Cover / Banner (optional future) -->
        <div class="h-32 md:h-48 bg-gradient-to-r from-red-600 to-red-400"></div>

        <div class="relative px-6 lg:px-10 pb-10">
            <!-- Avatar -->
            <div class="absolute -top-16 left-6 lg:left-10">
                <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-900 overflow-hidden bg-gray-200 dark:bg-gray-700 shadow-lg">
                    @if($user->profile_photo_path)
                        <img src="{{ $user->profilePhotoUrl }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-gray-500 dark:text-gray-400 uppercase">
                            {{ substr($user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Basic Info -->
            <div class="pt-20 lg:pt-24">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </h1>

                        @if($user->headline)
                            <p class="mt-1 text-xl text-gray-600 dark:text-gray-400">
                                {{ $user->headline }}
                            </p>
                        @endif

                        <div class="mt-3 flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($user->location)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $user->location }}
                                </span>
                            @endif

                            @if($user->date_of_birth)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years old
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        {{-- @if($isOwnProfile) --}}
                            <a href="{{ route('profile.edit') }}"
                               class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition-colors">
                                Edit Profile
                            </a>
                        {{-- @endif --}}
                    </div>
                </div>

                <!-- Summary -->
                @if($user->summary)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            About
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $user->summary }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Professional Sections -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

        <!-- Skills -->
        @if($user->skills?->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Skills
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->skills as $skill)
                        <span class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-sm font-medium">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Education -->
@if($user->education->isNotEmpty())
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
            Education
        </h3>
        <div class="space-y-8">
            @foreach($user->education as $edu)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $edu->degree }} in {{ $edu->field_of_study }}
                    </h4>
                    <p class="text-gray-700 dark:text-gray-300 mt-1">
                        {{ $edu->institution }} • {{ $edu->location ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $edu->duration }} • {{ $edu->is_current ? 'Currently studying' : '' }}
                    </p>
                    @if($edu->description)
                        <p class="mt-3 text-gray-600 dark:text-gray-400">
                            {{ $edu->description }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
    </div>

    <!-- Resume Download -->
    @if($user->resume_path)
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                Resume
            </h3>
            <a href="{{ asset('storage/' . $user->resume_path) }}"
               target="_blank"
               class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-md transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Resume (PDF)
            </a>
        </div>
    @endif

</div>
@endsection