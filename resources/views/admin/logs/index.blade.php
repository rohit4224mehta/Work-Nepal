@extends('layouts.admin')

@section('title', 'Audit Logs - WorkNepal Admin')

@section('header', 'Audit Logs')

@section('content')
<div class="py-6" x-data="logsManagement()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Security Audit Logs</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Track all admin actions and system events
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <button @click="exportLogs()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
                <button @click="showClearModal = true" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Old Logs
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Total Events</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-green-600">{{ $stats['today'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Today</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['this_week'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">This Week</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['this_month'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">This Month</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-red-600">{{ $stats['critical'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Critical</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['warnings'] }}</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Warnings</div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Activity by Hour --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Today (by Hour)</h3>
                <div class="h-64" id="hourly-chart"></div>
            </div>

            {{-- Activity by Action --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Actions (Last 7 Days)</h3>
                <div class="h-64" id="action-chart"></div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.logs.index') }}" class="space-y-4">
                    <div class="flex flex-wrap gap-4">
                        {{-- Search --}}
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by description, IP, or admin..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Action Filter --}}
                        <div class="w-48">
                            <select name="action" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Level Filter --}}
                        <div class="w-48">
                            <select name="level" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Levels</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Admin Filter --}}
                        <div class="w-48">
                            <select name="admin_id" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Admins</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
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

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.logs.index') }}" 
                               class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Logs Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $log->timestamp->format('M d, Y H:i:s') }}
                                    <span class="block text-xs">{{ $log->timestamp->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->admin)
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($log->admin->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->admin->name }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $log->admin_id }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">System</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($log->action == 'delete' || $log->action == 'suspend' || $log->action == 'ban') bg-red-100 text-red-800
                                        @elseif($log->action == 'create') bg-green-100 text-green-800
                                        @elseif($log->action == 'update') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $log->description }}</div>
                                    @if($log->subject_type)
                                        <div class="text-xs text-gray-500">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($log->level == 'critical') bg-red-100 text-red-800
                                        @elseif($log->level == 'danger') bg-orange-100 text-orange-800
                                        @elseif($log->level == 'warning') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($log->level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.logs.show', $log) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No logs found</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Top Admins Card --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Active Admins (Last 30 Days)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($actionCounts as $actionCount)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $actionCount->count }}</div>
                        <div class="text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst($actionCount->action) }}</div>
                        <div class="text-xs text-gray-500">actions</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Clear Logs Modal --}}
<div x-data="{ showClearModal: false, days: 30 }" 
     x-show="showClearModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Clear Old Logs</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                This will permanently delete logs older than the specified number of days.
            </p>
            
            <form method="POST" action="{{ route('admin.logs.clear') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Delete logs older than (days)
                    </label>
                    <input type="number" 
                           name="older_than" 
                           x-model="days"
                           min="1"
                           max="365"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                           required>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showClearModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function logsManagement() {
    return {
        init() {
            this.initCharts();
            this.startRealTimeUpdates();
        },
        
        initCharts() {
            // Fetch stats and initialize charts
            fetch('{{ route("admin.logs.stats") }}')
                .then(response => response.json())
                .then(data => {
                    this.initHourlyChart(data.by_hour);
                    this.initActionChart(data.by_action);
                });
        },
        
        initHourlyChart(hourlyData) {
            const ctx = document.getElementById('hourly-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: hourlyData.map(item => item.hour + ':00'),
                    datasets: [{
                        label: 'Activities',
                        data: hourlyData.map(item => item.count),
                        backgroundColor: '#3b82f6',
                        borderRadius: 4
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
        },
        
        initActionChart(actionData) {
            const ctx = document.getElementById('action-chart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: actionData.map(item => item.action),
                    datasets: [{
                        data: actionData.map(item => item.count),
                        backgroundColor: ['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
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
        
        startRealTimeUpdates() {
            // Poll for new logs every 30 seconds
            setInterval(() => {
                this.fetchNewLogs();
            }, 30000);
        },
        
        fetchNewLogs() {
            const lastId = document.querySelector('table tbody tr:first-child td:first-child')?.textContent || 0;
            
            fetch('{{ route("admin.logs.stream") }}?last_id=' + lastId)
                .then(response => response.json())
                .then(data => {
                    if (data.logs.length > 0) {
                        // Show notification for new logs
                        this.showNotification('info', data.logs.length + ' new log entries');
                    }
                });
        },
        
        exportLogs() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '{{ route("admin.logs.export") }}?' + params.toString();
        },
        
        showNotification(type, message) {
            // Simple notification - you can enhance this
            alert(message);
        }
    }
}
</script>
@endpush
@endsection