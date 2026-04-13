{{-- resources/views/components/notification-item.blade.php --}}
@props(['notification'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition notification-item
    {{ !$notification->is_read ? 'border-l-4 border-l-red-600' : '' }}" 
    data-id="{{ $notification->id }}">
    
    <div class="flex items-start gap-4">
        {{-- Icon with Color --}}
        <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-full 
                @if($notification->color == 'blue') bg-blue-100 text-blue-600
                @elseif($notification->color == 'green') bg-green-100 text-green-600
                @elseif($notification->color == 'red') bg-red-100 text-red-600
                @elseif($notification->color == 'yellow') bg-yellow-100 text-yellow-600
                @elseif($notification->color == 'purple') bg-purple-100 text-purple-600
                @elseif($notification->color == 'emerald') bg-emerald-100 text-emerald-600
                @elseif($notification->color == 'orange') bg-orange-100 text-orange-600
                @elseif($notification->color == 'teal') bg-teal-100 text-teal-600
                @elseif($notification->color == 'indigo') bg-indigo-100 text-indigo-600
                @else bg-gray-100 text-gray-600
                @endif
                flex items-center justify-center text-xl">
                {{ $notification->icon }}
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
                        @if($notification->priority === 'urgent')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                                Urgent
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
            @if($notification->action_url != '#')
                <div class="mt-3">
                    <a href="{{ $notification->action_url }}" 
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