@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-[#FAFAFA] py-16">
    <div class="max-w-5xl mx-auto px-6">
        
        {{-- Header Section --}}
        <div class="mb-16">
            <span class="text-[#EC4899] font-black tracking-widest uppercase text-sm">Status Pesanan</span>
            <h1 class="text-6xl font-black text-slate-950 mt-2 tracking-tighter">#{{ $order->order_code }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            {{-- Kiri: Status & Produk --}}
            <div class="md:col-span-8 space-y-8">
                
                {{-- Status Card --}}
                <div class="bg-slate-950 p-10 rounded-[2rem] text-white">
                    <p class="text-slate-400 font-medium mb-2">Estimasi kedatangan</p>
                    <h3 class="text-3xl font-bold mb-8">
                        {{ $order->completed_at ? $order->completed_at->format('d M Y') : 'Dalam Proses Pengiriman' }}
                    </h3>
                    
                    <div class="relative border-l-2 border-slate-800 ml-3 space-y-10">
                        @foreach(['Dikemas', 'Dikirim', 'Transit', 'Sampai'] as $step)
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-[#EC4899] ring-4 ring-slate-950"></div>
                            <p class="font-bold text-lg">{{ $step }}</p>
                            <p class="text-slate-500 text-sm">Status: {{ ucfirst($order->shipping_status) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Detail Produk --}}
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h4 class="font-bold text-xl mb-6">Ringkasan Produk</h4>
                    @foreach($order->items as $item)
                    <div class="flex gap-4 mb-4 items-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}';">
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">{{ $item->product->name }}</p>
                            <p class="text-sm text-slate-500">
                                Qty: {{ $item->qty }} • Rp{{ number_format($item->price * $item->qty, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: Sidebar --}}
            <div class="md:col-span-4 space-y-8">
                
                {{-- Info Pengiriman & Resi --}}
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 space-y-6">
                    <div>
                        <h4 class="font-bold text-slate-400 uppercase text-xs tracking-widest mb-4">Tujuan Pengiriman</h4>
                        <p class="font-bold text-lg leading-tight">{{ $order->address->recipient_name ?? 'Pelanggan' }}</p>
                        <p class="text-slate-600 mt-2 text-sm">{{ $order->address->full_address ?? 'Alamat tidak tersedia' }}</p>
                    </div>

                    {{-- Informasi Resi --}}
                    <div class="pt-6 border-t border-slate-100">
                        <h4 class="font-bold text-slate-400 uppercase text-xs tracking-widest mb-2">No. Resi ({{ $order->courier_name ?? 'Kurir' }})</h4>
                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl">
                            <code id="resi-code" class="font-mono font-bold text-slate-800">{{ $order->tracking_number ?? 'Menunggu Resi' }}</code>
                            <button onclick="copyResi()" class="text-[#EC4899] font-bold text-xs hover:text-pink-700">Salin</button>
                        </div>
                    </div>
                </div>

                {{-- Total Pembayaran --}}
                <div class="bg-[#EC4899] p-8 rounded-[2rem] text-white">
                    <h4 class="opacity-70 mb-1">Total Pembayaran</h4>
                    <p class="text-4xl font-black">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>

                {{-- Tombol Kembali --}}
                <a href="{{ route('profile.index') }}" class="block text-center w-full py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                    Kembali
                </a>
            </div>

        </div>
    </div>
</div>

<script>
function copyResi() {
    const code = document.getElementById('resi-code').innerText;
    navigator.clipboard.writeText(code).then(() => {
        alert('Nomor resi ' + code + ' berhasil disalin!');
    });
}
</script>
@endsection
