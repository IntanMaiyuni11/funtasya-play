@extends('layouts.main')

@section('content')

<div class="bg-white min-h-screen">
    
    {{-- 1. HERO SECTION --}}
    <section class="pt-4 md:pt-6 pb-2">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h1 class="text-[#ec4899] font-bold text-[28px] md:text-[36px] mb-2 tracking-tight">Tentang Funtasya Play</h1>
            <p class="text-[#000000] leading-relaxed text-[14px] md:text-[14px] px-2 opacity-80">
                Kami percaya setiap momen bermain adalah peluang belajar, dan Funtasya Play hadir menemani tumbuh kembang anak dengan mainan edukatif yang dirancang penuh kreativitas dan kasih sayang.
            </p>
        </div>
    </section>

    {{-- 2. VIDEO SECTION --}}
    {{-- Menggunakan mt-2 agar jarak dengan teks di atas sangat dekat sesuai gambar --}}
    <section class="pb-6 mt-2">
        <div class="max-w-4xl mx-auto px-6">
            <div class="relative group cursor-pointer overflow-hidden rounded-[30px] shadow-xl border-4 border-white">
                <div class="aspect-video w-full bg-gray-200">
                    <iframe 
                        class="w-full h-full" 
                        src="https://www.youtube.com/embed/gKPchfmymbM" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. MISI KAMI --}}
    {{-- Py-4 agar box ungu ini rapat dengan section video di atas dan gallery di bawah --}}
    <section class="py-4">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-[#ce82ff] text-[#ffffff] p-8 md:p-10 rounded-[30px] text-center shadow-md">
                <h2 class="text-[24px] font-bold mb-2">Misi Kami</h2>
                <p class="text-[13px] md:text-[15px] leading-relaxed opacity-95">
                    Di Funtasya Play, kami percaya belajar paling efektif saat anak merasa senang, поэтому kami menghadirkan pengalaman bermain yang seru, penuh eksplorasi, dan bermakna untuk membantu anak mengembangkan kreativitas, kemampuan berpikir, serta rasa percaya diri, karena bermain adalah cara alami anak untuk tumbuh dan memahami dunia.
                </p>
            </div>
        </div>
    </section>

    {{-- 4. KESERUAN BERMAIN --}}
    <section class="py-6">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-[#0093F5] font-bold text-[26px] mb-1">Keseruan Bermain</h2>
            <p class="text-[#000000] mb-6 text-[14px] opacity-60">Intip keseruan teman-teman kecil Funtasya Play saat belajar sambil bermain!</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="space-y-3">
                    <img src="{{ asset('images/au-1.png') }}" class="rounded-[20px] w-full h-40 object-cover">
                    <img src="{{ asset('images/au-2.png') }}" class="rounded-[20px] w-full h-52 object-cover">
                </div>
                <div class="pt-6">
                    <img src="{{ asset('images/au-3.png') }}" class="rounded-[20px] w-full h-[320px] object-cover">
                </div>
                <div class="space-y-3">
                    <img src="{{ asset('images/au-4.png') }}" class="rounded-[20px] w-full h-44 object-cover">
                    <img src="{{ asset('images/au-5.png') }}" class="rounded-[20px] w-full h-44 object-cover">
                </div>
                <div class="pt-6">
                    <img src="{{ asset('images/au-6.png') }}" class="rounded-[20px] w-full h-[320px] object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- 5. CTA SECTION --}}
    <section class="pb-12 pt-4">
        <div class="max-w-4xl mx-auto px-6">
            <div class="rounded-[35px] p-8 md:p-12 text-center text-white relative overflow-hidden shadow-lg" 
                 style="background: linear-gradient(90deg, #ec4899 0%, #ec48d6 50%, #ec4899 100%);">
                
                <img src="{{ asset('images/Kucing-bawah-hati.png') }}" class="absolute left-2 bottom-0 w-20 md:w-28 hidden md:block">
                
                <div class="relative z-20">
                    <h2 class="text-[26px] md:text-[32px] font-extrabold mb-2 text-white">Siap Untuk Mulai Belajar?</h2>
                    <p class="mb-6 opacity-90 text-xs md:text-sm">Temukan koleksi mainan edukatif terbaik untuk buah hati Anda.</p>
                    <a href="{{ route('catalog') }}" class="bg-white text-[#ec4899] px-8 py-3 rounded-full font-bold shadow-md hover:scale-105 transition-all inline-block text-sm">
                        Belanja Sekarang
                    </a>
                </div>

                <img src="{{ asset('images/Kucing-bawah-hati.png') }}" class="absolute right-2 bottom-0 w-20 md:w-28 hidden md:block scale-x-[-1]">
            </div>
        </div>
    </section>
</div>
@endsection
