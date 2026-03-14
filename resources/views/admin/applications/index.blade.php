@extends('layouts.admin')

@section('title', 'Applications Monitoring - WorkNepal Admin')

@section('header', 'Applications Monitoring')

@section('content')
<div class="py-6" x-data="applicationsManagement()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Applications</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Monitor and manage all job applications
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <button @click="exportApplications()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <button @click="refreshStats()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Total</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['applied'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Applied</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['viewed'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Viewed</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['shortlisted'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Shortlisted</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Rejected</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Hired</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-indigo-600">{{ $stats['today'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Today</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-pink-600">{{ $stats['this_month'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">This Month</div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Applications Trend --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Applications Trend (Last 30 Days)</h3>
                <div class="h-64" id="trends-chart"></div>
            </div>

            {{-- Status Distribution --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Distribution</h3>
                <div class="h-64" id="status-chart"></div>
            </div>
        </div>

        {{-- Bulk Actions Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-4 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="select-all" 
                           @click="toggleSelectAll"
                           :checked="selectedApplications.length === totalApplications && totalApplications > 0"
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                    <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</label>
                </div>
                
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span x-text="selectedApplications.length" class="font-semibold"></span> applications selected
                </span>

                <div class="flex-1"></div>

                <select x-model="bulkAction" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Bulk Actions</option>
                    <option value="viewed">Mark as Viewed</option>
                    <option value="shortlisted">Mark as Shortlisted</option>
                    <option value="rejected">Mark as Rejected</option>
                    <option value="hired">Mark as Hired</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <button @click="applyBulkAction" 
                        :disabled="!bulkAction || selectedApplications.length === 0"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Apply
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.applications.index') }}" class="space-y-4" id="filter-form">
                    <div class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by applicant, job, or company..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Company Filter --}}
                        <div class="w-48">
                            <select name="company_id" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Companies</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Job Filter --}}
                        <div class="w-48">
                            <select name="job_id" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Jobs</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                        {{ Str::limit($job->title, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-40">
                            <select name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Status</option>
                                <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>Applied</option>
                                <option value="viewed" {{ request('status') == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        {{-- Date Range --}}
                        <div class="w-40">
                            <input type="date" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   placeholder="From Date"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="w-40">
                            <input type="date" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}"
                                   placeholder="To Date"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        {{-- Sort --}}
                        <div class="w-40">
                            <select name="sort" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="status_asc" {{ request('sort') == 'status_asc' ? 'selected' : '' }}>Status A-Z</option>
                                <option value="status_desc" {{ request('sort') == 'status_desc' ? 'selected' : '' }}>Status Z-A</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.applications.index') }}" 
                               class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Applications Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <span class="sr-only">Select</span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Applicant
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Job Position
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Company
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Applied Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Resume
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applications as $application)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" 
                                x-data="{ selected: false }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           value="{{ $application->id }}"
                                           x-model="selectedApplications"
                                           @change="selected = $el.checked"
                                           class="application-checkbox w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($application->applicant->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $application->applicant->profile_photo_url }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold">
                                                    {{ substr($application->applicant->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('admin.users.show', $application->applicant) }}" class="hover:text-red-600">
                                                    {{ $application->applicant->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $application->applicant->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.jobs.show', $application->jobPosting) }}" class="hover:text-red-600">
                                            {{ $application->jobPosting->title }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        ID: {{ $application->jobPosting->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $application->jobPosting->company->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $application->created_at->format('M d, Y') }}
                                    <span class="block text-xs">{{ $application->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select onchange="updateStatus({{ $application->id }}, this.value)"
                                            class="status-select text-xs px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                            data-application-id="{{ $application->id }}">
                                        <option value="applied" {{ $application->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="viewed" {{ $application->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                        <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($application->applicant->resume_path)
                                        <a href="{{ Storage::url($application->applicant->resume_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg text-xs hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            View CV
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">No CV</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.applications.show', $application) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                           title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" 
                                              onsubmit="return confirm('Delete this application? This action cannot be undone.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Delete Application">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No applications found</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Top Jobs by Applications --}}
        @if($trends['top_jobs']->isNotEmpty())
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Jobs by Applications</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($trends['top_jobs'] as $job)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $job['count'] }}</div>
                            <div class="text-sm text-gray-900 dark:text-white font-medium truncate">{{ $job['title'] }}</div>
                            <div class="text-xs text-gray-500">applications</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Bulk Action Form --}}
<form id="bulk-action-form" method="POST" action="{{ route('admin.applications.bulk-update') }}" class="hidden">
    @csrf
</form>

<form id="bulk-delete-form" method="POST" action="{{ route('admin.applications.bulk-delete') }}" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function applicationsManagement() {
    return {
        selectedApplications: [],
        bulkAction: '',
        totalApplications: {{ $applications->total() }},
        
        init() {
            this.initCharts();
        },
        
        initCharts() {
            // Trends Chart
            const trendsCtx = document.getElementById('trends-chart').getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: @json($trends['labels']),
                    datasets: [{
                        label: 'Applications',
                        data: @json($trends['applications']),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('status-chart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Applied', 'Viewed', 'Shortlisted', 'Rejected', 'Hired'],
                    datasets: [{
                        data: [
                            {{ $trends['status_breakdown']['applied'] }},
                            {{ $trends['status_breakdown']['viewed'] }},
                            {{ $trends['status_breakdown']['shortlisted'] }},
                            {{ $trends['status_breakdown']['rejected'] }},
                            {{ $trends['status_breakdown']['hired'] }}
                        ],
                        backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        },
        
        toggleSelectAll() {
            if (this.selectedApplications.length === this.totalApplications) {
                this.selectedApplications = [];
            } else {
                this.selectedApplications = @json($applications->pluck('id'));
            }
        },
        
        applyBulkAction() {
            if (!this.bulkAction || this.selectedApplications.length === 0) return;
            
            if (this.bulkAction === 'delete') {
                if (!confirm(`Delete ${this.selectedApplications.length} applications? This action cannot be undone.`)) return;
                
                const form = document.getElementById('bulk-delete-form');
                form.innerHTML = '';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                this.selectedApplications.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                form.submit();
            } else {
                if (!confirm(`Mark ${this.selectedApplications.length} applications as ${this.bulkAction}?`)) return;
                
                const form = document.getElementById('bulk-action-form');
                form.innerHTML = '';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = this.bulkAction;
                form.appendChild(actionInput);
                
                this.selectedApplications.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                form.submit();
            }
        },
        
        exportApplications() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '{{ route("admin.applications.export") }}?' + params.toString();
        },
        
        refreshStats() {
            fetch('{{ route("admin.applications.stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update stats display
                    // This would require Alpine to re-render the stats section
                    location.reload();
                });
        }
    }
}

function updateStatus(applicationId, status) {
    fetch(`/admin/applications/${applicationId}/status`, {
        method: 'POST',
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
    // Your notification logic
    alert(message);
}
</script>
@endpush
@endsection