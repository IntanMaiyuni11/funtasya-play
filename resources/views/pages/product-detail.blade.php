@extends('layouts.main')

@section('content')

{{-- SECTION 1: HERO PRODUK --}}
{{-- Root Element dengan Alpine.js untuk kontrol Modal, Gambar, dan Harga --}}
<div class="bg-white h-auto pb-10" x-data="{ 
    showModal: false,
    activeImg: '{{ asset("products/" . $product->image) }}',
    qty: 1,
    selectedVariation: '',
    allVariations: @js($product->variations ?? []),
    currentPrice: {{ (int) $product->price }},
    isRange: {{ isset($product->price_max) && $product->price_max > $product->price ? 'true' : 'false' }},
    
    {{-- Fungsi untuk memilih variasi di dalam modal --}}
        selectVar(v) {
            // Jika v adalah object, ambil v.nama. Jika v adalah string, ambil v langsung.
            this.selectedVariation = typeof v === 'object' ? v.nama : v;
            
            // Ini juga untuk update harga jika harganya beda tiap tema
            if(typeof v === 'object' && v.harga) {
                this.currentPrice = v.harga;
            }
        }
}">

    <div class="max-w-7xl mx-auto px-6 pt-6">
        
        {{-- Breadcrumb --}}
        <nav class="flex text-sm font-medium mb-6 text-gray-500">
            <a href="/" class="hover:text-[#000000] transition-colors duration-200"">Home</a>
            <span class="mx-2">></span>
            <a href="{{ route('catalog') }}" class="hover:text-[#000000] transition-colors duration-200"">Catalog</a>
            <span class="mx-2">></span>
            <span class="text-black font-bold">Product</span>
        </nav>

        {{-- GRID UTAMA: Detail Produk --}}
        <div class="flex flex-col lg:flex-row gap-12" x-data="{ 
            activeImg: '{{ asset('products/' . $product->image) }}',
            qty: 1,
            selectedVariation: '',
            {{-- Menggunakan @js agar array PHP aman masuk ke JavaScript --}}
            allVariations: @js($product->variations ?? []),
            currentPrice: {{ (int) $product->price }},
            currentOriginal: {{ (int) ($product->price_original ?? 0) }},
            isRange: true
        }">
    
            {{-- KIRI: Image Gallery --}}
            <div class="w-full lg:w-1/2">
                {{-- Gambar Utama --}}
                <div class="relative bg-[#D6F7FE] rounded-[40px] p-10 mb-6 flex items-center justify-center min-h-[450px]">
                    <img :src="activeImage" class="max-w-full max-h-[400px] object-contain drop-shadow-xl transition-all duration-300" alt="{{ $product->name }}">
                </div>
                
                {{-- List Thumbnail Gambar --}}
                <div class="flex items-center gap-4">
                    <button class="w-10 h-10 flex items-center justify-center border border-pink-200 rounded-full text-pink-500 hover:bg-pink-50">
                        <i class="fa-solid fa-chevron-left text-sm"></i>
                    </button>
                    
                    <div class="flex-1 flex gap-4 overflow-x-auto no-scrollbar py-2">
                        {{-- Main Image Thumbnail --}}
                        <button @click="activeImage = '{{ asset('products/' . $product->image) }}'" 
                            class="w-24 h-24 flex-shrink-0 border-2 rounded-[20px] p-2 bg-white transition-all" 
                            :class="activeImage.includes('{{ $product->image }}') ? 'border-[#ec4899]' : 'border-transparent'">
                            <img src="{{ asset("products/" . $product->image) }}" class="w-full h-full object-contain">
                        </button>

                        {{-- Gallery Images --}}
                        @if($product->gallery && is_array($product->gallery))
                            @foreach($product->gallery as $img)
                            <button @click="activeImage = '{{ asset('products/' . $img) }}'" 
                                class="w-24 h-24 flex-shrink-0 border-2 rounded-[20px] p-2 bg-white transition-all" 
                                :class="activeImage.includes('{{ $img }}') ? 'border-[#ec4899]' : 'border-transparent'">
                                <img src="{{ asset('products/' . $img) }}" class="w-full h-full object-contain">
                            </button>
                            @endforeach
                        @endif
                    </div>
                    
                    <button class="w-10 h-10 flex items-center justify-center border border-pink-200 rounded-full text-pink-500 hover:bg-pink-50">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- KANAN: Detail Info Produk --}}
            <div class="w-full lg:w-1/2 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h1 class="text-4xl font-bold text-black font-gotham leading-tight">{{ $product->name }}</h1>
                    <button class="text-gray-400 hover:text-[#ec4899] text-2xl ml-4"><i class="fa-solid fa-share-nodes"></i></button>
                </div>

                <div class="mb-4">
                    <span class="inline-block bg-[#6F5CE4] text-white px-6 py-1.5 rounded-xl text-sm font-bold shadow-sm">
                        {{ $product->product_type ?? 'Fisik' }}
                    </span>
                </div>

                <p class="text-[#000000] text-lg leading-relaxed mb-4">
                    {{ $product->short_description }}
                </p>

                {{-- Harga --}}
                <div class="flex flex-wrap items-center gap-4 mb-5 font-gotham">
                    <template x-if="currentOriginal > 0">
                        <span class="text-2xl text-[#8f8f8f] line-through">
                            Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentOriginal)"></span>
                            <template x-if="isRange && {{ (int) ($product->max_price_original ?? 0) }} > currentOriginal">
                                <span> - <span x-text="new Intl.NumberFormat('id-ID').format({{ (int) ($product->max_price_original ?? 0) }})"></span></span>
                            </template>
                        </span>
                    </template>

                    <span class="text-4xl font-bold text-[#F8A410]">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentPrice)"></span>
                        <template x-if="isRange && {{ (int) ($product->price_max ?? 0) }} > currentPrice">
                            <span> - <span x-text="new Intl.NumberFormat('id-ID').format({{ (int) ($product->price_max ?? 0) }})"></span></span>
                        </template>
                    </span>
                </div>

                <div class="space-y-2 mb-3 text-lg font-gotham">
                    <p class="font-bold text-[#000000]">Stok: <span class="font-medium">{{ $product->stock }}</span></p>
                    <p class="font-bold text-[#000000]">Berat: <span class="font-medium">{{ $product->weight }} gr</span></p>
                </div>

                {{-- Kuantitas --}}
                <div class="flex items-center gap-4 mb-8">
                    <span class="text-xl font-bold font-gotham text-black">Kuantitas:</span>
                    <div class="flex items-center w-40 bg-[#F2f7f6] rounded-full px-2 py-2">
                        <button @click="if(qty > 1) qty--" type="button" class="w-10 h-10 flex items-center justify-center text-2xl text-black hover:text-[#ec4899] font-bold">
                            &minus;
                        </button>
                        <input type="number" x-model="qty" class="flex-1 w-full text-center border-none focus:ring-0 font-bold text-xl p-0 bg-transparent" readonly>
                        <button @click="qty++" type="button" class="w-10 h-10 flex items-center justify-center text-2xl text-black hover:text-[#ec4899] font-bold">
                            &plus;
                        </button>
                    </div>
                </div>

             {{-- TOMBOL AKSI (Trigger Modal) --}}
                <div class="flex flex-col gap-4 mt-6">
                    <button @click="showModal = true" type="button" 
                        class="w-full border-2 border-[#ec4899] text-[#ec4899] py-3.5 rounded-2xl font-bold hover:bg-pink-50 transition-all flex items-center justify-center gap-2">
                        Tambah Ke Keranjang
                    </button>

                    <button @click="showModal = true" type="button" 
                        class="w-full bg-[#ec4899] text-white py-4 rounded-2xl font-black text-xl shadow-lg hover:shadow-pink-200 hover:-translate-y-1 transition-all">
                        <i class="fa-solid fa-cart-shopping mr-2"></i> Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ALA SHOPEE (Bottom Sheet) --}}
    <div x-show="showModal" 
         class="fixed inset-0 z-[100] flex items-end justify-center bg-black/60" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showModal = false" 
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="bg-white w-full max-w-2xl rounded-t-[40px] p-8 shadow-2xl relative">
            
            {{-- Header Modal --}}
            <div class="flex gap-6 mb-8 border-b pb-6">
                <div class="w-28 h-28 bg-gray-100 rounded-2xl p-2 flex items-center justify-center">
                    <img :src="activeImage" class="max-w-full max-h-full object-contain">
                </div>
                <div class="flex-1 flex flex-col justify-end relative">
                    <button @click="showModal = false" class="absolute -top-2 -right-2 text-gray-400 hover:text-black">
                        <i class="fa-solid fa-circle-xmark text-3xl"></i>
                    </button>
                    <p class="text-3xl font-bold text-[#F8A410] mb-1">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentPrice)"></span>
                    </p>
                    <p class="text-sm text-gray-500">Stok Tersedia: {{ $product->stock }}</p>
                </div>
            </div>

            {{-- Pemilihan Variasi --}}
            <div class="max-h-[50vh] overflow-y-auto no-scrollbar pr-2">
                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Variasi Tema:</h3>
                    <div class="flex flex-wrap gap-3">
                        @if($product->variations)
                            @foreach($product->variations as $v)
                                @php $val = is_array($v) ? ($v['nama'] ?? '') : $v; @endphp
                                
                                <button @click="selectVar(@js($v))" 
                                    type="button"
                                    {{-- KUNCI: Class ini akan berubah jadi PINK jika terpilih --}}
                                    :class="selectedVariation === '{{ $val }}' ? 'border-[#ec4899] bg-pink-50 text-[#ec4899] ring-2 ring-pink-100' : 'border-gray-200 text-gray-600'"
                                    class="px-6 py-2.5 border-2 rounded-xl text-sm font-bold transition-all">
                                    {{ $val }}
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

            {{-- Submit Form --}}
            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4">
                @csrf
                {{-- Hidden Inputs --}}
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" :value="qty">
                <input type="hidden" name="variation" :value="selectedVariation">
                <input type="hidden" name="buy_now" value="1"> {{-- Flag untuk proteksi --}}

                {{-- Tombol Keranjang (Normal Submit) --}}
                <button type="submit" class="flex-1 border-2 border-[#ec4899] text-[#ec4899] py-4 rounded-2xl font-bold hover:bg-pink-50 transition-all">
                    + Keranjang
                </button>
                
                {{-- Tombol Beli Sekarang (Redirect ke Checkout Langsung) --}}
                <button type="button"
                    @click="
                        if (allVariations.length > 0 && !selectedVariation) { 
                            alert('Pilih variasi tema dulu ya!'); 
                            return; 
                        }
                        
                        let url = '{{ route('checkout.index') }}' + 
                                '?product_id={{ $product->id }}' + 
                                '&quantity=' + qty + 
                                '&variation=' + encodeURIComponent(selectedVariation) +
                                '&direct=1';

                        window.location.href = url;
                    "
                    class="flex-1 bg-[#ec4899] text-white py-4 rounded-2xl font-black text-lg shadow-lg hover:brightness-110 transition-all">
                    Beli Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

    {{-- SECTION 2: DESCRIPTION --}}
    <div class="max-w-7xl mx-auto px-6 mt-6">
        <div class="flex flex-col space-y-12">
            <div>
                <h2 class="text-3xl font-black text-[#EC4899] font-gotham mb-6">Deskripsi Produk</h2>
                <div class="text-[#333333] leading-relaxed text-lg">
                    <div class="inline">
                        @php
                            // Cek apakah deskripsi sudah diawali dengan nama produk (case insensitive)
                            $hasName = Str::startsWith(strtolower(strip_tags($product->description)), strtolower($product->name));
                        @endphp

                        @if($hasName)
                            {{-- Jika sudah ada namanya, kita replace bagian pertama menjadi bold --}}
                            {!! Str::replaceFirst($product->name, '<strong>' . $product->name . '</strong>', $product->description) !!}
                        @else
                            {{-- Jika belum ada, kita tambahkan manual di depan --}}
                            <strong>{{ $product->name }}</strong> {!! $product->description !!}
                        @endif
                    </div>
                    <span class="text-[#F8A410] italic font-bold ml-1">
                        usia {{ $product->age_range ?? '4-7 tahun' }}.
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-24">
                <div>
                    <h2 class="text-2xl font-black text-[#EC4899] font-gotham mb-6">Variasi Tema Tersedia</h2>
                    <ul class="space-y-2 text-lg font-medium text-[#444444]">
                        @if($product->variations && is_array($product->variations))
                            @foreach($product->variations as $v)
                                <li class="flex items-start">
                                    <span class="mr-2 text-black">•</span> {{ is_array($v) ? ($v['nama'] ?? '') : $v }}
                                </li>
                            @endforeach
                        @else
                            <li class="text-gray-400 italic text-sm">Belum ada data variasi.</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-[#EC4899] font-gotham mb-6">Keunggulan</h2>
                    <ul class="space-y-2 text-lg font-medium text-[#444444]">
                        @if($product->features && is_array($product->features))
                            @foreach($product->features as $feature)
                                <li class="flex items-start">
                                    <span class="mr-2 text-black">•</span> {{ $feature }}
                                </li>
                            @endforeach
                        @else
                            <li class="text-gray-400 italic text-sm">Belum ada data keunggulan.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

   {{-- SECTION 3: RELATED PRODUCTS (Produk Lainnya) --}}
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="max-w-7xl mx-auto px-6 mt-24 mb-10">
    <h2 class="text-[28px] md:text-[32px] font-black text-[#EC4899] font-gotham mb-10 text-center tracking-wider">
        Produk Lainnya
    </h2>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
        @foreach($relatedProducts as $item)
        {{-- Alpine.js Scope per Produk --}}
        <div class="group relative" x-data="{ 
            showQuickModal: false,
            activeImg: '{{ asset("products/" . $item->image) }}',
            selectedVar: '',
            qty: 1,
            price: {{ (int) $item->price }},
            variations: @js($item->variations ?? []),
            
            selectQuickVar(v) {
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

            {{-- QUICK MODAL (Bottom Sheet) --}}
            <div x-show="showQuickModal" 
                 class="fixed inset-0 z-[110] flex items-end justify-center bg-black/60" 
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div @click.away="showQuickModal = false" 
                     x-show="showQuickModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full"
                     class="bg-white w-full max-w-lg rounded-t-[30px] p-6 shadow-2xl relative text-left">
                    
                    <button @click="showQuickModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-black">
                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                    </button>

                    <div class="flex gap-4 mb-6 border-b pb-4">
                        <img :src="activeImg" class="w-20 h-20 object-contain bg-gray-50 rounded-xl">
                        <div class="flex flex-col justify-end">
                            <p class="text-xl font-bold text-[#F8A410]">Rp <span x-text="new Intl.NumberFormat('id-ID').format(price)"></span></p>
                            <p class="text-xs text-gray-500">Stok: {{ $item->stock }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-bold text-sm mb-3 text-gray-800">Pilih Tema:</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variations" :key="typeof v === 'object' ? v.nama : v">
                                <button @click="selectQuickVar(v)" 
                                        type="button"
                                        :class="selectedVar === (typeof v === 'object' ? v.nama : v) ? 'border-[#ec4899] bg-pink-50 text-[#ec4899]' : 'border-gray-200 text-gray-600'"
                                        class="px-4 py-2 border-2 rounded-lg text-xs font-bold transition-all">
                                    <span x-text="typeof v === 'object' ? v.nama : v"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="variation" :value="selectedVar">

                        <button type="submit" class="flex-1 border-2 border-[#ec4899] text-[#ec4899] py-3 rounded-xl font-bold text-sm hover:bg-pink-50">
                            + Keranjang
                        </button>
                        
                        <button type="button"
                            @click="
                                if (!selectedVar && variations.length > 0) { alert('Pilih tema dulu ya!'); return; }
                                
                                // KIRIM LEWAT URL (GET)
                                $el.form.method = 'GET'; 
                                $el.form.action = '{{ route('checkout.index') }}';
                                $el.form.submit();
                            "
                            class="flex-1 bg-[#ec4899] text-white py-3 rounded-xl font-bold text-sm shadow-md">
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
    .font-gotham { font-family: 'Gotham Rounded', sans-serif; font-weight: 700; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection
