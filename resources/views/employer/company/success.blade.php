{{-- resources/views/employer/company/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Company Created Successfully - WorkNepal')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Success Header --}}
        <div class="p-8 text-center border-b border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Company Created Successfully! 🎉
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Your company profile has been created and is pending verification.
            </p>
        </div>

        {{-- Company Info --}}
        <div class="p-8">
            <div class="flex items-center gap-6 p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-8">
                <div class="w-20 h-20 rounded-xl bg-white dark:bg-gray-700 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-600">
                    @if($company->logo_path)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-gray-400">{{ substr($company->name, 0, 1) }}</span>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $company->industry }} • {{ $company->location }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pending Verification
                        </span>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">What happens next?</h3>
            
            <div class="space-y-4 mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-500 font-bold">1</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Verification Process</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Our team will review your company details within 24-48 hours. You'll receive an email once verified.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-500 font-bold">2</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Start Posting Jobs</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Once verified, you can post jobs and start receiving applications immediately.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 dark:text-blue-500 font-bold">3</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Add Team Members</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Invite colleagues to help manage job postings and applications.</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('employer.dashboard') }}" 
                   class="flex-1 px-6 py-4 bg-red-600 hover:bg-red-700 text-white text-center font-semibold rounded-xl transition-colors">
                    Go to Employer Dashboard
                </a>
                <a href="{{ route('employer.company.preview', $company) }}" 
                   class="flex-1 px-6 py-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-center font-semibold rounded-xl transition-colors">
                    View Company Profile
                </a>
            </div>
        </div>

        {{-- Tips --}}
        <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
            <h4 class="font-semibold text-gray-900 dark:text-white mb-3">💡 Pro Tips</h4>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Complete your company profile to get verified faster
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Add a professional logo to build trust with candidates
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Prepare your first job posting while waiting for verification
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection