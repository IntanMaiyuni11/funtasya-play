@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans">
    <div class="mb-8">
        <h2 class="text-gray-800 text-3xl font-black tracking-tight">Daftar Biaya Ongkir</h2>
        <p class="text-gray-500">Mode Lihat (Admin Staff). Perubahan hanya dapat dilakukan oleh Super Admin.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-widest">
                        <th class="px-8 py-5">Lokasi</th>
                        <th class="px-8 py-5 text-right">Biaya Pengiriman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($shippingCosts as $item)
                    <tr>
                        <td class="px-8 py-6 uppercase font-bold text-gray-700 tracking-tight">
                            {{ $item->province }} — {{ $item->city }}
                        </td>
                        <td class="px-8 py-6 text-right font-black text-lg text-pink-600">
                            Rp {{ number_format($item->cost, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-gray-50">
            {{ $shippingCosts->links() }}
        </div>
    </div>
</div>
@endsection