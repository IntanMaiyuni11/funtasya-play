<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo_funtasyaplay.png') }}">
    <title>@yield('title') | Funtasya Play Admin</title>

    {{-- STYLE --}}
    @stack('prepend-style')
    @include('includes.style')
    @stack('addon-style')

    <style>
        .sidebar-active {
            background-color: #EC4899; 
            color: white !important;
        }
        /* Ikon jadi putih saat aktif */
        .sidebar-active svg {
            stroke: white !important;
        }
        /* Efek hover pada sidebar */
        .sidebar-link:hover:not(.sidebar-active) {
            background-color: #fff1f8;
            color: #EC4899;
        }
        .sidebar-link:hover:not(.sidebar-active) svg {
            stroke: #EC4899;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #f1f1f1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900">

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside class="w-72 bg-white border-r border-gray-200 hidden md:flex flex-col flex-shrink-0 sticky top-0 h-screen">
            {{-- LOGO AREA - DIBESARKAN LAGI --}}
            <div class="p-10 border-b border-gray-50 flex justify-center items-center">
                <a href="{{ route('home') }}" class="flex flex-col items-center group">
                    {{-- Ukuran logo diganti ke w-32 (128px) --}}
                    <img src="{{ asset('images/logo_funtasyaplay.png') }}" 
                         class="w-32 h-32 object-contain transition-transform group-hover:scale-105 duration-300">
                </a>
            </div>

            {{-- MENU NAVIGASI --}}
            <nav class="flex-1 overflow-y-auto p-6 space-y-1 custom-scrollbar">
                <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Utama</p>
                
                {{-- Dashboard --}}
                <a href="{{ Auth::user()->role == 'super_admin' ? route('superadmin.dashboard') : '#' }}" 
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-gray-500 transition-all duration-200 {{ request()->routeIs('*.dashboard') ? 'sidebar-active shadow-lg shadow-pink-200' : '' }}">
                    <svg class="w-5 h-5 transition-colors" fill="none" stroke="#EC4899" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path></svg>
                    <span class="font-bold text-sm">Dashboard</span>
                </a>

                {{-- Pesanan --}}
                <a href="{{ Auth::user()->role == 'super_admin' ? route('superadmin.orders.index') : route('admin.orders.index') }}" 
                   class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-gray-500 transition-all duration-200 {{ request()->routeIs('*.orders.*') ? 'sidebar-active shadow-lg shadow-pink-200' : '' }}">
                    <svg class="w-5 h-5 transition-colors" fill="none" stroke="#EC4899" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                    <span class="font-bold text-sm">Manajemen Pesanan</span>
                </a>

                
                {{-- Ongkir --}}
                <a href="{{ Auth::user()->role == 'super_admin' ? route('superadmin.shipping.index') : route('admin.shipping.index') }}" 
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 
                {{ request()->routeIs('*.shipping.*') ? 'sidebar-active shadow-lg shadow-pink-200' : 'text-gray-500' }}">
                    
                    {{-- Ikon Fast Delivery (Clean Version) --}}
                    <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('*.shipping.*') ? 'text-white' : 'text-[#EC4899]' }}" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round">
                        <rect x="1" y="3" width="15" height="13"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                    
                    <span class="font-bold text-sm">Biaya Ongkir</span>
                </a>

                {{-- MASTER DATA --}}
                @if(Auth::user()->role == 'super_admin')
                <div class="pt-8">
                    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Master Data</p>
                    
                    <a href="{{ route('superadmin.products.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-gray-500 transition-all duration-200 {{ request()->routeIs('*.products.*') ? 'sidebar-active shadow-lg shadow-pink-200' : '' }}">
                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="#EC4899" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"></path></svg>
                        <span class="font-bold text-sm">Katalog Produk</span>
                    </a>

                    <a href="{{ route('superadmin.categories.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-gray-500 transition-all duration-200 {{ request()->routeIs('*.categories.*') ? 'sidebar-active shadow-lg shadow-pink-200' : '' }}">
                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="#EC4899" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.159 3.659A2.25 2.25 0 009.568 3zM6 6h1.5v1.5H6V6z"></path></svg>
                        <span class="font-bold text-sm">Kategori Produk</span>
                    </a>

                    <a href="{{ route('superadmin.users.index') }}" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-gray-500 transition-all duration-200 {{ request()->routeIs('*.users.*') ? 'sidebar-active shadow-lg shadow-pink-200' : '' }}">
                        <svg class="w-5 h-5 transition-colors" fill="none" stroke="#EC4899" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path></svg>
                        <span class="font-bold text-sm">Kelola Pengguna</span>
                    </a>
                </div>
                @endif
            </nav>

            {{-- LOGOUT BUTTON --}}
            <div class="p-6 border-t border-gray-100 bg-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-3 w-full px-4 py-4 bg-white text-red-500 border border-red-100 hover:bg-red-50 rounded-2xl transition-all duration-200 font-black text-xs uppercase tracking-widest shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path></svg>
                        Keluar Panel
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white/80 backdrop-blur-md h-20 border-b border-gray-200 flex items-center justify-between px-10 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <span class="px-4 py-1.5 bg-pink-100 text-pink-600 rounded-full text-[10px] font-black uppercase tracking-[0.15em]">
                        {{ Auth::user()->role == 'super_admin' ? 'Owner Account' : 'Staff Operational' }}
                    </span>
                    <h2 class="text-xl font-black text-gray-800 hidden lg:block ml-2">@yield('title')</h2>
                </div>
                
                <div class="flex items-center gap-5">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-black text-gray-800 leading-none mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold tracking-tight">{{ Auth::user()->email }}</p>
                    </div>
                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name.'&background=EC4899&color=fff' }}" 
                         class="w-12 h-12 rounded-2xl border-2 border-white shadow-md object-cover">
                </div>
            </header>

            <main class="p-10 bg-gray-50/50 flex-grow">
                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r-xl shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>

            {{-- FOOTER --}}
            @include('components.footer')
        </div>
    </div>

    {{-- SCRIPT --}}
    @stack('prepend-script')
    @include('includes.script')
    @stack('addon-script')
</body>
</html>