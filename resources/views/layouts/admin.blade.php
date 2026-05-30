<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images3/logo.png') }}">
    <title>@yield('title') | Panel Admin Funtasya Play</title>
    
    {{-- STYLE --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')

    <style>
        body { font-family: 'Gotham rounded', sans-serif; background-color: #FFFFFF; }
        .sidebar-text { font-weight: 700; font-size: 14px; line-height: 20px; letter-spacing: -0.35px; }
        .nav-link.active { background-color: rgba(255, 109, 174, 0.2); color: #EC4899; }
        .nav-link { color: #2c2f30; transition: all 0.2s ease-in-out; }
        .nav-link:hover:not(.active) { background-color: rgba(0, 0, 0, 0.03); }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #ACB3B7; border-radius: 10px; }

        /* Filter ini mengubah warna icon SVG menjadi #EC4899 */
        .icon-active {
            filter: invert(43%) sepia(48%) saturate(3781%) hue-rotate(309deg) brightness(97%) contrast(92%);
            opacity: 1 !important;
        }
    </style>
    <style>
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
    .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, 
    .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, 
    .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #EC4899 !important;
        border-color: #EC4899 !important;
    }
</style>
</head>

<body class="flex min-h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-72 bg-[#F0F4F7] flex flex-col border-r border-[#ACB3B7] flex-shrink-0 z-10">
        <div class="p-8 mb-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-funtasya.png') }}" alt="Logo" class="w-12 h-12">
                <div>
                    <h1 class="text-xl font-black text-gray-800 leading-none tracking-tight">Panel Admin</h1>
                    <p class="text-[10px] font-bold text-gray-400 tracking-[0.1em] uppercase">Funtasya Play</p>
                </div>
            </div>
        </div>

        @php
            $role = Auth::user()->role;
            // Logic: Jika admin, gunakan prefix route 'admin', jika super_admin gunakan 'superadmin'
            $prefix = ($role == 'super_admin') ? 'superadmin' : 'admin';

            $dashboardRoute = "{$prefix}.dashboard";
            $orderRoute = "{$prefix}.orders.index";
            $userRoute = "{$prefix}.users.index";
            $productRoute = "{$prefix}.products.index";
            $shippingRoute = "{$prefix}.shipping.index";
            $customerRoute = "{$prefix}.customers.index";
            $promoRoute = "{$prefix}.promo.index";
            $reportRoute = "{$prefix}.laporan.index";
            $paymentRoute = "{$prefix}.payments.index";
            $reviewRoute = "{$prefix}.reviews.index";
            
            $isProductActive = request()->is('*/products*') || request()->is('*/categories*') || request()->routeIs('*.products.*') || request()->routeIs('*.categories.*');
        @endphp

        <nav class="flex-1 px-4 space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route($dashboardRoute) }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/dashboard.svg') }}" class="w-5 h-5 {{ request()->routeIs('*.dashboard') ? 'icon-active' : 'opacity-70' }}">
                Dashboard
            </a>

            {{-- Manajemen User (Sekarang Admin bisa lihat) --}}
            <a href="{{ route($userRoute) }}" class="nav-link {{ request()->routeIs('*.users.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/users.svg') }}" class="w-5 h-5 {{ request()->routeIs('*.users.*') ? 'icon-active' : 'opacity-70' }}">
                Manajemen User
            </a>

            {{-- Manajemen Produk --}}
            <a href="{{ route($productRoute) }}" class="nav-link {{ $isProductActive ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/products.svg') }}" class="w-5 h-5 {{ $isProductActive ? 'icon-active' : 'opacity-70' }}">
                Manajemen Produk
            </a>

            {{-- Manajemen Pesanan --}}
            <a href="{{ route($orderRoute) }}" class="nav-link {{ request()->routeIs('*.orders.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/orders.svg') }}" class="w-5 h-5 {{ request()->routeIs('*.orders.*') ? 'icon-active' : 'opacity-70' }}">
                Manajemen Pesanan
            </a>

            {{-- Metode Pembayaran --}}
            <a href="{{ route($paymentRoute) }}" class="nav-link {{ request()->routeIs('*.payments.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/payment.svg') }}" class="w-5 h-5 {{ request()->routeIs('*.payments.*') ? 'icon-active' : 'opacity-70' }}">
                Metode Pembayaran
            </a>

            {{-- Promo & Diskon --}}
            <a href="{{ route($promoRoute) }}" 
            class="nav-link {{ request()->routeIs('*.promo.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/promo.svg') }}" 
                    class="w-5 h-5 {{ request()->routeIs('*.promo.*') ? 'icon-active' : 'opacity-70' }}">
                Promo & Diskon
            </a>
            {{-- Laporan --}}
           <a href="{{ route($reportRoute) }}" 
            class="nav-link {{ request()->routeIs('*.laporan.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <img src="{{ asset('images/report.svg') }}" 
                    class="w-5 h-5 {{ request()->routeIs('*.laporan.*') ? 'icon-active' : 'opacity-70' }}">
                Laporan
            </a>
          {{-- Manajemen Pelanggan --}}
            <a href="{{ route($customerRoute) }}" 
                class="nav-link {{ request()->routeIs('*.customers.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                    <img src="{{ asset('images/customer.svg') }}" 
                        class="w-5 h-5 {{ request()->routeIs('*.customers.*') ? 'icon-active' : 'opacity-70' }}">
                    Manajemen Pelanggan
            </a>
            {{-- Manajemen Testimoni (Review) --}}
            <a href="{{ route($reviewRoute) }}" 
                class="nav-link {{ request()->routeIs('*.reviews.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <div class="w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5 {{ request()->routeIs('*.reviews.*') ? 'text-[#EC4899]' : 'text-gray-400' }}" 
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                </div>
                Manajemen Testimoni
            </a>
            {{-- Manajemen Ongkir --}}
            <a href="{{ route($shippingRoute) }}" 
            class="nav-link {{ request()->routeIs('*.shipping.*') ? 'active' : '' }} flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text">
                <div class="w-5 h-5 flex items-center justify-center">
                    <svg class="w-5 h-5 {{ request()->routeIs('*.shipping.*') ? 'text-[#EC4899]' : 'text-gray-400' }}" 
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                Manajemen Ongkir
            </a>
        </nav>

        {{-- LOGOUT --}}
        <div class="px-4 pb-8">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center gap-4 px-5 py-3.5 rounded-2xl sidebar-text text-red-500 hover:bg-red-50 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-10V7a3 3 0 00-6 0v1"></path>
                </svg>
                Keluar Panel
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-24 w-full bg-white flex items-center justify-end px-12 gap-8 flex-shrink-0 border-b border-gray-50 shadow-sm no-print">

            {{-- Notifikasi & Help --}}
            <div class="flex items-center gap-7 text-gray-400">
                <button class="hover:text-[#EC4899] transition-all duration-300 transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>
                <button class="hover:text-[#EC4899] transition-all duration-300 transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>

           <div class="h-10 border-r-2 border-gray-200 mx-4"></div>

          @php
            // Menentukan nama route berdasarkan role user yang login
            $profileRoute = Auth::user()->role == 'super_admin' ? 'superadmin.profile.edit' : 'admin.profile.edit';
            
            // Menentukan label Role sesuai desain (Super Admin = OWNER, Admin = STAFF/ADMINISTRATOR)
            $roleLabel = Auth::user()->role == 'super_admin' ? 'OWNER' : 'ADMINISTRATOR';
        @endphp

        <a href="{{ route($profileRoute) }}" class="flex items-center gap-4 pl-2 group">
            <div class="text-right">
                {{-- Baris 1: Menampilkan Username (Lebih Besar & Bold) --}}
                <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 18px; color: #2C2F30;" 
                class="leading-tight group-hover:text-[#EC4899] transition-colors">
                    {{ Auth::user()->username ?? Auth::user()->name }}
                </p>
                
                {{-- Baris 2: Menampilkan Role/Label (Lebih Kecil & Abu-abu) --}}
                <p style="font-family: 'Inter', sans-serif; font-weight: 700; font-size: 11px; color: #9CA3AF; letter-spacing: 0.05em;" 
                class="uppercase text-right">
                    {{ $roleLabel }}
                </p>
            </div>
            
            {{-- Avatar Bulat sesuai desain --}}
            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm ring-2 ring-gray-100 group-hover:ring-[#EC4899] transition-all">
                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=EC4899&color=fff' }}" 
                    class="w-full h-full object-cover">
            </div>
        </a>
        </header>

        <div class="flex-1 overflow-y-auto bg-white">
            @yield('content')
        </div>
    </div>
    @stack('addon-script')
</body>
</html>
