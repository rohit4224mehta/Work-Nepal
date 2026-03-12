@extends('layouts.app')

@section('title', 'Help Center - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900 min-h-screen">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">How can we help you?</h1>
            <p class="text-xl text-red-100 mb-8">Search our help center or browse topics below</p>
            
            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           placeholder="Search for help articles, FAQs, topics..." 
                           class="w-full px-6 py-4 pl-14 rounded-xl border-0 focus:ring-2 focus:ring-white outline-none text-gray-900">
                    <svg class="w-6 h-6 text-gray-400 absolute left-4 top-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Quick Help Categories --}}
        <div class="grid md:grid-cols-4 gap-6 mb-12">
            <a href="#getting-started" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Getting Started</h3>
                <p class="text-sm text-gray-500">New to WorkNepal?</p>
            </a>

            <a href="#account" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Account & Profile</h3>
                <p class="text-sm text-gray-500">Manage your account</p>
            </a>

            <a href="#job-search" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Finding Jobs</h3>
                <p class="text-sm text-gray-500">Search & apply tips</p>
            </a>

            <a href="#employers" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow text-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white">For Employers</h3>
                <p class="text-sm text-gray-500">Post jobs & hire</p>
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Help Content --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Getting Started --}}
                <div id="getting-started" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-20">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Getting Started
                        </h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I create an account?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Click the "Sign Up" button on the top right corner. You can register using:</p>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Email address and password</li>
                                    <li>Mobile number (OTP verification)</li>
                                    <li>Google account (OAuth)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">Is WorkNepal free to use?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Yes! WorkNepal is completely free for job seekers. You can:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Create your profile</li>
                                    <li>Search and apply for jobs</li>
                                    <li>Upload your CV</li>
                                    <li>Track applications</li>
                                </ul>
                                <p class="mt-2">Employers have free and paid options for posting jobs.</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I complete my profile?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Go to your Dashboard and click "Edit Profile". Complete these sections:</p>
                                <ol class="list-decimal list-inside mt-2 space-y-1">
                                    <li>Personal Information</li>
                                    <li>Professional Headline</li>
                                    <li>Work Experience</li>
                                    <li>Education</li>
                                    <li>Skills</li>
                                    <li>Upload CV & Photo</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Account & Profile --}}
                <div id="account" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-20">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Account & Profile
                        </h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I change my password?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Go to Settings → Security → Change Password. You'll need:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Current password</li>
                                    <li>New password (min 8 characters)</li>
                                    <li>Confirm new password</li>
                                </ul>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I delete my account?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>To delete your account:</p>
                                <ol class="list-decimal list-inside mt-2">
                                    <li>Go to Settings → Account</li>
                                    <li>Click "Delete Account"</li>
                                    <li>Confirm your password</li>
                                    <li>Provide feedback (optional)</li>
                                </ol>
                                <p class="mt-2 text-yellow-600">⚠️ This action is permanent and cannot be undone.</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I update my CV?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Go to Profile → CV & Documents. You can:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Upload new CV (PDF or DOC)</li>
                                    <li>Replace existing CV</li>
                                    <li>Delete current CV</li>
                                </ul>
                                <p class="mt-2">Max file size: 5MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Job Search --}}
                <div id="job-search" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-20">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Finding Jobs
                        </h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I search for jobs?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Use the search bar or go to Jobs section. You can filter by:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Location (city, remote)</li>
                                    <li>Job category</li>
                                    <li>Job type (full-time, part-time)</li>
                                    <li>Salary range</li>
                                    <li>Experience level</li>
                                </ul>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I apply for a job?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>On the job details page, click "Apply Now". You can:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Use your profile CV</li>
                                    <li>Upload a new CV</li>
                                    <li>Add a cover letter</li>
                                </ul>
                                <p class="mt-2">You'll receive a confirmation email after applying.</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I track my applications?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>Go to Dashboard → My Applications. You'll see:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>Applied</li>
                                    <li>Under Review</li>
                                    <li>Shortlisted</li>
                                    <li>Rejected</li>
                                    <li>Hired</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- For Employers --}}
                <div id="employers" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden scroll-mt-20">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                            For Employers
                        </h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I post a job?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>After creating a company profile:</p>
                                <ol class="list-decimal list-inside mt-2">
                                    <li>Go to Employer Dashboard</li>
                                    <li>Click "Post New Job"</li>
                                    <li>Fill in job details</li>
                                    <li>Submit for review</li>
                                </ol>
                                <p class="mt-2">Jobs are reviewed within 24 hours.</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <button class="w-full flex justify-between items-center text-left" onclick="toggleHelp(this)">
                                <span class="font-medium text-gray-900 dark:text-white">How do I manage applications?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="hidden mt-4 text-gray-600 dark:text-gray-400">
                                <p>In your Employer Dashboard:</p>
                                <ul class="list-disc list-inside mt-2">
                                    <li>View all applicants</li>
                                    <li>Download CVs</li>
                                    <li>Update application status</li>
                                    <li>Message candidates</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Contact Support --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Still need help?</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Our support team is ready to assist you</p>
                    <div class="space-y-3">
                        <a href="{{ route('pages.contact') }}" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Email Support</div>
                                <div class="text-sm text-gray-500">support@worknepal.com</div>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Live Chat</div>
                                <div class="text-sm text-gray-500">Available 9AM-6PM</div>
                            </div>
                        </a>

                        <a href="tel:+9771234567890" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">Call Us</div>
                                <div class="text-sm text-gray-500">+977 1234567890</div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Popular Articles --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Popular Articles</h3>
                    <div class="space-y-3">
                        <a href="#" class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <div class="font-medium text-gray-900 dark:text-white">How to create a standout profile</div>
                            <div class="text-sm text-gray-500">5 min read</div>
                        </a>
                        <a href="#" class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <div class="font-medium text-gray-900 dark:text-white">Tips for freshers applying for first job</div>
                            <div class="text-sm text-gray-500">3 min read</div>
                        </a>
                        <a href="#" class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <div class="font-medium text-gray-900 dark:text-white">Understanding job application status</div>
                            <div class="text-sm text-gray-500">2 min read</div>
                        </a>
                        <a href="#" class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-colors">
                            <div class="font-medium text-gray-900 dark:text-white">Foreign job application process</div>
                            <div class="text-sm text-gray-500">4 min read</div>
                        </a>
                    </div>
                </div>

                {{-- FAQ Categories --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browse by Topic</h3>
                    <div class="space-y-2">
                        <a href="#" class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Account Settings</span>
                            <span class="text-sm text-gray-400">12</span>
                        </a>
                        <a href="#" class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Job Applications</span>
                            <span class="text-sm text-gray-400">8</span>
                        </a>
                        <a href="#" class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">CV & Documents</span>
                            <span class="text-sm text-gray-400">6</span>
                        </a>
                        <a href="#" class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Notifications</span>
                            <span class="text-sm text-gray-400">4</span>
                        </a>
                        <a href="#" class="flex justify-between items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Privacy & Security</span>
                            <span class="text-sm text-gray-400">7</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleHelp(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('svg');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endsection