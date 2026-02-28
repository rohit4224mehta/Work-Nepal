@extends('layouts.guest')

@section('title', 'Account Suspended - WorkNepal')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 py-12 px-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-10 text-center">
        <div class="text-red-600 text-6xl mb-6">🚫</div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            Your Account is Suspended
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
            We're sorry, but your account has been temporarily suspended due to a violation of our terms or for security reasons.
        </p>
        <p class="text-gray-700 dark:text-gray-300 mb-10">
            Please contact our support team at 
            <a href="mailto:support@worknepal.com" class="text-red-600 hover:underline">support@worknepal.com</a>
            to appeal or get more information.
        </p>
        <a href="{{ route('login') }}" 
           class="inline-block px-8 py-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
            Back to Login
        </a>
    </div>
</div>
@endsection