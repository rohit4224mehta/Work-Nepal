@extends('layouts.guest')

@section('title', 'Verify Email - WorkNepal')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 py-12 px-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 lg:p-12 text-center">
        <div class="mx-auto w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-8">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
            Verify Your Email
        </h2>

        <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
            We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.<br>
            Please check your inbox (and spam folder) and click the link to continue.
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg">
                A new verification link has been sent!
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit"
                    class="w-full py-4 px-6 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200">
                Resend Verification Email
            </button>
        </form>

        <p class="mt-8 text-sm text-gray-500 dark:text-gray-400">
            Need help? <a href="{{ route('pages.contact') }}" class="text-red-600 hover:underline">Contact support</a>
        </p>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('logout') }}" class="text-gray-600 dark:text-gray-400 hover:text-red-600 hover:underline"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Click here to log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </p>
    </div>
</div>
@endsection