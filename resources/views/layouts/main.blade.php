<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <link rel="icon" href="{{ asset('images/logo_funtasyaplay.png') }}">
    <title>@yield('title', 'Funtasya Play')</title>

    {{-- STYLE --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>

</head>

<body class="bg-white flex flex-col min-h-screen">

    {{-- NAVBAR STICKY --}}
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
        @include('components.navbar.home')
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content') 
    </main>

    {{-- FOOTER --}}
    @include('components.footer')

    {{-- SCRIPT --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')

</body>
</html>