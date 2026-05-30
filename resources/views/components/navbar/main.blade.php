<nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100" x-data="{ open: false }">


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

    <div class="flex items-center justify-between">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="shrink-0">
            <img
                src="{{ asset('images3/logo.png') }}"
                alt="PlayLearn"
                class="h-12 w-auto">
        </a>

        <!-- MENU -->
        <ul class="hidden lg:flex items-center gap-8 text-[15px] font-medium text-gray-600">

            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->is('/') ? 'text-purple-600 font-semibold' : 'hover:text-purple-600' }}">
                    Beranda
                </a>
            </li>

            <li>
                <a href="{{ route('catalog') }}"
                   class="{{ request()->is('catalog') ? 'text-purple-600 font-semibold' : 'hover:text-purple-600' }}">
                    Katalog
                </a>
            </li>

            <li>
                <a href="#"
                   class="hover:text-purple-600">
                    Promo
                </a>
            </li>

            <li>
                <a href="#"
                   class="hover:text-purple-600">
                    Artikel
                </a>
            </li>

            <li>
                <a href="{{ route('about') }}"
                   class="{{ request()->is('about') ? 'text-purple-600 font-semibold' : 'hover:text-purple-600' }}">
                    Tentang Kami
                </a>
            </li>

        </ul>

        <!-- SEARCH -->
        <div class="hidden lg:block flex-1 max-w-md mx-8">

            <form action="{{ route('catalog') }}" method="GET">

                <div class="relative">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk edukatif untuk si kecil..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-full pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-purple-300 outline-none">

                    <button
                        type="submit"
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </button>

                </div>

            </form>

        </div>

        <!-- USER MENU -->
        <div class="flex items-center gap-3">

            <a href="/wishlist"
               class="hidden md:flex w-10 h-10 rounded-full bg-purple-50 items-center justify-center text-purple-600 hover:bg-purple-100 transition">

                <i class="fa-regular fa-heart"></i>

            </a>

            <a href="/cart"
               class="relative flex w-10 h-10 rounded-full bg-purple-600 items-center justify-center text-white hover:bg-purple-700 transition">

                <i class="fa-solid fa-cart-shopping"></i>

                @auth
                    @php
                        $totalCartItems = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                    @endphp

                    @if($totalCartItems > 0)
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white text-black">
                            {{ $totalCartItems }}
                        </span>
                    @endif
                @endauth

            </a>

            @auth

                <a href="{{ Auth::user()->role === 'super_admin' ? '/admin/dashboard' : '/profile' }}"
                   class="flex items-center gap-2 bg-purple-50 rounded-full px-4 py-2 hover:bg-purple-100 transition">

                    <i class="fa-solid fa-user text-purple-600"></i>

                    <span class="hidden xl:block text-sm font-medium text-gray-700">
                        {{ Auth::user()->name }}
                    </span>

                </a>

            @else

                <a href="/login"
                   class="hidden md:block border border-purple-200 text-purple-600 px-4 py-2 rounded-full font-medium hover:bg-purple-50 transition">

                    Masuk

                </a>

                <a href="/register"
                   class="hidden md:block bg-gradient-to-r from-purple-600 to-blue-500 text-white px-5 py-2 rounded-full font-semibold shadow hover:opacity-90 transition">

                    Daftar Gratis

                </a>

            @endauth

            <!-- MOBILE BUTTON -->
            <button
                @click="open = !open"
                class="lg:hidden p-2 text-gray-600">

                <i class="fa-solid"
                   :class="open ? 'fa-xmark' : 'fa-bars'"></i>

            </button>

        </div>

    </div>

    <!-- MOBILE MENU -->
    <div
        x-show="open"
        x-transition
        class="lg:hidden mt-4 border-t pt-4">

        <form action="{{ route('catalog') }}" method="GET" class="mb-4">

            <div class="relative">

                <input
                    type="text"
                    name="search"
                    placeholder="Cari produk edukatif..."
                    class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-3 text-sm">

                <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-gray-400"></i>

            </div>

        </form>

        <ul class="space-y-3 text-gray-600 font-medium">

            <li>
                <a href="{{ route('home') }}" class="block">
                    Beranda
                </a>
            </li>

            <li>
                <a href="{{ route('catalog') }}" class="block">
                    Katalog
                </a>
            </li>

            <li>
                <a href="#" class="block">
                    Promo
                </a>
            </li>

            <li>
                <a href="#" class="block">
                    Artikel
                </a>
            </li>

            <li>
                <a href="{{ route('about') }}" class="block">
                    Tentang Kami
                </a>
            </li>

        </ul>

        @guest

        <div class="flex flex-col gap-2 mt-4">

            <a href="/login"
               class="text-center py-3 border border-purple-200 rounded-full font-medium text-purple-600">

                Masuk

            </a>

            <a href="/register"
               class="text-center py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-full font-semibold">

                Daftar Gratis

            </a>

        </div>

        @endguest

    </div>

</div>

</nav>
