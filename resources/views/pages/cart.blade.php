@extends('layouts.main')

@section('content')
<div class="bg-white min-h-screen pb-20 font-gotham" x-data="{ 
    cart: {{ $cart->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->product->name,
            'price' => $item->product->price,
            'image' => $item->product->image,
            'quantity' => $item->quantity,
            'variation' => $item->variation
        ];
    })->values()->toJson() }},
    get total() {
        return Object.values(this.cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },
    formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('IDR', 'Rp');
    }
}">
    {{-- Breadcrumbs --}}
    <nav class="max-w-7xl mx-auto px-6 py-6 text-sm text-gray-500">
        <a href="/" class="hover:text-[#000000] transition-colors duration-200">Home</a> 
        <span class="mx-2">></span> 
        <a href="{{ route('catalog') }}" class="hover:text-[#000000] transition-colors duration-200"">Product</a>
        <span class="mx-2">></span> 
        <span class="text-[#000000] font-bold">Cart</span>
    </nav>

    {{-- section 1 : tampilan product yang mau di cek out  --}}
    {{-- KONDISI 1: KERANJANG ADA ISI --}}
    <template x-if="cart.length > 0">
        <div class="max-w-7xl mx-auto px-6">
            <div class="space-y-6 mb-10">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex flex-col lg:flex-row items-center justify-between bg-white rounded-[30px] p-6 gap-6 shadow-sm border border-gray-50 mb-4">
                        <div class="flex items-center gap-6 flex-1 w-full">
                            <div class="w-24 h-24 bg-white rounded-[20px] p-2 flex-shrink-0 border border-gray-100">
                               <img :src="'/products/' + item.image" class="w-full h-full object-contain">
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-bold text-black" x-text="item.name"></h3>

                                    {{-- Tombol Hapus --}}
                                    <form :action="'/cart/remove/' + item.id" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- Badge Variasi/Tema --}}
                                <template x-if="item.variation">
                                    <div class="mt-1">
                                        <span class="inline-block bg-[#FFE4EA] text-[#E94E77] px-3 py-1 rounded-full text-[10px] font-bold">
                                            Tema: <span x-text="item.variation"></span>
                                        </span>
                                    </div>
                                </template>

                                <p class="text-black font-bold mt-2" x-text="formatRupiah(item.price)"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-12 w-full lg:w-auto justify-between lg:justify-end">
                            {{-- Kontrol Kuantitas --}}
                            <div class="flex items-center justify-between w-32 border border-gray-200 rounded-full px-4 py-1.5 bg-[#F2F7F6] shadow-sm">
                                <button @click="if(item.quantity > 1) updateQty(item.id, 'minus')" class="font-bold text-lg hover:text-[#EC4899]">-</button>
                                <span class="font-bold" x-text="item.quantity"></span>
                                <button @click="updateQty(item.id, 'plus')" class="font-bold text-lg hover:text-[#EC4899]">+</button>
                            </div>
                            
                            {{-- Harga Total Per Item --}}
                            <p class="text-xl font-bold text-black min-w-[140px] text-right" x-text="formatRupiah(item.price * item.quantity)"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-end items-center gap-4 mb-10">
                <span class="text-2xl font-bold text-black">Total:</span>
                <span class="text-2xl font-bold text-black" x-text="formatRupiah(total)"></span>
            </div>

            <div class="flex flex-col lg:flex-row justify-end gap-4">
                <a href="{{ route('catalog') }}" class="px-10 py-3 border-2 border-[#EC4899] text-[#EC4899] rounded-full font-bold text-center hover:bg-pink-50 transition-all">
                    Kembali Belanja
                </a>
                <a href="{{ route('checkout.index') }}" class="bg-[#ec4899] text-white px-10 py-3 rounded-full font-bold text-center hover:brightness-110 transition-all">
                    Belanja Sekarang
                </a>
            </div>
        </div>
    </template>

    {{-- KONDISI 2: KERANJANG KOSONG --}}
    <template x-if="cart.length === 0">
        <div class="max-w-7xl mx-auto px-6 text-center py-20">
            <img src="{{ asset('images/Kucing-menulis.png') }}" alt="Empty Cart" class="w-64 mx-auto mb-8">
            <p class="text-xl font-medium text-gray-700 mb-8">
                Sepertinya kamu belum memilih petualangan baru untuk si kecil.<br>Yuk, cek koleksi kami!
            </p>
            <a href="{{ route('home') }}" class="inline-block bg-[#EC4899] text-white px-10 py-3 rounded-full font-bold shadow-lg hover:bg-[#d83685] transition-all">
                Kembali ke Home
            </a>
        </div>
    </template>

   {{-- SECTION 2: RELATED PRODUCTS (Produk Lainnya) --}}
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="max-w-7xl mx-auto px-6 mt-24 mb-10">
    <h2 class="text-[28px] md:text-[32px] font-black text-[#EC4899] font-gotham mb-10 text-center tracking-wider">
        Produk Lainnya
    </h2>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
        @foreach($relatedProducts as $item)
        {{-- Scope Alpine.js per Produk --}}
        <div class="group relative" x-data="{ 
            showQuickModal: false,
            activeImage: '{{ asset("products/" . $item->image) }}',
            selectedVar: '',
            price: {{ (int) $item->price }},
            variations: @js($item->variations ?? []),
            
            selectVar(v) {
                this.selectedVar = typeof v === 'object' ? v.nama : v;
                if(typeof v === 'object' && v.harga) {
                    this.price = v.harga;
                }
            }
        }">
            {{-- 1. Link Utama ke Detail Product --}}
            <a href="{{ route('product.show', $item->slug) }}" class="block">
                <div class="bg-[#D6F7FE] rounded-[30px] lg:rounded-[35px] p-5 lg:p-6 relative flex flex-col items-center transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-2 min-h-[240px] lg:min-h-[350px]">
                    
                    <div class="w-full h-32 lg:h-44 flex items-center justify-center mb-4">
                        <img :src="activeImg" 
                             class="max-w-[85%] max-h-full object-contain drop-shadow-md transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3"
                             alt="{{ $item->name }}">
                    </div>
                    
                    <h3 class="text-[11px] md:text-[13px] font-black text-center text-[#444444] leading-tight px-1 mb-12 lg:mb-16">
                        {{ Str::limit($item->name, 35) }}
                    </h3>
                </div>
            </a>

            {{-- 2. Footer Tombol Aksi (Trigger Modal) --}}
            <div class="absolute bottom-4 md:bottom-6 left-2 right-2 md:left-5 md:right-5 flex items-center gap-1.5 z-20">
                
                {{-- TOMBOL KERANJANG --}}
                <button @click="showQuickModal = true" type="button" 
                        title="Tambah ke Keranjang"
                        class="bg-[#ED4D9E] w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-[10px] md:rounded-[14px] hover:bg-pink-600 hover:rotate-12 transition-all shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-white text-[12px] md:text-[16px]"></i>
                </button>
                
                {{-- TOMBOL HARGA --}}
                <button @click="showQuickModal = true" type="button" 
                        class="flex-1 bg-[#2D68F8] h-8 md:h-10 flex flex-col items-center justify-center text-[#ffffff] font-bold rounded-full hover:bg-blue-700 transition-all shadow-sm group/btn"
                        style="font-family: 'Roboto', sans-serif;">
                    <span class="truncate px-1 text-[9px] md:text-[11px]">
                        @if($item->price_max)
                            Rp {{ number_format($item->price, 0, ',', '.') }} - {{ number_format($item->price_max, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        @endif
                    </span>
                </button>
            </div>

            {{-- QUICK SELECTION MODAL --}}
            <div x-show="showQuickModal" 
                 class="fixed inset-0 z-[200] flex items-end justify-center bg-black/60" 
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
                            <p class="text-[10px] text-gray-500">Stok: {{ $item->stock }}</p>
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
                                        :class="selectedVar === (typeof v === 'object' ? v.nama : v) ? 'border-[#ec4899] bg-pink-50 text-[#ec4899]' : 'border-gray-200 text-gray-600'"
                                        class="px-4 py-2 border-2 rounded-xl text-xs font-bold transition-all">
                                    <span x-text="typeof v === 'object' ? v.nama : v"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Form Aksi --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="variation" :value="selectedVar">

                        <button type="submit" class="flex-1 border-2 border-[#ec4899] text-[#ec4899] py-3.5 rounded-2xl font-bold text-sm hover:bg-pink-50 transition-all">
                            + Keranjang
                        </button>
                        
                        <button type="button"
                            @click="
                                (variations.length > 0 && !selectedVar) 
                                ? alert('Pilih tema dulu ya!') 
                                : window.location.href = '{{ route('checkout.index') }}' + 
                                    '?product_id={{ $item->id ?? $product->id }}' + 
                                    '&quantity=1' + 
                                    '&variation=' + encodeURIComponent(selectedVar) + 
                                    '&direct=1'
                            "
                            class="flex-1 bg-[#ec4899] text-white py-3 rounded-xl font-bold text-sm shadow-md hover:brightness-110 transition-all">
                            Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif


<style>
    /* Menghilangkan scrollbar pada thumbnail */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
