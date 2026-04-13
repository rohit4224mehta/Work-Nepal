{{-- components.notification-dropdown.blade.php --}}
<div class="relative" x-data="notificationDropdown()" x-init="init()">
    <button @click="toggle()" class="relative p-2 rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" 
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full">
        </span>
    </button>
    
    <div x-show="open" @click.away="close()" 
         class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50">
        <div class="p-3 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold dark:text-white">Notifications</h3>
            <button @click="markAllRead()" class="text-xs text-red-600">Mark all read</button>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            <template x-for="n in notifications" :key="n.id">
                <a :href="n.action_url" @click="markRead(n.id)"
                   class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700"
                   :class="{'bg-blue-50 dark:bg-blue-900/20': !n.is_read}">
                    <div class="flex gap-3">
                        <span class="text-xl" x-text="n.icon"></span>
                        <div class="flex-1">
                            <p class="text-sm font-medium dark:text-white" x-text="n.title"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="n.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="n.created_at"></p>
                        </div>
                    </div>
                </a>
            </template>
            
            <div x-show="notifications.length === 0" class="p-8 text-center text-gray-500">
                No notifications
            </div>
        </div>
        
        <div class="p-2 border-t dark:border-gray-700 text-center">
            <a href="{{ route('notifications.index') }}" class="text-sm text-red-600">View all</a>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        open: false,
        unreadCount: 0,
        notifications: [],
        
        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },
        
        toggle() { this.open = !this.open; },
        close() { this.open = false; },
        
        fetchNotifications() {
            fetch('{{ route("notifications.recent") }}')
                .then(res => res.json())
                .then(data => {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                })
                .catch(err => console.error('Error fetching notifications:', err));
        },
        
        markRead(id) {
            fetch(`/notifications/${id}/read`, { 
                method: 'POST', 
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} 
            })
            .then(() => this.fetchNotifications());
        },
        
        markAllRead() {
            fetch('{{ route("notifications.mark-all-read") }}', { 
                method: 'POST', 
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} 
            })
            .then(() => this.fetchNotifications());
        }
    }
}
</script>