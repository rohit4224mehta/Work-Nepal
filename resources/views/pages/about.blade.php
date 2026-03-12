@extends('layouts.app')

@section('title', 'About Us - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900">
    
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-r from-red-600 to-red-700 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <h1 class="text-4xl lg:text-6xl font-bold mb-6 text-center">
                Connecting Nepali Talent with<br>Global Opportunities
            </h1>
            <p class="text-xl lg:text-2xl text-center text-red-100 max-w-3xl mx-auto">
                WorkNepal is Nepal's fastest-growing job platform, trusted by 10,000+ employers and 100,000+ job seekers.
            </p>
        </div>
        {{-- Decorative wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="fill-current text-white dark:text-gray-900" viewBox="0 0 1440 120">
                <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </div>

    {{-- Stats Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 dark:text-red-500">{{ number_format($stats['jobs']) }}+</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Active Jobs</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 dark:text-red-500">{{ number_format($stats['companies']) }}+</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Verified Companies</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 dark:text-red-500">{{ number_format($stats['users']) }}+</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Job Seekers</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 dark:text-red-500">{{ number_format($stats['placements']) }}+</div>
                <div class="text-gray-600 dark:text-gray-400 mt-2">Successful Placements</div>
            </div>
        </div>
    </div>

    {{-- Our Story --}}
    <div class="bg-gray-50 dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Our Story</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">
                        WorkNepal was born from a simple observation: talented Nepali professionals struggle to find genuine opportunities, while employers struggle to find the right talent.
                    </p>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">
                        Founded in 2025, we started with a mission to create a trusted platform where job seekers and employers can connect with confidence. Every job posting is verified, every company is screened, and every application matters.
                    </p>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Today, we're proud to be Nepal's most trusted job platform, helping thousands find their dream jobs and companies build their dream teams.
                    </p>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/about/office.jpg') }}" alt="WorkNepal Office" class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-red-600 text-white p-6 rounded-2xl shadow-xl">
                        <div class="text-3xl font-bold">2025</div>
                        <div class="text-sm opacity-90">Founded in Kathmandu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Our Values --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">Our Core Values</h2>
        <div class="grid md:grid-cols-4 gap-8">
            @foreach($values as $value)
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($value['icon'] == 'shield-check')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        @elseif($value['icon'] == 'users')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        @elseif($value['icon'] == 'eye')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        @endif
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $value['title'] }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ $value['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Team Section --}}
    <div class="bg-gray-50 dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">Leadership Team</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($team as $member)
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
                    <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $member['name'] }}</h3>
                        <p class="text-red-600 dark:text-red-500 mb-3">{{ $member['role'] }}</p>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $member['bio'] }}</p>
                        <div class="mt-4 flex space-x-3">
                            <a href="{{ $member['linkedin'] }}" class="text-gray-400 hover:text-red-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Testimonials --}}
    @if($testimonials->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">What People Say</h2>
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div class="text-yellow-400 flex">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 {{ $i < $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-400 italic mb-4">"{{ $testimonial->content }}"</p>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full mr-3"></div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $testimonial->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $testimonial->user->headline ?? 'Job Seeker' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection