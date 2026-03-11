@extends('layouts.app')

@section('title', $user->name . ' - WorkNepal Profile')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 bg-gray-50 min-h-screen">

    <!-- Profile Header Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-10">
        <!-- Cover Banner -->
        <div class="h-48 bg-gradient-to-r from-red-600 to-red-400"></div>

        <div class="relative px-6 lg:px-12 pb-12">
            <!-- Avatar -->
            <div class="absolute -top-20 left-6 lg:left-12">
                <div class="w-40 h-40 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gray-100">
                    @if($user->profile_photo_path)
                        <img src="{{ $user->profilePhotoUrl }}" alt="{{ $user->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl font-bold text-gray-500 uppercase">
                            {{ substr($user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Info -->
            <div class="pt-24 lg:pt-28">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            {{ $user->name }}
                        </h1>

                        @if($user->headline)
                            <p class="text-xl text-gray-700 font-medium">
                                {{ $user->headline }}
                            </p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-6 text-gray-600">
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
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($isOwnProfile)
                        <div class="flex gap-4 mt-6 lg:mt-0">
                            <a href="{{ route('profile.edit') }}"
                               class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-md transition-colors">
                                Edit Profile
                            </a>
                            <a href="{{ route('profile.password') }}"
                               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-xl transition-colors">
                                Change Password
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Summary -->
                @if($user->summary)
                    <div class="mt-10">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">About</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                            {{ $user->summary }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Professional Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Skills -->
        @if($user->skills && count($user->skills) > 0)
            <div class="bg-white rounded-2xl shadow border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Skills</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($user->skills as $skill)
                        <span class="px-5 py-2.5 bg-blue-50 text-blue-800 rounded-full text-base font-medium">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Education -->
        @if($user->education?->isNotEmpty())
            <div class="bg-white rounded-2xl shadow border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Education</h3>
                <div class="space-y-8">
                    @foreach($user->education as $edu)
                        <div class="border-b border-gray-200 pb-6 last:border-0">
                            <h4 class="text-xl font-semibold text-gray-900">
                                {{ $edu->degree }} • {{ $edu->field_of_study }}
                            </h4>
                            <p class="text-lg text-gray-700 mt-1">
                                {{ $edu->institution }} • {{ $edu->location ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 mt-1">
                                {{ $edu->duration }} • 
                                <span class="font-medium">
                                    {{ $edu->is_current ? 'Currently studying' : 'Completed' }}
                                </span>
                            </p>
                            @if($edu->description)
                                <p class="mt-4 text-gray-600">
                                    {{ $edu->description }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Experience -->
        @if($user->experience?->isNotEmpty())
            <div class="bg-white rounded-2xl shadow border border-gray-200 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Experience</h3>
                <div class="space-y-8">
                    @foreach($user->experience as $exp)
                        <div class="border-b border-gray-200 pb-6 last:border-0">
                            <h4 class="text-xl font-semibold text-gray-900">
                                {{ $exp->position }} at {{ $exp->company }}
                            </h4>
                            <p class="text-lg text-gray-700 mt-1">
                                {{ $exp->location ?? 'Remote' }} • {{ $exp->duration }}
                            </p>
                            @if($exp->description)
                                <p class="mt-4 text-gray-600 whitespace-pre-wrap">
                                    {{ $exp->description }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- Resume Section -->
    @if($user->resume_path)
        <div class="mt-10 bg-white rounded-2xl shadow border border-gray-200 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Resume / CV</h3>
            <a href="{{ asset('storage/' . $user->resume_path) }}"
               target="_blank"
               class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-medium text-lg rounded-xl shadow-lg transition-colors">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Resume (PDF)
            </a>
        </div>
    @endif

</div>
@endsection