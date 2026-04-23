@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <h3 class="text-gray-700 text-3xl font-bold">Daftar Pesanan (Orders)</h3>
    </div>

    @if(session('success'))
    <div class="mt-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="mt-8">
        <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs font-bold shadow-sm">
                        <th class="py-4 px-6 text-left">Order ID</th>
                        <th class="py-4 px-6 text-left">Customer</th>
                        <th class="py-4 px-6 text-left">Total Bayar</th>
                        <th class="py-4 px-6 text-left">Status Bayar</th>
                        <th class="py-4 px-6 text-left">Status Kirim</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($orders as $order)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 text-left font-bold text-gray-900">
                            #{{ $order->order_code }}
                        </td>
                        <td class="py-4 px-6 text-left">
                            <div class="flex flex-col">
                                <span class="font-semibold">{{ $order->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-left font-bold">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-left">
                            {{-- Status Pembayaran (Kolom: status) --}}
                            @php
                                $paymentColors = [
                                    'process'   => 'bg-orange-100 text-orange-700',
                                    'complete'  => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ][$order->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="{{ $paymentColors }} py-1 px-3 rounded-full text-[10px] font-bold uppercase">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-left">
                            {{-- Status Pengiriman (Kolom: shipping_status) --}}
                            @php
                                $shippingColors = [
                                    'dikemas' => 'bg-blue-100 text-blue-700',
                                    'dikirim' => 'bg-indigo-100 text-indigo-700',
                                    'transit' => 'bg-purple-100 text-purple-700',
                                    'selesai' => 'bg-teal-100 text-teal-700',
                                ][$order->shipping_status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <div class="flex flex-col gap-1">
                                <span class="{{ $shippingColors }} py-1 px-3 rounded-full text-[10px] font-bold uppercase text-center w-max">
                                    {{ $order->shipping_status }}
                                </span>
                                @if($order->tracking_number)
                                <span class="text-[10px] text-gray-400 italic">Resi: {{ $order->tracking_number }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @php
                                // Cek role user untuk menentukan prefix route yang benar sesuai web.php kamu
                                $rolePrefix = Auth::user()->role == 'super_admin' ? 'superadmin' : 'admin';
                            @endphp
                            
                            <a href="{{ route($rolePrefix . '.orders.show', $order->id) }}" 
                            class="inline-flex items-center justify-center bg-[#EC4899] text-white w-9 h-9 rounded-xl hover:bg-pink-600 transition-all shadow-md shadow-pink-100"
                            title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Belum ada pesanan yang masuk.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection