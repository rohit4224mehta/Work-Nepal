@extends('layouts.app')

@section('title', 'Applications for ' . $job->title . ' - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('employer.jobs.index') }}" 
               class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $job->title }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">{{ $job->company->name }} • {{ $job->location }}</p>
            </div>
        </div>
        
        {{-- Job Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $applications->total() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-blue-600">{{ $applications->where('status', 'applied')->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">New</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-green-600">{{ $applications->where('status', 'shortlisted')->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Shortlisted</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                <div class="text-2xl font-bold text-purple-600">{{ $applications->where('status', 'viewed')->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Viewed</div>
            </div>
        </div>
    </div>

    {{-- Applications Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Applicant</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Applied</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Resume</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                            {{ substr($application->applicant->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $application->applicant->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->applicant->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $application->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <select onchange="updateStatus({{ $application->id }}, this.value)"
                                        class="status-select px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                    <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                    <option value="viewed" {{ $application->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                    <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                @if($application->applicant->resume_path)
                                    <a href="{{ Storage::url($application->applicant->resume_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg text-sm hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        View CV
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">No CV</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('employer.applicants.show', $application) }}" 
                                   class="text-red-600 hover:text-red-700 font-medium inline-flex items-center">
                                    View Details
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications yet</h3>
                                <p class="text-gray-600 dark:text-gray-400">Applications will appear here when candidates apply</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateStatus(applicationId, status) {
    fetch(`/employer/applicants/${applicationId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Status updated successfully');
        } else {
            showNotification('error', data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        showNotification('error', 'Network error. Please try again.');
    });
}

function showNotification(type, message) {
    // Your notification logic here
    alert(message);
}
</script>
@endpush
@endsection