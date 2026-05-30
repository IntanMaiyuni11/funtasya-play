@extends('layouts.main')

@section('content')
<div class="bg-slate-50 min-h-screen py-12" x-data="{ 
    cart: {{ $cart->map(fn($item) => [
        'id' => $item->id, 'name' => $item->product->name, 'price' => $item->product->price,
        'image' => $item->product->image, 'quantity' => $item->quantity, 'variation' => $item->variation
    ])->values()->toJson() }},
    get total() { return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0); },
    formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number).replace('IDR', 'Rp'); }
}">

    <div class="max-w-5xl mx-auto px-6">
        <h1 class="text-4xl font-black text-slate-900 mb-10">Keranjang Belanja</h1>

        {{-- Section: Cart Items --}}
        <template x-if="cart.length > 0">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="space-y-6">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
                            {{-- Image --}}
                            <div class="w-28 h-28 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-grow">
                                <h3 class="text-xl font-extrabold text-slate-800" x-text="item.name"></h3>
                                <p class="text-slate-500 font-bold" x-text="'Varian: ' + (item.variation || '-')"></p>
                                <p class="text-blue-600 font-black mt-1" x-text="formatRupiah(item.price)"></p>
                            </div>

                            {{-- Actions --}}
                            <div class="text-right">
                                <p class="text-2xl font-black text-slate-900" x-text="formatRupiah(item.price * item.quantity)"></p>
                                <form :action="'/cart/remove/' + item.id" method="POST" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('catalog') }}" class="text-slate-500 font-bold hover:text-slate-900">← Lanjut Belanja</a>
                    <div class="text-right">
                        <p class="text-sm text-slate-400 font-bold">Total Pembayaran</p>
                        <p class="text-4xl font-black text-slate-900" x-text="formatRupiah(total)"></p>
                        <a href="{{ route('checkout.index') }}" class="inline-block mt-4 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black hover:bg-slate-800">
                            Checkout Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
<template x-if="cart.length === 0">
    <div class="bg-white rounded-3xl p-16 text-center border-2 border-dashed border-slate-200 shadow-sm">
        {{-- Gambar diperbesar menjadi w-64 --}}
        <img src="{{ asset('images3/gambar2.png') }}" alt="Empty" class="w-64 mx-auto mb-8">
        <h2 class="text-3xl font-black text-slate-800">Keranjang Kosong</h2>
        <p class="text-slate-500 mt-3 mb-8 text-lg">Belum ada petualangan yang dipilih untuk si kecil.</p>
        <a href="{{ route('catalog') }}" class="inline-block bg-slate-900 text-white px-10 py-4 rounded-2xl font-bold hover:bg-slate-800 transition-all">
            Cari Produk Sekarang
        </a>
    </div>
</template>
    </div>
</div>
@endsection
