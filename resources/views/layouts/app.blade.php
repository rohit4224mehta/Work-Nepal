<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="WorkNepal – Trusted job search & hiring platform in Nepal">
    <meta name="author" content="Rohit Mehta">

    <!-- Title -->
    <title>@yield('title', 'WorkNepal – Jobs in Nepal')</title>

    <!-- Favicon (add your own later) -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    

    <!-- Custom styles -->
    <style>
        :root {
            --primary:    #1E3A8A;  /* Deep Blue */
            --accent:     #DC2626;  /* Nepal Red */
            --bg:         #F8FAFC;
            --text:       #1F2937;
            --text-muted: #6B7280;
            --success:    #16A34A;
        }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #172554;
            border-color: #172554;
        }
        .text-accent { color: var(--accent) !important; }
        .bg-accent   { background-color: var(--accent) !important; }
        .badge-accent { background-color: var(--accent); color: white; }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Header / Navbar -->
    @include('partials.header')  <!-- or navbar.blade.php if you renamed it -->

    <!-- Main Content -->
    <main class="flex-grow-1 py-4 py-lg-5">
        <div class="container-xl">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Scripts stack -->
    @stack('scripts')

</body>
</html>