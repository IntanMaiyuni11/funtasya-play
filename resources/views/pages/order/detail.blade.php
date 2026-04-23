@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl font-inter">
    
    {{-- Section 1: Breadcrumbs --}}
    <nav class="mb-10">
        <ul class="flex items-center text-[16px] md:text-[18px] text-[#555555] font-inter">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="hover:text-[#000000] transition-colors duration-200">Home</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="flex items-center">
                <a href="#" class="hover:text-[#000000] transition-colors duration-200">Checkout</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="flex items-center">
                <a href="{{ route('payment.show', ['order_id' => $order->id]) }}" class="hover:text-[#000000] transition-colors duration-200">Payment</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="flex items-center">
                <a href="{{ route('profile.index') }}" class="hover:text-[#000000] transition-colors duration-200">Profile</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="font-bold text-[#000000]">Order-Detail</li>
        </ul>
    </nav>

    <h1 class="text-[#1E1E1E] font-bold text-[16.25px] mb-6">Lacak Pesanan</h1>

    {{-- Section 2: Order Header --}}
    <div class="bg-white p-5 md:p-7 rounded-[35px] border border-[#D9D9D9] flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div class="flex items-center gap-6 w-full">
            {{-- Box Icon --}}
            <div class="w-20 h-20 bg-[#F7F0F0] rounded-[22px] flex items-center justify-center shrink-0">
                <img src="{{ asset('images/Big Parcel.svg') }}" alt="Order Icon" class="w-12 h-12">
            </div>
            
            {{-- Order Info --}}
            <div class="flex-1">
                <h4 class="font-semibold text-[24px] text-[#000000]">#{{ $order->order_code }}</h4>
                    <div class="flex items-center gap-2 font-medium text-[16px] text-[#000000] mt-0.5">
                        <span>{{ $order->created_at->format('d F Y') }}</span>
                        <span class="text-[16px]">●</span>
                       <span>{{ $order->items->count() }} Item</span>
                    </div>
                <p class="text-[#EC4899] font-semibold text-[16px] mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            </div>
         </div>

       {{-- Status Badge --}}
        <div class="flex items-center w-full md:w-auto justify-start md:justify-end">
            @php
                // Mapping warna dan label berdasarkan shipping_status
                $statusConfigs = [
                    'dikemas' => [
                        'bg' => '#FFF8ED', 
                        'text' => '#E5A94D', 
                        'label' => 'Dikemas'
                    ],
                    'dikirim' => [
                        'bg' => '#FFF8ED', 
                        'text' => '#E5A94D', 
                        'label' => 'Dikirim'
                    ],
                    'transit' => [
                        'bg' => '#FFF8ED', 
                        'text' => '#E5A94D', 
                        'label' => 'Transit'
                    ],
                    'selesai' => [
                        'bg' => '#EEF9F1', 
                        'text' => '#78C28D', 
                        'label' => 'Selesai'
                    ],
                ];

                // Default jika status tidak ditemukan (misal: dibatalkan)
                $config = $statusConfigs[$order->shipping_status] ?? [
                    'bg' => '#FEF2F2', 
                    'text' => '#F87171', 
                    'label' => 'Dibatalkan'
                ];
            @endphp

            <span class="px-8 py-3 rounded-[20px] text-[16px] font-bold transition-colors duration-300"
                style="background-color: {{ $config['bg'] }}; color: {{ $config['text'] }};">
                {{ $config['label'] }}
            </span>
        </div>
    </div>

      {{-- Section 3: Alamat --}}
        <div class="bg-white p-6 rounded-[20px] border border-[#D9D9D9] mb-6">
            <h2 class="text-[#000000] font-bold text-[18px] mb-1.5">
                {{ $order->address->recipient_name ?? $order->user->name }}
            </h2>
            <p class="text-[#555555] font-medium text-[16px] mb-1.5">
                {{ $order->address->phone_number ?? '-' }}
            </p>
            <p class="text-[#555555] font-medium text-[16px] leading-[1.6]">
                @php
                    
                    $addressParts = array_filter([
                        $order->address->full_address ?? null, 
                        $order->address->city ?? null,
                        $order->address->postal_code ?? null  
                    ]);
                @endphp
                
                {{ !empty($addressParts) ? implode(', ', $addressParts) : 'Alamat belum diatur' }}
            </p>
        </div>

        {{-- Section 4: Statistic Pesanan (Timeline) --}}
            <div class="bg-white p-8 rounded-[25px] border border-[#D9D9D9] mb-10">
                <div class="relative flex justify-between items-start">
                    
                    {{-- Logika Penentuan Progress Berdasarkan shipping_status --}}
                    @php
                        $statusMapping = [
                            'dikemas' => 0,
                            'dikirim' => 1,
                            'transit' => 2,
                            'selesai' => 3
                        ];

                        // Mengambil index berdasarkan status dari database
                        $currentStepIndex = $statusMapping[$order->shipping_status] ?? 0;
                        
                        // Menghitung lebar garis pink (per 3 celah antar titik)
                        $lineWidth = ($currentStepIndex / 3) * 100;

                        $steps = [
                            ['label' => 'Dikemas', 'date' => $order->created_at],
                            ['label' => 'Dikirim', 'date' => $order->shipped_at],
                            ['label' => 'Dalam Transit', 'date' => $order->shipped_at ? $order->shipped_at->addHours(2) : null],
                            ['label' => 'Sampai Tujuan', 'date' => $order->completed_at],
                        ];
                    @endphp

                    {{-- Container Garis (Dasar Abu-abu) --}}
                    {{-- Kita gunakan width 75% dan mx-auto agar posisi pas di tengah antara 4 titik --}}
                    <div class="absolute top-5 left-0 right-0 mx-auto w-[75%] h-1 bg-[#D9D9D9] -z-0">
                        {{-- Active Line (Pink) --}}
                        {{-- width akan otomatis menyesuaikan berdasarkan currentStepIndex --}}
                        <div class="h-full bg-[#EC4899] transition-all duration-1000 ease-in-out" 
                            style="width: {{ ($currentStepIndex / 3) * 100 }}%"></div>
                    </div>

                    @foreach($steps as $index => $step)
                        @php
                            $isActive = $index <= $currentStepIndex;
                        @endphp
                        <div class="relative z-10 flex flex-col items-center flex-1">
                            {{-- Icon Circle --}}
                            {{-- Warna BG berubah jadi Pink jika status sudah tercapai atau aktif --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white shadow-sm transition-colors duration-500 
                                {{ $isActive ? 'bg-[#EC4899]' : 'bg-[#D9D9D9]' }} mb-3">
                                <i class="fa-solid fa-check text-white text-sm"></i>
                            </div>

                            {{-- Label & Date --}}
                            <div class="text-center">
                                <p class="text-[#000000] font-bold text-[16px] mb-1">{{ $step['label'] }}</p>
                                <p class="text-[#555555] font-medium text-[14px]">
                                    @if($step['date'])
                                        {{ $step['date']->translatedFormat('d F Y') }}
                                    @else
                                        <span class="text-gray-300">-- --- ----</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

              {{-- Section 5: Informasi Kurir --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start pt-4">
    
                        {{-- 1. Tanggal Pemesanan (Center alignment untuk keselarasan desain) --}}
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#888888] font-normal text-[14px]">Tanggal Pemesanan</p>
                            <p class="text-[#000000] font-bold text-[16px] mt-1">
                                {{ $order->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        {{-- 2. Info Kurir (Pusat / Center) --}}
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#888888] font-normal text-[14px] mb-2">Kurir</p>
                            
                            @php
                                $courierKey = strtolower($order->courier_name ?? '');
                                $logoName = 'default.png';

                                if (str_contains($courierKey, 'jne')) {
                                    $logoName = 'jne.png';
                                } elseif (str_contains($courierKey, 'j&t')) {
                                    $logoName = 'jnt.svg';
                                } elseif (str_contains($courierKey, 'anteraja')) {
                                    $logoName = 'anteraja.png';
                                } elseif (str_contains($courierKey, 'sicepat')) {
                                    $logoName = 'sicepat.png';
                                } elseif (str_contains($courierKey, 'gosend')) {
                                    $logoName = 'gosend.png';
                                }
                            @endphp

                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ asset('images/' . $logoName) }}" 
                                    class="h-4 object-contain" 
                                    alt="{{ $order->courier_name }}">
                                <span class="text-[#000000] font-bold text-[16px]">
                                    {{ $order->courier_name ?? 'J&T Express' }}
                                </span>
                            </div>
                            
                            {{-- Nomor Resi --}}
                            <div class="flex items-center gap-2">
                                <span class="text-[#888888] font-normal text-[14px]">No Resi:</span>
                                <span id="resi" class="text-[#000000] font-bold text-[14px]">
                                    {{ $order->tracking_number ?? 'FTS12345' }}
                                </span>
                                <button onclick="copyResi()" class="bg-[#EEEEEE] px-2 py-0.5 rounded-md text-[10px] font-bold flex items-center gap-1 hover:bg-gray-300 transition-colors">
                                    <i class="fa-regular fa-copy"></i> Salin
                                </button>
                            </div>
                        </div>

                        {{-- 3. Tanggal Diterima --}}
                        <div class="flex flex-col items-center text-center">
                            <p class="text-[#888888] font-normal text-[14px]">Tanggal Diterima</p>
                            <p class="text-[#000000] font-bold text-[16px] mt-1">
                                @if($order->shipping_status == 'selesai' || $order->completed_at)
                                    {{ ($order->completed_at ?? now())->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                    </div>

            {{-- Section 6 & 7: Informasi Pesanan --}}
                <h2 class="text-[#1E1E1E] font-bold text-[16.25px] mt-12 mb-6">Informasi Pesanan</h2>

                <div class="bg-white p-8 rounded-[25px] border border-[#D9D9D9] mb-10">
                    
                    {{-- List Produk --}}
                <div class="space-y-6 mb-8">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-6">
                        {{-- Foto Produk --}}
                        <div class="w-20 h-20 bg-[#F7F0F0] rounded-[18px] overflow-hidden shrink-0 flex items-center justify-center">
                            <img src="{{ asset("products/" . $item->product->image) }}" 
                                class="w-full h-full object-cover" 
                                alt="{{ $item->product->name }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}';">
                        </div>
                        
                        <div class="flex-1 flex justify-between items-center">
                            <div>
                                <p class="text-[#000000] font-normal text-[16px]">
                                    {{ $item->product->name }}
                                </p>
                                {{-- Menampilkan Variation (Warna/Ukuran) --}}
                                @if($item->variation)
                                    <p class="text-gray-500 text-[13px] mt-1 italic">
                                        Variasi: {{ $item->variation }}
                                    </p>
                                @endif
                                {{-- Menampilkan jumlah per item --}}
                                <p class="text-gray-400 text-[12px]">x{{ $item->qty }}</p>
                            </div>
                            <p class="text-[#000000] font-bold text-[16px] whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-[#D9D9D9] opacity-30 my-6"></div>

                {{-- Rincian Biaya Sinkron Database --}}
                <div class="space-y-4">
                    {{-- Baris Jumlah --}}
                    <div class="flex justify-between items-center">
                        <p class="text-[#000000] font-normal text-[16px]">Jumlah</p>
                        <p class="text-[#000000] font-bold text-[16px]">
                            {{ $order->items->sum('qty') }}
                        </p>
                    </div>

                    {{-- Baris Biaya Pengiriman --}}
                    <div class="flex justify-between items-center">
                        <p class="text-[#000000] font-normal text-[16px]">Biaya Pengiriman</p>
                        <p class="text-[#000000] font-bold text-[16px]">
                          
                            @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            @else
                                <span class="text-green-600">Gratis Ongkir</span>
                            @endif
                        </p>
                    </div>

                    {{-- Baris Estimasi Pajak (Opsional, jika ingin ditampilkan kembali) --}}
                    <div class="flex justify-between items-center">
                        <p class="text-[#000000] font-normal text-[16px]">Estimasi Pajak (PPN)</p>
                        <p class="text-[#000000] font-bold text-[16px]">Rp 3.000</p>
                    </div>

                    <div class="border-t border-[#D9D9D9] opacity-30 my-4"></div>

                    {{-- Baris Total --}}
                    <div class="flex justify-between items-center">
                        <p class="text-[#000000] font-bold text-[18px]">Total</p>
                        <p class="text-[#EC4899] font-bold text-[20px]">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                </div>

    {{-- Section 8: Buttons --}}
    <div class="flex gap-4 justify-between">
        <a href="{{ route('profile.index') }}" class="bg-[#D9D9D9] text-[#000000] font-medium text-[16px] px-10 py-3 rounded-xl">Kembali</a>
        
        @if($order->status == 'complete')
            <a href="{{ route('catalog') }}" class="bg-[#EC4899] text-[#ffffff] font-medium text-[16px] px-10 py-3 rounded-xl">Beli Lagi</a>
        @endif
    </div>
</div>
@endsection

@push('scripts') 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script>
$(document).ready(function() {
    $('#city_input').on('change', function() {
        let cityName = $(this).val();

        $.ajax({
            url: '/get-shipping-cost',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                city_name: cityName
            },
            success: function(response) {
                // 1. Update teks Biaya Pengiriman di layar
                // Pastikan id="shipping-cost-text" ada di elemen HTML ongkir kamu
                $('#shipping-cost-text').text('Rp ' + new Intl.NumberFormat('id-ID').format(response.cost));

                // 2. Hitung ulang total
                let subtotal = {{ $subtotal ?? 0 }}; 
                let serviceFee = 5000; 
                let total = subtotal + response.cost + serviceFee;

                // 3. Update teks Total
                // Pastikan id="total-text" ada di elemen HTML total harga kamu
                $('#total-text').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            }
        });
    });
});
</script>
@endpush

@push('addon-script')
<script>
function copyResi() {
    const resiText = document.getElementById('resi').innerText;
    navigator.clipboard.writeText(resiText);
    alert('Nomor resi berhasil disalin!');
}
</script>
    
@endpush