@extends('layouts.app')

@section('title', 'Post a New Job - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.jobs.index') }}" 
               class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Post a New Job</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">Create a job posting to find the best talent</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Job Details</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Fill in the information about the position</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('employer.jobs.store') }}" class="p-6 lg:p-8">
            @csrf
            
            <div class="space-y-6">
                {{-- Company Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Select Company <span class="text-red-600">*</span>
                    </label>
                    <select name="company_id" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('company_id') border-red-500 @enderror"
                            required>
                        <option value="">Choose a company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }} ({{ $company->verification_status }})
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Job Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Job Title <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           placeholder="e.g. Senior Laravel Developer"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location & Job Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Location <span class="text-red-600">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               value="{{ old('location') }}"
                               placeholder="e.g. Kathmandu, Nepal"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('location') border-red-500 @enderror"
                               required>
                        @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Type <span class="text-red-600">*</span>
                        </label>
                        <select name="job_type" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('job_type') border-red-500 @enderror"
                                required>
                            <option value="">Select Type</option>
                            <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="remote" {{ old('job_type') == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        @error('job_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Category & Experience Level --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category <span class="text-red-600">*</span>
                        </label>
                        <select name="category" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('category') border-red-500 @enderror"
                                required>
                            <option value="">Select Category</option>
                            <option value="IT & Software" {{ old('category') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                            <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Sales" {{ old('category') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Finance" {{ old('category') == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Human Resources" {{ old('category') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                            <option value="Engineering" {{ old('category') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Healthcare" {{ old('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>Education</option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Experience Level <span class="text-red-600">*</span>
                        </label>
                        <select name="experience_level" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('experience_level') border-red-500 @enderror"
                                required>
                            <option value="">Select Level</option>
                            <option value="entry" {{ old('experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="mid" {{ old('experience_level') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior Level</option>
                            <option value="lead" {{ old('experience_level') == 'lead' ? 'selected' : '' }}>Lead / Manager</option>
                            <option value="executive" {{ old('experience_level') == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                        @error('experience_level')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Salary Range & Deadline --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Salary Range
                        </label>
                        <input type="text" 
                               name="salary_range" 
                               value="{{ old('salary_range') }}"
                               placeholder="e.g. NPR 50,000 - 80,000"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Application Deadline <span class="text-red-600">*</span>
                        </label>
                        <input type="date" 
                               name="deadline" 
                               value="{{ old('deadline') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white @error('deadline') border-red-500 @enderror"
                               required>
                        @error('deadline')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Job Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Job Description <span class="text-red-600">*</span>
                    </label>
                    <textarea name="description" 
                              rows="8"
                              placeholder="Describe the role, responsibilities, requirements, benefits, etc."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y @error('description') border-red-500 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimum 50 characters. Be detailed to attract qualified candidates.</p>
                </div>

                {{-- Skills Required (Optional) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Required Skills (Optional)
                    </label>
                    <input type="text" 
                           name="skills" 
                           value="{{ old('skills') }}"
                           placeholder="e.g. Laravel, React, MySQL, Project Management"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-xs text-gray-500">Separate skills with commas</p>
                </div>

                {{-- Benefits (Optional) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Benefits (Optional)
                    </label>
                    <textarea name="benefits" 
                              rows="3"
                              placeholder="e.g. Health insurance, Paid time off, Remote work options"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y">{{ old('benefits') }}</textarea>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('employer.jobs.index') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Post Job
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection