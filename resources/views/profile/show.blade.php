@extends('layouts.app')

@section('title', ($user->name ?? 'User') . ' - WorkNepal Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 bg-gray-50 dark:bg-gray-900 min-h-screen">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Profile Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-10">
        <!-- Cover Banner -->
        <div class="h-48 bg-gradient-to-r from-red-600 to-red-400"></div>

        <div class="relative px-6 lg:px-12 pb-12">
            <!-- Avatar -->
            <div class="absolute -top-20 left-6 lg:left-12">
                <div class="w-40 h-40 rounded-full border-4 border-white dark:border-gray-800 shadow-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                    @if($user->profile_photo_path)
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl font-bold text-gray-500 dark:text-gray-400 uppercase bg-gray-200 dark:bg-gray-600">
                            {{ substr($user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Info -->
            <div class="pt-24 lg:pt-28">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $user->name }}
                        </h1>

                        @if($user->headline)
                            <p class="text-xl text-gray-700 dark:text-gray-300 font-medium">
                                {{ $user->headline }}
                            </p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-6 text-gray-600 dark:text-gray-400">
                            @if($user->location)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $user->location }}</span>
                                </div>
                            @endif

                            @if($user->date_of_birth)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years old</span>
                                </div>
                            @endif

                            @if($user->gender)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="capitalize">{{ $user->gender }}</span>
                                </div>
                            @endif

                            @if($user->email)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $user->email }}</span>
                                </div>
                            @endif

                            @if($user->mobile)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $user->mobile }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($isOwnProfile)
                        <div class="flex flex-wrap gap-4 mt-6 lg:mt-0">
                            <a href="{{ route('profile.edit') }}"
                               class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-md transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </a>
                            <a href="{{ route('profile.password') }}"
                               class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl transition-colors inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Change Password
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Summary -->
                @if($user->summary)
                    <div class="mt-10">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            About
                        </h3>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                                {{ $user->summary }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Professional Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Skills -->
        @if($user->skills && $user->skills->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                    Skills
                </h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($user->skills as $skill)
                        <span class="px-5 py-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-base font-medium border border-blue-200 dark:border-blue-800">
                            {{ $skill->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Education -->
        @if($user->education && $user->education->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                    Education
                </h3>
                <div class="space-y-8">
                    @foreach($user->education as $edu)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $edu->degree }} in {{ $edu->field_of_study }}
                            </h4>
                            <p class="text-lg text-gray-700 dark:text-gray-300 mt-1">
                                {{ $edu->institution }}
                                @if($edu->location)
                                    <span class="text-gray-500 dark:text-gray-400"> • {{ $edu->location }}</span>
                                @endif
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} - 
                                {{ $edu->is_current ? 'Present' : \Carbon\Carbon::parse($edu->end_date)->format('Y') }}
                                @if(!$edu->is_current && $edu->end_date)
                                    <span class="text-gray-500 dark:text-gray-500 ml-2">
                                        ({{ \Carbon\Carbon::parse($edu->start_date)->diffInYears(\Carbon\Carbon::parse($edu->end_date)) }} years)
                                    </span>
                                @endif
                            </p>
                            @if($edu->description)
                                <p class="mt-4 text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                                    {{ $edu->description }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Experience -->
        @if($user->experience && $user->experience->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Work Experience
                </h3>
                <div class="space-y-8">
                    @foreach($user->experience as $exp)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $exp->position }}
                            </h4>
                            <p class="text-lg text-gray-700 dark:text-gray-300 mt-1">
                                {{ $exp->company_name }}
                                @if($exp->location)
                                    <span class="text-gray-500 dark:text-gray-400"> • {{ $exp->location }}</span>
                                @endif
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - 
                                {{ $exp->is_current ? 'Present' : \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                @if(!$exp->is_current && $exp->end_date)
                                    <span class="text-gray-500 dark:text-gray-500 ml-2">
                                        ({{ \Carbon\Carbon::parse($exp->start_date)->diffInMonths(\Carbon\Carbon::parse($exp->end_date)) }} months)
                                    </span>
                                @endif
                            </p>
                            @if($exp->description)
                                <p class="mt-4 text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg whitespace-pre-wrap">
                                    {{ $exp->description }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Job Preferences -->
        @if($user->jobPreference)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Job Preferences
                </h3>
                <div class="space-y-4">
                    @if($user->jobPreference->preferred_location)
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Location:</span>
                            <span class="text-gray-900 dark:text-white">{{ $user->jobPreference->preferred_location }}</span>
                        </div>
                    @endif

                    @if($user->jobPreference->preferred_job_type)
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Job Type:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $user->jobPreference->preferred_job_type) as $type)
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-sm">
                                        {{ ucfirst(str_replace('-', ' ', $type)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($user->jobPreference->expected_salary)
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Salary:</span>
                            <span class="text-gray-900 dark:text-white">रू {{ number_format($user->jobPreference->expected_salary) }}/month</span>
                        </div>
                    @endif

                    @if($user->jobPreference->fresher)
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Level:</span>
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full text-sm">Fresher</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>

    <!-- Resume Section -->
    @if($user->resume_path)
        <div class="mt-10 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Resume / CV
            </h3>
            <a href="{{ Storage::url($user->resume_path) }}"
               target="_blank"
               class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-medium text-lg rounded-xl shadow-lg transition-colors">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Resume
                <span class="ml-2 text-sm opacity-75">({{ strtoupper(pathinfo($user->resume_path, PATHINFO_EXTENSION)) }})</span>
            </a>
        </div>
    @endif

    <!-- Contact Section (Only visible to employers/recruiters) -->
    @auth
        @if(auth()->user()->isEmployer() && !$isOwnProfile)
            <div class="mt-10 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($user->email)
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                <a href="mailto:{{ $user->email }}" class="text-lg font-medium text-gray-900 dark:text-white hover:text-red-600">
                                    {{ $user->email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($user->mobile)
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                                <a href="tel:{{ $user->mobile }}" class="text-lg font-medium text-gray-900 dark:text-white hover:text-red-600">
                                    {{ $user->mobile }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endauth

    <!-- Back to Search Button (for public viewing) -->
    @if(!$isOwnProfile)
        <div class="mt-10 text-center">
            <a href="{{ route('jobs.search') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-xl transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Job Search
            </a>
        </div>
    @endif

</div>
@endsection