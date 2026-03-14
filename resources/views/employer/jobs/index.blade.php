@extends('layouts.app')

@section('title', 'Manage Jobs - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Jobs</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
                View and manage all your job postings
            </p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" 
           class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Post New Job
        </a>
    </div>

    {{-- Jobs Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Job Title</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Company</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Location</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Type</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Applications</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Posted</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $job->title }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $job->company->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $job->location }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-xs">
                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('employer.jobs.applications', $job) }}" 
                                   class="text-red-600 hover:text-red-700 font-medium">
                                    {{ $job->applications_count ?? 0 }} applications
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($job->status == 'active') bg-green-100 text-green-800
                                    @elseif($job->status == 'closed') bg-gray-100 text-gray-800
                                    @elseif($job->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($job->status) }}
                                </span>
                                @if($job->verification_status == 'pending')
                                    <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                {{ $job->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('employer.jobs.edit', $job) }}" 
                                       class="p-2 text-gray-500 hover:text-blue-600 transition-colors"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('employer.jobs.destroy', $job) }}" 
                                          onsubmit="return confirm('Are you sure you want to delete this job?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 transition-colors" title="Delete">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No jobs posted yet</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Start by posting your first job</p>
                                <a href="{{ route('employer.jobs.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Post a Job
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(isset($jobs) && method_exists($jobs, 'links'))
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection