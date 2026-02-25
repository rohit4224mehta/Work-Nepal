<header class="bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-50 transition-colors duration-300">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:shadow-lg transition-all duration-300">
                        WN
                    </div>
                    <span class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        WorkNepal
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation + Auth -->
            <div class="hidden md:flex items-center space-x-10">

                <!-- Main Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('jobs.index') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors duration-200 relative group">
                        Jobs
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('companies.show', 'demo') }}" {{-- replace with real route later --}}
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors duration-200 relative group">
                        Companies
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    <a href="{{ route('pages.cv-tips') }}"
                       class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors duration-200 relative group">
                        CV Tips
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-red-600 dark:bg-red-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>

                    {{-- Future: Language switcher --}}
                    {{-- <button class="flex items-center space-x-1 text-gray-700 dark:text-gray-300 hover:text-red-600">
                        <span>EN</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button> --}}
                </div>

                <!-- Auth / User Actions -->
                <div class="flex items-center space-x-4 lg:space-x-6">
                    @guest
                        <a href="{{ route('login') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                            Log in
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            Sign Up Free
                        </a>
                    @else
                        <!-- Logged-in user -->
                        <a href="{{ route('dashboard') }}"
                           class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 font-medium transition-colors">
                                Log out
                            </button>
                        </form>
                    @endguest
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button"
                        type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500"
                        aria-controls="mobile-menu"
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Hamburger icon -->
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('jobs.index') }}"
               class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Jobs
            </a>

            <a href="#" 
               class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Companies
            </a>

            <a href="{{ route('pages.cv-tips') }}"
               class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                CV Tips
            </a>

            <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                @guest
                    <a href="{{ route('login') }}"
                       class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Log in
                    </a>

                    <a href="{{ route('register') }}"
                       class="mt-2 block px-3 py-3 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition-colors text-center">
                        Sign Up Free
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-3 py-3 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            Log out
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</header>

<!-- Mobile menu toggle script (minimal vanilla JS) -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !expanded);
            menu.classList.toggle('hidden');
            
            // Optional: change icon to X when open
            btn.querySelector('svg').innerHTML = expanded 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
        });
    });
</script>