@extends('layouts.app')

@section('title', 'Edit Company - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
    
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.dashboard') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Company Profile</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Update your company information</p>
            </div>
        </div>
    </div>
    
    <form action="{{ route('employer.company.update', $company) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        {{-- Basic Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Essential details about your company</p>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Company Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $company->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be displayed on your company profile</p>
                </div>
                
                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Description
                    </label>
                    <textarea name="description" id="description" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">{{ old('description', $company->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tell candidates about your company, mission, and culture</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Industry --}}
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Industry
                        </label>
                        <select name="industry" id="industry"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select Industry</option>
                            <option value="Technology" {{ old('industry', $company->industry) == 'Technology' ? 'selected' : '' }}>Technology</option>
                            <option value="Finance" {{ old('industry', $company->industry) == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Healthcare" {{ old('industry', $company->industry) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ old('industry', $company->industry) == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Manufacturing" {{ old('industry', $company->industry) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                            <option value="Retail" {{ old('industry', $company->industry) == 'Retail' ? 'selected' : '' }}>Retail</option>
                            <option value="Hospitality" {{ old('industry', $company->industry) == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            <option value="Construction" {{ old('industry', $company->industry) == 'Construction' ? 'selected' : '' }}>Construction</option>
                            <option value="Consulting" {{ old('industry', $company->industry) == 'Consulting' ? 'selected' : '' }}>Consulting</option>
                            <option value="Other" {{ old('industry', $company->industry) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    {{-- Company Size --}}
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Company Size
                        </label>
                        <select name="size" id="size"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">Select Size</option>
                            <option value="1-10" {{ old('size', $company->size) == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                            <option value="11-50" {{ old('size', $company->size) == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                            <option value="51-200" {{ old('size', $company->size) == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                            <option value="201-500" {{ old('size', $company->size) == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                            <option value="501-1000" {{ old('size', $company->size) == '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                            <option value="1000+" {{ old('size', $company->size) == '1000+' ? 'selected' : '' }}>1000+ employees</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Founded Year --}}
                    <div>
                        <label for="founded_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Founded Year
                        </label>
                        <input type="number" name="founded_year" id="founded_year" 
                               value="{{ old('founded_year', $company->founded_year) }}"
                               min="1900" max="{{ date('Y') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Website
                        </label>
                        <input type="url" name="website" id="website" 
                               value="{{ old('website', $company->website) }}"
                               placeholder="https://example.com"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Location Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Location Information</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Where is your company located?</p>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Location --}}
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Primary Location
                        </label>
                        <input type="text" name="location" id="location" 
                               value="{{ old('location', $company->location) }}"
                               placeholder="Kathmandu, Nepal"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    {{-- Headquarters --}}
                    <div>
                        <label for="headquarters" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Headquarters
                        </label>
                        <input type="text" name="headquarters" id="headquarters" 
                               value="{{ old('headquarters', $company->headquarters) }}"
                               placeholder="Kathmandu, Nepal"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Contact Email --}}
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Email
                        </label>
                        <input type="email" name="contact_email" id="contact_email" 
                               value="{{ old('contact_email', $company->contact_email) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be displayed on your company profile</p>
                    </div>
                    
                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" 
                               value="{{ old('phone', $company->phone) }}"
                               placeholder="+977 1234567890"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Branding (Logo & Cover) --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Branding</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Upload your company logo and cover image</p>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Company Logo
                    </label>
                    <div class="flex items-start gap-6">
                        @if($company->logo_path)
                            <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: Square image, 200x200px, Max 2MB</p>
                        </div>
                    </div>
                </div>
                
                {{-- Cover Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cover Image
                    </label>
                    @if($company->cover_path)
                        <div class="mb-3 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img src="{{ Storage::url($company->cover_path) }}" alt="Cover" class="w-full h-32 object-cover">
                        </div>
                    @endif
                    <input type="file" name="cover" id="cover" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended: 1200x400px, Max 5MB</p>
                </div>
            </div>
        </div>
        
        {{-- Social Media Links --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Social Media Links</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Connect your social media profiles</p>
            </div>
            
            <div class="p-6 space-y-4">
                @php
                    $socialLinks = json_decode($company->social_links ?? '{}', true);
                @endphp
                
                {{-- Facebook --}}
                <div>
                    <label for="social_links[facebook]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Facebook
                    </label>
                    <input type="url" name="social_links[facebook]" id="facebook" 
                           value="{{ old('social_links.facebook', $socialLinks['facebook'] ?? '') }}"
                           placeholder="https://facebook.com/yourcompany"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                {{-- Twitter --}}
                <div>
                    <label for="social_links[twitter]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Twitter
                    </label>
                    <input type="url" name="social_links[twitter]" id="twitter" 
                           value="{{ old('social_links.twitter', $socialLinks['twitter'] ?? '') }}"
                           placeholder="https://twitter.com/yourcompany"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                {{-- LinkedIn --}}
                <div>
                    <label for="social_links[linkedin]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        LinkedIn
                    </label>
                    <input type="url" name="social_links[linkedin]" id="linkedin" 
                           value="{{ old('social_links.linkedin', $socialLinks['linkedin'] ?? '') }}"
                           placeholder="https://linkedin.com/company/yourcompany"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                
                {{-- Instagram --}}
                <div>
                    <label for="social_links[instagram]" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instagram
                    </label>
                    <input type="url" name="social_links[instagram]" id="instagram" 
                           value="{{ old('social_links.instagram', $socialLinks['instagram'] ?? '') }}"
                           placeholder="https://instagram.com/yourcompany"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>
        
        {{-- Form Actions --}}
        <div class="flex items-center justify-between gap-4 pt-4">
            <a href="{{ route('employer.dashboard') }}" 
               class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                Update Company Profile
            </button>
        </div>
    </form>
</div>
@endsection