@extends('layouts.admin')

@section('title', $job->title . ' - Insights - WorkNepal Admin')

@section('header', 'Job Insights')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.jobs.show', $job) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $job->title }} - Insights</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->company->name }}</p>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $job->applications()->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-blue-600">{{ $statusBreakdown['viewed'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Viewed</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-green-600">{{ $statusBreakdown['shortlisted'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Shortlisted</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-purple-600">{{ $statusBreakdown['hired'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Hired</div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Application Trends --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Trends (Last 30 Days)</h3>
                <div class="h-80" id="application-trends-chart"></div>
            </div>

            {{-- Status Breakdown --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Status Breakdown</h3>
                <div class="h-80" id="status-breakdown-chart"></div>
            </div>
        </div>

        {{-- Conversion Funnel --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Application Funnel</h3>
            
            <div class="space-y-4">
                {{-- Applied --}}
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Applied</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $statusBreakdown['applied'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                {{-- Viewed --}}
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Viewed</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $statusBreakdown['viewed'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $total = $statusBreakdown['applied'] ?? 1;
                            $viewedPercent = $total > 0 ? round((($statusBreakdown['viewed'] ?? 0) / $total) * 100) : 0;
                        @endphp
                        <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $viewedPercent }}%"></div>
                    </div>
                </div>

                {{-- Shortlisted --}}
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Shortlisted</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $statusBreakdown['shortlisted'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $shortlistedPercent = $total > 0 ? round((($statusBreakdown['shortlisted'] ?? 0) / $total) * 100) : 0;
                        @endphp
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $shortlistedPercent }}%"></div>
                    </div>
                </div>

                {{-- Hired --}}
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Hired</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $statusBreakdown['hired'] ?? 0 }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $hiredPercent = $total > 0 ? round((($statusBreakdown['hired'] ?? 0) / $total) * 100) : 0;
                        @endphp
                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $hiredPercent }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Application Trends Chart
    const trendsCtx = document.getElementById('application-trends-chart').getContext('2d');
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: @json($applicationTrends->pluck('date')),
            datasets: [{
                label: 'Applications',
                data: @json($applicationTrends->pluck('count')),
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

    // Status Breakdown Chart
    const statusCtx = document.getElementById('status-breakdown-chart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Applied', 'Viewed', 'Shortlisted', 'Rejected', 'Hired'],
            datasets: [{
                data: [
                    {{ $statusBreakdown['applied'] ?? 0 }},
                    {{ $statusBreakdown['viewed'] ?? 0 }},
                    {{ $statusBreakdown['shortlisted'] ?? 0 }},
                    {{ $statusBreakdown['rejected'] ?? 0 }},
                    {{ $statusBreakdown['hired'] ?? 0 }}
                ],
                backgroundColor: [
                    '#3b82f6',
                    '#f59e0b',
                    '#10b981',
                    '#ef4444',
                    '#8b5cf6'
                ],
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
});
</script>
@endpush
@endsection