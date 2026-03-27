@extends('layouts.app')

@section('title', 'Manage Applicants - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Applicants</h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                    Review and manage all job applications across your companies
                </p>
            </div>
            
            {{-- Filters --}}
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="applied">Applied</option>
                    <option value="viewed">Viewed</option>
                    <option value="shortlisted">Shortlisted</option>
                    <option value="rejected">Rejected</option>
                    <option value="hired">Hired</option>
                </select>
                
                {{-- ✅ FIXED: Use parentheses after accessibleCompanies() --}}
                <select id="company-filter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    <option value="">All Companies</option>
                    @foreach(auth()->user()->accessibleCompanies() as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>

                <input type="text" 
                       id="search-input" 
                       placeholder="Search by name or email..."
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white w-full md:w-64">

                <button id="reset-filters" 
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    @php
        $stats = [
            'total' => $applications->total(),
            'applied' => $applications->where('status', 'applied')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'hired' => $applications->where('status', 'hired')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];
    @endphp
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Pending Review</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-500">{{ number_format($stats['applied']) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Shortlisted</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-500">{{ number_format($stats['shortlisted']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Rejected</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-500">{{ number_format($stats['rejected']) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Hired</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-500">{{ number_format($stats['hired']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
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
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Job Position</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Company</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Applied</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Resume</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="applications-table-body">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors application-row" 
                            data-status="{{ $application->status }}"
                            data-company="{{ $application->jobPosting->company_id }}"
                            data-applicant-name="{{ strtolower($application->applicant->name) }}"
                            data-applicant-email="{{ strtolower($application->applicant->email) }}">
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
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $application->jobPosting->title }}</div>
                                <div class="text-sm text-gray-500">{{ ucfirst(str_replace('-', ' ', $application->jobPosting->job_type)) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($application->jobPosting->company->logo_path)
                                        <img src="{{ Storage::url($application->jobPosting->company->logo_path) }}" 
                                             alt="" 
                                             class="w-6 h-6 rounded-full object-cover">
                                    @endif
                                    <span class="text-gray-600 dark:text-gray-400">{{ $application->jobPosting->company->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                <div>{{ $application->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $application->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <select onchange="updateStatus({{ $application->id }}, this.value)"
                                        class="status-select px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                        data-application-id="{{ $application->id }}">
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
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employer.applicants.show', $application) }}" 
                                       class="p-2 text-gray-500 hover:text-blue-600 transition-colors"
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if($application->applicant->email)
                                        <a href="mailto:{{ $application->applicant->email }}?subject=Regarding your application for {{ $application->jobPosting->title }}" 
                                           class="p-2 text-gray-500 hover:text-green-600 transition-colors"
                                           title="Send Email">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </a>
                                    @endif
                                    <button onclick="showNotesModal({{ $application->id }}, '{{ addslashes($application->employer_feedback) }}')" 
                                            class="p-2 text-gray-500 hover:text-yellow-600 transition-colors"
                                            title="Add Notes">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications yet</h3>
                                <p class="text-gray-600 dark:text-gray-400">Applications from job seekers will appear here</p>
                                <a href="{{ route('employer.jobs.index') }}" 
                                   class="inline-flex items-center px-4 py-2 mt-4 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Post a Job
                                </a>
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

{{-- Notes Modal --}}
<div id="notesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Add Notes</h3>
            <button onclick="closeNotesModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="notesForm">
            @csrf
            <input type="hidden" id="note_application_id" name="application_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Feedback / Notes
                </label>
                <textarea name="feedback" id="note_feedback" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Add your notes about this candidate..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeNotesModal()" 
                        class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    Save Notes
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentApplicationId = null;

// Update application status
function updateStatus(applicationId, status) {
    if (!confirm(`Are you sure you want to change this application status to ${status}?`)) {
        // Reset select to previous value if cancelled
        const select = document.querySelector(`select[data-application-id="${applicationId}"]`);
        if (select) {
            select.value = select.getAttribute('data-current-status') || 'applied';
        }
        return;
    }
    
    fetch(`/employer/applicants/${applicationId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status updated successfully', 'success');
            
            // Update the select
            const select = document.querySelector(`select[data-application-id="${applicationId}"]`);
            if (select) {
                select.setAttribute('data-current-status', status);
            }
            
            // Update row data attribute
            const row = select.closest('.application-row');
            if (row) {
                row.setAttribute('data-status', status);
            }
            
            // Update statistics without page reload
            updateStatistics();
        } else {
            showNotification(data.message || 'Failed to update status', 'error');
            // Reset select
            const select = document.querySelector(`select[data-application-id="${applicationId}"]`);
            if (select && select.getAttribute('data-current-status')) {
                select.value = select.getAttribute('data-current-status');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        // Reset select
        const select = document.querySelector(`select[data-application-id="${applicationId}"]`);
        if (select && select.getAttribute('data-current-status')) {
            select.value = select.getAttribute('data-current-status');
        }
    });
}

// Show notes modal
function showNotesModal(applicationId, currentNotes) {
    currentApplicationId = applicationId;
    document.getElementById('note_application_id').value = applicationId;
    document.getElementById('note_feedback').value = currentNotes || '';
    document.getElementById('notesModal').classList.remove('hidden');
    document.getElementById('notesModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeNotesModal() {
    document.getElementById('notesModal').classList.add('hidden');
    document.getElementById('notesModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Save notes
document.getElementById('notesForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const applicationId = document.getElementById('note_application_id').value;
    const feedback = document.getElementById('note_feedback').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch(`/employer/applicants/${applicationId}/feedback`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ feedback: feedback })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Notes saved successfully', 'success');
            closeNotesModal();
        } else {
            showNotification(data.message || 'Failed to save notes', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Filter functionality
const statusFilter = document.getElementById('status-filter');
const companyFilter = document.getElementById('company-filter');
const searchInput = document.getElementById('search-input');
const resetBtn = document.getElementById('reset-filters');

function applyFilters() {
    const status = statusFilter?.value;
    const company = companyFilter?.value;
    const search = searchInput?.value?.toLowerCase();
    
    const rows = document.querySelectorAll('.application-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let show = true;
        
        // Status filter
        if (status && row.dataset.status !== status) {
            show = false;
        }
        
        // Company filter
        if (show && company && row.dataset.company !== company) {
            show = false;
        }
        
        // Search filter
        if (show && search) {
            const name = row.dataset.applicantName || '';
            const email = row.dataset.applicantEmail || '';
            if (!name.includes(search) && !email.includes(search)) {
                show = false;
            }
        }
        
        if (show) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    let noResultsMsg = document.getElementById('no-results-message');
    if (visibleCount === 0 && rows.length > 0) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('tr');
            noResultsMsg.id = 'no-results-message';
            noResultsMsg.innerHTML = `
                <td colspan="7" class="px-6 py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No matching applications</h3>
                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters</p>
                </td>
            `;
            const tbody = document.getElementById('applications-table-body');
            if (tbody) tbody.appendChild(noResultsMsg);
        }
        noResultsMsg.style.display = '';
    } else if (noResultsMsg) {
        noResultsMsg.style.display = 'none';
    }
}

function resetFilters() {
    if (statusFilter) statusFilter.value = '';
    if (companyFilter) companyFilter.value = '';
    if (searchInput) searchInput.value = '';
    applyFilters();
}

function updateStatistics() {
    // This could fetch updated statistics via AJAX
    // For now, just reload the page
    location.reload();
}

// Event listeners
statusFilter?.addEventListener('change', applyFilters);
companyFilter?.addEventListener('change', applyFilters);
searchInput?.addEventListener('input', applyFilters);
resetBtn?.addEventListener('click', resetFilters);

// Notification system
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg z-50 text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
            </svg>
            <span>${message}</span>
        </div>
    `;
    notification.style.animation = 'slideIn 0.3s ease-out';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Initialize filters on page load
applyFilters();
</script>
@endpush

@endsection