@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name . ' - WorkNepal Admin')

@section('header', 'User Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">User Profile</h2>
            </div>
            
            <div class="flex gap-3">
                @if($user->account_status == 'active')
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}" 
                          onsubmit="return confirm('Suspend this user?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Suspend User
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.activate', $user) }}" 
                          onsubmit="return confirm('Activate this user?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Activate User
                        </button>
                    </form>
                @endif
                
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                      onsubmit="return confirm('Permanently delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete User
                    </button>
                </form>
            </div>
        </div>

        {{-- User Info Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Profile Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Profile Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-4">
                            @if($user->profile_photo_path)
                                <img class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700" 
                                     src="{{ $user->profile_photo_url }}" alt="">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white text-3xl font-bold mx-auto border-4 border-gray-200 dark:border-gray-700">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member since {{ $user->created_at->format('M d, Y') }}</p>
                        
                        <div class="mt-4 flex justify-center gap-2">
                            @foreach($user->roles as $role)
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($role->name == 'admin') bg-purple-100 text-purple-800
                                    @elseif($role->name == 'employer') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @endforeach
                            
                            @if($user->account_status == 'active')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Active</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Suspended</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->email }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->mobile ?? 'Not provided' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Email Verified</dt>
                                <dd class="text-sm font-medium">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">{{ $user->email_verified_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-red-600">No</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Last Login</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Last IP</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->last_login_ip ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->jobApplications()->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Applications</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->savedJobs()->count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Saved Jobs</div>
                        </div>
                        @if($user->hasRole('employer'))
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->ownedCompanies->count() }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Companies</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                @php
                                    $totalJobs = $user->ownedCompanies->sum(function($company) {
                                        return $company->jobPostings()->count();
                                    });
                                @endphp
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalJobs }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Jobs Posted</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column - Detailed Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Skills --}}
                @if($user->skills->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Skills</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->skills as $skill)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Education --}}
                @if($user->education->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Education</h4>
                        <div class="space-y-4">
                            @foreach($user->education as $edu)
                                <div class="border-l-4 border-red-600 pl-4">
                                    <h5 class="font-medium text-gray-900 dark:text-white">{{ $edu->degree }} in {{ $edu->field_of_study }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $edu->institution }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $edu->start_date->format('Y') }} - {{ $edu->is_current ? 'Present' : $edu->end_date->format('Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Experience --}}
                @if($user->experience->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Work Experience</h4>
                        <div class="space-y-4">
                            @foreach($user->experience as $exp)
                                <div class="border-l-4 border-green-600 pl-4">
                                    <h5 class="font-medium text-gray-900 dark:text-white">{{ $exp->position }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $exp->company_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $exp->start_date->format('M Y') }} - {{ $exp->is_current ? 'Present' : $exp->end_date->format('M Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Recent Applications --}}
                @if($user->jobApplications->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Applications</h4>
                        <div class="space-y-3">
                            @foreach($user->jobApplications as $application)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->jobPosting->title }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->jobPosting->company->name }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($application->status == 'applied') bg-blue-100 text-blue-800
                                        @elseif($application->status == 'shortlisted') bg-green-100 text-green-800
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Companies (for employers) --}}
                @if($user->hasRole('employer') && $user->ownedCompanies->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Owned Companies</h4>
                        <div class="space-y-3">
                            @foreach($user->ownedCompanies as $company)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            @if($company->logo_path)
                                                <img src="{{ $company->logo_url }}" alt="" class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <span class="text-lg font-bold text-gray-500">{{ substr($company->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $company->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $company->jobPostings()->count() }} jobs posted</p>
                                        </div>
                                    </div>
                                    @if($company->verification_status == 'verified')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">Verified</span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection