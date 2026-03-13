@extends('layouts.app')

@section('title', 'Create Company - Step 2: Company Details - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Tell Us More About Your Company
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Help job seekers understand your company better
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-12">
        <div class="flex items-center justify-center">
            {{-- Step 1 Complete --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Basic Info</span>
            </div>
            <div class="w-16 h-0.5 mx-4 bg-green-600"></div>
            
            {{-- Step 2 Active --}}
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Company Details</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Step 2: Company Details</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Add detailed information about your company</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('employer.company.store.step2') }}" class="p-6 lg:p-8">
            @csrf
            
            <div class="space-y-6">
                {{-- Location --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Location <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="location" 
                           value="{{ session('company_data.location') ?? old('location') }}"
                           placeholder="e.g. Kathmandu, Nepal"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('location') border-red-500 @enderror"
                           required>
                    @error('location')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Headquarters --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Headquarters
                    </label>
                    <input type="text" 
                           name="headquarters" 
                           value="{{ session('company_data.headquarters') ?? old('headquarters') }}"
                           placeholder="e.g. Kathmandu (if different from location)"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                </div>

                {{-- Contact Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Email
                        </label>
                        <input type="email" 
                               name="contact_email" 
                               value="{{ session('company_data.contact_email') ?? old('contact_email', auth()->user()->email) }}"
                               placeholder="hr@company.com"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" 
                               name="phone" 
                               value="{{ session('company_data.phone') ?? old('phone') }}"
                               placeholder="+977 1 2345678"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                {{-- Company Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Description <span class="text-red-600">*</span>
                    </label>
                    <textarea name="description" 
                              rows="6"
                              placeholder="Tell us about your company, mission, culture, and what makes you unique..."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y @error('description') border-red-500 @enderror"
                              required>{{ session('company_data.description') ?? old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimum 50 characters. Help job seekers understand your company.</p>
                </div>

                {{-- Social Links --}}
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Social Media Links (Optional)</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </div>
                            <input type="url" 
                                   name="facebook" 
                                   value="{{ session('company_data.facebook') ?? old('facebook') }}"
                                   placeholder="https://facebook.com/yourcompany"
                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.104c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.33-12.342c0-.213-.005-.425-.014-.636A10 10 0 0023.953 4.57z"/>
                                </svg>
                            </div>
                            <input type="url" 
                                   name="twitter" 
                                   value="{{ session('company_data.twitter') ?? old('twitter') }}"
                                   placeholder="https://twitter.com/yourcompany"
                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.222 0h.003z"/>
                                </svg>
                            </div>
                            <input type="url" 
                                   name="linkedin" 
                                   value="{{ session('company_data.linkedin') ?? old('linkedin') }}"
                                   placeholder="https://linkedin.com/company/yourcompany"
                                   class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('employer.company.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Step 1
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Continue to Step 3
                        <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection