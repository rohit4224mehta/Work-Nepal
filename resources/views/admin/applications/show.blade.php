@extends('layouts.admin')

@section('title', 'Application Details - WorkNepal Admin')

@section('header', 'Application Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Application #{{ $application->id }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($application->status == 'applied') bg-blue-100 text-blue-800
                    @elseif($application->status == 'viewed') bg-yellow-100 text-yellow-800
                    @elseif($application->status == 'shortlisted') bg-green-100 text-green-800
                    @elseif($application->status == 'rejected') bg-red-100 text-red-800
                    @elseif($application->status == 'hired') bg-purple-100 text-purple-800
                    @endif">
                    {{ ucfirst($application->status) }}
                </span>
            </div>
            
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" 
                      onsubmit="return confirm('Delete this application? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Application
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Applicant Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Applicant Profile Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center mb-4">
                        <div class="w-24 h-24 mx-auto mb-4">
                            @if($application->applicant->profile_photo_path)
                                <img class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700" 
                                     src="{{ $application->applicant->profile_photo_url }}" alt="">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white text-3xl font-bold mx-auto border-4 border-gray-200 dark:border-gray-700">
                                    {{ substr($application->applicant->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $application->applicant->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $application->applicant->headline ?? 'Job Seeker' }}</p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <a href="mailto:{{ $application->applicant->email }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600">
                                    {{ $application->applicant->email }}
                                </a>
                            </div>
                        </div>

                        @if($application->applicant->mobile)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Phone</p>
                                    <a href="tel:{{ $application->applicant->mobile }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600">
                                        {{ $application->applicant->mobile }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($application->applicant->date_of_birth)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Age</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($application->applicant->date_of_birth)->age }} years
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if($application->applicant->location)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Location</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $application->applicant->location }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Resume Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resume / CV</h4>
                    
                    @if($application->applicant->resume_path)
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">{{ basename($application->applicant->resume_path) }}</p>
                                <p class="text-xs text-gray-500">Uploaded {{ $application->applicant->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ Storage::url($application->applicant->resume_path) }}" 
                               target="_blank"
                               class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                View Resume
                            </a>
                            <a href="{{ Storage::url($application->applicant->resume_path) }}" 
                               download
                               class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                Download
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No resume uploaded</p>
                        </div>
                    @endif
                </div>

                {{-- Skills Card --}}
                @if($application->applicant->skills->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Skills</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($application->applicant->skills as $skill)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Middle Column - Application Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Job Details Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Applied Position</h4>
                        <a href="{{ route('admin.jobs.show', $application->jobPosting) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View Job →
                        </a>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if($application->jobPosting->company->logo_path)
                                <img src="{{ Storage::url($application->jobPosting->company->logo_path) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-gray-500">{{ substr($application->jobPosting->company->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h5 class="text-xl font-bold text-gray-900 dark:text-white">{{ $application->jobPosting->title }}</h5>
                            <p class="text-gray-600 dark:text-gray-400">{{ $application->jobPosting->company->name }}</p>
                            
                            <div class="flex flex-wrap gap-3 mt-3 text-sm">
                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $application->jobPosting->location ?? 'Nepal' }}
                                </span>
                                <span class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $application->jobPosting->job_type)) }}
                                </span>
                                @if($application->jobPosting->salary_range)
                                    <span class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $application->jobPosting->salary_range }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Update Status Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Application Status</h4>
                    
                    <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                <option value="viewed" {{ $application->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Feedback / Notes (Optional)
                            </label>
                            <textarea name="feedback" 
                                      rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Add private notes or feedback for this applicant...">{{ $application->employer_feedback }}</textarea>
                        </div>

                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Application Timeline --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Timeline</h4>
                    
                    <div class="space-y-4">
                        {{-- Applied --}}
                        <div class="flex gap-3">
                            <div class="relative">
                                <div class="w-3 h-3 mt-1.5 bg-blue-500 rounded-full"></div>
                                @if($application->status_updated_at)
                                    <div class="absolute top-4 left-1.5 w-0.5 h-full bg-gray-300 dark:bg-gray-600 -translate-x-1/2"></div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Applied</p>
                                <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                                <p class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($application->status_updated_at && $application->status != 'applied')
                            <div class="flex gap-3">
                                <div class="w-3 h-3 mt-1.5 
                                    @if($application->status == 'viewed') bg-yellow-500
                                    @elseif($application->status == 'shortlisted') bg-green-500
                                    @elseif($application->status == 'rejected') bg-red-500
                                    @elseif($application->status == 'hired') bg-purple-500
                                    @endif rounded-full">
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Status Updated to {{ ucfirst($application->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->status_updated_at)->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($application->status_updated_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Applications by Same Applicant --}}
        @if($otherApplications->isNotEmpty())
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Other Applications by {{ $application->applicant->name }}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($otherApplications as $other)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-start justify-between mb-2">
                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $other->jobPosting->title }}</h5>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($other->status == 'applied') bg-blue-100 text-blue-800
                                    @elseif($other->status == 'viewed') bg-yellow-100 text-yellow-800
                                    @elseif($other->status == 'shortlisted') bg-green-100 text-green-800
                                    @elseif($other->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($other->status == 'hired') bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($other->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $other->jobPosting->company->name }}</p>
                            <p class="text-xs text-gray-500 mt-2">Applied {{ $other->created_at->diffForHumans() }}</p>
                            <a href="{{ route('admin.applications.show', $other) }}" 
                               class="mt-3 text-red-600 hover:text-red-700 text-sm font-medium inline-block">
                                View →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Other Applicants for Same Job --}}
        @if($otherApplicants->isNotEmpty())
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Other Applicants for this Job</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($otherApplicants as $other)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($other->applicant->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900 dark:text-white">{{ $other->applicant->name }}</h5>
                                    <p class="text-xs text-gray-500">{{ $other->applicant->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($other->status == 'applied') bg-blue-100 text-blue-800
                                    @elseif($other->status == 'viewed') bg-yellow-100 text-yellow-800
                                    @elseif($other->status == 'shortlisted') bg-green-100 text-green-800
                                    @elseif($other->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($other->status == 'hired') bg-purple-100 text-purple-800
                                    @endif">
                                    {{ ucfirst($other->status) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $other->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('admin.applications.show', $other) }}" 
                               class="mt-3 text-red-600 hover:text-red-700 text-sm font-medium inline-block">
                                View →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection