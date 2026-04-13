{{-- resources/views/components/notification-panel.blade.php --}}
@php
    $unreadCount = auth()->user()->unreadNotificationCount ?? 0;
@endphp

<div class="relative" x-data="notificationComponent()" x-init="init()">
    {{-- Bell Icon Button --}}
    <button @click="toggleDropdown()" 
            class="relative p-2 text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500 transition-colors focus:outline-none rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
        <span class="sr-only">View notifications</span>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        {{-- Unread Badge --}}
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
              style="display: none;">
        </span>
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="isOpen" 
         @click.away="closeDropdown()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 z-50"
         style="display: none;">
        
        {{-- Header --}}
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-show="unreadCount > 0">
                    <span x-text="unreadCount"></span> unread
                </p>
            </div>
            <button x-show="unreadCount > 0" 
                    @click="markAllAsRead()"
                    class="text-xs text-red-600 hover:text-red-700 font-medium transition">
                Mark all read
            </button>
        </div>
        
        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
            <template x-for="notification in notifications" :key="notification.id">
                <a :href="notification.action_url" 
                   @click="markAsRead(notification.id)"
                   class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                   :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read}">
                    <div class="flex gap-3">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <span class="text-xl" x-text="notification.icon"></span>
                        </div>
                        
                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="notification.time_ago"></p>
                        </div>
                        
                        {{-- Unread Indicator --}}
                        <div x-show="!notification.is_read" class="flex-shrink-0">
                            <span class="inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                        </div>
                    </div>
                </a>
            </template>
            
            {{-- Loading State --}}
            <div x-show="loading" class="px-4 py-8 text-center">
                <svg class="w-8 h-8 mx-auto text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-500 mt-2">Loading...</p>
            </div>
            
            {{-- Empty State --}}
            <div x-show="!loading && notifications.length === 0" class="px-4 py-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No notifications yet</p>
                <p class="text-xs text-gray-400 mt-1">We'll notify you when something happens</p>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="p-2 border-t border-gray-200 dark:border-gray-700 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm text-red-600 hover:text-red-700 font-medium transition">
                View all notifications
                <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
function notificationComponent() {
    return {
        isOpen: false,
        loading: false,
        unreadCount: {{ $unreadCount }},
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
                if (data.success) {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    // Update local state
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification && !notification.is_read) {
                        notification.is_read = true;
                        this.unreadCount--;
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                const response = await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    // Update local state
                    this.notifications.forEach(n => n.is_read = true);
                    this.unreadCount = 0;
                }
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