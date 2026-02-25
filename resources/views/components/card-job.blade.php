<div class="group bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 flex flex-col h-full">
    <div class="p-5 flex flex-col flex-grow">
        <!-- Company Logo + Name + Location -->
        <div class="flex items-center gap-3 mb-4">
            @if(isset($job->company) && is_object($job->company) && $job->company->logo_path)
                <img src="{{ Storage::url($job->company->logo_path) }}"
                     alt="{{ $job->company->name ?? 'Company Logo' }}"
                     class="w-12 h-12 rounded-lg object-contain border border-gray-200 dark:border-gray-600 flex-shrink-0"
                     loading="lazy">
            @else
                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 border border-gray-200 dark:border-gray-600">
                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                    </svg>
                </div>
            @endif

            <div>
                <h6 class="font-semibold text-gray-900 dark:text-white text-base leading-tight">
                    {{ $job->company->name ?? ($job->company ?? 'Company Name') }}
                </h6>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $job->location ?? 'Location N/A' }}
                </p>
            </div>
        </div>

        <!-- Job Title -->
        <h5 class="font-bold text-lg text-gray-900 dark:text-white mb-3 line-clamp-2">
            <a href="{{ route('jobs.show', $job->slug ?? $job->id ?? 'demo') }}"
               class="hover:text-red-600 dark:hover:text-red-500 transition-colors">
                {{ $job->title ?? 'Job Title' }}
            </a>
        </h5>

        <!-- Badges -->
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300">
                {{ ucfirst($job->type ?? $job->job_type ?? 'N/A') }}
            </span>

            @if($job->fresher_friendly ?? false)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">
                    Fresher Friendly
                </span>
            @endif

            @if($job->verified ?? $job->isVerified() ?? false)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300">
                    Verified
                </span>
            @endif
        </div>

        <!-- Salary & Experience -->
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-5 flex flex-wrap gap-4">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $job->salary_min || $job->salary_max
                    ? number_format($job->salary_min ?? 0) . ' – ' . number_format($job->salary_max ?? 0) . ' NPR'
                    : 'Salary not disclosed' }}
            </span>

            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $job->experience_level ?? 'Any' }} exp.
            </span>
        </div>

        <!-- Action Button -->
        <div class="mt-auto">
            <a href="{{ route('jobs.show', $job->slug ?? $job->id ?? 'demo') }}"
               class="block w-full text-center py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                View Details
            </a>
        </div>
    </div>
</div>