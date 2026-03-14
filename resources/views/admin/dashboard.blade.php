{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard - WorkNepal')

@section('header', 'Dashboard')

@section('content')
<div class="py-6" x-data="dashboard()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Period Selector --}}
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard Overview</h1>
            <div class="flex items-center space-x-3">
                <select @change="changePeriod($event.target.value)" 
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
                <button @click="exportData()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Users --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 uppercase tracking-wider">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" x-text="stats.totals.users">{{ number_format($stats['totals']['users']) }}</p>
                        <p class="text-sm mt-2">
                            <span class="text-green-600" x-show="stats.growth.users > 0" x-text="'+' + stats.growth.users + '%'"></span>
                            <span class="text-red-600" x-show="stats.growth.users < 0" x-text="stats.growth.users + '%'"></span>
                            <span class="text-gray-500 ml-1">vs last period</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between text-xs">
                    <span class="text-gray-500">{{ number_format($stats['current']['users']) }} new</span>
                    <span class="text-gray-500">{{ $stats['totals']['job_seekers'] }} job seekers</span>
                    <span class="text-gray-500">{{ $stats['totals']['employers'] }} employers</span>
                </div>
            </div>

            {{-- Total Companies --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 uppercase tracking-wider">Companies</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" x-text="stats.totals.companies">{{ number_format($stats['totals']['companies']) }}</p>
                        <p class="text-sm mt-2">
                            <span class="text-green-600" x-show="stats.growth.companies > 0" x-text="'+' + stats.growth.companies + '%'"></span>
                            <span class="text-red-600" x-show="stats.growth.companies < 0" x-text="stats.growth.companies + '%'"></span>
                            <span class="text-gray-500 ml-1">vs last period</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between text-xs">
                    <span class="text-green-600">{{ number_format($stats['totals']['verified_companies']) }} verified</span>
                    <span class="text-yellow-600">{{ number_format($pendingCounts['pending_companies']) }} pending</span>
                    <span class="text-gray-500">{{ number_format($stats['current']['companies']) }} new</span>
                </div>
            </div>

            {{-- Active Jobs --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 uppercase tracking-wider">Active Jobs</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['current']['active_jobs']) }}</p>
                        <p class="text-sm mt-2">
                            <span class="text-green-600" x-show="stats.growth.jobs > 0" x-text="'+' + stats.growth.jobs + '%'"></span>
                            <span class="text-red-600" x-show="stats.growth.jobs < 0" x-text="stats.growth.jobs + '%'"></span>
                            <span class="text-gray-500 ml-1">vs last period</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between text-xs">
                    <span class="text-yellow-600">{{ number_format($pendingCounts['pending_jobs']) }} pending</span>
                    <span class="text-gray-500">{{ number_format($stats['totals']['jobs']) }} total</span>
                </div>
            </div>

            {{-- Applications --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-red-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 uppercase tracking-wider">Applications</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2" x-text="stats.current.applications">{{ number_format($stats['current']['applications']) }}</p>
                        <p class="text-sm mt-2">
                            <span class="text-green-600" x-show="stats.growth.applications > 0" x-text="'+' + stats.growth.applications + '%'"></span>
                            <span class="text-red-600" x-show="stats.growth.applications < 0" x-text="stats.growth.applications + '%'"></span>
                            <span class="text-gray-500 ml-1">vs last period</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex justify-between text-xs">
                    <span class="text-green-600">{{ number_format($stats['current']['hired']) }} hired</span>
                    <span class="text-gray-500">{{ number_format($stats['totals']['applications']) }} total</span>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Jobs & Applications Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Jobs & Applications Trend</h3>
                <div class="h-80" x-ref="jobsChart"></div>
            </div>

            {{-- Users Growth Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Growth</h3>
                <div class="h-80" x-ref="usersChart"></div>
            </div>
        </div>

        {{-- Quick Action Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.companies.pending') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600">{{ $pendingCounts['pending_companies'] }}</span>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Pending Companies</h4>
                <p class="text-sm text-gray-500 mt-1">Awaiting verification</p>
            </a>

            <a href="{{ route('admin.jobs.pending') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600">{{ $pendingCounts['pending_jobs'] }}</span>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Pending Jobs</h4>
                <p class="text-sm text-gray-500 mt-1">Awaiting approval</p>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-red-600">{{ $pendingCounts['reported_jobs'] }}</span>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Reports</h4>
                <p class="text-sm text-gray-500 mt-1">Flagged content</p>
            </a>

            <a href="{{ route('admin.testimonials.pending') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600">{{ $pendingCounts['pending_testimonials'] }}</span>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">Testimonials</h4>
                <p class="text-sm text-gray-500 mt-1">Awaiting approval</p>
            </a>
        </div>

        {{-- Recent Activities & System Health --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Activities --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($recentActivities as $index => $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-full bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 flex items-center justify-center">
                                                    @if($activity['icon'] == 'user')
                                                        <svg class="h-5 w-5 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    @elseif($activity['icon'] == 'building')
                                                        <svg class="h-5 w-5 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                                        </svg>
                                                    @elseif($activity['icon'] == 'briefcase')
                                                        <svg class="h-5 w-5 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <p class="text-sm text-gray-900 dark:text-white">
                                                        {{ $activity['action'] == 'new_registration' ? 'New user registered' : 
                                                           ($activity['action'] == 'new_company' ? 'New company created' : 'New job posted') }}
                                                    </p>
                                                    <p class="mt-0.5 text-sm text-gray-500">
                                                        {{ $activity['subject'] }} • {{ $activity['time'] }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- System Health --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Health</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Database</dt>
                            <dd class="mt-1 flex items-center">
                                @if($systemHealth['database'])
                                    <span class="text-green-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Connected
                                    </span>
                                @else
                                    <span class="text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Error
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cache</dt>
                            <dd class="mt-1 flex items-center">
                                @if($systemHealth['cache'])
                                    <span class="text-green-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Operational
                                    </span>
                                @else
                                    <span class="text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Error
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Storage</dt>
                            <dd class="mt-1">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-900 dark:text-white">{{ $systemHealth['storage']['used'] }} used</span>
                                    <span class="text-gray-500">of {{ $systemHealth['storage']['total'] }}</span>
                                </div>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $systemHealth['storage']['percent'] }}%"></div>
                                </div>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Backup</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $systemHealth['last_backup'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PHP Version</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $systemHealth['php_version'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Laravel Version</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $systemHealth['laravel_version'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function dashboard() {
    return {
        stats: @json($stats),
        charts: @json($charts),
        jobsChart: null,
        usersChart: null,
        
        init() {
            this.initJobsChart();
            this.initUsersChart();
        },
        
        initJobsChart() {
            const ctx = this.$refs.jobsChart.getContext('2d');
            this.jobsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.charts.labels,
                    datasets: [
                        {
                            label: 'Jobs',
                            data: this.charts.jobs,
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Applications',
                            data: this.charts.applications,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
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
        
        initUsersChart() {
            const ctx = this.$refs.usersChart.getContext('2d');
            this.usersChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.charts.labels,
                    datasets: [
                        {
                            label: 'New Users',
                            data: this.charts.users,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6
                        },
                        {
                            label: 'New Companies',
                            data: this.charts.companies,
                            backgroundColor: '#10b981',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
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
        
        changePeriod(period) {
            window.location.href = '{{ route("admin.dashboard") }}?period=' + period;
        },
        
        exportData() {
            window.location.href = '{{ route("admin.dashboard.export") }}?period=' + document.querySelector('select').value;
        },
        
        refreshData() {
            fetch('{{ route("admin.dashboard.refresh") }}?period=' + document.querySelector('select').value)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.stats = data.stats;
                        this.charts = data.charts;
                        
                        // Update charts
                        this.jobsChart.data.labels = data.charts.labels;
                        this.jobsChart.data.datasets[0].data = data.charts.jobs;
                        this.jobsChart.data.datasets[1].data = data.charts.applications;
                        this.jobsChart.update();
                        
                        this.usersChart.data.labels = data.charts.labels;
                        this.usersChart.data.datasets[0].data = data.charts.users;
                        this.usersChart.data.datasets[1].data = data.charts.companies;
                        this.usersChart.update();
                    }
                });
        }
    }
}
</script>
@endpush
@endsection