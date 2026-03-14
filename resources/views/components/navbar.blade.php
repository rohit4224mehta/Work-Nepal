<header class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50 transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false, profileDropdownOpen: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20" :class="{ 'shadow-md': scrolled }">

            <!-- Logo with enhanced branding -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-500 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                    WN
                </div>
                <span class="text-2xl md:text-3xl font-extrabold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent tracking-tight">
                    WorkNepal
                </span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8 lg:space-x-12">

                <!-- Common Navigation Links -->
                <div class="flex items-center space-x-6 lg:space-x-8">
                    <a href="{{ route('jobs.index') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-all duration-200 relative group py-2">
                        Find Jobs
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-red-600 to-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('companies.index') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-all duration-200 relative group py-2">
                        Companies
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-red-600 to-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('pages.cv-tips') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-all duration-200 relative group py-2">
                        Career Advice
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-red-600 to-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('pages.foreign-safety') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-all duration-200 relative group py-2">
                        <span class="flex items-center gap-1">
                            Foreign Jobs
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-red-600 to-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <!-- Authenticated / Role-Specific Area -->
                <div class="flex items-center space-x-4 lg:space-x-6">

                    @guest
                        <!-- Guest State with enhanced CTAs -->
                        <a href="{{ route('login') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            Log in
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Join Free
                        </a>
                    @else
                        <!-- Job Seeker Quick Links -->
                        @role('job_seeker')
                            <a href="{{ route('saved.jobs') }}" 
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 relative group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="hidden lg:inline">Saved</span>
                                @if(auth()->user()->savedJobs()->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ auth()->user()->savedJobs()->count() }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('applications.index') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 relative group">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="hidden lg:inline">Applications</span>
                                @php
                                    $pendingCount = auth()->user()->jobApplications()->whereIn('status', ['applied', 'viewed'])->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </a>
                        @endrole

                        <!-- Employer Quick Actions - FIXED VERSION -->
                        @role('employer|recruiter')
                            @php
                                try {
                                    $user = auth()->user();
                                    $companies = $user->accessibleCompanies()->pluck('id');
                                    
                                    // Check which relationship exists and use the correct one
                                    if (method_exists(\App\Models\JobApplication::class, 'jobPosting')) {
                                        $newApplicants = \App\Models\JobApplication::whereHas('jobPosting', function($q) use ($companies) {
                                            $q->whereIn('company_id', $companies);
                                        })->where('status', 'applied')->count();
                                    } elseif (method_exists(\App\Models\JobApplication::class, 'job')) {
                                        $newApplicants = \App\Models\JobApplication::whereHas('job', function($q) use ($companies) {
                                            $q->whereIn('company_id', $companies);
                                        })->where('status', 'applied')->count();
                                    } else {
                                        // Fallback: direct join if relationships don't exist
                                        $newApplicants = \App\Models\JobApplication::join('job_postings', 'job_applications.job_posting_id', '=', 'job_postings.id')
                                            ->whereIn('job_postings.company_id', $companies)
                                            ->where('job_applications.status', 'applied')
                                            ->count();
                                    }
                                } catch (\Exception $e) {
                                    $newApplicants = 0;
                                }
                            @endphp
                            
                            <a href="{{ route('employer.jobs.create') }}"
                               class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                Post a Job
                            </a>

                            <a href="{{ route('employer.jobs.index') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                                My Jobs
                            </a>

                            <a href="{{ route('employer.applicants.index') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 relative">
                                Applicants
                                @if($newApplicants > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $newApplicants > 9 ? '9+' : $newApplicants }}
                                    </span>
                                @endif
                            </a>
                        @endrole

                        <!-- Admin Quick Actions -->
                        @role('admin|super_admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                Admin
                            </a>
                        @endrole

                        <!-- Notifications Bell with Alpine.js -->
                        <div class="relative" x-data="{ notificationsOpen: false }">
                            <button @click="notificationsOpen = !notificationsOpen" 
                                    class="relative text-gray-700 dark:text-gray-300 hover:text-red-600 transition-colors focus:outline-none p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                
                                @php
                                    try {
                                        $unreadCount = auth()->user()->unreadNotifications()->count();
                                    } catch (\Exception $e) {
                                        $unreadCount = 0;
                                    }
                                @endphp
                                
                                @if($unreadCount > 0)
                                    <span class="absolute top-1 right-1 bg-red-600 text-white text-xs font-bold rounded-full min-w-[18px] h-5 px-1.5 flex items-center justify-center animate-pulse">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="notificationsOpen" 
                                 @click.away="notificationsOpen = false"
                                 class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transition-all duration-200 origin-top-right z-50"
                                 x-cloak>
                                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                    @if($unreadCount > 0)
                                        <a href="#" class="text-sm text-red-600 hover:text-red-700">Mark all read</a>
                                    @endif
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @php
                                        try {
                                            $notifications = auth()->user()->notifications()->take(5)->get();
                                        } catch (\Exception $e) {
                                            $notifications = collect([]);
                                        }
                                    @endphp
                                    
                                    @forelse($notifications as $notification)
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400">No notifications</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($notifications->count() > 5)
                                    <div class="p-3 border-t border-gray-200 dark:border-gray-800 text-center">
                                        <a href="#" class="text-sm text-red-600 hover:text-red-700">View all</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Dropdown with Alpine.js -->
                        <div class="relative" x-data="{ profileDropdownOpen: false }">
                            <button @click="profileDropdownOpen = !profileDropdownOpen" 
                                    class="flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden border-2 border-transparent hover:border-red-500 transition-all">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300 uppercase">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': profileDropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div x-show="profileDropdownOpen" 
                                 @click.away="profileDropdownOpen = false"
                                 class="absolute right-0 mt-3 w-64 bg-white dark:bg-gray-900 rounded-xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transition-all duration-200 origin-top-right z-50"
                                 x-cloak>
                                <div class="py-2">
                                    <!-- User Info Header -->
                                    <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-800">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs rounded-full">
                                                {{ auth()->user()->getRoleDisplayName() ?? 'Job Seeker' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Menu Items -->
                                    <a href="{{ route('profile.show', auth()->user()) }}" 
                                       class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>

                                    <a href="{{ route('profile.edit') }}" 
                                       class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Profile
                                    </a>

                                    @role('job_seeker')
                                        <a href="{{ route('dashboard.jobseeker') }}" 
                                           class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                            Dashboard
                                        </a>
                                    @endrole

                                    @role('employer|recruiter')
                                        <a href="{{ route('employer.dashboard') }}" 
                                           class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Company Dashboard
                                        </a>
                                    @endrole

                                    <a href="{{ route('settings.index') }}"
                                       class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Settings
                                    </a>

                                    <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>

                                    @role('admin|super_admin')
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="block px-5 py-3 text-sm text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/30 transition-colors flex items-center gap-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Admin Panel
                                        </a>
                                        <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
                                    @endrole

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-5 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors flex items-center gap-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button with animation -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="md:hidden text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-lg p-2 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    aria-label="Toggle menu">
                <svg x-show="!mobileMenuOpen" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu with Alpine.js -->
    <div x-show="mobileMenuOpen" 
         @click.away="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 shadow-lg"
         x-cloak>
        <div class="px-4 py-6 space-y-5 max-h-[calc(100vh-4rem)] overflow-y-auto">
            <!-- User Info for Mobile -->
            @auth
                <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-medium text-gray-700 dark:text-gray-300 uppercase">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            @endauth

            <!-- Common Links -->
            <a href="{{ route('jobs.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                Find Jobs
            </a>
            <a href="{{ route('companies.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                Companies
            </a>
            <a href="{{ route('pages.cv-tips') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                Career Advice
            </a>
            <a href="{{ route('pages.foreign-safety') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                Foreign Jobs
            </a>

            @auth
                <!-- Role-specific links for mobile -->
                @role('job_seeker')
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-4">
                        <a href="{{ route('dashboard.jobseeker') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Dashboard
                        </a>
                        <a href="{{ route('saved.jobs') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Saved Jobs
                            @if(auth()->user()->savedJobs()->count() > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded-full">
                                    {{ auth()->user()->savedJobs()->count() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('applications.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            My Applications
                            @php
                                $pendingCount = auth()->user()->jobApplications()->whereIn('status', ['applied', 'viewed'])->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endrole

                @role('employer|recruiter')
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-4">
                        <a href="{{ route('employer.dashboard') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Company Dashboard
                        </a>
                        <a href="{{ route('employer.jobs.create') }}" class="block text-lg font-medium bg-red-600 text-white px-4 py-3 rounded-xl text-center hover:bg-red-700 transition-colors">
                            + Post a Job
                        </a>
                        <a href="{{ route('employer.jobs.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            My Jobs
                        </a>
                        <a href="{{ route('employer.applicants.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Applicants
                            @php
                                try {
                                    $user = auth()->user();
                                    $companies = $user->accessibleCompanies()->pluck('id');
                                    
                                    if (method_exists(\App\Models\JobApplication::class, 'jobPosting')) {
                                        $newApplicants = \App\Models\JobApplication::whereHas('jobPosting', function($q) use ($companies) {
                                            $q->whereIn('company_id', $companies);
                                        })->where('status', 'applied')->count();
                                    } elseif (method_exists(\App\Models\JobApplication::class, 'job')) {
                                        $newApplicants = \App\Models\JobApplication::whereHas('job', function($q) use ($companies) {
                                            $q->whereIn('company_id', $companies);
                                        })->where('status', 'applied')->count();
                                    } else {
                                        $newApplicants = \App\Models\JobApplication::join('job_postings', 'job_applications.job_posting_id', '=', 'job_postings.id')
                                            ->whereIn('job_postings.company_id', $companies)
                                            ->where('job_applications.status', 'applied')
                                            ->count();
                                    }
                                } catch (\Exception $e) {
                                    $newApplicants = 0;
                                }
                            @endphp
                            @if($newApplicants > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded-full">
                                    {{ $newApplicants }}
                                </span>
                            @endif
                        </a>
                    </div>
                @endrole

                @role('admin|super_admin')
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                        <a href="{{ route('admin.dashboard') }}" class="block text-lg font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 transition-colors py-2">
                            Admin Panel
                        </a>
                    </div>
                @endrole

                <!-- Common authenticated links for mobile -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-4">
                    <a href="{{ route('profile.show', auth()->user()) }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        My Profile
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        Edit Profile
                    </a>
                    <a href="{{ route('settings.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full text-left text-lg font-medium text-red-600 dark:text-red-400 hover:text-red-700 transition-colors py-2">
                            Sign Out
                        </button>
                    </form>
                </div>
            @else
                <!-- Guest links for mobile -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-4">
                    <a href="{{ route('login') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="block text-center py-4 px-6 bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-600 transition-all shadow-md">
                        Create Free Account
                    </a>
                </div>
            @endauth

            <!-- Footer Links -->
            <div class="pt-6 mt-4 border-t border-gray-200 dark:border-gray-800 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('pages.about') }}" class="hover:text-red-600">About</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-red-600">Contact</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-red-600">Privacy</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-red-600">Terms</a>
                    <a href="{{ route('pages.help-center') }}" class="hover:text-red-600">Help</a>
                </div>
                <p class="mt-4">© {{ date('Y') }} WorkNepal. All rights reserved.</p>
            </div>
        </div>
    </div>
</header>

<!-- Required Alpine.js and styles -->
<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Add Alpine.js if not already included -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>