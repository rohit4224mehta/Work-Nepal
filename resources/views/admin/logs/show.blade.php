@extends('layouts.admin')

@section('title', 'Log Entry #' . $log->id . ' - WorkNepal Admin')

@section('header', 'Log Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.logs.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Log Entry #{{ $log->id }}</h2>
            <span class="px-3 py-1 rounded-full text-xs font-medium
                @if($log->level == 'critical') bg-red-100 text-red-800
                @elseif($log->level == 'danger') bg-orange-100 text-orange-800
                @elseif($log->level == 'warning') bg-yellow-100 text-yellow-800
                @else bg-blue-100 text-blue-800
                @endif">
                {{ ucfirst($log->level) }}
            </span>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Basic Info --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Basic Information Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                    
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Log ID</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $log->id }}</dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Timestamp</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $log->timestamp->format('M d, Y H:i:s') }}
                                <span class="block text-xs text-gray-500">{{ $log->timestamp->diffForHumans() }}</span>
                            </dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Action</dt>
                            <dd class="text-sm font-medium">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($log->action == 'delete' || $log->action == 'suspend' || $log->action == 'ban') bg-red-100 text-red-800
                                    @elseif($log->action == 'create') bg-green-100 text-green-800
                                    @elseif($log->action == 'update') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Level</dt>
                            <dd class="text-sm font-medium">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($log->level == 'critical') bg-red-100 text-red-800
                                    @elseif($log->level == 'danger') bg-orange-100 text-orange-800
                                    @elseif($log->level == 'warning') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($log->level) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Admin Information Card --}}
                @if($log->admin)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admin Information</h3>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold">
                                {{ substr($log->admin->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $log->admin->name }}</p>
                                <p class="text-sm text-gray-500">{{ $log->admin->email }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.users.show', $log->admin) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View Admin Profile →
                        </a>
                    </div>
                @endif

                {{-- User Information Card --}}
                @if($log->user && $log->user->id != $log->admin_id)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Affected User</h3>
                        
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center text-white font-bold">
                                {{ substr($log->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $log->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $log->user->email }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.users.show', $log->user) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View User Profile →
                        </a>
                    </div>
                @endif

                {{-- Request Information Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Information</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">IP Address</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $log->ip_address }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">User Agent</dt>
                            <dd class="text-sm text-gray-600 dark:text-gray-400 break-words">{{ $log->user_agent }}</dd>
                        </div>
                        
                        @if(isset($log->properties['url']))
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">URL</dt>
                                <dd class="text-sm text-gray-600 dark:text-gray-400 break-words">{{ $log->properties['url'] }}</dd>
                            </div>
                        @endif
                        
                        @if(isset($log->properties['method']))
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Method</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->properties['method'] }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Right Column - Detailed Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Description</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $log->description }}</p>
                </div>

                {{-- Subject Information Card --}}
                @if($log->subject_type)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Subject Information</h3>
                        
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Type</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ class_basename($log->subject_type) }}</dd>
                            </div>
                            
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500 dark:text-gray-400">ID</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $log->subject_id }}</dd>
                            </div>
                        </dl>

                        @if($log->subject)
                            <div class="mt-4">
                                <a href="{{ $log->subject instanceof \App\Models\User ? route('admin.users.show', $log->subject) : ($log->subject instanceof \App\Models\Company ? route('admin.companies.show', $log->subject) : '#') }}" 
                                   class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    View Subject Details →
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Properties Card --}}
                @if($log->properties)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Data</h3>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-sm text-gray-700 dark:text-gray-300">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                {{-- Similar Logs --}}
                @if($similarLogs->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity by Same Admin</h3>
                        
                        <div class="space-y-3">
                            @foreach($similarLogs as $similar)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $similar->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $similar->timestamp->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.logs.show', $similar) }}" 
                                       class="text-red-600 hover:text-red-700 text-sm">
                                        View →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Subject Logs --}}
                @if($subjectLogs->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity on Same Subject</h3>
                        
                        <div class="space-y-3">
                            @foreach($subjectLogs as $subjectLog)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $subjectLog->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $subjectLog->timestamp->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.logs.show', $subjectLog) }}" 
                                       class="text-red-600 hover:text-red-700 text-sm">
                                        View →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection