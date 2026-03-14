@extends('layouts.admin')

@section('title', 'Pending Companies - WorkNepal Admin')

@section('header', 'Pending Companies')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pending Verification</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Companies awaiting verification
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.companies.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to All Companies
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.companies.pending') }}" class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by company name or owner..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Search
                    </button>
                    <a href="{{ route('admin.companies.pending') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        {{-- Companies Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($companies as $company)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        {{-- Company Header --}}
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($company->logo_path)
                                    <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-gray-500 dark:text-gray-400 uppercase">
                                        {{ substr($company->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="hover:text-red-600">
                                        {{ $company->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company->industry ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Owner: {{ $company->owner->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Company Details --}}
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $company->location ?? 'Location not specified' }}
                            </div>
                            
                            @if($company->website)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                                    </svg>
                                    <a href="{{ $company->website }}" target="_blank" class="hover:text-red-600 truncate">
                                        {{ preg_replace('#^https?://#', '', $company->website) }}
                                    </a>
                                </div>
                            @endif

                            @if($company->contact_email)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $company->contact_email }}
                                </div>
                            @endif
                        </div>

                        {{-- Submitted Info --}}
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Submitted:</span>
                                <span class="text-gray-900 dark:text-white">{{ $company->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-500 dark:text-gray-400">Jobs Posted:</span>
                                <span class="text-gray-900 dark:text-white">{{ $company->total_jobs_count ?? 0 }}</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <form method="POST" action="{{ route('admin.companies.verify', $company) }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                                    Verify
                                </button>
                            </form>
                            
                            <button type="button" 
                                    @click="showRejectModal = true; companyId = {{ $company->id }}"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                Reject
                            </button>
                        </div>
                        
                        <div class="mt-2">
                            <a href="{{ route('admin.companies.show', $company) }}" 
                               class="block w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm font-medium">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No pending companies</h3>
                    <p class="text-gray-600 dark:text-gray-400">All companies have been processed</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($companies->hasPages())
            <div class="mt-6">
                {{ $companies->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div x-data="{ showRejectModal: false, companyId: null }" 
     x-show="showRejectModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Company</h3>
            <form method="POST" :action="`/admin/companies/${companyId}/reject`">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Rejection
                    </label>
                    <textarea name="rejection_reason" 
                              rows="4" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Please provide a reason for rejecting this company..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            @click="showRejectModal = false"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Reject Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection