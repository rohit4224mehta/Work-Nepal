@extends('layouts.admin')

@section('title', $company->name . ' - Insights - WorkNepal Admin')

@section('header', 'Company Insights')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.companies.show', $company) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $company->name }} - Insights</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Analytics and performance metrics</p>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $company->jobPostings()->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Jobs Posted</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-green-600">{{ $company->jobPostings()->where('status', 'active')->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Active Jobs</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-purple-600">{{ DB::table('job_applications')->whereIn('job_posting_id', $company->jobPostings()->pluck('id'))->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-blue-600">{{ $company->teamMembers()->count() }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Team Members</div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Job Posting Trends --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Job Posting Trends</h3>
                <div class="h-80" id="job-trends-chart"></div>
            </div>

            {{-- Application Trends --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Application Trends</h3>
                <div class="h-80" id="application-trends-chart"></div>
            </div>
        </div>

        {{-- Top Categories --}}
        @if($topCategories->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Job Categories</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($topCategories as $category)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $category->count }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $category->category }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Job Trends Chart
    const jobCtx = document.getElementById('job-trends-chart').getContext('2d');
    new Chart(jobCtx, {
        type: 'line',
        data: {
            labels: @json($jobTrends->pluck('date')),
            datasets: [{
                label: 'Jobs Posted',
                data: @json($jobTrends->pluck('count')),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
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

    // Application Trends Chart
    const appCtx = document.getElementById('application-trends-chart').getContext('2d');
    new Chart(appCtx, {
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
});
</script>
@endpush
@endsection