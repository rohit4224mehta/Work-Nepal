<button class="relative text-gray-700 dark:text-gray-300 hover:text-red-600 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-2">
    <!-- Bell Icon -->
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
    </svg>

    <!-- Badge (real count later) -->
    @if (isset($count) && $count > 0)
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full min-w-[18px] h-5 px-1.5 flex items-center justify-center animate-pulse">
            {{ $count }}
        </span>
    @endif
</button>