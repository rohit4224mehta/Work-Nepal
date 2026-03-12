@extends('layouts.app')

@section('title', 'Terms of Service - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Terms of Service</h1>
            <p class="text-xl text-red-100">
                Please read these terms carefully before using WorkNepal
            </p>
            <div class="mt-6 inline-flex items-center px-4 py-2 bg-white/10 rounded-lg">
                <svg class="w-5 h-5 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-white">Last Updated: {{ $lastUpdated ?? 'March 15, 2025' }}</span>
            </div>
        </div>
    </div>

    {{-- Quick Navigation --}}
    <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#agreement" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Agreement</a>
                <a href="#eligibility" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Eligibility</a>
                <a href="#accounts" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Accounts</a>
                <a href="#jobseekers" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Job Seekers</a>
                <a href="#employers" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Employers</a>
                <a href="#prohibited" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Prohibited</a>
                <a href="#content" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Content</a>
                <a href="#privacy" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Privacy</a>
                <a href="#liability" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Liability</a>
                <a href="#termination" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Termination</a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Introduction --}}
        <div id="agreement" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    Agreement to Terms
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    By accessing or using WorkNepal ("the Platform"), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, you may not access or use the Platform.
                </p>
                <p class="text-gray-600 dark:text-gray-400">
                    These Terms constitute a legally binding agreement between you and WorkNepal regarding your use of the Platform. We may modify these Terms from time to time, and your continued use constitutes acceptance of the modified Terms.
                </p>
            </div>
        </div>

        {{-- Eligibility --}}
        <div id="eligibility" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                    Eligibility
                </h2>
                <div class="space-y-4 text-gray-600 dark:text-gray-400">
                    <p>By using WorkNepal, you represent and warrant that:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>You are at least 16 years of age</li>
                        <li>You have the legal capacity to enter into binding contracts</li>
                        <li>You are not located in a country that is subject to trade sanctions</li>
                        <li>You will provide accurate and complete information</li>
                        <li>Your use complies with all applicable laws and regulations</li>
                    </ul>
                    <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                        <p class="text-sm text-yellow-800 dark:text-yellow-400">
                            <strong>Note for minors:</strong> Users under 18 must have parental consent to use the Platform.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Accounts --}}
        <div id="accounts" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    User Accounts
                </h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Account Creation</h3>
                        <p class="text-gray-600 dark:text-gray-400">You must create an account to access certain features. You agree to:</p>
                        <ul class="list-disc list-inside space-y-1 mt-2 text-gray-600 dark:text-gray-400 ml-4">
                            <li>Provide accurate and current information</li>
                            <li>Maintain the security of your credentials</li>
                            <li>Notify us immediately of unauthorized use</li>
                            <li>Not share your account with others</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Account Responsibility</h3>
                        <p class="text-gray-600 dark:text-gray-400">You are solely responsible for all activities that occur under your account. We are not liable for any loss or damage arising from your failure to comply with these obligations.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mt-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">✅ Do's</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Use strong passwords</li>
                                <li>• Log out from shared devices</li>
                                <li>• Update information regularly</li>
                            </ul>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">❌ Don'ts</h4>
                            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <li>• Share login credentials</li>
                                <li>• Use bots or automated access</li>
                                <li>• Create multiple accounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Job Seeker Responsibilities --}}
        <div id="jobseekers" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    Job Seeker Responsibilities
                </h2>
                <div class="space-y-4">
                    <p class="text-gray-600 dark:text-gray-400">As a job seeker, you agree to:</p>
                    
                    <div class="grid gap-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Accurate Information</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Provide truthful information in your profile, CV, and job applications</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Genuine Interest</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Only apply to positions you are genuinely interested in and qualified for</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Professional Conduct</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Communicate professionally with employers and respond to inquiries</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Report Suspicious Activity</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Report any fraudulent or suspicious job postings immediately</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <p class="text-sm text-blue-800 dark:text-blue-400">
                            <strong>💡 Tip:</strong> Keep your profile updated and respond to employer messages promptly to increase your chances of getting hired.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employer Responsibilities --}}
        <div id="employers" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                    </span>
                    Employer Responsibilities
                </h2>
                <div class="space-y-4">
                    <p class="text-gray-600 dark:text-gray-400">As an employer, you agree to:</p>

                    <div class="grid gap-4">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Genuine Job Postings</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Post only genuine job opportunities with accurate descriptions and requirements</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Timely Responses</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Respond to applications in a timely manner and update application statuses</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Fair Treatment</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Treat all applicants fairly and without discrimination</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Company Verification</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Maintain accurate company information and cooperate with verification</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                        <p class="text-sm text-yellow-800 dark:text-yellow-400">
                            <strong>⚠️ Important:</strong> Posting fraudulent jobs or collecting application fees may result in immediate account suspension and legal action.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Prohibited Activities --}}
        <div id="prohibited" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </span>
                    Prohibited Activities
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-red-600 dark:text-red-500 mb-3">🚫 Fraud & Deception</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Posting fake or misleading job listings
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Collecting money from job seekers
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Impersonating companies or individuals
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Phishing or collecting sensitive data
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-red-600 dark:text-red-500 mb-3">🚫 Harassment & Abuse</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Harassing other users
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Discriminatory job postings
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Hate speech or offensive content
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Stalking or intimidation
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-red-600 dark:text-red-500 mb-3">🚫 Technical Abuse</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Automated scraping or bots
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Hacking or security testing
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Spam or bulk messaging
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Malware or harmful code
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-red-600 dark:text-red-500 mb-3">🚫 Content Violations</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Illegal or regulated content
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Copyright infringement
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Adult or explicit content
                            </li>
                            <li class="flex items-start">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full mt-2 mr-2"></span>
                                Pyramid schemes or MLM
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                    <p class="text-sm text-red-800 dark:text-red-400">
                        <strong>⚠️ Violation Consequences:</strong> Accounts engaged in prohibited activities may be immediately suspended, terminated, and reported to authorities.
                    </p>
                </div>
            </div>
        </div>

        {{-- Content Ownership --}}
        <div id="content" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </span>
                    Content Ownership & License
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Your Content</h3>
                        <p class="text-gray-600 dark:text-gray-400">You retain ownership of all content you submit (profile info, CVs, job postings). By submitting content, you grant WorkNepal a worldwide, non-exclusive license to:</p>
                        <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-400 ml-4">
                            <li>Host, store, and display your content</li>
                            <li>Make your content available to other users</li>
                            <li>Use content for platform improvements</li>
                            <li>Create anonymized analytics</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Platform Content</h3>
                        <p class="text-gray-600 dark:text-gray-400">All platform content (logos, design, text, software) is owned by WorkNepal and protected by intellectual property laws. You may not copy, modify, or distribute platform content without permission.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Limitation of Liability --}}
        <div id="liability" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Limitation of Liability</h2>
                
                <div class="space-y-4 text-gray-600 dark:text-gray-400">
                    <p>To the maximum extent permitted by law, WorkNepal shall not be liable for:</p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Any indirect, incidental, or consequential damages</li>
                        <li>Loss of profits, data, or opportunities</li>
                        <li>Employment outcomes or decisions made by employers</li>
                        <li>Third-party actions or content</li>
                        <li>Service interruptions or technical issues</li>
                    </ul>
                    <p class="mt-4">Our total liability shall not exceed the amount you paid us (if any) in the past 12 months.</p>
                </div>
            </div>
        </div>

        {{-- Termination --}}
        <div id="termination" class="mb-12 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Termination</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">By You</h3>
                        <p class="text-gray-600 dark:text-gray-400">You may delete your account at any time through account settings or by contacting support.</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">By Us</h3>
                        <p class="text-gray-600 dark:text-gray-400">We may suspend or terminate accounts for:</p>
                        <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-400 ml-4">
                            <li>Violation of these Terms</li>
                            <li>Fraudulent or illegal activity</li>
                            <li>Inactivity for extended periods</li>
                            <li>At our discretion with notice</li>
                        </ul>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <strong>Upon termination:</strong> Your access ceases, but we may retain certain information as required by law or for legitimate business purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Governing Law --}}
        <div class="mb-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Governing Law</h2>
                <p class="text-gray-600 dark:text-gray-400">These Terms shall be governed by the laws of Nepal. Any disputes shall be resolved in the courts of Kathmandu, Nepal.</p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Questions About These Terms?</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                If you have any questions about these Terms of Service, please contact our legal team.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('pages.contact') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700">
                    Contact Support
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                <a href="mailto:legal@worknepal.com" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700">
                    Email Legal Team
                </a>
            </div>
        </div>
    </div>
</div>
@endsection