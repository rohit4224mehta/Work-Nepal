{{-- resources/views/employer/company/team.blade.php --}}
@extends('layouts.app')

@section('title', 'Manage Team - ' . $company->name . ' - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('employer.dashboard') }}" 
               class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Team</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">{{ $company->name }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Team Members List --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Team Members</h2>
                </div>
                
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    {{-- Owner --}}
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <span class="text-lg font-bold text-purple-600 dark:text-purple-500">
                                        {{ substr($company->owner->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $company->owner->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $company->owner->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-xs font-medium">
                                Owner
                            </span>
                        </div>
                    </div>

                    {{-- Team Members --}}
                    @foreach($company->teamMembers as $member)
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-500">
                                            {{ substr($member->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $member->name }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $member->email }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Role: <span class="font-medium capitalize">{{ $member->pivot->role }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $member->pivot->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $member->pivot->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    
                                    @if(auth()->id() === $company->owner_id || auth()->user()->canAccessCompany($company))
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" @click.away="open = false"
                                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-10">
                                                <div class="py-1">
                                                    <form method="POST" action="{{ route('employer.company.team.toggle', [$company, $member]) }}">
                                                        @csrf
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            {{ $member->pivot->is_active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                    
                                                    <form method="POST" action="{{ route('employer.company.team.remove', [$company, $member]) }}"
                                                          onsubmit="return confirm('Are you sure you want to remove this team member?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Add Member Form --}}
        @if(auth()->id() === $company->owner_id || auth()->user()->canAccessCompany($company))
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Add Team Member</h2>
                    
                    <form method="POST" action="{{ route('employer.company.team.add', $company) }}">
                        @csrf
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       name="email" 
                                       required
                                       placeholder="colleague@company.com"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Role
                                </label>
                                <select name="role" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                    <option value="recruiter">Recruiter</option>
                                    <option value="hr">HR Manager</option>
                                    <option value="manager">Hiring Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                                Add Team Member
                            </button>
                        </div>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">About Team Members</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Team members can post jobs and manage applications. They will also receive notifications for new applications.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection