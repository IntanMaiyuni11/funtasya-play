@extends('layouts.main')

@section('content')

        {{-- SECTION 1: HERO & CAROUSEL --}}
        <section class="mt-8">
            <div class="max-w-7xl mx-auto px-6 text-center">
                {{-- Judul Utama dengan warna Pink Khas Funtasya --}}
                <h1 class="text-2xl md:text-3xl font-bold text-[#E5488E] leading-tight">
                    Ayo Masuk ke Dunia Main & Belajar yang Seru!
                </h1>
                <p class="mt-2 text-gray-600 text-[15px]">
                    Temukan buku cerita, puzzle, dan permainan edukatif yang bikin si kecil terus penasaran.
                </p>

                {{-- Container Carousel dengan sudut melengkung lebar (40px) --}}
                <div class="mt-8 relative max-w-5xl mx-auto overflow-hidden rounded-[40px] shadow-sm border border-gray-100">
                    <div id="carousel" class="flex transition-transform duration-700 ease-in-out">
                        @for ($i = 0; $i < 3; $i++)
                        <div class="min-w-full bg-[#FFF9F0]">
                            {{-- Banner Promo --}}
                            <img src="{{ asset('images/banner.png') }}" 
                                class="w-full h-auto object-cover" 
                                alt="Banner Promo Digital Product">
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION 2: KEUNGGULAN (VALUE PROPOSITION) --}}
        <section class="mt-16">
            <div class="max-w-6xl mx-auto px-6 text-center">
                <h2 class="text-[#64748B] font-bold text-[22px] mb-10">
                    Kenapa FuntasyaPlay Jadi Pilihan Orang Tua?
                </h2>

                <div class="border-2 border-[#64748B] rounded-[20px] bg-white grid md:grid-cols-3 overflow-hidden">
                    
                    {{-- Poin 1 --}}
                    <div class="p-10 flex flex-col items-center text-center">
                        <img src="{{ asset('images/K-1.png') }}" class="w-10 h-10 mb-5 object-contain" alt="Ramah Anak">
                        <h3 class="text-[#e21b70] font-bold text-[17px] mb-2">100% Ramah Petualang Cilik</h3>
                        <p class="text-[#374151] text-[13px] leading-relaxed">
                            Aman dari monster, debu sihir, dan bahan berbahaya.
                        </p>
                    </div>

                    {{-- Poin 2: Bagian tengah dengan garis pembatas --}}
                    <div class="relative py-10 flex flex-col items-center text-center">
                        
                        {{-- Garis Pembatas ATAS (Muncul di HP/Tablet, Hilang di Desktop) --}}
                        <div class="block md:hidden absolute top-0 left-10 right-10 h-[2px] bg-[#64748B]"></div>
                        
                        {{-- Garis Pembatas Kiri (Hilang di HP/Tablet, Muncul di Desktop) --}}
                        <div class="hidden md:block absolute left-0 top-10 bottom-10 w-[2px] bg-[#64748B]"></div>
                        
                        <img src="{{ asset('images/k-2.png') }}" class="w-10 h-10 mb-5 object-contain" alt="Aman Teruji">
                        <h3 class="text-[#e21b70] font-bold text-[17px] mb-2">Aman & Teruji</h3>
                        <p class="text-[#374151] text-[13px] leading-relaxed px-6">
                            Material ramah anak, warna cerah, dan aman.
                        </p>

                        {{-- Garis Pembatas Kanan (Hilang di HP/Tablet, Muncul di Desktop) --}}
                        <div class="hidden md:block absolute right-0 top-10 bottom-10 w-[2px] bg-[#64748B]"></div>

                        {{-- Garis Pembatas BAWAH (Muncul di HP/Tablet, Hilang di Desktop) --}}
                        <div class="block md:hidden absolute bottom-0 left-10 right-10 h-[2px] bg-[#64748B]"></div>
                    </div>

                    {{-- Poin 3 --}}
                    <div class="p-10 flex flex-col items-center text-center">
                        <img src="{{ asset('images/K-3.png') }}" class="w-10 h-10 mb-5 object-contain" alt="Belajar Bermain">
                        <h3 class="text-[#e21b70] font-bold text-[17px] mb-2">Belajar Lewat Bermain</h3>
                        <p class="text-[#374151] text-[13px] leading-relaxed">
                            Setiap produk dirancang untuk bikin rasa ingin tahu terus hidup.
                        </p>
                    </div>

                </div>
            </div>
        </section>

{{-- SECTION 3: KATALOG PRODUK --}}
<section class="max-w-7xl mx-auto px-4 py-6 md:py-10">
    <h2 class="text-center text-xl md:text-3xl font-black text-[#444444] mb-6 md:mb-10 px-4">
        Petualangan Apa yang Mau Dimulai Hari Ini?
    </h2>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
        @foreach($products as $product)
        {{-- Scope Alpine.js per Produk --}}
        <div class="group relative" x-data="{ 
            showQuickModal: false,
            activeImg: '{{ asset("products/" . $product->image) }}',
            selectedVar: '',
            price: {{ (int) $product->price }},
            variations: @js($product->variations ?? []),
            
            selectVar(v) {
                this.selectedVar = typeof v === 'object' ? v.nama : v;
                if(typeof v === 'object' && v.harga) {
                    this.price = v.harga;
                }
            }
        }">
            {{-- Link Utama ke Detail Produk --}}
            <a href="{{ route('product.show', $product->slug) }}" class="block">
                <div class="bg-[#D6F7FE] rounded-[20px] lg:rounded-[30px] p-4 lg:p-6 relative flex flex-col items-center transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-2 min-h-[220px] lg:min-h-[340px]">
                    
                    <div class="w-full h-28 lg:h-44 flex items-center justify-center mb-2 lg:mb-4">
                        <img :src="activeImg" 
                             class="max-w-[85%] max-h-full object-contain drop-shadow-md transition-transform duration-500 group-hover:scale-110 group-hover:rotate-2" 
                             alt="{{ $product->name }}">
                    </div>

                    <h3 class="text-[10px] sm:text-[11px] md:text-[13px] font-black text-[#444444] text-center leading-tight px-1 mb-10 md:mb-14">
                        {{ Str::limit($product->name, 35) }}
                    </h3>
                </div>
            </a>

            {{-- 3. Footer (Tombol Aksi - Trigger Modal) --}}
            <div class="absolute bottom-4 md:bottom-6 left-2 right-2 md:left-5 md:right-5 flex items-center gap-1.5 z-10">
                
                {{-- Tombol Keranjang --}}
                <button @click="showQuickModal = true" type="button" 
                        title="Tambah ke Keranjang"
                        class="bg-[#ED4D9E] w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-[10px] md:rounded-[14px] hover:bg-pink-600 hover:rotate-12 transition-all shadow-sm">
                    <i class="fa-solid fa-bag-shopping text-white text-[12px] md:text-[16px]"></i>
                </button>
                
                {{-- Tombol Harga --}}
                <button @click="showQuickModal = true" type="button" 
                        class="flex-1 bg-[#2D68F8] h-8 md:h-10 flex flex-col items-center justify-center text-[#ffffff] font-bold rounded-full hover:bg-blue-700 transition-all shadow-sm group/btn"
                        style="font-family: 'Roboto', sans-serif;">
                    <span class="truncate px-1 text-[9px] md:text-[11px]">
                        @if($product->price_max)
                            Rp {{ number_format($product->price, 0, ',', '.') }} - {{ number_format($product->price_max, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </span>
                </button>
            </div>

            {{-- POP UP SELEKSI TEMA (Modal) --}}
            <div x-show="showQuickModal" 
                 class="fixed inset-0 z-[100] flex items-end justify-center bg-black/50"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display: none;">
                
                <div @click.away="showQuickModal = false" 
                     class="bg-white w-full max-w-md rounded-t-[25px] p-6 shadow-2xl relative">
                    
                    {{-- Close Button --}}
                    <button @click="showQuickModal = false" class="absolute top-4 right-4 text-gray-400">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>

                    {{-- Info Singkat --}}
                    <div class="flex gap-4 mb-5 border-b pb-4">
                        <img :src="activeImg" class="w-16 h-16 object-contain bg-blue-50 rounded-lg">
                        <div class="flex flex-col justify-center">
                            <p class="text-lg font-bold text-[#EC4899]">Rp <span x-text="new Intl.NumberFormat('id-ID').format(price)"></span></p>
                            <p class="text-[10px] text-gray-400">Pilih variasi untuk melihat harga tepat</p>
                        </div>
                    </div>

                    {{-- List Variasi --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-xs mb-3 text-gray-500 uppercase tracking-widest">Pilih Tema</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variations" :key="typeof v === 'object' ? v.nama : v">
                                <button @click="selectVar(v)" 
                                        type="button"
                                        :class="selectedVar === (typeof v === 'object' ? v.nama : v) ? 'border-[#EC4899] bg-pink-50 text-[#EC4899]' : 'border-gray-200 text-gray-600'"
                                        class="px-3 py-2 border-2 rounded-xl text-xs font-bold transition-all">
                                    <span x-text="typeof v === 'object' ? v.nama : v"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Form Submit --}}
                    <form action="{{ route('cart.add') }}" method="POST" class="grid grid-cols-2 gap-3" x-ref="productForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="variation" :value="selectedVar">

                        {{-- Tombol Tambah Keranjang --}}
                        <button type="submit" 
                                class="border-2 border-[#EC4899] text-[#EC4899] py-3 rounded-xl font-bold text-xs hover:bg-pink-50 transition-colors">
                            + Keranjang
                        </button>
                        
                        {{-- Tombol Beli Sekarang --}}
                        <button type="button"
                            @click="
                                if (variations.length > 0 && !selectedVar) { 
                                    alert('Silakan pilih tema terlebih dahulu!'); 
                                } else {
                                    window.location.href = '{{ route('checkout.index') }}' + 
                                        '?product_id={{ $product->id ?? $item->id }}' + 
                                        '&quantity=1' + 
                                        '&variation=' + encodeURIComponent(selectedVar) + 
                                        '&direct=1';
                                }
                            "
                            class="bg-[#EC4899] text-white py-3 rounded-xl font-bold text-xs shadow-lg shadow-pink-200 hover:brightness-110 transition-all">
                            Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

    {{-- Tombol Lihat Selengkapnya --}}
    <div class="text-center mt-12">
        <a href="{{ route('catalog') }}" 
           class="inline-flex bg-[#FFA122] hover:bg-[#e0911d] text-white px-10 py-3.5 rounded-full font-black shadow-lg items-center gap-3 transition-all transform hover:scale-105 active:scale-95">
            Lihat Selengkapnya <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
    </div>
</section>

        {{-- SECTION 4: TESTIMONI ORANG TUA --}}
        <section class="py-12 bg-white overflow-hidden">
            <div class="max-w-6xl mx-auto px-6">
                <h2 class="text-center text-[#556385] font-bold text-[24px] mb-10">
                    Apa Kata Para Orang Tua?
                </h2>
                
                @if(count($reviews) > 0)
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach($reviews as $review)
                            <div class="swiper-slide !h-auto"> {{-- !h-auto agar kartu sama tinggi --}}
                                <div class="relative p-8 rounded-[35px] text-white shadow-lg flex flex-col justify-between h-full" 
                                    style="background-color: {{ $review->card_color ?? '#E5488E' }}; min-height: 250px;">
                                    
                                    {{-- Foto Profil --}}
                                    <div class="absolute top-6 right-6">
                                        <div class="w-16 h-16 rounded-full border-4 border-white/30 overflow-hidden shadow-md">
                                            <img src="{{ asset('avatars/' . ($review->user_avatar ?? 'default-user.png')) }}"
                                                class="w-full h-full object-cover" alt="User">
                                        </div>
                                    </div>

                                    {{-- Konten --}}
                                    <div class="mt-12">
                                        <div class="text-yellow-400 mb-4 flex gap-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-5 h-5 {{ $i < ($review->rating ?? 5) ? 'fill-current' : 'fill-white/30' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-[14px] md:text-[15px] leading-relaxed font-medium mb-4">
                                            "{{ $review->comment }}"
                                        </p>
                                    </div>

                                    <p class="text-[12px] font-bold italic opacity-90">
                                        - {{ $review->user_name_display }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                @else
                    <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-3xl">
                        <p class="text-gray-400">Belum ada testimoni di database.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- SECTION 5: BACKGROUND KUCING (TRANSISI FOOTER) --}}
        <section class="w-full relative z-0 overflow-hidden bg-white section-kucing"> 
            <img src="{{ asset('images/kucing-background.png') }}" 
                class="w-full h-auto block img-kucing" 
                alt="Funtasya Illustration">
        </section>
@endsection


@push('addon-script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalReviews = document.querySelectorAll('.swiper-slide').length;

        if (totalReviews > 0) {
            const swiper = new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 25,
                loop: totalReviews > 3,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 1 },
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                },
            });
        }
    });
</script>

<style>
    /* 1. Beri ruang super lega di bawah section agar kartu punya tempat */
    section.py-12 {
        padding-bottom: 200px !important; 
        position: relative;
        z-index: 10;
        overflow: visible !important;
    }

    /* 2. Swiper Container Fix */
    .mySwiper {
        width: 100%;
        min-height: 450px; 
        padding: 20px 0 100px 0 !important; 
        display: block !important;
    }

    /* 3. Kartu Testimoni */
    .swiper-slide {
        height: auto !important;
        display: flex;
        align-items: stretch;
    }

    /* 4. Custom Pagination */
    .swiper-pagination {
        bottom: 50px !important; 
    }

    .swiper-pagination-bullet-active {
        background: #E5488E !important;
        width: 20px !important;
        border-radius: 10px !important;
    }

    /* 5. Pengaturan Gambar Kucing*/
    .section-kucing {
        position: relative;
        z-index: 20; 
        display: block;
        pointer-events: none; 
    }

    .img-kucing {
        display: block;
        width: 100%;
        margin-bottom: -1px !important; 
    }


    /* Layar Laptop/Desktop Besar */
    @media (min-width: 1024px) {
        .section-kucing {
            margin-top: -380px !important; 
        }
    }

    /* Layar Tablet */
    @media (min-width: 768px) and (max-width: 1023px) 
{
        .section-kucing {
            margin-top: -280px !important;
        }
    }

    /* Layar HP  */
    @media (max-width: 767px) {
        .section-kucing {
            margin-top: -150px !important; 
        }
        .mySwiper {
            min-height: 480px; 
        }
        section.py-12 {
            padding-bottom: 250px !important; 
        }
    }
</style>
@endpush