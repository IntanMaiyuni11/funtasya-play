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


</head>

<body class="bg-white flex flex-col min-h-screen font-[Inter]">

    {{-- NAVBAR --}}
    @include('components.navbar.auth')

    {{-- MAIN CONTENT --}}
    <div class="min-h-screen flex flex-col">
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