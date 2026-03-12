<header class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50 transition-shadow duration-300">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:shadow-lg transition-all duration-300">
                    WN
                </div>
                <span class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    WorkNepal
                </span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-10">

                <!-- Common Navigation Links (always visible) -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('jobs.index') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors relative group">
                        Jobs
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('companies.show', 'demo') }}" {{-- replace 'demo' with real route later --}}
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors relative group">
                        Companies
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('pages.cv-tips') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors relative group">
                        CV Tips
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <!-- Authenticated / Role-Specific Area -->
                <div class="flex items-center space-x-6 lg:space-x-8">

                    @guest
                        <!-- Guest State: Conversion-focused -->
                        <a href="{{ route('login') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                            Log in
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Sign Up Free
                        </a>
                    @else
                        <!-- Authenticated State: Role-aware actions -->

                        <!-- Job Seeker Quick Links -->
                        @role('job_seeker')
                            <a href="{{ route('saved.jobs') ?? '#' }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Saved Jobs
                            </a>

                            <a href="{{ route('applications.index') ?? '#' }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Applications
                            </a>
                          
                        
                        @endrole

                        <!-- Employer Quick Actions -->
                        @role('employer|recruiter')
                            <a href="{{ route('employer.post.job') ?? '#' }}"
                               class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                Post a Job
                            </a>

                            <a href="{{ route('employer.jobs.index') ?? '#' }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                                My Jobs
                            </a>

                            <a href="{{ route('employer.applicants') ?? '#' }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                                Applicants
                            </a>
                        @endrole

                        <!-- Notifications Bell -->
                        <button class="relative text-gray-700 dark:text-gray-300 hover:text-red-600 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            <!-- Badge (placeholder for real count) -->
                            {{-- @if (auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full min-w-[18px] h-5 px-1.5 flex items-center justify-center">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif --}}
                        </button>

                        <!-- Profile Dropdown (click-based) -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-3 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-1">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-600 transition-all">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ auth()->user()->profilePhotoUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-base font-medium text-gray-700 dark:text-gray-300 uppercase">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium hidden lg:inline-block">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-64 bg-white dark:bg-gray-900 rounded-xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 transition-all duration-200 origin-top-right">
                                <div class="py-2">
                                    <a href="{{ route('profile.show', auth()->user()) }}" 
   class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    Profile Settings
</a>

                                    <a href="#" class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        Notifications
                                    </a>

                                    <a href="{{ route('dashboard.jobseeker') }}" class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        Dashboard
                                    </a>

                                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-5 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors flex items-center gap-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-lg p-2"
                    aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
                <svg id="menu-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
        <div class="px-4 py-6 space-y-5">
            <!-- Common Links -->
            <a href="{{ route('jobs.index') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                Jobs
            </a>
            <a href="#" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                Companies
            </a>
            <a href="{{ route('pages.cv-tips') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                CV Tips
            </a>

            @auth
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-5">
                    <!-- Role-specific links -->
                    @role('job_seeker')
                        <a href="{{ route('saved.jobs') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Saved Jobs
                        </a>
                        
                        <a href="{{ route('applications.index') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Applications
                        </a>
                        
                    @endrole

                    @role('employer|recruiter')
                        <a href="{{ route('employer.post.job') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Post a Job
                        </a>
                        <a href="{{ route('employer.jobs.index') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            My Jobs
                        </a>
                        <a href="{{ route('employer.applicants') ?? '#' }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Applicants
                        </a>
                    @endrole

                    <a href="{{ route('profile.show', auth()->user())}}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                        Profile Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-lg font-medium text-red-600 dark:text-red-400 hover:text-red-700 transition-colors">
                            Log out
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-5">
                    <a href="{{ route('login') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="block text-center py-4 px-6 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors">
                        Sign Up Free
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<!-- Mobile Menu Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');

    if (btn && menu && icon) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            icon.innerHTML = menu.classList.contains('hidden')
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
            }
        });
    }
});
</script>