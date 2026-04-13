<header class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50 transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false, profileDropdownOpen: false, notificationsOpen: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)">
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

                <!-- Common Navigation Links (Visible to Everyone) -->
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

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4 lg:space-x-6">

                    @guest
                        <!-- Guest State -->
                        
                        <a href="{{ route('login') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                            Log in
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Join Free
                        </a>
                    @else
                        <!-- Job Seeker Quick Links (Only visible to job seekers) -->
                        @role('job_seeker')
                            
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

                        <!-- Notifications Bell - LinkedIn Style (Direct Link) -->
<a href="{{ route('notifications.index') }}" 
   class="relative text-gray-700 dark:text-gray-300 hover:text-red-600 transition-colors focus:outline-none p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
    <span class="sr-only">View notifications</span>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
    </svg>
    
    <!-- Unread Badge -->
    @php
        try {
            $unreadCount = auth()->user()->unreadNotifications()->count();
        } catch (\Exception $e) {
            $unreadCount = 0;
        }
    @endphp
    @if($unreadCount > 0)
        <span class="absolute top-1 right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
    @endif
</a>


                        <!-- Profile Dropdown with Alpine.js -->
                        <div class="relative" @click.away="profileDropdownOpen = false">
                            <button @click="profileDropdownOpen = !profileDropdownOpen" 
                                    class="flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden border-2 border-transparent hover:border-red-500 transition-all">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300 uppercase">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true"></span>
                                    <svg class="ml-2 h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': profileDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            <div x-show="profileDropdownOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                                 style="display: none;">
                                <div class="py-1">
                                    <!-- User Info Header -->
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                @if(auth()->user()->hasRole('super_admin')) bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                                @elseif(auth()->user()->hasRole('admin')) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @elseif(auth()->user()->hasRole('employer')) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @endif">
                                                {{ auth()->user()->getRoleDisplayName() }}
                                            </span>
                                            @if(auth()->user()->account_status == 'active')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Common Menu Items -->
                                    <a href="{{ route('dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 2 2 4-4 4 4 2-2 2 2M5 12v6h4v-4h6v4h4v-6" />
                                        </svg>
                                        Dashboard
                                    </a>

                                    <a href="{{ route('profile.show', auth()->user()) }}" 
                                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>

                                    

                                    <a href="{{ route('settings.index') }}"
                                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Settings
                                    </a>

                                    <!-- JOB SEEKER SPECIFIC ACTIONS -->
                                    @role('job_seeker')
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        
                                        {{-- <a href="{{ route('dashboard.jobseeker') }}" 
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                            Job Seeker Dashboard
                                        </a> --}}
                                        
                                        <a href="{{ route('saved-jobs.index') }}" 
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                            </svg>
                                            Saved Jobs
                                            @if(auth()->user()->savedJobs()->count() > 0)
                                                <span class="ml-auto bg-red-100 text-red-600 text-xs font-medium px-2 py-0.5 rounded-full">
                                                    {{ auth()->user()->savedJobs()->count() }}
                                                </span>
                                            @endif
                                        </a>
                                        
                                        <a href="{{ route('applications.index') }}" 
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            My Applications
                                            @php
                                                $pendingCount = auth()->user()->jobApplications()->whereIn('status', ['applied', 'viewed'])->count();
                                            @endphp
                                            @if($pendingCount > 0)
                                                <span class="ml-auto bg-yellow-100 text-yellow-600 text-xs font-medium px-2 py-0.5 rounded-full">
                                                    {{ $pendingCount }} pending
                                                </span>
                                            @endif
                                        </a>
                                    @endrole

                                    <!-- EMPLOYER SPECIFIC ACTIONS -->
                                    @role('employer|recruiter')
                                        @php
                                            $user = auth()->user();
                                            $companyIds = $user->ownedCompanies()->pluck('id')
                                                ->merge($user->teamMemberCompanies()->pluck('id'));
                                        @endphp
                                        
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        
                                        {{-- <a href="{{ route('employer.dashboard') }}" 
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Company Dashboard
                                        </a>
                                         --}}
                                        <a href="{{ route('employer.jobs.create') }}"
                                           class="block px-4 py-2 text-sm text-white bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 mx-2 my-1 rounded-lg font-medium transition-colors text-center">
                                            <span class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Post a New Job
                                            </span>
                                        </a>
                                        
                                        <a href="{{ route('employer.jobs.index') }}"
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Manage Jobs
                                            @php
                                                $activeJobsCount = 0;
                                                if ($companyIds->isNotEmpty()) {
                                                    $activeJobsCount = \App\Models\JobPosting::whereIn('company_id', $companyIds)
                                                        ->where('status', 'active')
                                                        ->count();
                                                }
                                            @endphp
                                            @if($activeJobsCount > 0)
                                                <span class="ml-auto bg-blue-100 text-blue-600 text-xs font-medium px-2 py-0.5 rounded-full">
                                                    {{ $activeJobsCount }} active
                                                </span>
                                            @endif
                                        </a>
                                        
                                        <a href="{{ route('employer.applicants.index') }}"
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Applicants
                                            @php
                                                $newApplicants = 0;
                                                if ($companyIds->isNotEmpty()) {
                                                    try {
                                                        $newApplicants = \App\Models\JobApplication::whereHas('jobPosting', function($q) use ($companyIds) {
                                                            $q->whereIn('company_id', $companyIds);
                                                        })->where('status', 'applied')->count();
                                                    } catch (\Exception $e) {
                                                        $newApplicants = 0;
                                                    }
                                                }
                                            @endphp
                                            @if($newApplicants > 0)
                                                <span class="ml-auto bg-red-100 text-red-600 text-xs font-medium px-2 py-0.5 rounded-full animate-pulse">
                                                    {{ $newApplicants }} new
                                                </span>
                                            @endif
                                        </a>
                                    @endrole

                                    <!-- ADMIN SPECIFIC ACTIONS -->
                                    @role('admin|super_admin')
                                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                        
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/30 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Admin Dashboard
                                        </a>
                                    @endrole

                                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors flex items-center gap-3">
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

            <!-- Mobile Menu Button -->
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

    <!-- Mobile Menu -->
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
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-3">
                        <a href="{{ route('dashboard') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Dashboard
                        </a>
                        {{-- <a href="{{ route('saved-jobs.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Saved Jobs
                            @if(auth()->user()->savedJobs()->count() > 0)
                                <span class="ml-2 inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded-full">
                                    {{ auth()->user()->savedJobs()->count() }}
                                </span>
                            @endif
                        </a> --}}
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
                    @php
                        $user = auth()->user();
                        $companyIds = $user->ownedCompanies()->pluck('id')
                            ->merge($user->teamMemberCompanies()->pluck('id'));
                    @endphp
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-3">
                        <a href="{{ route('dashboard') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors py-2">
                            Dashboard
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
                                $newApplicants = 0;
                                if ($companyIds->isNotEmpty()) {
                                    try {
                                        $newApplicants = \App\Models\JobApplication::whereHas('jobPosting', function($q) use ($companyIds) {
                                            $q->whereIn('company_id', $companyIds);
                                        })->where('status', 'applied')->count();
                                    } catch (\Exception $e) {
                                        $newApplicants = 0;
                                    }
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
                        <a href="{{ route('dashboard') }}" class="block text-lg font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 transition-colors py-2">
                            Dashboard
                        </a>
                    </div>
                @endrole

                <!-- Common authenticated links for mobile -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-3">
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

            
        </div>
    </div>
</header>

<!-- Required Alpine.js and styles -->
<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Add Alpine.js if not already included -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function notificationComponent() {
    return {
        isOpen: false,
        loading: false,
        unreadCount: 0,
        notifications: [],
        pollingInterval: null,
        
        init() {
            this.fetchNotifications();
            // Poll every 30 seconds for new notifications
            this.pollingInterval = setInterval(() => this.fetchNotifications(), 30000);
        },
        
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchNotifications();
            }
        },
        
        closeDropdown() {
            this.isOpen = false;
        },
        
        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("notifications.recent") }}', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async markAsRead(notificationId) {
            try {
                await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                // Update local state
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.is_read) {
                    notification.is_read = true;
                    this.unreadCount--;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                // Update local state
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        // Clean up interval when component is destroyed
        destroy() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
        }
    }
}
</script>