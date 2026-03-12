{{-- resources/views/settings/confirm-delete.blade.php --}}
@extends('layouts.app')

@section('title', 'Delete Account Confirmation - WorkNepal')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 bg-gray-50 dark:bg-gray-900 min-h-screen">
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Confirm Account Deletion</h1>
        </div>
        
        <div class="p-6">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-red-800 dark:text-red-400 mb-2">Warning: This action is permanent</h3>
                        <p class="text-red-700 dark:text-red-300">
                            You are about to permanently delete your WorkNepal account. This will:
                        </p>
                        <ul class="list-disc list-inside mt-3 text-red-700 dark:text-red-300 space-y-1">
                            <li>Remove all your personal information</li>
                            <li>Delete your profile and resume</li>
                            <li>Cancel all job applications</li>
                            <li>Remove saved jobs and preferences</li>
                            <li>You will lose access to all WorkNepal services</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.account.delete') }}" class="space-y-6">
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

                <div class="flex gap-4">
                    <a href="{{ route('settings.index') }}" 
                       class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center font-medium rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                        Permanently Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection