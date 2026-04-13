{{-- Determine which layout to use based on user role --}}
@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin') || $user->hasRole('super_admin');
    $layout = $isAdmin ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layout)

@section('title', 'Notifications - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 {{ $isAdmin ? '' : 'bg-gray-50 dark:bg-gray-900' }} min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Stay updated with your activities and updates
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                @if($unreadCount > 0)
                    <button id="markAllReadBtn" 
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Mark all as read
                    </button>
                @endif
                @if($notifications->count() > 0)
                    <button id="clearAllBtn" 
                            class="px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                        Clear all
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Unread</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-500">{{ number_format($stats['unread']) }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Applications</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-500">{{ number_format($stats['application']) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">System</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-500">{{ number_format($stats['system']) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 rounded-lg transition {{ !request('category') && !request('filter') ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                All
            </a>
            <a href="{{ route('notifications.index', ['category' => 'application']) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('category') == 'application' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                📝 Applications
            </a>
            <a href="{{ route('notifications.index', ['category' => 'job']) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('category') == 'job' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                💼 Jobs
            </a>
            <a href="{{ route('notifications.index', ['category' => 'company']) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('category') == 'company' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                🏢 Companies
            </a>
            <a href="{{ route('notifications.index', ['category' => 'system']) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('category') == 'system' ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                ⚙️ System
            </a>
        </div>
        
        {{-- Read/Unread Filter Separator --}}
        <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('notifications.index', array_merge(request()->except('filter'), ['filter' => 'unread'])) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('filter') == 'unread' ? 'bg-yellow-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                🔴 Unread
                @if($stats['unread'] > 0)
                    <span class="ml-1 px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $stats['unread'] }}</span>
                @endif
            </a>
            <a href="{{ route('notifications.index', array_merge(request()->except('filter'), ['filter' => 'read'])) }}" 
               class="px-4 py-2 rounded-lg transition {{ request('filter') == 'read' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                ✅ Read
            </a>
            @if(request('category') || request('filter'))
                <a href="{{ route('notifications.index') }}" 
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Clear Filters
                </a>
            @endif
        </div>
    </div>

    {{-- Notifications List --}}
    @if($notifications->count() > 0)
        <div class="space-y-2" id="notifications-list">
            @foreach($notifications as $notification)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition notification-item
                    {{ !$notification->is_read ? 'border-l-4 border-l-red-600' : '' }}" 
                    data-id="{{ $notification->id }}">
                    
                    <div class="flex items-start gap-4">
                        {{-- Icon with Color --}}
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full {{ $notification->getColor() == 'blue' ? 'bg-blue-100 text-blue-600' : 
                                                                   ($notification->getColor() == 'green' ? 'bg-green-100 text-green-600' :
                                                                   ($notification->getColor() == 'red' ? 'bg-red-100 text-red-600' :
                                                                   ($notification->getColor() == 'yellow' ? 'bg-yellow-100 text-yellow-600' :
                                                                   ($notification->getColor() == 'purple' ? 'bg-purple-100 text-purple-600' :
                                                                   ($notification->getColor() == 'emerald' ? 'bg-emerald-100 text-emerald-600' :
                                                                   ($notification->getColor() == 'orange' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-600')))))) }} 
                                flex items-center justify-center text-xl">
                                {{ $notification->getIcon() }}
                            </div>
                        </div>
                        
                        {{-- Content --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $notification->title }}
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $notification->message }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-3 mt-2">
                                        <p class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                        @if($notification->category)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                {{ ucfirst($notification->category) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    @if(!$notification->is_read)
                                        <button onclick="markAsRead({{ $notification->id }})" 
                                                class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 transition">
                                            Mark as read
                                        </button>
                                    @endif
                                    <button onclick="deleteNotification({{ $notification->id }})" 
                                            class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 transition">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Action Button --}}
                            @if($notification->getActionUrl() != '#')
                                <div class="mt-3">
                                    <a href="{{ $notification->getActionUrl() }}" 
                                       class="inline-flex items-center text-sm text-red-600 hover:text-red-700 dark:text-red-400 transition group">
                                        View Details
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Notifications</h3>
            <p class="text-gray-600 dark:text-gray-400">
                When you receive notifications, they'll appear here
            </p>
            @if(request('category') || request('filter'))
                <a href="{{ route('notifications.index') }}" 
                   class="inline-flex items-center px-4 py-2 mt-4 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Clear Filters
                </a>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
let isProcessing = false;

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"/>
            </svg>
            <span>${message}</span>
        </div>
    `;
    notification.style.animation = 'slideIn 0.3s ease-out';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function markAsRead(notificationId) {
    if (isProcessing) return;
    isProcessing = true;
    
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the unread styling without reload
            const notificationDiv = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationDiv) {
                notificationDiv.classList.remove('border-l-4', 'border-l-red-600');
                const markButton = notificationDiv.querySelector('button:first-child');
                if (markButton) markButton.remove();
            }
            
            // Update unread count in stats
            const unreadCountSpan = document.querySelector('.text-2xl.font-bold.text-yellow-600');
            if (unreadCountSpan) {
                let currentCount = parseInt(unreadCountSpan.innerText);
                if (currentCount > 0) {
                    unreadCountSpan.innerText = currentCount - 1;
                }
            }
            
            showNotification('Notification marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to mark as read', 'error');
    })
    .finally(() => {
        isProcessing = false;
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    if (isProcessing) return;
    isProcessing = true;
    
    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the notification from DOM
            const notificationDiv = document.querySelector(`.notification-item[data-id="${notificationId}"]`);
            if (notificationDiv) {
                notificationDiv.remove();
            }
            
            showNotification('Notification deleted', 'success');
            
            // Check if no notifications left
            const remainingNotifications = document.querySelectorAll('.notification-item');
            if (remainingNotifications.length === 0) {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to delete notification', 'error');
    })
    .finally(() => {
        isProcessing = false;
    });
}

// Mark all as read
document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
    if (isProcessing) return;
    isProcessing = true;
    
    fetch('{{ route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove all unread styling
            document.querySelectorAll('.notification-item.border-l-4').forEach(item => {
                item.classList.remove('border-l-4', 'border-l-red-600');
                const markButton = item.querySelector('button:first-child');
                if (markButton) markButton.remove();
            });
            
            // Update unread count to 0
            const unreadCountSpan = document.querySelector('.text-2xl.font-bold.text-yellow-600');
            if (unreadCountSpan) {
                unreadCountSpan.innerText = '0';
            }
            
            showNotification('All notifications marked as read', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to mark all as read', 'error');
    })
    .finally(() => {
        isProcessing = false;
    });
});

// Clear all notifications
document.getElementById('clearAllBtn')?.addEventListener('click', function() {
    if (!confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
        return;
    }
    
    if (isProcessing) return;
    isProcessing = true;
    
    fetch('{{ route("notifications.clear-all") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to clear notifications', 'error');
    })
    .finally(() => {
        isProcessing = false;
    });
});

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush

{{-- Add route for clear all if not exists --}}
@php
    if (!Route::has('notifications.clear-all')) {
        \Illuminate\Support\Facades\Route::delete('/notifications/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])
            ->name('notifications.clear-all');
    }
@endphp

@endsection