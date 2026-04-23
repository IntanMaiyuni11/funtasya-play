@extends('layouts.main')

@section('content')
<div class="min-h-screen flex flex-col items-center bg-white px-4 pt-10 pb-40 relative overflow-hidden">
    
    {{-- SECTION 1: GAMBAR KUCING (Reward) --}}
    <div class="mb-6 z-10 mt-6">
        <img src="{{ asset('images/claim_reward.png') }}" alt="Success Reward" class="w-40 md:w-48 object-contain">
    </div>

    {{-- SECTION 2: TEKS & BUTTON --}}
    <div class="text-center max-w-2xl z-10">
        <p class="text-[#1E1E1E] font-inter text-[16px] mb-1">
            Hey {{ Auth::user()->name }},
        </p>
        
        <h1 class="text-[#1E1E1E] font-gotham font-bold text-[28px] md:text-[36px] leading-tight mb-4">
            Hore! Pembayaran Berhasil
        </h1>
        
        <p class="text-[#707070] font-inter font-semibold text-[14px] md:text-[16px] mb-8 px-4 leading-relaxed">
            Kami akan memberikan kamu bukti pemesanan di<br class="hidden md:block"> email secepatnya!
        </p>

       <a href="{{ route('profile.index') }}" 
            class="inline-block bg-[#EC4899] text-[#FFFFFF] font-inter font-medium text-[16px] px-10 py-3 rounded-full shadow-lg hover:bg-[#d83685] transition-all duration-300">
                Check Status
            </a>
    </div>

    {{-- SECTION 3: LANJUT BELANJA --}}
    <div class="mt-4 text-center z-10">
        <p class="text-[#000000] font-inter font-medium text-[16px]">
            Ingin lanjut belanja? 
            <a href="{{ route('catalog') }}" class="text-[#335FFF] font-bold underline ml-1 hover:text-blue-800 transition-colors">
                Kembali ke Katalog
            </a>
        </p>
    </div>

    {{-- BACKGROUND ILLUSTRATION --}}
    {{-- Perubahan: Mengganti bottom-0 menjadi -bottom-10 atau -bottom-16 untuk menaikkan background --}}
    <div class="absolute -bottom-10 left-0 w-full pointer-events-none z-0">
        <img src="{{ asset('images/kucing-background.png') }}" alt="Background Illustration" class="w-full h-auto object-cover block">
    </div>
</div>

<style>
    @font-face {
        font-family: 'Gotham Rounded';
        src: url('/fonts/GothamRounded-Bold.woff2') format('woff2');
        font-weight: bold;
        font-style: normal;
    }
    .font-gotham {
        font-family: 'Gotham Rounded', sans-serif;
    }
    .font-inter {
        font-family: 'Inter', sans-serif;
    }
</style>
@endsection