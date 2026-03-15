@extends('layouts.admin')

@section('title', 'Reports & Abuse Handling - WorkNepal Admin')

@section('header', 'Reports & Abuse Handling')

@section('content')
<div class="py-6" x-data="reportsManagement()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Reports</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage user reports and abuse cases
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <button @click="exportReports()" 
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
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Pending</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['in_review'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">In Review</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['resolved'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Resolved</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600">{{ $stats['critical'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Critical</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-orange-600">{{ $stats['high'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">High Priority</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $entityStats['jobs'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Job Reports</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-indigo-600">{{ $entityStats['users'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">User Reports</div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Reports Trend --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reports Trend (Last 30 Days)</h3>
                <div class="h-64" id="trends-chart"></div>
            </div>

            {{-- Reports by Type --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reports by Type</h3>
                <div class="h-64" id="type-chart"></div>
            </div>
        </div>

        {{-- Bulk Actions Bar --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-4 p-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="select-all" 
                           @click="toggleSelectAll"
                           :checked="selectedReports.length === totalReports && totalReports > 0"
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                    <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</label>
                </div>
                
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span x-text="selectedReports.length" class="font-semibold"></span> reports selected
                </span>

                <div class="flex-1"></div>

                <select x-model="bulkAction" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Bulk Actions</option>
                    <option value="mark_in_review">Mark as In Review</option>
                    <option value="mark_resolved">Mark as Resolved</option>
                    <option value="mark_dismissed">Mark as Dismissed</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <select x-model="assignTo" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                        x-show="bulkAction === 'assign'">
                    <option value="">Select Admin</option>
                    @foreach(\App\Models\User::role(['admin', 'super_admin'])->get() as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                    @endforeach
                </select>

                <button @click="applyBulkAction" 
                        :disabled="!bulkAction || selectedReports.length === 0"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Apply
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
                    <div class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by reason, description, or reporter..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div class="w-40">
                            <select name="status" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                            </select>
                        </div>

                        {{-- Priority Filter --}}
                        <div class="w-40">
                            <select name="priority" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Priorities</option>
                                <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        {{-- Entity Type Filter --}}
                        <div class="w-40">
                            <select name="entity_type" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Types</option>
                                <option value="job" {{ request('entity_type') == 'job' ? 'selected' : '' }}>Jobs</option>
                                <option value="company" {{ request('entity_type') == 'company' ? 'selected' : '' }}>Companies</option>
                                <option value="user" {{ request('entity_type') == 'user' ? 'selected' : '' }}>Users</option>
                                <option value="review" {{ request('entity_type') == 'review' ? 'selected' : '' }}>Reviews</option>
                            </select>
                        </div>

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
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="priority_high" {{ request('sort') == 'priority_high' ? 'selected' : '' }}>Priority (High to Low)</option>
                                <option value="priority_low" {{ request('sort') == 'priority_low' ? 'selected' : '' }}>Priority (Low to High)</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.reports.index') }}" 
                               class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Reports Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <span class="sr-only">Select</span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reporter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Entity Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition" 
                                x-data="{ selected: false }">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" 
                                           value="{{ $report->id }}"
                                           x-model="selectedReports"
                                           @change="selected = $el.checked"
                                           class="report-checkbox w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    #{{ $report->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('admin.users.show', $report->reporter) }}" class="hover:text-red-600">
                                            {{ $report->reporter->name ?? 'Unknown' }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $report->reporter->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($report->reported_entity_type == 'job') bg-blue-100 text-blue-800
                                        @elseif($report->reported_entity_type == 'company') bg-green-100 text-green-800
                                        @elseif($report->reported_entity_type == 'user') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($report->reported_entity_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $report->reason }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($report->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($report->priority == 'critical') bg-red-100 text-red-800
                                        @elseif($report->priority == 'high') bg-orange-100 text-orange-800
                                        @elseif($report->priority == 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($report->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($report->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($report->status == 'in_review') bg-blue-100 text-blue-800
                                        @elseif($report->status == 'resolved') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->assignedTo->name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.reports.show', $report) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                           title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 
                                              onsubmit="return confirm('Delete this report? This action cannot be undone.')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Delete Report">
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
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No reports found</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($reports->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $reports->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Bulk Action Form --}}
<form id="bulk-action-form" method="POST" action="{{ route('admin.reports.bulk') }}" class="hidden">
    @csrf
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function reportsManagement() {
    return {
        selectedReports: [],
        bulkAction: '',
        assignTo: '',
        totalReports: {{ $reports->total() }},
        
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
                        label: 'Reports',
                        data: @json($trends['reports']),
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

            // Type Chart
            const typeCtx = document.getElementById('type-chart').getContext('2d');
            new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Jobs', 'Companies', 'Users', 'Reviews'],
                    datasets: [{
                        data: [
                            {{ $trends['by_type']['jobs'] }},
                            {{ $trends['by_type']['companies'] }},
                            {{ $trends['by_type']['users'] }},
                            {{ $trends['by_type']['reviews'] ?? 0 }}
                        ],
                        backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b'],
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
            if (this.selectedReports.length === this.totalReports) {
                this.selectedReports = [];
            } else {
                this.selectedReports = @json($reports->pluck('id'));
            }
        },
        
        applyBulkAction() {
            if (!this.bulkAction || this.selectedReports.length === 0) return;
            
            let confirmMessage = '';
            switch (this.bulkAction) {
                case 'mark_in_review':
                    confirmMessage = `Mark ${this.selectedReports.length} report(s) as In Review?`;
                    break;
                case 'mark_resolved':
                    confirmMessage = `Mark ${this.selectedReports.length} report(s) as Resolved?`;
                    break;
                case 'mark_dismissed':
                    confirmMessage = `Mark ${this.selectedReports.length} report(s) as Dismissed?`;
                    break;
                case 'delete':
                    confirmMessage = `Delete ${this.selectedReports.length} report(s)? This action cannot be undone.`;
                    break;
            }
            
            if (!confirm(confirmMessage)) return;
            
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
            
            if (this.bulkAction === 'assign' && this.assignTo) {
                const assignInput = document.createElement('input');
                assignInput.type = 'hidden';
                assignInput.name = 'assign_to';
                assignInput.value = this.assignTo;
                form.appendChild(assignInput);
            }
            
            this.selectedReports.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'report_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            form.submit();
        },
        
        exportReports() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '{{ route("admin.reports.export") }}?' + params.toString();
        },
        
        refreshStats() {
            fetch('{{ route("admin.reports.stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update stats display
                    location.reload();
                });
        }
    }
}
</script>
@endpush
@endsection