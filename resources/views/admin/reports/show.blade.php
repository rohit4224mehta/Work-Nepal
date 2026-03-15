@extends('layouts.admin')

@section('title', 'Report #' . $report->id . ' Details - WorkNepal Admin')

@section('header', 'Report Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header with Actions --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Report #{{ $report->id }}</h2>
                <div class="flex gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($report->priority == 'critical') bg-red-100 text-red-800
                        @elseif($report->priority == 'high') bg-orange-100 text-orange-800
                        @elseif($report->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($report->priority) }} Priority
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($report->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($report->status == 'in_review') bg-blue-100 text-blue-800
                        @elseif($report->status == 'resolved') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3">
                <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" 
                      onsubmit="return confirm('Delete this report? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Report
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column - Report Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Report Information Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Information</h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Report ID</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $report->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Reported Entity Type</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($report->reported_entity_type == 'job') bg-blue-100 text-blue-800
                                    @elseif($report->reported_entity_type == 'company') bg-green-100 text-green-800
                                    @elseif($report->reported_entity_type == 'user') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($report->reported_entity_type) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Reported Entity ID</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">#{{ $report->reported_entity_id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Reason</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->reason }}</dd>
                        </div>
                        
                        <div class="md:col-span-2">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Description</dt>
                            <dd class="text-sm text-gray-900 dark:text-white mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                {{ $report->description ?: 'No description provided.' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Created At</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->created_at->format('M d, Y h:i A') }}</dd>
                            <dd class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</dd>
                        </div>
                        
                        @if($report->resolved_at)
                            <div>
                                <dt class="text-sm text-gray-500 dark:text-gray-400">Resolved At</dt>
                                <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->resolved_at->format('M d, Y h:i A') }}</dd>
                                <dd class="text-xs text-gray-500">{{ $report->resolved_at->diffForHumans() }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Reported Content Card --}}
                @if($reportedEntity)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reported Content</h3>
                        
                        @if($report->reported_entity_type == 'job')
                            <div class="space-y-3">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                        @if($reportedEntity->company && $reportedEntity->company->logo_path)
                                            <img src="{{ Storage::url($reportedEntity->company->logo_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-bold text-gray-500">{{ substr($reportedEntity->company->name ?? 'J', 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $reportedEntity->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reportedEntity->company->name ?? 'Unknown Company' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Location:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $reportedEntity->location ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Job Type:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $reportedEntity->job_type)) }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.jobs.show', $reportedEntity) }}" 
                                       target="_blank"
                                       class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        View Full Job Details →
                                    </a>
                                </div>
                            </div>
                        
                        @elseif($report->reported_entity_type == 'company')
                            <div class="space-y-3">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                        @if($reportedEntity->logo_path)
                                            <img src="{{ Storage::url($reportedEntity->logo_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl font-bold text-gray-500">{{ substr($reportedEntity->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $reportedEntity->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reportedEntity->industry ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Location:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $reportedEntity->location ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Status:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst($reportedEntity->verification_status) }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.companies.show', $reportedEntity) }}" 
                                       target="_blank"
                                       class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        View Full Company Profile →
                                    </a>
                                </div>
                            </div>
                        
                        @elseif($report->reported_entity_type == 'user')
                            <div class="space-y-3">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($reportedEntity->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $reportedEntity->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reportedEntity->email }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-gray-500">Role:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">
                                            @foreach($reportedEntity->roles as $role)
                                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}@if(!$loop->last), @endif
                                            @endforeach
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Status:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst($reportedEntity->account_status) }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.users.show', $reportedEntity) }}" 
                                       target="_blank"
                                       class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        View Full User Profile →
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Resolution Notes Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resolution Details</h3>
                    
                    @if($report->resolution_notes || $report->action_taken)
                        <div class="space-y-4">
                            @if($report->action_taken)
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Action Taken</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        {{ $report->action_taken }}
                                    </dd>
                                </div>
                            @endif
                            
                            @if($report->resolution_notes)
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Resolution Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        {{ $report->resolution_notes }}
                                    </dd>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">No resolution details have been added yet.</p>
                    @endif
                </div>
            </div>

            {{-- Right Column - Actions & Assignment --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Assignment Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Assignment</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assigned To
                            </label>
                            <form method="POST" action="{{ route('admin.reports.assign', $report) }}" class="flex gap-2">
                                @csrf
                                <select name="assigned_to" 
                                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Unassigned</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $report->assigned_to == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Assign
                                </button>
                            </form>
                        </div>
                        
                        @if($report->assignedTo)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($report->assignedTo->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->assignedTo->name }}</p>
                                    <p class="text-xs text-gray-500">Assigned</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Update Status Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status</h3>
                    
                    <form method="POST" action="{{ route('admin.reports.status', $report) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <select name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_review" {{ $report->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                            </select>
                        </div>

                        <div>
                            <textarea name="resolution_notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Add resolution notes...">{{ $report->resolution_notes }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Take Action Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Take Action</h3>
                    
                    <form method="POST" action="{{ route('admin.reports.action', $report) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <select name="action" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Action</option>
                                <option value="warn">Send Warning</option>
                                <option value="suspend">Suspend User/Company</option>
                                <option value="ban">Ban User</option>
                                <option value="delete">Delete Content</option>
                                <option value="dismiss">Dismiss Report</option>
                            </select>
                        </div>

                        <div>
                            <textarea name="action_details" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Add details about the action taken..."></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                onclick="return confirm('Are you sure you want to take this action?')">
                            Take Action
                        </button>
                    </form>
                </div>

                {{-- Reporter Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reported By</h3>
                    
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-purple-500 flex items-center justify-center text-white font-bold">
                            {{ substr($report->reporter->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $report->reporter->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $report->reporter->email ?? '' }}</p>
                        </div>
                    </div>
                    
                    @if($report->reporter)
                        <a href="{{ route('admin.users.show', $report->reporter) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View Reporter Profile →
                        </a>
                    @endif
                </div>

                {{-- Reported User Card --}}
                @if($report->reportedUser)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reported User</h3>
                        
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-600 to-red-500 flex items-center justify-center text-white font-bold">
                                {{ substr($report->reportedUser->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $report->reportedUser->name }}</p>
                                <p class="text-xs text-gray-500">{{ $report->reportedUser->email }}</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.users.show', $report->reportedUser) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View Reported User Profile →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Similar Reports --}}
        @if($similarReports->isNotEmpty())
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Similar Reports</h3>
                
                <div class="space-y-3">
                    @foreach($similarReports as $similar)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($similar->reporter->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $similar->reason }}</p>
                                    <p class="text-xs text-gray-500">
                                        Reported by {{ $similar->reporter->name ?? 'Unknown' }} • {{ $similar->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($similar->priority == 'critical') bg-red-100 text-red-800
                                    @elseif($similar->priority == 'high') bg-orange-100 text-orange-800
                                    @elseif($similar->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($similar->priority) }}
                                </span>
                                <a href="{{ route('admin.reports.show', $similar) }}" 
                                   class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection