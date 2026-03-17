{{-- resources/views/admin/profile/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'My Profile - WorkNepal Admin')

@section('header', 'My Profile')

@section('content')
<div class="py-6" x-data="{ activeTab: 'profile' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h2>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    @if(auth()->user()->isSuperAdmin()) bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                    @elseif(auth()->user()->isAdmin()) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                    @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                    @endif">
                    {{ auth()->user()->getRoleDisplayName() }}
                </span>
            </div>
            
            <div class="mt-4 sm:mt-0 flex gap-3">
                <a href="{{ route('admin.profile.edit') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
                </a>
                <a href="{{ route('admin.profile.password') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Change Password
                </a>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'profile'" 
                        :class="{ 'border-red-600 text-red-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'profile' }"
                        class="py-3 px-1 border-b-2 font-medium text-sm transition">
                    Profile Information
                </button>
                <button @click="activeTab = 'activity'" 
                        :class="{ 'border-red-600 text-red-600': activeTab === 'activity', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'activity' }"
                        class="py-3 px-1 border-b-2 font-medium text-sm transition">
                    Activity Log
                </button>
                <button @click="activeTab = 'security'" 
                        :class="{ 'border-red-600 text-red-600': activeTab === 'security', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'security' }"
                        class="py-3 px-1 border-b-2 font-medium text-sm transition">
                    Security
                </button>
            </nav>
        </div>

        {{-- Tab: Profile Information --}}
        <div x-show="activeTab === 'profile'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column - Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-24">
                        <div class="text-center">
                            <div class="w-32 h-32 mx-auto mb-4">
                                @if($admin->profile_photo_path)
                                    <img class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700" 
                                         src="{{ $admin->profile_photo_url }}" 
                                         alt="{{ $admin->name }}">
                                @else
                                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white text-4xl font-bold mx-auto border-4 border-gray-200 dark:border-gray-700">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $admin->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $admin->email }}</p>
                            <p class="text-xs text-gray-500 mt-1">Member since {{ $admin->created_at->format('M d, Y') }}</p>

                            <div class="mt-4 flex justify-center gap-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($admin->isSuperAdmin()) bg-purple-100 text-purple-800
                                    @elseif($admin->isAdmin()) bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ $admin->getRoleDisplayName() }}
                                </span>
                                @if($admin->account_status == 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Active
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Last Login</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Last IP</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white font-mono">
                                        {{ $admin->last_login_ip ?? 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Personal Information --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Personal Information</h4>
                        
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Full Name</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $admin->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Email Address</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1 break-all">{{ $admin->email }}</dd>
                                @if($admin->email_verified_at)
                                    <span class="inline-flex items-center mt-1 text-xs text-green-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Mobile Number</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $admin->mobile ?? 'Not provided' }}</dd>
                                @if($admin->mobile_verified_at)
                                    <span class="inline-flex items-center mt-1 text-xs text-green-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Date of Birth</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                    {{ $admin->date_of_birth ? $admin->date_of_birth->format('M d, Y') : 'Not provided' }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Gender</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                    {{ $admin->gender ? ucfirst(str_replace('_', ' ', $admin->gender->value)) : 'Not specified' }}
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Account Created</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                    {{ $admin->created_at->format('M d, Y h:i A') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Activity Log --}}
        <div x-show="activeTab === 'activity'" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Activity</h4>
                
                @if($recentActivities->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="w-2 h-2 mt-2 rounded-full
                                    @if($activity->level == 'critical') bg-red-500
                                    @elseif($activity->level == 'warning') bg-yellow-500
                                    @else bg-blue-500
                                    @endif">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $activity->description }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500">{{ $activity->created_at->format('M d, Y h:i A') }}</span>
                                        <span class="text-xs text-gray-400">({{ $activity->created_at->diffForHumans() }})</span>
                                        @if($activity->ip_address)
                                            <span class="text-xs text-gray-400 font-mono">{{ $activity->ip_address }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.logs.index', ['admin_id' => $admin->id]) }}" 
                           class="inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                            View All Activity
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No recent activity found</p>
                @endif
            </div>
        </div>

        {{-- Tab: Security --}}
        <div x-show="activeTab === 'security'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Password Age Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Password</h4>
                            <p class="text-sm text-gray-500">Last changed {{ $admin->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @php
                        $passwordAge = $admin->updated_at->diffInDays(now());
                    @endphp
                    
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-600 dark:text-gray-400">Password Age</span>
                            <span class="font-medium {{ $passwordAge > 90 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $passwordAge }} days
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $passwordAge > 90 ? 'bg-red-600' : 'bg-green-600' }}" 
                                 style="width: {{ min(100, ($passwordAge / 90) * 100) }}%"></div>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.profile.password') }}" 
                       class="mt-6 inline-flex items-center text-red-600 hover:text-red-700 text-sm font-medium">
                        Change Password
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                {{-- Two Factor Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Two-Factor Auth</h4>
                            <p class="text-sm text-gray-500">Add an extra layer of security</p>
                        </div>
                    </div>
                    
                    <button disabled 
                            class="mt-6 inline-flex items-center text-gray-400 text-sm font-medium cursor-not-allowed">
                        Coming Soon
                    </button>
                </div>

                {{-- Login Sessions Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Active Sessions</h4>
                            <p class="text-sm text-gray-500">Manage your logged-in devices</p>
                        </div>
                    </div>
                    
                    <button disabled 
                            class="mt-6 inline-flex items-center text-gray-400 text-sm font-medium cursor-not-allowed">
                        Coming Soon
                    </button>
                </div>

                {{-- API Tokens Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">API Tokens</h4>
                            <p class="text-sm text-gray-500">Manage API access</p>
                        </div>
                    </div>
                    
                    <button disabled 
                            class="mt-6 inline-flex items-center text-gray-400 text-sm font-medium cursor-not-allowed">
                        Coming Soon
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadMoreActivity() {
        // AJAX function to load more activity
    }
</script>
@endpush
@endsection