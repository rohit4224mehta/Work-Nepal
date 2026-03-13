@extends('layouts.app')

@section('title', 'Create Company - Step 1: Basic Information - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Become an Employer on WorkNepal
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Create your company profile in just a few steps and start finding the best talent in Nepal.
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-12">
        <div class="flex items-center justify-center">
            {{-- Step 1 Active --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Basic Info</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-gray-300 dark:bg-gray-700"></div>
            
            {{-- Step 2 Inactive --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full flex items-center justify-center font-bold">2</div>
                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">Company Details</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-gray-300 dark:bg-gray-700"></div>
            
            {{-- Step 3 Inactive --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full flex items-center justify-center font-bold">3</div>
                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">Branding</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-gray-300 dark:bg-gray-700"></div>
            
            {{-- Step 4 Inactive --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-full flex items-center justify-center font-bold">4</div>
                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">Review</span>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Form Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Step 1: Basic Information</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Let's start with the basics about your company</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('employer.company.store.step1') }}" class="p-6 lg:p-8">
            @csrf
            
            <div class="space-y-6">
                {{-- Company Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="company_name" 
                           value="{{ session('company_data.name', old('company_name')) }}"
                           placeholder="e.g. Quantineers GmbH"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('company_name') border-red-500 @enderror"
                           required>
                    @error('company_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">This will be your public company profile name</p>
                </div>

                {{-- Industry & Company Size --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Industry <span class="text-red-600">*</span>
                        </label>
                        <select name="industry" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('industry') border-red-500 @enderror"
                                required>
                            <option value="">Select Industry</option>
                            <option value="Technology" {{ (session('company_data.industry') ?? old('industry')) == 'Technology' ? 'selected' : '' }}>Technology</option>
                            <option value="Finance" {{ (session('company_data.industry') ?? old('industry')) == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Healthcare" {{ (session('company_data.industry') ?? old('industry')) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ (session('company_data.industry') ?? old('industry')) == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Manufacturing" {{ (session('company_data.industry') ?? old('industry')) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="Retail" {{ (session('company_data.industry') ?? old('industry')) == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Construction" {{ (session('company_data.industry') ?? old('industry')) == 'Construction' ? 'selected' : '' }}>Construction</option>
                            <option value="Hospitality" {{ (session('company_data.industry') ?? old('industry')) == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            <option value="Transportation" {{ (session('company_data.industry') ?? old('industry')) == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                            <option value="Other" {{ (session('company_data.industry') ?? old('industry')) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('industry')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company Size
                        </label>
                        <select name="company_size" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <option value="">Select size</option>
                            <option value="1-10" {{ (session('company_data.company_size') ?? old('company_size')) == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="11-50" {{ (session('company_data.company_size') ?? old('company_size')) == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                            <option value="51-200" {{ (session('company_data.company_size') ?? old('company_size')) == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                            <option value="201-500" {{ (session('company_data.company_size') ?? old('company_size')) == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                            <option value="501-1000" {{ (session('company_data.company_size') ?? old('company_size')) == '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                            <option value="1000+" {{ (session('company_data.company_size') ?? old('company_size')) == '1000+' ? 'selected' : '' }}>1000+ employees</option>
                        </select>
                    </div>
                </div>

                {{-- Founded Year & Website --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Founded Year
                        </label>
                        <input type="number" 
                               name="founded_year" 
                               value="{{ session('company_data.founded_year') ?? old('founded_year') }}"
                               placeholder="e.g. 2020"
                               min="1900"
                               max="{{ date('Y') }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company Website
                        </label>
                        <input type="url" 
                               name="website" 
                               value="{{ session('company_data.website') ?? old('website') }}"
                               placeholder="https://www.example.com"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                {{-- Progress Save Info --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-800 dark:text-blue-400">
                            Your progress is saved as you go. You can continue from this step anytime.
                        </p>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard.jobseeker') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors mr-4">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Continue to Step 2
                        <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Help Card --}}
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Why create a company profile?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Post jobs and reach thousands of candidates
            </div>
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Get verified and build trust with job seekers
            </div>
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Manage applications from one dashboard
            </div>
        </div>
    </div>
</div>
@endsection