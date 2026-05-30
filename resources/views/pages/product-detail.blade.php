@extends('layouts.main')

@section('content')

<div class="bg-white py-12" x-data="{ 
    qty: 1, 
    selectedVariation: '', 
    activeImage: '{{ asset('storage/' . $product->image) }}'
}">
    <div class="max-w-6xl mx-auto px-6">
        
        {{-- FORM PEMBELIAN --}}
        <form action="{{ route('cart.add') }}" method="POST" id="product-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" x-model="qty">
            <input type="hidden" name="variation" x-model="selectedVariation">
            
            <div class="grid md:grid-cols-2 gap-16 items-start">
                
                {{-- KOLOM KIRI: Gambar --}}
                <div class="sticky top-24">
                    <div class="bg-gray-100 rounded-3xl overflow-hidden shadow-inner">
                        <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-auto object-cover">
                    </div>
                    <div class="flex gap-4 mt-6">
                        <button type="button" @click="activeImage = '{{ asset('storage/' . $product->image) }}'" class="w-20 h-20 rounded-2xl border-2 overflow-hidden">
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        </button>
                        @foreach($product->variants as $v)
                            <button type="button" @click="activeImage = '{{ asset('storage/' . $v->image) }}'" class="w-20 h-20 rounded-2xl border-2 overflow-hidden">
                                <img src="{{ asset('storage/' . $v->image) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- KOLOM KANAN: Info & Aksi --}}
                <div class="space-y-8">
                    <div>
                        <span class="text-sm font-bold text-blue-600 tracking-widest uppercase">{{ $product->category->name ?? 'Produk' }}</span>
                        <h1 class="text-5xl font-extrabold text-slate-900 mt-2 leading-tight">{{ $product->name }}</h1>
                    </div>

                    <div class="text-4xl font-black text-blue-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    {{-- Varian --}}
                    <div class="space-y-4">
                        <label class="font-bold text-slate-800">Pilih Varian:</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($product->variants as $v)
                                <button type="button" @click="selectedVariation = '{{ $v->name }}'" 
                                        class="px-4 py-3 border-2 rounded-xl text-left font-bold transition-all"
                                        :class="selectedVariation === '{{ $v->name }}' ? 'border-blue-600 bg-blue-50 text-blue-600' : 'border-gray-200'">
                                    {{ $v->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Qty --}}
                    <div class="flex items-center gap-4">
                        <label class="font-bold text-slate-800">Jumlah:</label>
                        <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                            <button type="button" @click="if(qty > 1) qty--" class="px-4 py-2 hover:bg-gray-100 font-bold">-</button>
                            <span class="px-4 font-bold" x-text="qty"></span>
                            <button type="button" @click="qty++" class="px-4 py-2 hover:bg-gray-100 font-bold">+</button>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="flex flex-col gap-3 pt-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition-all">
                            Tambah ke Keranjang
                        </button>
                        
                        <button type="button" 
                            @click="
                                if('{{ $product->variants->count() > 0 }}' && !selectedVariation) { alert('Pilih varian dulu ya!'); return; }
                                let url = '{{ route('checkout.index') }}?product_id={{ $product->id }}&quantity=' + qty + '&variation=' + encodeURIComponent(selectedVariation) + '&direct=1';
                                window.location.href = url;
                            "
                            class="w-full border-2 border-slate-900 text-slate-900 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- SECTION BAWAH: Deskripsi --}}
        <div class="mt-24 bg-slate-50 p-10 rounded-[3rem]">
            <h2 class="text-3xl font-black mb-8">Informasi Lengkap</h2>
            <div class="prose max-w-none">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</div>

@endsection
