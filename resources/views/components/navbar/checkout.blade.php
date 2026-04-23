<nav class="bg-white shadow-sm sticky top-0 z-50">
   <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}">
            <img src="/images/logo_funtasyaplay.png" alt="Logo" class="h-7 md:h-9" />
        </a>

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
                            @auth
                                @php
                                    $totalItems = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                                @endphp
                                
                                @if($totalItems > 0)
                                    <span class="absolute -top-1 -right-1 bg-yellow-400 text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white text-black shadow-sm">
                                        {{ $totalItems }}
                                    </span>
                                @endif
                            @endauth
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
</nav>