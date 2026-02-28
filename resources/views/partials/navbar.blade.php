<header class="bg-white dark:bg-gray-950 shadow-sm sticky top-0 z-50 transition-all duration-300">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:shadow-lg transition-all duration-300">
                        WN
                    </div>
                    <span class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        WorkNepal
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-10">

                <!-- Always visible links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('jobs.index') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors relative group">
                        Jobs
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('companies.show', 'demo') }}"
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

                <!-- Auth / Role-based section -->
                <div class="flex items-center space-x-6 lg:space-x-8">

                    @guest
                        <a href="{{ route('login') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                            Log in
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Sign Up Free
                        </a>
                    @else
                        <!-- Role-specific links -->
                        @if(auth()->user()->hasRole('job_seeker'))
                            <a href="{{ route('dashboard.jobseeker') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        @elseif(auth()->user()->hasRole('employer') || auth()->user()->hasRole('recruiter'))
                            <a href="{{ route('employer.dashboard') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                                </svg>
                                Employer Dashboard
                            </a>
                        @elseif(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                </svg>
                                Admin Panel
                            </a>
                        @endif

                        <!-- Profile Dropdown – FIXED -->
                        <div class="relative group">
                            <!-- Trigger area – extended hover zone -->
                            <div class="group flex items-center gap-2 cursor-pointer p-2 -m-2">
                                <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-600 transition-all group-hover:border-red-500">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ auth()->user()->profilePhotoUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-gray-700 dark:text-gray-300 font-medium hidden lg:inline-block">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- Dropdown – appears when hovering the group -->
                            <div class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-900 rounded-xl shadow-2xl ring-1 ring-black/5 dark:ring-white/10 
                                        opacity-0 scale-95 -translate-y-2 pointer-events-none 
                                        group-hover:opacity-100 group-hover:scale-100 group-hover:translate-y-0 group-hover:pointer-events-auto 
                                        transition-all duration-200 origin-top-right">
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Profile Settings
                                    </a>

                                    <a href="#" 
                                       class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                        Notifications
                                    </a>

                                    <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-5 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
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
            <!-- Common links -->
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
                    @if(auth()->user()->hasRole('job_seeker'))
                        <a href="{{ route('dashboard.jobseeker') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('employer') || auth()->user()->hasRole('recruiter'))
                        <a href="{{ route('employer.dashboard') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Employer Dashboard
                        </a>
                    @elseif(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('admin.dashboard') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
                            Admin Panel
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="block text-lg font-medium text-gray-900 dark:text-white hover:text-red-600 transition-colors">
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
            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden');

            // Toggle icon: hamburger ↔ X
            icon.innerHTML = isOpen
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.add('hidden');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
            }
        });
    }
});
</script>