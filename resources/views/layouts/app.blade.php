<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WorkNepal') }} @yield('title', 'Job Platform')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @livewireStyles --}}

    @stack('head')
</head>

<body class="font-inter antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">

    <!-- Flash Messages -->
    @include('partials.flash-messages')

    <!-- Low-data Notice -->
    @include('partials.low-data-notice')

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Optional Header -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-900 shadow-sm">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Main Content – grows to fill space -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    {{-- @livewireScripts --}}
    @stack('scripts')
</body>
</html>