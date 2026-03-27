@extends('layouts.app')

@section('title', 'Edit Job - WorkNepal')

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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Job</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">{{ $job->title }}</p>
            </div>
        </div>
    </div>

    {{-- Status Alerts --}}
    @if($job->status === 'pending')
        <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">
                        This job is pending admin approval. After editing, it will need to be re-approved.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($job->status === 'active')
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        This job is currently active. After editing, it will be sent for re-approval.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Company Verification Warning --}}
    @if(trim($job->company->verification_status) !== 'verified')
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">
                        ⚠️ Your company is not verified. Jobs will not be visible to job seekers until your company is verified by admin.
                    </p>
                    <p class="text-xs text-red-700 dark:text-red-400 mt-1">
                        Please contact admin or wait for company verification to complete.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Form Header --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Job Details</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update the information about this position</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('employer.jobs.update', $job) }}" class="p-6 lg:p-8">
            @csrf
            @method('PUT')
            
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
                            @php
                                $isVerified = trim($company->verification_status) === 'verified';
                                $isCurrent = old('company_id', $job->company_id) == $company->id;
                            @endphp
                            <option value="{{ $company->id }}" 
                                {{ $isCurrent ? 'selected' : '' }}
                                {{ !$isVerified ? 'disabled' : '' }}
                                class="{{ !$isVerified ? 'text-gray-400' : '' }}">
                                {{ $company->name }}
                                @if(!$isVerified)
                                    (Pending Verification)
                                @elseif($isCurrent && !$isVerified)
                                    (⚠️ Not Verified)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    @if(trim($job->company->verification_status) !== 'verified')
                        <div class="mt-2 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-xs text-yellow-800 dark:text-yellow-300">
                                <strong>Note:</strong> Your company is not verified. You can still edit this job, but it won't be visible to job seekers until your company is verified.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Rest of the form fields (same as before) --}}
                {{-- Job Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Job Title <span class="text-red-600">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title', $job->title) }}"
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
                               value="{{ old('location', $job->location) }}"
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
                            <option value="full-time" {{ old('job_type', $job->job_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('job_type', $job->job_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="remote" {{ old('job_type', $job->job_type) == 'remote' ? 'selected' : '' }}>Remote</option>
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
                            <option value="IT & Software" {{ old('category', $job->category) == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                            <option value="Marketing" {{ old('category', $job->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Sales" {{ old('category', $job->category) == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Finance" {{ old('category', $job->category) == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Human Resources" {{ old('category', $job->category) == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                            <option value="Engineering" {{ old('category', $job->category) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Healthcare" {{ old('category', $job->category) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ old('category', $job->category) == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Hospitality" {{ old('category', $job->category) == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            <option value="Construction" {{ old('category', $job->category) == 'Construction' ? 'selected' : '' }}>Construction</option>
                            <option value="Other" {{ old('category', $job->category) == 'Other' ? 'selected' : '' }}>Other</option>
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
                            <option value="entry" {{ old('experience_level', $job->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level (0-1 years)</option>
                            <option value="mid" {{ old('experience_level', $job->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level (2-5 years)</option>
                            <option value="senior" {{ old('experience_level', $job->experience_level) == 'senior' ? 'selected' : '' }}>Senior Level (5-8 years)</option>
                            <option value="lead" {{ old('experience_level', $job->experience_level) == 'lead' ? 'selected' : '' }}>Lead / Manager (8+ years)</option>
                            <option value="executive" {{ old('experience_level', $job->experience_level) == 'executive' ? 'selected' : '' }}>Executive / Director</option>
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
                               value="{{ old('salary_range', $job->salary_range) }}"
                               placeholder="e.g. NPR 50,000 - 80,000"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Optional. Leave blank if salary is negotiable</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Application Deadline <span class="text-red-600">*</span>
                        </label>
                        <input type="date" 
                               name="deadline" 
                               value="{{ old('deadline', $job->deadline ? $job->deadline->format('Y-m-d') : '') }}"
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
                              rows="10"
                              placeholder="Describe the role, responsibilities, requirements, benefits, etc."
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y @error('description') border-red-500 @enderror"
                              required>{{ old('description', $job->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimum 50 characters. Be detailed to attract qualified candidates.</p>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('employer.jobs.index') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        Update Job
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Character count for description
    const descriptionField = document.querySelector('textarea[name="description"]');
    if (descriptionField) {
        const charCount = document.createElement('p');
        charCount.className = 'mt-1 text-xs text-gray-500';
        charCount.id = 'charCount';
        descriptionField.parentNode.appendChild(charCount);
        
        function updateCharCount() {
            charCount.textContent = `Characters: ${descriptionField.value.length} (minimum 50 required)`;
            if (descriptionField.value.length < 50 && descriptionField.value.length > 0) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        }
        
        descriptionField.addEventListener('input', updateCharCount);
        updateCharCount();
    }
    
    // Validate deadline
    const deadlineField = document.querySelector('input[name="deadline"]');
    if (deadlineField) {
        deadlineField.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate <= today) {
                alert('Deadline must be after today\'s date.');
                this.value = '';
            }
        });
    }
</script>
@endpush

@endsection