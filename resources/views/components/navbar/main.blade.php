<nav class="bg-white border-b sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            
            <div class="flex items-center gap-4 lg:gap-12">
                <a href="{{ route('home') }}" class="shrink-0">
                    <img src="/images/logo_funtasyaplay.png" alt="Logo" class="h-8 md:h-10 w-auto" />
                </a>

                <ul class="hidden md:flex items-center gap-6 lg:gap-10 text-[14px] font-medium text-[#707070]">
                    <li>
                        <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'border-b-2 border-black pb-1 text-black' : 'hover:text-black' }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="{{ request()->is('about') ? 'border-b-2 border-black pb-1 text-black' : 'hover:text-black' }}">About us</a>
                    </li>
                    <li>
                        <a href="{{ route('catalog') }}" class="{{ request()->is('catalog') ? 'border-b-2 border-black pb-1 text-black' : 'hover:text-black' }}">Catalog</a>
                    </li>
                </ul>
            </div>

             {{-- Menu Pencarian --}}
                <div class="hidden md:flex flex-1 justify-center px-6 lg:px-12">
                    <div class="relative w-full max-w-xl">
                        {{-- Mengarahkan ke route 'catalog' sesuai dengan konfigurasi route Anda --}}
                        <form action="{{ route('catalog') }}" method="GET" class="relative">
                            {{-- Input Pencarian --}}
                            <input type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Cari mainan seru..."
                                class="w-full pl-12 pr-4 py-2.5 rounded-full border border-gray-200 bg-[#F5F5F5] text-sm focus:ring-2 focus:ring-pink-300 outline-none transition-all"
                                autocomplete="off">

                            {{-- Tombol Kaca Pembesar: Terpusat secara vertikal --}}
                            <button type="submit" 
                                    class="absolute left-0 top-0 h-full flex items-center justify-center px-4 text-gray-400 hover:text-[#EC4899] transition-colors focus:outline-none">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>

            <div class="flex items-center gap-2 md:gap-4">
                @auth
                    <div class="flex items-center gap-2 md:gap-3">
                        <span class="hidden xl:block text-xs font-bold text-gray-600">Halo, {{ Auth::user()->name }}</span>
                        
                        <a href="{{ Auth::user()->role === 'super_admin' ? '/admin/dashboard' : '/profile' }}" 
                           class="w-9 h-9 md:w-10 md:h-10 bg-[#2563eb] rounded-lg flex items-center justify-center text-white hover:bg-blue-600 transition-colors">
                            <i class="fa-solid fa-user text-xs md:text-sm"></i>
                        </a>

                        <a href="/cart" class="w-9 h-9 md:w-10 md:h-10 bg-[#EC4899] rounded-lg flex items-center justify-center text-white hover:bg-pink-600 transition-colors relative">
                            <i class="fa-solid fa-bag-shopping text-xs md:text-sm"></i>
                            {{-- Hitung jumlah item unik di tabel carts untuk user yang login --}}
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                            @endphp
                            <template x-if="{{ $cartCount }} > 0">
                                <span class="absolute -top-1 -right-1 bg-yellow-400 text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white text-black">
                                    {{ $cartCount }}
                                </span>
                            </template>
                        </a>
                    </div>
                @else
                    <div class="hidden md:flex items-center gap-2">
                        <a href="/login" class="text-[14px] font-bold text-[#707070] px-3 py-2 hover:text-black">Masuk</a>
                        <a href="/register" class="bg-[#E5488E] text-white px-5 py-2 rounded-full font-bold text-[12px] hover:bg-[#d13d7f] transition-all">Daftar</a>
                    </div>
                @endauth

                <button @click="open = !open" class="md:hidden p-2 text-gray-600">
                    <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <div x-show="open" x-collapse class="md:hidden mt-4 pb-4 space-y-4 border-t pt-4">
            <form action="/catalog" method="GET" class="relative">
                <input type="text" name="search" placeholder="Cari..." class="w-full pl-10 pr-4 py-2 rounded-full bg-gray-100 text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400 text-xs"></i>
            </form>
            
            <ul class="space-y-3 font-medium text-gray-600 px-2">
                <li><a href="{{ route('home') }}" class="block">Home</a></li>
                <li><a href="{{ route('about') }}" class="block">About us</a></li>
                <li><a href="/catalog" class="block">Catalog</a></li>
            </ul>

            @guest
            <div class="flex flex-col gap-2 px-2 pt-2">
                <a href="/login" class="w-full text-center py-2 border rounded-full font-bold text-sm">Masuk</a>
                <a href="/register" class="w-full text-center py-2 bg-[#E5488E] text-white rounded-full font-bold text-sm">Daftar</a>
            </div>
            @endguest
        </div>
    </div>
</nav>