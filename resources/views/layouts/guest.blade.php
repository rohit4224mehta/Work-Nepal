<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'WorkNepal') }} @yield('title', ' - Nepal Job Search')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SEO & meta can go here later -->
    <meta name="description" content="Find jobs in Nepal – fresher friendly, verified listings, foreign opportunities">
</head>
<body class="antialiased bg-gray-50 text-gray-900">

    <!-- Flash messages -->
    @include('partials.flash-messages')

    <!-- Low-data / simple mode notice (toggle-able later) -->
    @include('partials.low-data-notice')

    <!-- Guest Navbar -->
    @include('partials.navbar-guest')

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>