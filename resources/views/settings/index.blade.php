{{-- resources/views/settings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Account Settings - WorkNepal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Account Settings</h1>
        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">
            Manage your account preferences and security
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Settings Tabs --}}
    <div x-data="{ tab: 'profile' }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <nav class="flex flex-wrap -mb-px">
                <button @click="tab = 'profile'" :class="{ 'border-red-600 text-red-600': tab === 'profile' }" 
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-red-600 transition-colors">
                    Profile Settings
                </button>
                <button @click="tab = 'password'" :class="{ 'border-red-600 text-red-600': tab === 'password' }"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-red-600 transition-colors">
                    Password
                </button>
                <button @click="tab = 'notifications'" :class="{ 'border-red-600 text-red-600': tab === 'notifications' }"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-red-600 transition-colors">
                    Notifications
                </button>
                <button @click="tab = 'privacy'" :class="{ 'border-red-600 text-red-600': tab === 'privacy' }"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-red-600 transition-colors">
                    Privacy
                </button>
                <button @click="tab = 'delete'" :class="{ 'border-red-600 text-red-600': tab === 'delete' }"
                        class="px-6 py-4 text-sm font-medium border-b-2 border-transparent hover:text-red-600 transition-colors">
                    Delete Account
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="p-6 lg:p-8">
            
            {{-- Profile Settings Tab --}}
            <div x-show="tab === 'profile'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Profile Settings</h2>
                
                <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mobile Number
                            </label>
                            <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white"
                                   placeholder="98XXXXXXXX">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Language
                            </label>
                            <select name="language" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                <option value="en" {{ old('language', session('language', 'en')) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ne" {{ old('language', session('language', 'en')) == 'ne' ? 'selected' : '' }}>Nepali</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Timezone
                            </label>
                            <select name="timezone" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                                <option value="Asia/Kathmandu" {{ old('timezone', session('timezone', 'Asia/Kathmandu')) == 'Asia/Kathmandu' ? 'selected' : '' }}>Asia/Kathmandu (UTC+5:45)</option>
                                <option value="Asia/Kolkata" {{ old('timezone') == 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (UTC+5:30)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Tab --}}
            <div x-show="tab === 'password'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Change Password</h2>
                
                <form method="POST" action="{{ route('settings.password.update') }}" class="max-w-md space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Password
                        </label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                        </label>
                        <input type="password" name="new_password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" name="new_password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Notifications Tab --}}
            <div x-show="tab === 'notifications'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Notification Preferences</h2>
                
                <form method="POST" action="{{ route('settings.notifications.update') }}" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    {{-- Email Notifications --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Email Notifications</h3>
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="email_job_alerts" value="1" 
                                       {{ session('notification_preferences.email_job_alerts', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Job Alerts & Recommendations</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="email_application_updates" value="1"
                                       {{ session('notification_preferences.email_application_updates', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Application Status Updates</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="email_messages" value="1"
                                       {{ session('notification_preferences.email_messages', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Messages from Employers</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="email_newsletter" value="1"
                                       {{ session('notification_preferences.email_newsletter', false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Newsletter & Career Tips</span>
                            </label>
                        </div>
                    </div>

                    {{-- Push Notifications --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Push Notifications</h3>
                        <div class="space-y-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="push_job_alerts" value="1"
                                       {{ session('notification_preferences.push_job_alerts', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Job Alerts & Recommendations</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="push_application_updates" value="1"
                                       {{ session('notification_preferences.push_application_updates', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Application Status Updates</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" name="push_messages" value="1"
                                       {{ session('notification_preferences.push_messages', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Messages from Employers</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            {{-- Privacy Tab --}}
            <div x-show="tab === 'privacy'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Privacy Settings</h2>
                
                <form method="POST" action="{{ route('settings.privacy.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Profile Visibility
                        </label>
                        <select name="profile_visibility" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                            <option value="public" {{ session('privacy_settings.profile_visibility', 'public') == 'public' ? 'selected' : '' }}>Public - Anyone can see</option>
                            <option value="employers" {{ session('privacy_settings.profile_visibility') == 'employers' ? 'selected' : '' }}>Employers Only</option>
                            <option value="private" {{ session('privacy_settings.profile_visibility') == 'private' ? 'selected' : '' }}>Private - Only me</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Who can see your contact information?</h3>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="show_email" value="1"
                                   {{ session('privacy_settings.show_email', false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Show email address</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="show_phone" value="1"
                                   {{ session('privacy_settings.show_phone', false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Show phone number</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="show_current_company" value="1"
                                   {{ session('privacy_settings.show_current_company', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Show current company</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            Save Privacy Settings
                        </button>
                    </div>
                </form>
            </div>

            {{-- Delete Account Tab --}}
            <div x-show="tab === 'delete'" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Delete Account</h2>
                
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-400 mb-2">Warning: This action is permanent</h3>
                            <p class="text-red-700 dark:text-red-300">
                                Once you delete your account, all your data including profile information, applications, and saved jobs will be permanently removed. This cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.account.delete') }}" class="space-y-6" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter your password to confirm
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="confirmation" value="1" required
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                I understand that this action is permanent and cannot be undone
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            Permanently Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection