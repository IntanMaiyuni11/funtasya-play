@extends('layouts.checkout')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10 font-inter bg-white min-h-screen">
    {{-- Section 1: Breadcrumbs --}}
    <nav class="mb-10">
        <ul class="flex items-center text-[16px] md:text-[18px] text-[#555555] font-inter">
            <li class="flex items-center">
                <a href="{{ route('home') }}" class="hover:text-[#000000] transition-colors duration-200"">Home</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="flex items-center">
                <a href="{{ route('cart.index') }}" class="hover:text-[#000000] transition-colors duration-200"">Cart</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="flex items-center">
                <a href="#" class="hover:text-[#000000] transition-colors duration-200"">Checkout</a>
            </li>
            <li class="mx-3 text-gray-400"><i class="fa-solid fa-chevron-right text-[12px]"></i></li>
            <li class="font-bold text-[#000000]">Payment</li>
        </ul>
    </nav>

    <div class="flex flex-col lg:flex-row gap-10">

        {{-- BAGIAN KIRI (65%) --}}
        <div class="w-full lg:w-[65%] space-y-8">

            {{-- Status Pembayaran Card --}}
            <div class="border border-[#D9D9D9] rounded-[30px] p-8 shadow-sm bg-white relative">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-[18px] font-semibold text-[#000000]">Menunggu Pembayaran</h2>
                        <p class="text-[12px] text-[#8f8f8f] mt-1 flex items-center gap-2">
                            <i class="fa-regular fa-clock"></i> Selesaikan pembayaran sebelum waktu habis
                        </p>
                    </div>

                    {{-- Countdown --}}
                    <div id="countdown" class="bg-[#faecec] text-[#EC4899] font-bold text-[16px] px-4 py-1 rounded-lg tabular-nums">
                        23 : 59 : 59
                    </div>
                </div>

             <div class="bg-[#F8FAFC] rounded-xl p-6">

    {{-- 1. VIRTUAL ACCOUNT (BANK TRANSFER) --}}
    @if($payment_type == 'bank_transfer')
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                @php
                    $bankUpper = strtoupper($bank ?? 'BRI');
                    $logoUrl = match($bankUpper) {
                        'BRI'     => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg',
                        'MANDIRI' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg',
                        'BCA'     => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg',
                        default   => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg'
                    };
                @endphp
                <img src="{{ $logoUrl }}" class="h-5 w-auto object-contain">
                <p class="text-[16px] font-bold text-[#000000]">Virtual Account {{ $bankUpper }}</p>
            </div>
            
            <p class="text-[16px] text-[#8f8f8f]">Nomor Virtual Account</p>

            <div class="mt-4 border-2 border-[#EC4899] rounded-2xl p-3 flex items-center justify-between bg-white">
                <span id="vaNumber" class="text-[22px] font-bold text-[#000000] tracking-wider pl-2">
                    {{ $va_number }}
                </span>
                <button class="bg-[#EC4899] text-white px-5 py-2.5 rounded-2xl flex items-center gap-3 hover:bg-[#d43f89] transition-all shadow-sm active:scale-95" 
                        onclick="copyToClipboard()">
                    <i class="fa-regular fa-copy text-[18px]"></i>
                    <span class="text-[16px] font-bold tracking-wide">Salin</span>
                </button>
            </div>
        </div>

            {{-- 2. QRIS & E-WALLET --}}
            @elseif(in_array(strtolower($payment_type), ['qris', 'gopay', 'shopeepay', 'dana']))

                <div class="flex flex-col items-center py-4">

                @php
                        $paymentLower = strtolower($payment_type);

                        $qrString = $qr_string ?? ($response->qr_string ?? null);

                        $deeplinkUrl = null;
                        $qrUrl = null;

                        $actions = $actions ?? ($response->actions ?? []);

                        if(!empty($actions)) {
                            foreach($actions as $action) {

                                if($action->name == 'deeplink-redirect' || $action->name == 'url') {
                                    $deeplinkUrl = $action->url;
                                }

                                if($action->name == 'generate-qr-code') {
                                    $qrUrl = $action->url;
                                }
                            }
                        }

                        $walletLogo = match($paymentLower) {
                            'gopay'      => 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg',
                            'shopeepay'  => asset('images/sp.png'),
                            'dana'       => asset('images/dana.png'),
                            default      => 'https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg'
                        };
                    @endphp

                    {{-- LOGO --}}
                    <img src="{{ $walletLogo }}" class="h-10 mx-auto mb-6">

                    {{-- QRIS --}}
                   @if($paymentLower == 'qris' && $qrString)

                    <div class="bg-white p-4 rounded-2xl shadow mb-4">
                        <img id="qrImage"
                            src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $qrString }}"
                            class="w-64 h-64">
                    </div>

                    <p class="text-sm text-gray-500 text-center">
                        Scan QR menggunakan aplikasi pembayaran Anda
                    </p>

                    <div class="flex gap-3 mt-4">

                        {{-- SHARE --}}
                        <button onclick="shareQR()"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D9D9D9] hover:brightness-95 transition">

                            <img src="{{ asset('images/bagi.svg') }}" class="w-4 h-4 object-contain">

                            <span class="text-[#EC4899] text-[12px] font-semibold">
                                Share
                            </span>
                        </button>

                        {{-- DOWNLOAD --}}
                        <button onclick="downloadQR()"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EC4899] hover:brightness-110 transition">

                            <img src="{{ asset('images/Download.svg') }}" class="w-4 h-4 object-contain">

                            <span class="text-white text-[12px] font-semibold">
                                Download
                            </span>
                        </button>
                    </div>

                   {{-- GOPAY (QR + BUTTON) --}}
                    @elseif($paymentLower == 'gopay')

                        @if($qrUrl)

                            <div class="bg-white p-4 rounded-2xl shadow mb-4">
                                <img id="qrImage" src="{{ $qrUrl }}" class="w-64 h-64 mx-auto">
                            </div>

                            <p class="text-sm text-gray-500 text-center mb-4">
                                Scan QR menggunakan aplikasi GoPay
                            </p>

                            {{-- 🔥 FIX DI SINI --}}
                            <div class="flex justify-center gap-3 mt-4 mb-6">

                                {{-- SHARE --}}
                                <button onclick="shareQR()"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#D9D9D9] hover:brightness-95 transition">

                                    <img src="{{ asset('images/bagi.svg') }}" class="w-4 h-4 object-contain">

                                    <span class="text-[#EC4899] text-[12px] font-semibold">
                                        Share
                                    </span>
                                </button>

                                {{-- DOWNLOAD --}}
                                <button onclick="downloadQR()"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#EC4899] hover:brightness-110 transition">

                                    <img src="{{ asset('images/Download.svg') }}" class="w-4 h-4 object-contain">

                                    <span class="text-white text-[12px] font-semibold">
                                        Download
                                    </span>
                                </button>

                            </div>

                        @endif

                        @if($deeplinkUrl)
                            <a href="{{ $deeplinkUrl }}" target="_blank"
                            class="w-full max-w-md mx-auto bg-[#EC4899] text-white py-4 rounded-2xl font-bold text-lg text-center block">
                                Bayar dengan GoPay
                            </a>
                        @endif

                        @elseif($paymentLower == 'shopeepay')

                        @if($deeplinkUrl)
                            <a href="{{ $deeplinkUrl }}" target="_blank"
                            class="w-full max-w-md bg-[#EC4899] text-white py-4 rounded-2xl font-bold text-lg text-center block">
                                Bayar dengan ShopeePay
                            </a>
                        @else
                            <div class="text-gray-400 text-sm text-center">
                                Buka aplikasi ShopeePay untuk menyelesaikan pembayaran
                            </div>
                        @endif

                    {{-- E-WALLET LAIN --}}
                    @elseif($deeplinkUrl)

                        <div class="text-center w-full px-4">
                            <p class="text-gray-600 mb-4 text-sm">
                                Klik tombol di bawah untuk membayar menggunakan 
                                <strong>{{ ucfirst($payment_type) }}</strong>
                            </p>

                            <a href="{{ $deeplinkUrl }}" target="_blank"
                            class="w-full max-w-md bg-[#EC4899] text-white py-4 rounded-2xl font-bold text-lg block">
                                Bayar Sekarang
                            </a>
                        </div>

                    {{-- FALLBACK --}}
                    @else
                        <div class="text-gray-400 text-sm">
                            Menyiapkan metode pembayaran...
                        </div>
                    @endif
                </div>
            @endif
            </div>
            </div>

                {{-- Petunjuk Pembayaran --}}
                @if(!in_array(strtolower($payment_type), ['qris', 'gopay', 'shopeepay', 'dana']))
                    <div class="space-y-4 mt-8">
                        <h3 class="text-[18px] font-bold text-[#000000] flex items-center gap-2">
                            Petunjuk Pembayaran
                        </h3>
                        <div x-data="{ activeTab: 1 }" class="space-y-2 p-3 border border-[#D9D9D9] rounded-2xl bg-white shadow-sm">
                            {{-- ATM --}}
                            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                                <button @click="activeTab = activeTab === 1 ? 0 : 1" 
                                    :class="activeTab === 1 ? 'bg-[#faecec]' : 'bg-white'"
                                    class="w-full flex justify-between p-3 px-5 text-[15px] font-semibold items-center">
                                    <span class="text-gray-800">ATM {{ strtoupper($bank ?? 'BRI') }}</span>
                                    <i class="fa-solid fa-chevron-down text-[12px] transition-transform" :class="activeTab === 1 ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="activeTab === 1" x-collapse>
                                    <div class="p-5 pt-2 text-[13px] text-gray-600 border-t">
                                        <ol class="list-decimal ml-4 space-y-1">
                                            <li>Masukkan kartu ATM dan PIN Anda.</li>
                                            <li>Pilih menu <b>Transaksi Lainnya</b>.</li>
                                            <li>Pilih <b>Pembayaran</b> > <b>Virtual Account</b>.</li>
                                            <li>Masukkan Nomor VA: <b>{{ $va_number }}</b></li>
                                            <li>Konfirmasi dan simpan struk.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            {{-- Mobile Banking --}}
                            <div class="border border-gray-100 rounded-xl overflow-hidden bg-white">
                                <button @click="activeTab = activeTab === 2 ? 0 : 2" 
                                    :class="activeTab === 2 ? 'bg-[#faecec]' : 'bg-white'"
                                    class="w-full flex justify-between p-3 px-5 text-[15px] font-semibold items-center">
                                    <span class="text-gray-800">Mobile Banking</span>
                                    <i class="fa-solid fa-chevron-down text-[12px] transition-transform" :class="activeTab === 2 ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="activeTab === 2" x-collapse>
                                    <div class="p-5 pt-2 text-[13px] text-gray-600 border-t">
                                        <ol class="list-decimal ml-4 space-y-1">
                                            <li>Buka aplikasi m-banking Anda.</li>
                                            <li>Pilih menu <b>Transfer</b> > <b>Virtual Account</b>.</li>
                                            <li>Masukkan nomor VA: <b>{{ $va_number }}</b>.</li>
                                            <li>Masukkan PIN dan konfirmasi pembayaran.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            {{-- Button Aksi --}}
            <div class="flex flex-col md:flex-row gap-4 mt-10">
                <button onclick="checkPaymentStatus()" 
                    class="flex-1 bg-[#ec4899] text-white py-4 rounded-2xl font-bold text-[16px] shadow-lg shadow-pink-100 hover:bg-[#d43f89] transition-all">
                    <i class="fa-solid fa-rotate mr-2"></i> Cek Status Pembayaran
                </button>
                <a href="{{ route('home') }}" 
                    class="flex-1 bg-white border border-gray-300 text-gray-600 py-4 rounded-2xl font-bold text-center text-[16px] hover:bg-gray-50 transition-all">
                    Kembali
                </a>
            </div>
        </div>

       {{-- BAGIAN KANAN (35%) --}}
        <div class="w-full lg:w-[35%]">
            {{-- Class 'sticky' dan 'top-10' dihapus agar box tetap di tempatnya --}}
            <div class="border border-[#D9D9D9] rounded-[20px] overflow-hidden shadow-sm bg-white">
                <div class="bg-[#ec4899] py-4 text-center text-white font-bold uppercase tracking-wider text-[14px]">
                    Ringkasan Pesanan
                </div>
                <div class="p-8 space-y-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal (3 produk)</span>
                        <span class="font-medium text-black">Rp {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Ongkir</span>
                        <span class="text-[#74C123] font-bold">Gratis</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Estimasi Pajak</span>
                        <span class="font-medium text-black">Rp 5.000</span>
                    </div>
                    
                    <div class="border-b border-dashed border-gray-200 py-2"></div>
                    
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-[18px]">Total Pembayaran</span>
                        <span class="text-[20px] font-bold text-[#ec4899]">Rp {{ number_format($total) }}</span>
                    </div>

                    <div class="mt-8 bg-yellow-50 p-4 rounded-xl flex items-center gap-3 border border-yellow-100">
                        <i class="fa-solid fa-circle-exclamation text-yellow-500 text-sm"></i>
                        <p class="text-[11px] text-yellow-800 leading-relaxed">
                            Simpan bukti transfer hingga pembayaran Anda diverifikasi secara otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
  
@endsection
@push("addon-script")
<script>
    function startCountdown(durationInSeconds) {
        let timer = durationInSeconds;
        const display = document.querySelector('#countdown');

        const interval = setInterval(function () {
            let hours = parseInt(timer / 3600, 10);
            let minutes = parseInt((timer % 3600) / 60, 10);
            let seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + " : " + minutes + " : " + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                display.textContent = "00 : 00 : 00";
                // Opsional: reload halaman jika waktu habis
                // window.location.reload();
            }
        }, 1000);
    }

    // Jalankan countdown (Misal: 24 jam = 86400 detik)
    // Anda bisa mengambil sisa waktu asli dari database jika ada
    window.onload = function () {
        startCountdown(86399); 
    };

    function copyToClipboard() {
        const vaNumber = document.getElementById('vaNumber').innerText;
        navigator.clipboard.writeText(vaNumber.replace(/\s+/g, '')).then(() => {
            alert('Nomor VA berhasil disalin!');
        });
    }
</script>
<script>
function checkPaymentStatus() {
    fetch("{{ url('/payment/check/' . $response->order_id) }}")
        .then(res => res.json())
        .then(data => {

            if (data.status === 'complete') {
                window.location.href = "{{ route('order.success', $response->order_id) }}";
            } else {
                alert('Pembayaran belum selesai');
            }

        })
        .catch(() => {
            alert('Gagal cek status');
        });
}
</script>
<script>
setInterval(() => {
    fetch("{{ url('/payment/check/' . $response->order_id) }}")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'complete') {
                window.location.href = "{{ route('order.success', $response->order_id) }}";
            }
        });
}, 5000);
</script>
