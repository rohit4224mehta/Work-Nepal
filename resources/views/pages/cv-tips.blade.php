@extends('layouts.app')

@section('title', 'CV Writing Tips & Career Advice - WorkNepal')

@section('content')
<div class="bg-white dark:bg-gray-900">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-red-600 to-red-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">CV Writing Guide & Career Tips</h1>
            <p class="text-xl text-red-100 max-w-3xl mx-auto">
                Expert advice to help you create a standout CV and succeed in your job search
            </p>
        </div>
    </div>

    {{-- Quick Navigation --}}
    <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#basics" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">CV Basics</a>
                <a href="#structure" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Structure</a>
                <a href="#sections" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Key Sections</a>
                <a href="#nepal-tips" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Nepal-Specific</a>
                <a href="#mistakes" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Common Mistakes</a>
                <a href="#templates" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Templates</a>
                <a href="#examples" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Examples</a>
                <a href="#articles" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">Career Articles</a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- CV Basics --}}
        <div id="basics" class="mb-16 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">CV Writing Basics</h2>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Keep it Concise</h3>
                            <p class="text-gray-600 dark:text-gray-400">2 pages maximum. Recruiters spend only 6-8 seconds scanning each CV.</p>
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tailor for Each Job</h3>
                            <p class="text-gray-600 dark:text-gray-400">Customize your CV for each application. Highlight relevant skills and experience.</p>
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Use Keywords</h3>
                            <p class="text-gray-600 dark:text-gray-400">Include industry keywords from the job description for ATS systems.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CV Structure --}}
        <div id="structure" class="mb-16 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">CV Structure</h2>
                    
                    <div class="relative">
                        {{-- Timeline --}}
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        
                        <div class="space-y-8 relative">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4 z-10">
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">1</span>
                                </div>
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Contact Information</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Place at the top. Include:</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ Full Name</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ Phone Number</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ Email Address</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ Location</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ LinkedIn Profile</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">✅ Portfolio (if relevant)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4 z-10">
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">2</span>
                                </div>
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Professional Summary</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">3-4 sentences highlighting:</p>
                                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                                        <li>Years of experience</li>
                                        <li>Key skills and expertise</li>
                                        <li>Career achievements</li>
                                        <li>What you're looking for</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4 z-10">
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">3</span>
                                </div>
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Work Experience</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Reverse chronological order. For each role:</p>
                                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                                        <li>Company name and location</li>
                                        <li>Job title</li>
                                        <li>Employment dates</li>
                                        <li>3-5 bullet points of achievements (not just duties)</li>
                                        <li>Use action verbs and quantify results</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4 z-10">
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">4</span>
                                </div>
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Education</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Include:</p>
                                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400">
                                        <li>Degree and field of study</li>
                                        <li>Institution name</li>
                                        <li>Graduation year</li>
                                        <li>Relevant coursework or achievements</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-4 z-10">
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">5</span>
                                </div>
                                <div class="flex-1 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Skills</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Organize by category:</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Technical Skills</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Programming, Software, Tools</p>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Soft Skills</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Communication, Leadership, Teamwork</p>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Languages</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Nepali, English, Hindi, etc.</p>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-1">Certifications</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Relevant certificates</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nepal-Specific Tips --}}
        <div id="nepal-tips" class="mb-16 scroll-mt-20">
            <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-2xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Nepal-Specific CV Tips
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">🇳🇵 Language Skills</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Include both Nepali and English proficiency levels
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Mention local language certifications if any
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                For foreign jobs, highlight English proficiency
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">📍 Location Preferences</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Clearly state your preferred work locations
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Mention willingness to relocate if applicable
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                For foreign jobs, specify target countries
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">🎓 Local Education</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Include SLC/SEE, +2, Bachelor's details
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Mention CTEVT affiliations if applicable
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Include local certifications and training
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">📋 References</h3>
                        <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Include references from previous employers
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Add local community leaders if relevant
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Get permission before listing references
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Common Mistakes --}}
        <div id="mistakes" class="mb-16 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Common CV Mistakes to Avoid</h2>
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Spelling & Grammar Errors</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Always proofread or use tools like Grammarly</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Generic CV for All Jobs</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Tailor each application to the specific role</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Too Long</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Keep it to 1-2 pages maximum</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Missing Contact Info</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Always include phone and email</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Unprofessional Email</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Use firstname.lastname@email.com</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">Lying or Exaggerating</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Always be truthful; it will be verified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CV Templates --}}
        <div id="templates" class="mb-16 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Free CV Templates</h2>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-500 text-sm rounded-full">Free Download</span>
                    </div>
                    
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="group cursor-pointer">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl h-48 mb-3 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Professional</h3>
                            <p class="text-sm text-gray-500 mb-2">DOCX, PDF</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center">
                                Download Template
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>

                        <div class="group cursor-pointer">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl h-48 mb-3 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-green-500 to-teal-600 opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Creative</h3>
                            <p class="text-sm text-gray-500 mb-2">DOCX, PDF</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center">
                                Download Template
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>

                        <div class="group cursor-pointer">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl h-48 mb-3 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-red-500 to-pink-600 opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Simple</h3>
                            <p class="text-sm text-gray-500 mb-2">DOCX, PDF</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center">
                                Download Template
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>

                        <div class="group cursor-pointer">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl h-48 mb-3 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-900 opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Technical</h3>
                            <p class="text-sm text-gray-500 mb-2">DOCX, PDF</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center">
                                Download Template
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sample CV Examples --}}
        <div id="examples" class="mb-16 scroll-mt-20">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Sample CVs by Industry</h2>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">IT & Software</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">For developers, engineers, IT support</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">View Sample →</button>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Finance & Accounting</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">For accountants, analysts, finance managers</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">View Sample →</button>
                        </div>

                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Marketing & Sales</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">For marketers, sales professionals, PR</p>
                            <button class="text-red-600 hover:text-red-700 text-sm font-medium">View Sample →</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Career Articles --}}
        <div id="articles" class="mb-16 scroll-mt-20">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Latest Career Advice</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ asset('images/career/interview-tips.jpg') }}" alt="Interview Tips" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">March 10, 2025</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">10 Interview Tips for Freshers</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Learn how to ace your first job interview with confidence...</p>
                        <a href="#" class="text-red-600 hover:text-red-700 font-medium inline-flex items-center">
                            Read More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ asset('images/career/skills-2025.jpg') }}" alt="Skills 2025" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">March 5, 2025</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Top Skills Employers Want in 2025</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Discover the most in-demand skills in Nepal's job market...</p>
                        <a href="#" class="text-red-600 hover:text-red-700 font-medium inline-flex items-center">
                            Read More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <img src="{{ asset('images/career/linkedin.jpg') }}" alt="LinkedIn Profile" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="text-sm text-gray-500 mb-2">February 28, 2025</div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">How to Optimize Your LinkedIn Profile</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Stand out to recruiters with these LinkedIn tips...</p>
                        <a href="#" class="text-red-600 hover:text-red-700 font-medium inline-flex items-center">
                            Read More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-8 text-center">
            <h2 class="text-2xl font-bold text-white mb-4">Ready to Create Your CV?</h2>
            <p class="text-red-100 mb-6">Use our CV builder to create a professional CV in minutes</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-white text-red-600 rounded-xl hover:bg-red-50 font-semibold">
                    Build Your CV
                </a>
                <a href="#" class="px-6 py-3 bg-red-700 text-white rounded-xl hover:bg-red-800 font-semibold">
                    Search Jobs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection