{{-- resources/views/notifications/preferences.blade.php --}}
@extends('layouts.app')

@section('title', 'Notification Preferences - WorkNepal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Notification Preferences</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Choose how you want to receive notifications
                </p>
            </div>
        </div>
    </div>

    {{-- Preferences Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form method="POST" action="{{ route('notifications.preferences.update') }}" id="preferencesForm">
            @csrf
            @method('PUT')
            
            {{-- Email Notifications Section --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Email Notifications</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Receive important updates directly in your inbox
                </p>
                
                <div class="space-y-4">
                    {{-- Job Alerts Email --}}
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Job Alerts</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Receive daily/weekly job recommendations</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="email_job_alerts" 
                                   value="1"
                                   {{ $preferences->email_job_alerts ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                    
                    {{-- Application Updates Email --}}
                    <div class="flex items-center justify-between py-3 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Application Updates</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Get notified when your application status changes</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="email_application_updates" 
                                   value="1"
                                   {{ $preferences->email_application_updates ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                    
                    {{-- Email Digest Frequency --}}
                    <div class="flex items-center justify-between py-3 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Email Digest Frequency</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">How often to send email summaries</p>
                        </div>
                        <select name="email_digest_frequency" 
                                class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white">
                            <option value="daily" {{ $preferences->email_digest_frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $preferences->email_digest_frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>
                </div>
            </div>
            
            {{-- Push Notifications Section (Future) --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18v-5a7 7 0 10-14 0v5M5 18h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Push Notifications</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Coming soon! Get real-time notifications directly on your device
                </p>
                
                <div class="space-y-4 opacity-50">
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Job Alerts</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Instant job recommendations</p>
                        </div>
                        <div class="w-11 h-6 bg-gray-300 rounded-full"></div>
                    </div>
                    <div class="flex items-center justify-between py-3 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Application Updates</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Real-time status changes</p>
                        </div>
                        <div class="w-11 h-6 bg-gray-300 rounded-full"></div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <p class="text-xs text-yellow-800 dark:text-yellow-400">
                        🚀 Push notifications will be available in the next update. You'll be able to receive instant notifications on your browser and mobile device.
                    </p>
                </div>
            </div>
            
            {{-- Database Notifications Section --}}
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">In-App Notifications</h2>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Control notifications within the WorkNepal platform
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <label class="font-medium text-gray-900 dark:text-white">Enable In-App Notifications</label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Show notifications in your dashboard</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="db_notifications" 
                                   value="1"
                                   {{ $preferences->db_notifications ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        </label>
                    </div>
                </div>
            </div>
            
            {{-- Save Button --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <button type="submit" 
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all">
                    Save Preferences
                </button>
            </div>
        </form>
    </div>
    
    {{-- Info Card --}}
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm text-blue-800 dark:text-blue-300 font-medium">About Notifications</p>
                <p class="text-xs text-blue-700 dark:text-blue-400 mt-1">
                    You'll always receive critical notifications (like job offers and account security alerts) regardless of your preferences. 
                    You can change these settings at any time.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('preferencesForm')?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
});
</script>
@endpush

@endsection