@extends('layouts.admin')

@section('title', 'Katalog Produk')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans bg-[#FAFBFF]">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="relative">
            <h2 class="text-4xl font-black tracking-tighter text-gray-900 mb-1">
                Katalog <span class="text-[#EC4899]">Produk</span>
            </h2>
            <div class="flex items-center gap-2">
                <span class="w-8 h-1 bg-[#EC4899] rounded-full"></span>
                <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em]">Funtasya Play Inventory</p>
            </div>
        </div>
        <a href="{{ route('superadmin.products.create') }}" 
           class="group bg-[#EC4899] text-white px-10 py-5 rounded-[2rem] font-black uppercase tracking-widest hover:bg-pink-600 transition-all duration-300 shadow-2xl shadow-pink-200 flex items-center gap-3 text-xs active:scale-95">
            <div class="bg-white/20 p-1 rounded-lg group-hover:rotate-90 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
            </div>
            Tambah Produk
        </a>
    </div>

    {{-- Stats Mini (Optional - Biar makin pro) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Produk</p>
            <h3 class="text-2xl font-black text-gray-800">{{ $products->total() }}</h3>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Out of Stock</p>
            <h3 class="text-2xl font-black text-red-500">{{ $products->where('stock', 0)->count() }}</h3>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm p-4 border border-gray-100 mb-10 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <input type="text" id="product-search" placeholder="Cari nama mainan atau perlengkapan..." 
                   class="w-full pl-14 pr-6 py-5 bg-gray-50 border-transparent rounded-[1.8rem] text-sm font-bold focus:ring-4 focus:ring-pink-50 focus:bg-white focus:border-[#EC4899] transition-all duration-300 placeholder:text-gray-300">
            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        <div class="w-full md:w-64 relative">
            <select id="category-filter" class="w-full pl-6 pr-12 py-5 bg-gray-50 border-transparent rounded-[1.8rem] text-sm font-black text-gray-500 appearance-none focus:ring-4 focus:ring-pink-50 cursor-pointer">
                <option value="">Semua Kategori</option>
                {{-- Data kategori bisa dimasukkan di sini --}}
            </select>
            <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10" id="product-grid">
        @forelse($products as $product)
        <div class="bg-white rounded-[3rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-pink-100/50 transition-all duration-500 group product-card flex flex-col">
            {{-- Image Area --}}
            <div class="relative aspect-[4/5] overflow-hidden bg-gray-50">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400x500?text=Funtasya+Play' }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                
                {{-- Category Float --}}
                <div class="absolute top-6 left-6">
                    <span class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl text-[9px] font-black uppercase tracking-widest text-[#EC4899] shadow-sm">
                        {{ $product->category->name ?? 'No Category' }}
                    </span>
                </div>

                {{-- Action Overlay (Hanya muncul saat hover) --}}
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                    <a href="{{ route('superadmin.products.edit', $product->id) }}" class="bg-white p-4 rounded-2xl text-blue-600 hover:scale-110 transition-transform shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Info Area --}}
            <div class="p-8 flex flex-col flex-1">
                <div class="mb-4">
                    <h3 class="text-xl font-black text-gray-800 mb-1 line-clamp-2 product-name leading-tight group-hover:text-[#EC4899] transition-colors">
                        {{ $product->name }}
                    </h3>
                    <p class="text-[16px] font-black text-gray-900">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Inventory</span>
                        @if($product->stock > 0)
                            <span class="text-xs font-black text-green-500 uppercase">{{ $product->stock }} Ready Stock</span>
                        @else
                            <span class="text-xs font-black text-red-500 uppercase">Sold Out</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('superadmin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-4 bg-gray-50 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.85L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 text-center bg-white rounded-[4rem] border-4 border-dashed border-gray-50">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800">Ups! Katalog Kosong</h3>
            <p class="text-gray-400 font-bold italic mt-2">Mulai tambahkan produk pertamamu sekarang.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Custom Style --}}
    <div class="mt-16 flex justify-center">
        <div class="bg-white px-6 py-4 rounded-[2rem] shadow-sm border border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    document.getElementById('product-search').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.product-card');

        cards.forEach(card => {
            let name = card.querySelector('.product-name').textContent.toLowerCase();
            if (name.includes(filter)) {
                card.style.opacity = "1";
                card.style.display = "";
                card.style.transform = "scale(1)";
            } else {
                card.style.opacity = "0";
                setTimeout(() => {
                    if(card.style.opacity === "0") card.style.display = "none";
                }, 300);
            }
        });
    });
</script>
@endpush