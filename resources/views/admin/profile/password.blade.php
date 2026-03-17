{{-- resources/views/admin/profile/password.blade.php --}}
@extends('layouts.admin')

@section('title', 'Change Password - WorkNepal Admin')

@section('header', 'Change Password')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.profile.show') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Change Password</h2>
        </div>

        {{-- Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Password
                    </label>
                    <input type="password" 
                           id="current_password"
                           name="current_password" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('current_password') border-red-500 @enderror"
                           required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-4" x-data="{ password: '' }">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Password
                    </label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           x-model="password"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                           required>
                    
                    {{-- Password Strength Meter --}}
                    <div class="mt-2">
                        <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300"
                                 :class="{
                                     'bg-red-500 w-0': password.length === 0,
                                     'bg-red-500 w-1/4': password.length > 0 && password.length < 8,
                                     'bg-yellow-500 w-2/4': password.length >= 8 && !/[A-Z]/.test(password),
                                     'bg-blue-500 w-3/4': password.length >= 8 && /[A-Z]/.test(password) && !/[0-9]/.test(password),
                                     'bg-green-500 w-full': password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password)
                                 }">
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span :class="{ 'text-red-600': password.length > 0 && password.length < 8 }">8+ chars</span>
                            <span :class="{ 'text-yellow-600': password.length >= 8 && !/[A-Z]/.test(password) }">Uppercase</span>
                            <span :class="{ 'text-blue-600': password.length >= 8 && /[A-Z]/.test(password) && !/[0-9]/.test(password) }">Number</span>
                            <span :class="{ 'text-green-600': password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password) }">Strong</span>
                        </div>
                    </div>
                    
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" 
                           id="password_confirmation"
                           name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white @error('password_confirmation') border-red-500 @enderror"
                           required>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Requirements --}}
                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Password Requirements:</h4>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Minimum 8 characters
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            At least one uppercase letter
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            At least one number
                        </li>
                    </ul>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.profile.show') }}" 
                       class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection