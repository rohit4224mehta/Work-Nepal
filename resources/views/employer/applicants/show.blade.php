@extends('layouts.app')

@section('title', 'Applicant Details - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header with Back Button --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('employer.applicants.index') }}" 
               class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Applicant Details</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">Review application and candidate information</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - Applicant Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center border-b border-gray-200 dark:border-gray-700">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-600 to-red-500 mx-auto mb-4 flex items-center justify-center text-white text-3xl font-bold">
                        {{ substr($application->applicant->name, 0, 1) }}
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $application->applicant->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $application->applicant->headline ?? 'Job Seeker' }}</p>
                    
                    {{-- Status Badge --}}
                    <div class="mt-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($application->status == 'applied') bg-blue-100 text-blue-800
                            @elseif($application->status == 'viewed') bg-purple-100 text-purple-800
                            @elseif($application->status == 'shortlisted') bg-green-100 text-green-800
                            @elseif($application->status == 'rejected') bg-red-100 text-red-800
                            @elseif($application->status == 'hired') bg-emerald-100 text-emerald-800
                            @endif">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
                
                {{-- Contact Info --}}
                <div class="p-6 space-y-4">
                    @if($application->applicant->email)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <a href="mailto:{{ $application->applicant->email }}" class="text-sm text-gray-900 dark:text-white hover:text-red-600">
                                    {{ $application->applicant->email }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($application->applicant->mobile)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone</p>
                                <a href="tel:{{ $application->applicant->mobile }}" class="text-sm text-gray-900 dark:text-white hover:text-red-600">
                                    {{ $application->applicant->mobile }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Location</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $application->applicant->location ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resume Card --}}
            @if($application->applicant->resume_path)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resume / CV</h3>
                    <a href="{{ Storage::url($application->applicant->resume_path) }}" 
                       target="_blank"
                       class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Resume
                    </a>
                </div>
            @endif
        </div>

        {{-- Right Column - Application Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Job Details Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Applied Position</h3>
                
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                        @if($application->jobPosting->company->logo_path)
                            <img src="{{ $application->jobPosting->company->logo_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-gray-500">{{ substr($application->jobPosting->company->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $application->jobPosting->title }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $application->jobPosting->company->name }}</p>
                        
                        <div class="flex flex-wrap gap-3 mt-3">
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                {{ ucfirst($application->jobPosting->job_type) }}
                            </span>
                            @if($application->jobPosting->location)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-sm">
                                    {{ $application->jobPosting->location }}
                                </span>
                            @endif
                            @if($application->jobPosting->salary_range)
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-sm">
                                    {{ $application->jobPosting->salary_range }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Update Status Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Application Status</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status-select" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                            <option value="viewed" {{ $application->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Feedback (Optional)</label>
                        <textarea id="feedback" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white resize-y"
                                  placeholder="Add private notes or feedback for this applicant">{{ $application->employer_feedback }}</textarea>
                    </div>

                    <button onclick="updateApplicationStatus()" 
                            class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                        Update Status
                    </button>
                </div>
            </div>

            {{-- Timeline Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="relative">
                            <div class="w-3 h-3 mt-1.5 bg-green-500 rounded-full"></div>
                            <div class="absolute top-4 left-1.5 w-0.5 h-full bg-gray-300 dark:bg-gray-600 -translate-x-1/2"></div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Applied</p>
                            <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($application->status_updated_at && $application->status != 'applied')
                        <div class="flex gap-3">
                            <div class="w-3 h-3 mt-1.5 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Status Updated to {{ ucfirst($application->status) }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($application->status_updated_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateApplicationStatus() {
    const status = document.getElementById('status-select').value;
    const feedback = document.getElementById('feedback').value;
    
    fetch('{{ route("employer.applicants.status", $application) }}', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status, feedback: feedback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Application status updated successfully');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        showNotification('error', 'Network error. Please try again.');
    });
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>
@endsection