<footer class="bg-[#e84393] text-white">

    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-10 items-start">

        <!-- QR -->
        <div class="flex flex-col items-center md:items-start">
            <img src="https://funtasyaworld.com/img/qrcode.png" class="w-32">
            <img src="https://funtasyaworld.com/img/logowhite.png" class="h-8 mt-4">
        </div>

        <!-- FUNTASYA -->
        <div class="flex flex-col gap-3">

            <h3 class="font-semibold text-lg">Funtasya</h3>

            <ul class="space-y-2 text-sm text-pink-100">
                <li><a href="https://funtasyaworld.com/berita/all" class="hover:underline">Blog</a></li>
                <li><a href="https://funtasyaworld.com/games/all" class="hover:underline">Permainan</a></li>
                <li><a href="https://funtasyaworld.com/tentang-kami" class="hover:underline">Tentang Kami</a></li>
            </ul>

            <!-- SOCIAL -->
            <div class="flex gap-4 mt-3">
                <a href="https://www.instagram.com/funtasya.world/">
                <img src="https://funtasyaworld.com/img/instagram.png" class="h-7 w-auto object-contain">
                </a>

                 <a href="https://www.tiktok.com/@funtasya.world">
                <img src="https://funtasyaworld.com/img/tiktok.png" class="h-7 w-auto object-contain">
                </a>

                 <a href="#">
                <img src="https://funtasyaworld.com/img/facebook.png" class="h-7 w-auto object-contain">
                </a>

                 <a href="https://www.youtube.com/@funtasyagames">
                <img src="https://funtasyaworld.com/img/youtube.png" class="h-7 w-auto object-contain">
                </a>

            </div>

        </div>

        <!-- PROGRAM -->
        <div>
            <h3 class="font-semibold mb-3">Program</h3>
            <ul class="space-y-2 text-sm text-pink-100">
                <li><a href="https://funtasyaworld.com/internship">Magang</a></li>
                <li><a href="https://funtasyaworld.com/karir">Karir</a></li>
                <li><a href="https://funtasyaworld.com/kolaborasi">Kolaborasi</a></li>
            </ul>
        </div>

        <!-- LAINNYA -->
        <div>
            <h3 class="font-semibold mb-3">Lainnya</h3>
            <ul class="space-y-2 text-sm text-pink-100">
                <li><a href="#">Press</a></li>
                <li><a href="#">Toko</a></li>
            </ul>
        </div>

    </div>

{{-- BOTTOM FOOTER --}}
{{-- Latar belakang putih dengan border tipis --}}
<div class="bg-white border-t border-gray-100 w-full">
    {{-- 
        Gunakan 'w-full' dan hapus 'max-w-7xl' agar konten bisa mepet ke pinggir. 
        Gunakan 'px-4' atau 'px-10' untuk memberikan sedikit ruang agar tulisan tidak menyentuh layar monitor.
    --}}
    <div class="w-full px-4 md:px-10 py-5 flex flex-col md:flex-row items-center justify-between gap-4">

        {{-- LEFT: TEXT COPYRIGHT --}}
        {{-- Mepet ke kiri --}}
        <p class="text-gray-400 text-[11px] font-medium tracking-tight order-2 md:order-1">
            © Funtasya World 2026 - PT. Digital Leap Technologies
        </p>

        {{-- RIGHT: TOMBOL KEBIJAKAN --}}
        {{-- Mepet ke kanan --}}
        <div class="flex gap-2 order-1 md:order-2">
            <a href="#" class="border border-[#F6A122] text-[#F6A122] px-5 py-1.5 rounded-full text-[11px] font-bold hover:bg-[#F6A122] hover:text-white transition-all">
                Terms Of Use
            </a>

            <a href="#" class="border border-[#F6A122] text-[#F6A122] px-5 py-1.5 rounded-full text-[11px] font-bold hover:bg-[#F6A122] hover:text-white transition-all">
                Privacy Policy
            </a>
        </div>

    </div>
</div>
</footer>