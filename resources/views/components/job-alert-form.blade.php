{{-- resources/views/components/job-alert-form.blade.php --}}
@props(['alert' => null, 'submitUrl', 'buttonText' => 'Create Alert'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
        {{ $alert ? 'Edit Job Alert' : 'Create New Job Alert' }}
    </h3>
    
    <form method="POST" action="{{ $submitUrl }}">
        @csrf
        @if($alert)
            @method('PUT')
        @endif
        
        <div class="space-y-4">
            {{-- Alert Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alert Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $alert->name ?? '') }}"
                       placeholder="e.g., Laravel Developer Alert"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                       required>
                <p class="mt-1 text-xs text-gray-500">Give your alert a descriptive name</p>
            </div>
            
            {{-- Keywords --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Keywords
                </label>
                <input type="text" 
                       name="keywords" 
                       value="{{ old('keywords', $alert->keywords ?? '') }}"
                       placeholder="e.g., Laravel, React, Python, Full Stack"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Location --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Location
                    </label>
                    <input type="text" 
                           name="location" 
                           value="{{ old('location', $alert->location ?? '') }}"
                           placeholder="e.g., Kathmandu, Remote"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                </div>
                
                {{-- Job Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Job Type
                    </label>
                    <select name="job_type" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Any Type</option>
                        <option value="full-time" {{ old('job_type', $alert->job_type ?? '') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part-time" {{ old('job_type', $alert->job_type ?? '') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('job_type', $alert->job_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ old('job_type', $alert->job_type ?? '') == 'internship' ? 'selected' : '' }}>Internship</option>
                        <option value="remote" {{ old('job_type', $alert->job_type ?? '') == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <select name="category" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Any Category</option>
                        <option value="IT & Software" {{ old('category', $alert->category ?? '') == 'IT & Software' ? 'selected' : '' }}>IT & Software</option>
                        <option value="Marketing" {{ old('category', $alert->category ?? '') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Finance" {{ old('category', $alert->category ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Sales" {{ old('category', $alert->category ?? '') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="Human Resources" {{ old('category', $alert->category ?? '') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                        <option value="Engineering" {{ old('category', $alert->category ?? '') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="Healthcare" {{ old('category', $alert->category ?? '') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="Education" {{ old('category', $alert->category ?? '') == 'Education' ? 'selected' : '' }}>Education</option>
                    </select>
                </div>
                
                {{-- Experience Level --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Experience Level
                    </label>
                    <select name="experience_level" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Any Level</option>
                        <option value="entry" {{ old('experience_level', $alert->experience_level ?? '') == 'entry' ? 'selected' : '' }}>Entry Level (0-1 years)</option>
                        <option value="mid" {{ old('experience_level', $alert->experience_level ?? '') == 'mid' ? 'selected' : '' }}>Mid Level (2-5 years)</option>
                        <option value="senior" {{ old('experience_level', $alert->experience_level ?? '') == 'senior' ? 'selected' : '' }}>Senior Level (5-8 years)</option>
                        <option value="lead" {{ old('experience_level', $alert->experience_level ?? '') == 'lead' ? 'selected' : '' }}>Lead / Manager (8+ years)</option>
                        <option value="executive" {{ old('experience_level', $alert->experience_level ?? '') == 'executive' ? 'selected' : '' }}>Executive / Director</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Min Salary --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Minimum Salary (NPR)
                    </label>
                    <input type="number" 
                           name="salary_min" 
                           value="{{ old('salary_min', $alert->salary_min ?? '') }}"
                           placeholder="e.g., 50000"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                </div>
                
                {{-- Max Salary --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Maximum Salary (NPR)
                    </label>
                    <input type="number" 
                           name="salary_max" 
                           value="{{ old('salary_max', $alert->salary_max ?? '') }}"
                           placeholder="e.g., 150000"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            
            {{-- Frequency --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Alert Frequency
                </label>
                <div class="flex flex-wrap gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="frequency" value="daily" 
                               {{ old('frequency', $alert->frequency ?? 'daily') == 'daily' ? 'checked' : '' }}
                               class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Daily</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="frequency" value="weekly" 
                               {{ old('frequency', $alert->frequency ?? '') == 'weekly' ? 'checked' : '' }}
                               class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Weekly</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="frequency" value="instant" 
                               {{ old('frequency', $alert->frequency ?? '') == 'instant' ? 'checked' : '' }}
                               class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Instant</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">How often you want to receive job alerts</p>
            </div>
            
            {{-- Submit Button --}}
            <div class="pt-4">
                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    {{ $buttonText }}
                </button>
            </div>
        </div>
    </form>
</div>