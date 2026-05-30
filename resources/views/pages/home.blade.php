@extends('layouts.main')

@section('title', 'PlayLearn - Beranda')

@section('content')

{{-- HERO SECTION: Vibrant & Playful --}}
<section class="relative w-full bg-[#FFFBF0] overflow-hidden py-10">
    <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <span class="inline-block py-2 px-6 bg-orange-400 text-white rounded-full font-black tracking-widest text-xs uppercase rotate-[-2deg]">
                Edukasi Tanpa Batas
            </span>
            <h1 class="text-6xl lg:text-8xl font-black text-slate-900 tracking-tighter leading-[0.9]">
                Teman Bermain <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-yellow-400">Si Kecil!</span>
            </h1>
            <p class="text-xl text-slate-700 font-medium max-w-lg">
                Ubah waktu luang menjadi petualangan belajar yang seru dengan koleksi produk kreatif kami.
            </p>
            <div class="flex gap-4">
                <button onclick="document.getElementById('produk-unggulan').scrollIntoView({behavior: 'smooth'});"
                    class="bg-slate-900 text-white px-10 py-5 rounded-[2rem] font-black text-lg hover:rotate-[-2deg] transition-all shadow-[8px_8px_0px_0px_rgba(251,146,60,0.5)]">
                    Belanja Sekarang
                </button>
            </div>
        </div>
        <div class="relative">
            <div class="bg-orange-300 w-full aspect-square rounded-[3rem] rotate-[6deg] absolute top-0 left-0"></div>
            <img src="{{ asset('images3/gambar1.png') }}" alt="Hero" class="relative z-10 w-full rounded-[3rem] shadow-2xl border-4 border-white">
        </div>
    </div>
</section>

{{-- FEATURES: Card Tumpuk --}}
<section class="py-10 bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-6">
            @foreach([['icon' => 'fa-star', 't' => 'Edukasi Seru'], ['icon' => 'fa-rocket', 't' => 'Cepat'], ['icon' => 'fa-pen-nib', 't' => 'Kreatif'], ['icon' => 'fa-shield', 't' => 'Aman']] as $f)
            <div class="border-2 border-slate-700 p-8 rounded-[2rem] hover:border-orange-400 transition-colors">
                <i class="fa-solid {{ $f['icon'] }} text-4xl text-orange-400 mb-6"></i>
                <h3 class="font-black text-xl">{{ $f['t'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK: Grid Kartu Unik --}}
<section id="produk-unggulan" class="py-10 bg-[#FFFBF0]">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-5xl font-black text-slate-900 mb-12 text-center underline decoration-orange-400 decoration-8 underline-offset-8">Koleksi Pilihan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Kita batasi loop di sini agar tidak memproses lebih dari 3 item --}}
            @foreach($products->take(3) as $product)
            <div class="bg-white p-4 rounded-[2.5rem] shadow-[0_10px_0px_0px_rgba(0,0,0,0.1)] border-2 border-slate-900 group hover:translate-y-[-10px] transition-all">
                <div class="bg-slate-100 rounded-[2rem] aspect-[4/3] mb-6 overflow-hidden">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                <div class="px-2">
                    <h3 class="text-2xl font-black text-slate-900 mb-2">{{ $product->name }}</h3>
                    <p class="text-orange-600 font-bold text-lg mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <button @click="$dispatch('open-modal-cart', { 
                        id: '{{ $product->id }}', 
                        name: '{{ addslashes($product->name) }}', 
                        price: {{ $product->price }}, 
                        image: '{{ asset('storage/' . $product->image) }}', 
                        stock: '{{ $product->stock }}', 
                        variants: @js($product->variants) 
                    })" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-orange-500 transition-all">
                        Pilih Produk
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
