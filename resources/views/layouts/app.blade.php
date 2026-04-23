<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo_funtasyaplay.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo_funtasyaplay.png') }}">
    <title>Funtasya Play</title>
    @vite('resources/css/app.css')

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-white flex flex-col min-h-screen">

    {{-- NAVBAR STICKY --}}
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
        @include('components.navbar.main')
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen flex flex-col">
    <main class="flex-grow">
        @yield('content') 
    </main>

    {{-- FOOTER SECTION --}}
    @include('components.footer')

</body>
</html>