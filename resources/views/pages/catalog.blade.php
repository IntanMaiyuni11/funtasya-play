@extends('layouts.main')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Header --}}
        <div class="mb-12 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900">Katalog Produk</h1>
            <p class="text-slate-500 mt-2 text-lg">Temukan media belajar yang tepat untuk si kecil.</p>
        </div>

        {{-- Filter Categories --}}
        <div class="flex flex-wrap gap-2 mb-12 justify-center md:justify-start">
            <a href="{{ route('catalog') }}" 
               class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all {{ !request('category') ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                Semua Produk
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('catalog', ['category' => $cat->slug]) }}" 
               class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all {{ request('category') == $cat->slug ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="group bg-white rounded-[2rem] p-4 border border-slate-100 hover:border-blue-200 transition-all duration-300 hover:shadow-xl hover:shadow-blue-50/50">
                <a href="{{ route('product.show', $product->slug) }}">
                    <div class="relative aspect-square rounded-[1.5rem] overflow-hidden bg-slate-100 mb-4">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </a>
                
                <div class="px-2">
                    <span class="text-[10px] font-black tracking-widest uppercase text-blue-500">{{ $product->category->name ?? 'Produk' }}</span>
                    <h3 class="text-slate-900 font-bold text-lg mt-1 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {{ $product->name }}
                    </h3>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-black text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        
                        {{-- Tombol Trigger Modal --}}
                       <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal-cart', { detail: { 
                                id: '{{ $product->id }}', 
                                name: '{{ addslashes($product->name) }}', 
                                price: {{ $product->price }}, 
                                image: '{{ asset('storage/' . $product->image) }}', 
                                stock: '{{ $product->stock }}', 
                                variants: {{ json_encode($product->variants) }} 
                            }}))"
                            class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center hover:bg-blue-600 transition-all">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-16">
            {{ $products->links() }}
        </div>
    </div>


{{-- MODAL (Taruh di akhir file, sebelum @endsection) --}}
<div x-data="{ 
    open: false, 
    product: { id: '', name: '', price: 0, image: '', stock: 0, variants: [] }, 
    selectedVariation: null, 
    currentPrice: 0 
}"
     @open-modal-cart.window="
        open = true; 
        product = $event.detail; 
        currentPrice = $event.detail.price; 
        selectedVariation = null;
     ">

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
            
            <div x-show="open" @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm" x-transition.opacity></div>
            
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white w-full max-w-sm rounded-[2rem] p-6 shadow-2xl relative z-50">
                
                <button @click="open = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                </button>

                <div class="flex gap-4 mb-6">
                    <div class="w-20 h-20 bg-slate-100 rounded-2xl overflow-hidden shrink-0">
                        <img :src="product.image" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-center">
                        <h2 class="font-bold text-slate-900 text-base" x-text="product.name"></h2>
                        <p class="text-xl font-black text-blue-600">Rp <span x-text="new Intl.NumberFormat('id-ID').format(currentPrice)"></span></p>
                        <p class="text-xs text-slate-400" x-text="'Stok: ' + product.stock"></p>
                    </div>
                </div>

                <hr class="border-slate-100 mb-6">

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" :value="product.id">
                    <input type="hidden" name="variation" :value="selectedVariation">
                    
                    <div class="mb-6">
                        <p class="text-xs font-bold text-slate-900 mb-3">Variasi:</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in product.variants" :key="v.id">
                                <button type="button" 
                                        @click="selectedVariation = v.name; currentPrice = v.price"
                                        :class="selectedVariation === v.name ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600'"
                                        class="px-4 py-2 rounded-lg font-bold text-xs transition-all border border-slate-200"
                                        x-text="v.name">
                                </button>
                            </template>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-2xl font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>
</div>
@endsection
