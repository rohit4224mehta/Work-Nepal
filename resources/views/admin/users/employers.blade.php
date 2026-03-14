@extends('layouts.admin')

@section('title', 'Employers Management - WorkNepal Admin')

@section('header', 'Employers')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Employers</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage all employer accounts and their companies
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to All Users
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.users.employers') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by name, email, or company..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <select name="status" class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>

                    <select name="company_verified" class="w-48 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Company Status</option>
                        <option value="verified" {{ request('company_verified') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="pending" {{ request('company_verified') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>

                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Apply Filters
                    </button>
                    
                    <a href="{{ route('admin.users.employers') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Employer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Companies</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jobs Posted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            @php
                                $companies = $user->ownedCompanies;
                                $totalJobs = $companies->sum(function($company) {
                                    return $company->jobPostings()->count();
                                });
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($user->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-600 to-green-500 flex items-center justify-center text-white font-bold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->mobile ?? 'No phone' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @foreach($companies as $company)
                                        <div class="text-sm mb-1">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $company->name }}</span>
                                            @if($company->verification_status == 'verified')
                                                <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                            @else
                                                <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $totalJobs }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->account_status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Suspended
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        @if($user->account_status == 'active')
                                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" 
                                                  onsubmit="return confirm('Suspend this employer?')" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Suspend">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.activate', $user) }}" 
                                                  onsubmit="return confirm('Activate this employer?')" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Activate">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('admin.companies.index', ['owner' => $user->id]) }}" 
                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                           title="View Companies">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No employers found</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection