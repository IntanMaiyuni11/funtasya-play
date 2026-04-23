@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-pink-600">Detail Pesanan</h2>
            <p class="text-gray-500">Kode Order: <span class="font-mono font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded-lg">#{{ $order->order_code }}</span></p>
        </div>
        <a href="{{ Auth::user()->role == 'super_admin' ? route('superadmin.orders.index') : route('admin.orders.index') }}" 
           class="bg-white text-gray-700 border border-gray-200 px-6 py-2.5 rounded-2xl hover:bg-gray-50 transition shadow-sm font-bold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm animate-pulse">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- SISI KIRI: Item & Alamat --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Daftar Produk --}}
            <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 transition-all hover:shadow-md">
                <h4 class="text-xl font-black mb-6 text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-8 bg-pink-500 rounded-full"></span>
                    Item yang Dibeli
                </h4>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <div class="py-5 flex items-center gap-6 group">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-24 h-24 object-cover rounded-[1.5rem] bg-gray-50 border border-gray-100 group-hover:scale-105 transition-transform duration-300">
                            <div class="flex-1">
                                <h5 class="font-black text-lg text-gray-800">{{ $item->product->name }}</h5>
                                <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest italic">Qty: {{ $item->quantity }} Pcs</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold uppercase">Subtotal</p>
                                <p class="font-black text-xl text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Ringkasan Harga --}}
                <div class="mt-8 pt-6 border-t border-dashed border-gray-200 space-y-3">
                    <div class="flex justify-between text-gray-500 font-bold uppercase text-xs tracking-widest">
                        <span>Total Harga Produk</span>
                        <span>Rp {{ number_format($order->total_price - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500 font-bold uppercase text-xs tracking-widest">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-2xl font-black text-[#EC4899] pt-4">
                        <span class="tracking-tighter">TOTAL BAYAR</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-pink-100 text-pink-600 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-black text-gray-800">Tujuan Pengiriman</h4>
                </div>
                <div class="bg-gray-50 rounded-[1.5rem] p-6 border border-gray-100">
                    <p class="font-black text-gray-900 text-lg">{{ $order->address->receiver_name ?? $order->user->name }}</p>
                    <p class="text-pink-600 font-bold mb-3">{{ $order->address->phone }}</p>
                    <div class="text-gray-600 leading-relaxed font-medium">
                        <p>{{ $order->address->address_detail }}</p>
                        <p class="uppercase text-xs font-black tracking-widest text-gray-400 mt-2">
                            {{ $order->address->city }}, {{ $order->address->province }} - {{ $order->address->postal_code }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: Status & Resi --}}
        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-20 h-20 text-pink-600" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v3h3V7z"></path></svg>
                </div>
                
                <h4 class="text-xl font-black mb-6 text-gray-800">Kelola Status</h4>
                
                {{-- Status Ringkas --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2 text-center">Bayar</label>
                        <span class="block text-center px-3 py-2 rounded-xl text-[10px] font-black uppercase {{ $order->status == 'complete' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div>
                        <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2 text-center">Kirim</label>
                        @php
                            $shipColors = [
                                'dikemas' => 'bg-orange-100 text-orange-700',
                                'dikirim' => 'bg-blue-100 text-blue-700',
                                'transit' => 'bg-purple-100 text-purple-700',
                                'selesai' => 'bg-green-100 text-green-700',
                            ][$order->shipping_status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="{{ $shipColors }} block text-center px-3 py-2 rounded-xl text-[10px] font-black uppercase">
                            {{ $order->shipping_status }}
                        </span>
                    </div>
                </div>

                {{-- Form Update --}}
                @if($order->status == 'complete' && $order->shipping_status != 'selesai')
                <form action="{{ Auth::user()->role == 'super_admin' ? route('superadmin.orders.updateStatus', $order->id) : route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-black text-gray-600 mb-2 uppercase">Pilih Kurir</label>
                        <select name="courier" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-pink-500 focus:border-pink-500 text-sm font-bold p-4 appearance-none shadow-inner" required>
                            <option value="" disabled selected>Pilih Kurir...</option>
                            @foreach(['JNE', 'J&T Express', 'Sicepat', 'Anteraja', 'Gosend'] as $courier)
                                <option value="{{ $courier }}" {{ $order->courier_name == $courier ? 'selected' : '' }}>{{ $courier }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-600 mb-2 uppercase">Update Status</label>
                        <select name="shipping_status" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-pink-500 focus:border-pink-500 text-sm font-bold p-4 appearance-none shadow-inner" required>
                            @foreach([
                                'dikemas' => '📦 Dikemas',
                                'dikirim' => '🚚 Dikirim',
                                'transit' => '📍 Transit',
                                'selesai' => '✅ Selesai'
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ $order->shipping_status == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-600 mb-2 uppercase tracking-tighter">Nomor Resi</label>
                        <input type="text" name="resi" value="{{ $order->tracking_number }}" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-pink-500 text-sm font-mono font-bold p-4 shadow-inner" placeholder="Input resi..." required>
                    </div>

                    <button type="submit" class="w-full bg-[#EC4899] text-white py-4 rounded-[1.5rem] font-black uppercase tracking-widest hover:bg-pink-700 transition shadow-xl shadow-pink-100 flex items-center justify-center gap-2">
                        Simpan Data
                    </button>
                </form>
                @elseif($order->status != 'complete')
                <div class="p-6 bg-orange-50 rounded-[1.5rem] border border-orange-100 text-center">
                    <p class="text-xs text-orange-600 font-black uppercase tracking-widest leading-loose">Menunggu Konfirmasi<br>Pembayaran Lunas</p>
                </div>
                @else
                <div class="p-6 bg-green-50 rounded-[1.5rem] border border-green-100 text-center">
                    <p class="text-xs text-green-600 font-black uppercase tracking-widest">🎉 Pesanan Selesai</p>
                </div>
                @endif
            </div>

            {{-- Info Pelacakan Visual --}}
            @if($order->tracking_number)
            <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-pink-500 rounded-full blur-3xl opacity-20"></div>
                <h5 class="text-[10px] font-black text-pink-400 uppercase tracking-[0.3em] mb-4">Tracking Information</h5>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-xs font-bold uppercase">Logistik</span>
                        <span class="font-black text-sm">{{ $order->courier_name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-xs font-bold uppercase">Resi</span>
                        <span class="font-mono font-black text-pink-500">{{ $order->tracking_number }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-800">
                        <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1 italic text-right">
                            Last Updated:
                        </p>
                        <p class="text-xs font-bold text-gray-300 text-right">
                            {{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : '-' }} WIB
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection