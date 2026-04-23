@extends('layouts.main')

@section('content')
<div class="bg-white min-h-screen pb-20">

    {{-- SECTION 1: HERO EXPLORE --}}
    <section class="max-w-7xl mx-auto px-4 pt-6">
        <div class="relative rounded-[25px] md:rounded-[40px] overflow-hidden shadow-sm">
            <img src="{{ asset('images/explore.png') }}" 
                 class="w-full h-auto object-contain block" 
                 alt="Explore Funtasya Play">
            <div class="absolute inset-0 bg-black/5 pointer-events-none"></div>
        </div>
    </section>

    {{-- SECTION 2: CATEGORY TABS --}}
    <section class="max-w-4xl mx-auto px-6 py-10">
        <div class="bg-[#EC4899] p-1.5 md:p-2 rounded-full md:rounded-2xl flex flex-wrap justify-center items-center gap-1 md:gap-4 shadow-md">
            
            <a href="{{ route('catalog') }}" 
               class="{{ !request('category') ? 'bg-white text-[#EC4899]' : 'text-white hover:bg-white/20' }} px-6 py-2 rounded-full text-xs md:text-sm font-bold transition-all">
               All
            </a>

            @foreach($categories as $cat)
            <a href="{{ route('catalog', ['category' => $cat->slug]) }}" 
               class="{{ request('category') == $cat->slug ? 'bg-white text-[#EC4899]' : 'text-white hover:bg-white/20' }} px-6 py-2 rounded-full text-xs md:text-sm font-bold transition-all">
               {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </section>

 {{-- SECTION 3: PRODUCT GRID --}}
<section class="max-w-6xl mx-auto px-6">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
        @foreach($products as $product)
        {{-- Alpine.js Scope per Produk --}}
        <div class="group relative" x-data="{ 
            showQuickModal: false,
           activeImg: '{{ asset("products/" . $product->image) }}',
            selectedVar: '',
            price: {{ (int) $product->price }},
            variations: @js($product->variations ?? []),
            
            selectVar(v) {
                this.selectedVar = typeof v === 'object' ? v.nama : v;
                if(typeof v === 'object' && v.harga) {
                    this.price = v.harga;
                }
            }
        }">
            {{-- Link ke Detail Produk --}}
            <a href="{{ route('product.show', $product->slug) }}" class="block">
                <div class="bg-[#D6F7FE] rounded-[25px] lg:rounded-[35px] p-4 lg:p-6 relative flex flex-col items-center transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-2 min-h-[220px] lg:min-h-[340px]">
                    <div class="w-full h-28 lg:h-44 flex items-center justify-center mb-2 lg:mb-4">
                        <img :src="activeImg" 
                             class="max-w-[85%] max-h-full object-contain drop-shadow-md transition-transform duration-300 group-hover:scale-110" 
                             alt="{{ $product->name }}">
                    </div>

                    <h3 class="text-[10px] sm:text-[11px] md:text-[13px] font-black text-[#444444] text-center leading-tight px-1 mb-10 md:mb-14">
                        {{ Str::limit($product->name, 35) }}
                    </h3>
                </div>
            </a>
            
            {{-- Tombol Aksi (Trigger Modal) --}}
            <div class="absolute bottom-4 md:bottom-6 left-2 right-2 md:left-5 md:right-5 flex items-center gap-1.5 z-20">
                
                {{-- Tombol Keranjang --}}
                <button @click="showQuickModal = true" type="button"
                        class="bg-[#ED4D9E] w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-[10px] md:rounded-[14px] hover:bg-pink-600 transition-all shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-white text-[12px] md:text-[15px]"></i>
                </button>
                
                {{-- Tombol Harga --}}
                <button @click="showQuickModal = true" type="button"
                        class="flex-1 bg-[#2D68F8] h-8 md:h-10 flex flex-col items-center justify-center text-[#ffffff] font-bold rounded-full hover:bg-blue-700 transition-all shadow-sm group/btn"
                        style="font-family: 'Roboto', sans-serif;">
                    <span class="truncate px-1 text-[9px] md:text-[11px]">
                        @if($product->price_max)
                            Rp {{ number_format($product->price, 0, ',', '.') }} - {{ number_format($product->price_max, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </span>
                </button>
            </div>

            {{-- QUICK SELECTION MODAL --}}
            <div x-show="showQuickModal" 
                 class="fixed inset-0 z-[150] flex items-end justify-center bg-black/60" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                <div @click.away="showQuickModal = false" 
                     class="bg-white w-full max-w-md rounded-t-[30px] p-6 shadow-2xl relative text-left">
                    
                    {{-- Header Modal --}}
                    <div class="flex gap-4 mb-6 border-b pb-4">
                        <div class="w-16 h-16 bg-gray-50 rounded-xl p-1 flex items-center justify-center">
                            <img :src="activeImg" class="max-w-full max-h-full object-contain">
                        </div>
                        <div class="flex flex-col justify-end">
                            <p class="text-xl font-bold text-[#F8A410]">Rp <span x-text="new Intl.NumberFormat('id-ID').format(price)"></span></p>
                            <p class="text-[10px] text-gray-500">Stok: {{ $product->stock }}</p>
                        </div>
                        <button @click="showQuickModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-black">
                            <i class="fa-solid fa-circle-xmark text-2xl"></i>
                        </button>
                    </div>

                    {{-- Pilihan Variasi --}}
                    <div class="mb-8">
                        <h4 class="font-bold text-xs mb-3 text-gray-800 uppercase tracking-widest">Pilih Tema Produk:</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variations" :key="typeof v === 'object' ? v.nama : v">
                                <button @click="selectVar(v)" 
                                        type="button"
                                        :class="selectedVar === (typeof v === 'object' ? v.nama : v) ? 'border-[#ec4899] bg-pink-50 text-[#ec4899] ring-2 ring-pink-100' : 'border-gray-200 text-gray-600'"
                                        class="px-4 py-2 border-2 rounded-xl text-xs font-bold transition-all">
                                    <span x-text="typeof v === 'object' ? v.nama : v"></span>
                                </button>
                            </template>
                            @if(!$product->variations)
                                <p class="text-xs italic text-gray-400">Tidak ada variasi untuk produk ini.</p>
                            @endif
                        </div>
                    </div>

                   {{-- Form Submit --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="grid grid-cols-2 gap-3" x-ref="productForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="variation" :value="selectedVar">

                        {{-- Tombol Tambah Keranjang --}}
                        <button type="submit" 
                                class="border-2 border-[#EC4899] text-[#EC4899] py-3 rounded-xl font-bold text-xs hover:bg-pink-50 transition-colors">
                            + Keranjang
                        </button>
                        
                        {{-- Tombol Beli Sekarang --}}
                        <button type="button"
                            @click="
                                if (variations.length > 0 && !selectedVar) { 
                                    alert('Silakan pilih tema terlebih dahulu!'); 
                                } else {
                                    window.location.href = '{{ route('checkout.index') }}' + 
                                        '?product_id={{ $product->id ?? $item->id }}' + 
                                        '&quantity=1' + 
                                        '&variation=' + encodeURIComponent(selectedVar) + 
                                        '&direct=1';
                                }
                            "
                            class="bg-[#EC4899] text-white py-3 rounded-xl font-bold text-xs shadow-lg shadow-pink-200 hover:brightness-110 transition-all">
                            Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

    {{-- --- PAGINATION --- --}}
    <div class="mt-12 flex justify-center custom-pagination">
        {{ $products->appends(request()->query())->links() }}
    </div>

    @if($products->isEmpty())
    <div class="text-center py-20">
        <p class="text-gray-400 italic font-medium">Wah, belum ada mainan di kategori ini...</p>
    </div>
    @endif
</section>
@endsection