{{-- resources/views/admin/partials/navbar.blade.php --}}
<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700" x-data="{ profileOpen: false, notificationsOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            {{-- Left side - Mobile menu button --}}
            <div class="flex items-center lg:hidden">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700 dark:text-gray-300">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            {{-- Center/Left side - Page title (visible on desktop) --}}
            <div class="flex items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white hidden lg:block">
                    @yield('header', 'Dashboard')
                </h2>
            </div>

            {{-- Right side - Notifications & Profile --}}
            <div class="flex items-center gap-x-4 lg:gap-x-6">
                {{-- Search (optional) --}}
                <button class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>

                {{-- Notifications Dropdown --}}
                <div class="relative" @click.away="notificationsOpen = false">
                    <button type="button" @click="notificationsOpen = !notificationsOpen" class="relative p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadNotifications > 0)
                            <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                {{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}
                            </span>
                        @endif
                    </button>

                    {{-- Notifications Panel --}}
                    <div x-show="notificationsOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                         style="display: none;">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $notification->data['title'] ?? 'Notification' }}</p>
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
                        <div class="p-2 border-t border-gray-200 dark:border-gray-700 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-sm text-red-600 hover:text-red-700">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div class="relative" @click.away="profileOpen = false">
                    <button type="button" @click="profileOpen = !profileOpen" class="flex items-center gap-3">
                        <span class="sr-only">Open user menu</span>
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <span class="hidden lg:flex lg:items-center">
                            <span class="text-sm font-semibold leading-6 text-gray-900 dark:text-white" aria-hidden="true">{{ auth()->user()->name }}</span>
                            <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
                         style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show', auth()->user()) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Your Profile
                            </a>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>