<div x-data="{ open: false }" class="relative inline-block text-left">
    <!-- Trigger Button -->
    <button @click="open = !open" 
            type="button" 
            class="flex items-center gap-3 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-1 transition-all"
            aria-expanded="false" aria-haspopup="true">
        <!-- Avatar -->
        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden border-2 border-gray-300 dark:border-gray-600">
            @if(auth()->user()->profile_photo_path)
                <img src="{{ auth()->user()->profilePhotoUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-base font-medium text-gray-700 dark:text-gray-300 uppercase">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </span>
            @endif
        </div>

        <!-- Name (desktop only) -->
        <span class="text-gray-700 dark:text-gray-300 font-medium hidden lg:inline-block">
            {{ auth()->user()->name }}
        </span>

        <!-- Arrow -->
        <svg class="w-4 h-4 text-gray-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-3 w-64 origin-top-right bg-white dark:bg-gray-900 rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 dark:ring-white dark:ring-opacity-10 focus:outline-none divide-y divide-gray-100 dark:divide-gray-800">
        
        <div class="py-2">
            <a href="{{ route('profile.edit') }}" class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Settings
            </a>

            <a href="#" class="block px-5 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Notifications
            </a>
        </div>

        <div class="py-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-5 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>