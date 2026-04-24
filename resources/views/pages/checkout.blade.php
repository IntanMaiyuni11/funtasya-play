@extends('layouts.checkout')

@section('content')
<style>
    @font-face {
        font-family: 'Gotham Rounded';
        src: local('Gotham Rounded Bold'), local('GothamRounded-Bold');
    }
    .font-gotham { font-family: 'Gotham Rounded', sans-serif; font-weight: 700; }
    .font-inter { font-family: 'Inter', sans-serif; }
    
    /* CSS untuk modal input */
    .modal-input {
        width: 100%;
        background-color: #FAECEC;
        border: 1px solid #FFDDDD;
        border-radius: 12px;
        padding: 12px;
        color: #000000;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        outline: none;
    }
    .modal-input:focus {
        border-color: #EC4899;
    }
    .modal-input::placeholder {
        color: #8F8F8F;
    }
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto px-6 py-10 font-inter bg-white min-h-screen" 
    x-data="{ 
        paymentSelected: '',
        showPopup: @if(!Auth::check() || !isset($address)) true @else false @endif,
        openAddressModal: false,
        openAddAddressModal: false,
        selectedAddressId: {{ $address->id ?? 'null' }},
        provinces: [],
        cities: [],
        districts: [],
        postalCodes: [],
        selectedProvince: '',
        selectedCity: '',
        selectedDistrict: '',
        
        init() {
            this.fetchProvinces();
            if(this.showPopup) setTimeout(() => this.showPopup = false, 3000);
        },
        
        async fetchProvinces() {
            try {
                const response = await fetch('/api/provinces');
                this.provinces = await response.json();
            } catch (error) {
                console.error('Error loading provinces:', error);
            }
        },
        
        async fetchCities() {
            this.cities = [];
            this.selectedCity = '';
            if (!this.selectedProvince) return;
            try {
                const response = await fetch(`/api/cities/${this.selectedProvince}`);
                this.cities = await response.json();
            } catch (error) {
                console.error('Gagal load kota:', error);
            }
        },
        
        async fetchDistricts() {
            this.districts = [];
            this.selectedDistrict = '';
            this.postalCodes = [];
            if (!this.selectedCity) return;
            const city = this.cities.find(c => c.name === this.selectedCity);
            if (city) {
                try {
                    const response = await fetch(`/api/districts/${city.id}`);
                    this.districts = await response.json();
                } catch (error) {
                    console.error('Gagal load kecamatan:', error);
                }
            }
        },
        
        async fetchPostalCodes() {
            this.postalCodes = [];
            if (!this.selectedDistrict) return;
            try {
                const response = await fetch(`/api/postalcodes?district_name=${this.selectedDistrict}`);
                this.postalCodes = await response.json();
            } catch (error) {
                console.error('Gagal load kode pos:', error);
            }
        },
        
        selectAddress(addressId) {
            this.selectedAddressId = addressId;
            document.getElementById('formChangeAddress').submit();
        },
        
        editCheckoutAddress(addressId) {
            window.location.href = `/profile#address-${addressId}`;
        }
    }" 
    x-init="init()">

    {{-- SECTION 1: Breadcrumbs --}}
    <nav class="mb-10">
        <ol class="flex text-[18px] text-[#000000] font-inter">
            <li><a href="{{ route('home') }}" class="hover:text-[#000000] transition-colors duration-200"">Home</a></li>
            <li class="mx-3 text-gray-400">></li>
            <li><a href="{{ route('cart.index') }}" class="hover:text-[#000000] transition-colors duration-200"">Cart</a></li>
            <li class="mx-3 text-gray-400">></li>
            <li class="font-bold">Checkout</li>
        </ol>
    </nav>

    {{-- KONTEN UTAMA: Flex Row untuk Desktop --}}
    <div class="flex flex-col lg:flex-row items-start gap-10">
        
        {{-- BAGIAN KIRI: Form & Pembayaran (65%) --}}
        <div class="w-full lg:w-[65%] space-y-12">
            
           {{-- SECTION 2: Alamat Pengiriman --}}
            <div class="relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-[18px] font-bold font-gotham text-[#000000] flex items-center gap-2.5">
                        <img src="{{ asset('images/Address.svg') }}" alt="Icon Alamat" class="w-6 h-6">
                        Alamat Pengiriman
                    </h2>

                    @if(Auth::check() && isset($address))
                        <button @click="openAddressModal = true" 
                                class="text-[16px] text-[#EC4899] font-inter hover:opacity-80 transition-opacity flex items-center gap-1">
                            Ubah Alamat 
                            <img src="https://img.icons8.com/ios-glyphs/15/ec4899/edit--v1.png" alt="edit">
                        </button>
                    @endif
                </div>

                @if(!Auth::check() || !isset($address))
                    {{-- Tampilan saat Alamat Kosong --}}
                    <div class="bg-[#FAECEC] p-8 rounded-[20px] relative border border-[#ec4899]/10">
                        <p class="text-[#000000] font-inter text-[16px] leading-relaxed">
                            <strong>Ups! Alamat pengiriman belum tersedia.</strong><br>
                            Silakan login atau tambahkan alamat pengiriman.
                        </p>

                        {{-- Tooltip Pop-up --}}
                        <div x-show="showPopup" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:leave="transition ease-in duration-300"
                            class="absolute -top-14 right-0 bg-white border border-gray-200 shadow-2xl px-5 py-3 rounded-2xl flex items-center gap-2 animate-bounce z-10">
                            <span class="text-[12px] font-inter font-medium text-[#000000]">Silakan login atau tambahkan alamat disini!</span>
                            <div class="absolute -bottom-2 right-8 w-4 h-4 bg-white border-b border-r border-gray-200 rotate-45"></div>
                        </div>
                    </div>
                @else
                    {{-- Tampilan saat Alamat Ada --}}
                    <div class="border border-[#d9d9d9] p-7 rounded-[30px] shadow-sm bg-white">
                        <div class="flex items-center gap-4 mb-3">
                            <span class="text-[16px] font-semibold text-[#000000] font-inter">{{ $address->recipient_name }}</span>
                            @if($address->is_primary)
                                <span class="bg-[#FAECEC] text-[#ec4899] text-[10px] font-inter font-bold px-2 py-1 rounded-[5px]  tracking-wider">Utama</span>
                            @endif
                        </div>
                        <p class="text-[#8f8f8f] text-[16px] font-inter">{{ $address->phone_number }}</p>
                        <p class="text-[#8f8f8f] text-[16px] font-inter mt-2 leading-relaxed">
                            {{ $address->full_address }}, {{ $address->district }}, {{ $address->city }}, {{ $address->postal_code }}
                        </p>
                    </div>
                @endif
            </div>

           {{-- SECTION: Opsi Pembayaran --}}
            <div class="space-y-8">
                <h2 class="text-[18px] font-bold font-gotham text-[#000000] uppercase tracking-wide flex items-center gap-2">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="25" height="25" fill="url(#pattern0_161_568)"/>
                        <defs>
                        <pattern id="pattern0_161_568" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_161_568" transform="scale(0.0111111)"/>
                        </pattern>
                        <image id="image0_161_568" width="90" height="90" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEdElEQVR4nO2cS4gdRRRAKyp+ED+4MtGFulRRdCG60FGYefdOBvwgI250IyL+/0bwM4g7UWIgzLu3MySYZTBudSIY0YUKLhRUjKCRoMbpe1/GOMG/ttSLMZl53Zk3Q9X7VN8Dd/Oopm6dV327urpp5wzDMAzDMAzDMAzDMGrK3pFtpyrww4L8kSIdUuSiXkGHBPhDBX7oK9x0ShTJitn5AvRp/wfLAxGC/Il3Enwmm2QulR10Zvty0e9B6cAGPRhM9OGa3O8B8UCGAH0QTLQiL/R7QDq4sRBSdNW/+bsAPyWjM+vagbTB/xaqvQ5JxBeNtGFpW/9bqPY6JBFddA68dmnbufWbzw3VXockoov2p39H24nmeaHa65BEfNGlpYCfDtVehyTii/YXN39BW8nFELtvPywRXbQFm2g10ZxcmGg00UVKYaIxEdGuZqiJ7g0mukfUQrTWIYdaDLILTHSPSFr0XKN5uSI/X5WDIL+YA1/Zi1ySFH0At1ymyLu7X8/SezlOXxEzp+REC/AdgvxH95L/n91/KjbvipVXUqJbkIEA/71SyUdl0z/a4Ftj5JaM6GJyx8mCvK+ivx8EeKN/702Q71PklwXpm4qZrQdG+azQ+SUjugXZbaXigDeWvXpVjEydpEDPVtTsR0Lnl4xoBco6+gHaU0xNnXC84wR4pqSEvBU8v1REC/KbJcJe72YJKEhvLw7eGjq/dEQDz3bOaP7a1243AKQjGnlrxcVtZ9k7Ib0mGdE63ryzqi8F/kWA3hDgexSbFy9Xt2OQjOh9k6+c5pdxlbIXr0QOKvI7AvSCQPP6YnLHiS4yyYj2KPDoqu4Kgb9T4Of2j20/3UUiKdFHZCvw/pXKPiycvo2155GcaI/ipjMV+RlB3rti2cj648T0RS4wSYo+lhZklwjQ/Yq0XZG/bO9nLC97pwtM8qKX8lNjyzkt5EkBfq3qJUkB+mv+pm1nu4DUTvSxzEPzAkH6rFR2g29wAam1aI82mleV5hh4uzQJ0Tlk1yrSx4uDdxeuWLPcsXLjzBll+fm97ZA5JiG6NTZ96WpnpWJ2S9mxoVceSYguXLGmbNPf3wHmyLdX3XL7OqzIcx35Ae1xgUlCtEeRHz3Ocs2vpzcL0JPa4AcU+aX2lwYq2ufj2d0uMGk9ygJ+v6q/bkOQd8XYdEpG9JE1sgK9u2rJwLP+4ugikJToo88C219U6Ky9lUHfC/K9MbdPkxO9qJSM882+NleXCXo1B57wf46LTLKia5dDLQbZBSa6R9RC9CBgonuEiU5GNNDPZR34j5q4miCjM+tKl5fAB4N14h8flXXSAnrM1QRBfqJcNH0RrBMF5vLThn71sss+4ZMKOfBaafDjCvxbReloBuvsv63ILm6D6xf5eroumOi2bORd/R6UDljEeDW4/RBUgfJ+D04HJGK9O9KmBXSNAEu/B6n9jvaEy652MZkfyy6scxkR4Fl/drtekSOPKBL55U3i3y5dEODP/eoi+IXPMAzDMAzDMAzDMAzDDSf/Aj0D66jHI2lrAAAAAElFTkSuQmCC"/>
                        </defs>
                    </svg>
                    Opsi Pembayaran
                </h2>

                {{-- Virtual Account --}}
                <div class="space-y-5">
                    <h3 class="text-[16px] font-inter font-regular text-[#000000]">Virtual Account</h3>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach([
                            ['id' => 'BRI', 'name' => 'Bank BRI', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg'],
                            ['id' => 'Mandiri', 'name' => 'Bank Mandiri', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg'],
                            ['id' => 'BCA', 'name' => 'Bank BCA', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg']
                        ] as $bank)
                        <label class="relative flex items-center justify-between p-5 border rounded-2xl cursor-pointer transition-all duration-300"
                            :class="paymentSelected == '{{ $bank['id'] }}' ? 'bg-[#FAECEC] border-[#ec4899]' : 'border-gray-200 bg-white hover:border-gray-300'">
                            <div class="flex items-center gap-4">
                                <img src="{{ $bank['img'] }}" class="h-6 w-auto object-contain">
                                <span class="font-inter text-[16px] font-medium" :class="paymentSelected == '{{ $bank['id'] }}' ? 'text-[#1e1e1e]' : 'text-[#8f8f8f]'">
                                    {{ $bank['name'] }}
                                </span>
                            </div>
                            <input type="radio" name="payment_method" value="{{ $bank['id'] }}" x-model="paymentSelected" class="hidden">
                        </label>
                        @endforeach
                    </div>
                </div>

            {{-- E-Wallet --}}
                <div class="space-y-5">
                    <h3 class="text-[16px] font-inter font-regular text-[#000000]">E-Wallet</h3>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach([
                            [
                                'id' => 'Gopay', 
                                'name' => 'Gopay', 
                                'img' => 'https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg'
                            ],
                            [
                                'id' => 'Shopee Pay', 
                                'name' => 'Shopee Pay', 
                                'img' => asset('images/sp.png') {{-- Mengarah ke public/images/sp.png --}}
                            ],
                            [
                                'id' => 'Dana', 
                                'name' => 'Dana', 
                                'img' => asset('images/dana.png') {{-- Mengarah ke public/images/dana.png --}}
                            ]
                        ] as $wallet)
                        <label class="relative flex items-center justify-between p-5 border rounded-2xl cursor-pointer transition-all duration-300"
                            :class="paymentSelected == '{{ $wallet['id'] }}' ? 'bg-[#FAECEC] border-[#ec4899]' : 'border-gray-200 bg-white hover:border-gray-300'">
                            <div class="flex items-center gap-4">
                                <div class="w-16 flex justify-center">
                                    <img src="{{ $wallet['img'] }}" class="h-6 w-auto object-contain" alt="{{ $wallet['name'] }}">
                                </div>
                                <span class="font-inter text-[16px] font-medium" :class="paymentSelected == '{{ $wallet['id'] }}' ? 'text-[#1e1e1e]' : 'text-[#8f8f8f]'">
                                    {{ $wallet['name'] }}
                                </span>
                            </div>
                            <input type="radio" name="payment_method" value="{{ $wallet['id'] }}" x-model="paymentSelected" class="hidden">
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- QR Code --}}
                <div class="space-y-5">
                    <h3 class="text-[16px] font-inter font-regular text-[#000000]">QR Code</h3>
                    <label class="relative flex items-center justify-between p-5 border rounded-2xl cursor-pointer transition-all duration-300"
                        :class="paymentSelected == 'QRIS' ? 'bg-[#FAECEC] border-[#ec4899]' : 'border-gray-200 bg-white hover:border-gray-300'">
                        <div class="flex items-center gap-4">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-6 w-auto object-contain">
                            <span class="font-inter text-[16px] font-medium" :class="paymentSelected == 'QRIS' ? 'text-[#1e1e1e]' : 'text-[#8f8f8f]'">QRIS</span>
                        </div>
                        <input type="radio" name="payment_method" value="QRIS" x-model="paymentSelected" class="hidden">
                    </label>
                </div>
            </div>
        </div>

       {{-- BAGIAN KANAN: Ringkasan Pesanan --}}
            <div class="lg:w-[35%]">
                <div class="border border-gray-100 rounded-[30px] overflow-hidden shadow-sm sticky top-10">
                    <div class="bg-[#ec4899] py-5 text-center">
                        <h2 class="text-white font-bold font-inter text-[16px]">Ringkasan Pesanan</h2>
                    </div>

                    <div class="p-8 space-y-6 bg-white">

                    {{-- List Produk yang sedang di Checkout --}}
                        <div class="space-y-4 max-h-60 overflow-y-auto pr-2 no-scrollbar">
                            @if(isset($isInstant) && $isInstant)
                                {{-- MODE: BELI SEKARANG (Tampil 1 Produk) --}}
                                <div class="flex gap-4 items-center">
                                    <img src="{{ asset('products/' . $product->image) }}" class="w-16 h-16 rounded-xl object-contain border">
                                    <div class="flex-1">
                                        <p class="font-bold text-sm line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $quantity }} x Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        @if(isset($variation) && $variation)
                                            <span class="text-[10px] bg-pink-100 text-pink-600 px-2 py-0.5 rounded-full font-bold uppercase">
                                                {{ $variation }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                {{-- MODE: KERANJANG (Tampil Semua Isi Cart) --}}
                                @isset($cart)
                                    @foreach($cart as $item)
                                        <div class="flex items-center gap-4 border-b pb-4">
                                            <img src="{{ asset('products/' . $item->product->image) }}" class="w-20 rounded-xl">
                                            <div>
                                                <h4 class="font-bold">{{ $item->product->name }}</h4>
                                                <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price) }}</p>
                                                @if($item->variation)
                                                    <span class="text-xs bg-pink-100 text-pink-500 px-2 py-1 rounded">
                                                        Tema: {{ $item->variation }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            @endif
                        </div>

            <div class="border-b border-[#D9D9D9] w-full"></div>

            {{-- Bagian Perhitungan Biaya --}}
            <div class="space-y-3 mb-6 border-b pb-6">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ $cartCount }} Produk)</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Ongkir</span>
                        {{-- ID 'shipping-display' untuk diupdate via JS --}}
                        <span id="shipping-display" class="font-medium text-green-600">Gratis</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Estimasi Pajak (PPN)</span>
                        <span>Rp {{ number_format(3000, 0, ',', '.') }}</span>
                    </div>
            </div>

            <div class="border-b border-[#D9D9D9] w-full"></div>

            {{-- Kode Promo --}}
            <div>
                <p class="text-[#8F8F8F] text-[14px] font-inter mb-3">Punya kode promo?</p>
                <div class="flex gap-3">
                    <input type="text" placeholder="Masukkan kode promo" 
                        class="flex-1 px-4 py-3 border rounded-xl text-sm focus:outline-none placeholder:text-[#B4B4B4] transition-all border-[#ec4899]">
                    <button class="bg-[#6F5CE4] text-white px-6 py-3 rounded-xl text-sm font-bold hover:brightness-110 transition-all">
                        Gunakan
                    </button>
                </div>
            </div>

            <div class="border-t border-dashed border-[#d9d9d9] my-6"></div>

           {{-- Total Pembayaran --}}
            <div class="flex justify-between items-center mb-10">
                <span class="text-[18px] font-inter font-semibold text-[#000000]">Total Pembayaran</span>
                {{-- ID 'total-display' untuk diupdate via JS --}}
                <span id="total-display" class="text-[18px] font-inter font-semibold text-[#ec4899]">
                    Rp {{ number_format($subtotal + 3000, 0, ',', '.') }}
                </span>
            </div>

           {{-- Form Process Checkout --}}
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                {{-- Input Hidden yang WAJIB ada untuk Controller --}}
                <input type="hidden" name="payment_method" x-model="paymentSelected">
                <input type="hidden" id="hidden-total-input" name="total_amount" value="{{ $subtotal + 3000 }}">
                <input type="hidden" id="hidden-shipping-input" name="shipping_price" value="0">
                <input type="hidden" name="address_id" value="{{ $address->id ?? '' }}">
                
                {{-- Data untuk Instant Buy --}}
                @if(isset($isInstant) && $isInstant)
                    <input type="hidden" name="is_instant" value="1">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="{{ $quantity }}">
                    <input type="hidden" name="variation" value="{{ $variation }}">
                @endif

                @if(Auth::check() && isset($address))
                    <button type="submit" 
                        :disabled="!paymentSelected"
                        :class="!paymentSelected ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#ec4899] hover:scale-[1.02]'"
                        class="w-full text-white py-5 rounded-2xl font-bold text-[18px] transition-all">
                        Pesan Sekarang
                    </button>
                    <p x-show="!paymentSelected" class="text-center text-xs text-red-500 mt-2 italic">
                        *Silakan pilih metode pembayaran
                    </p>
                @else
                    <button disabled type="button" class="w-full bg-gray-300 text-white py-5 rounded-2xl font-bold text-[18px] cursor-not-allowed">
                        Alamat Belum Ada
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>

    {{-- MODAL PILIH ALAMAT --}}
<div x-show="openAddressModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
    <div class="bg-white rounded-[30px] w-full max-w-[500px] p-6 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="openAddressModal = false">
        
        {{-- Header Modal --}}
        <div class="flex justify-between items-center mb-6 pb-3 border-b border-gray-100">
            <h2 class="text-[20px] font-bold font-gotham text-[#000000]">Pilih Alamat</h2>
            <button @click="openAddressModal = false" class="hover:opacity-70 transition-all">
                <img src="{{ asset('images/silang.svg') }}" class="w-5 h-5" alt="Close">
            </button>
        </div>

        {{-- Subtitle --}}
        <div class="mb-4">
            <h3 class="text-[14px] font-semibold font-inter text-[#000000]">Alamat</h3>
        </div>

        {{-- Form untuk mengganti alamat --}}
        <form id="formChangeAddress" action="{{ route('checkout.changeAddress') }}" method="POST">
            @csrf
            <input type="hidden" name="address_id" x-model="selectedAddressId">
            
            {{-- List Alamat --}}
            <div class="space-y-4 mb-6">
                @forelse($addresses as $addr)
                    <div class="border border-[#FFDDDD] rounded-[20px] p-4 hover:border-[#EC4899] transition-all cursor-pointer 
                                {{ $address && $address->id == $addr->id ? 'border-[#EC4899] bg-pink-50/30' : '' }}"
                         @click="selectAddress({{ $addr->id }})">
                        
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-[16px] font-bold font-inter text-[#000000]">{{ $addr->recipient_name }}</h4>
                                <p class="text-[12px] font-inter text-[#64748B]">{{ $addr->phone_number }}</p>
                            </div>
                            <button type="button" 
                                    @click.stop="editCheckoutAddress({{ $addr->id }})" 
                                    class="text-[12px] font-semibold font-inter text-[#EC4899] hover:opacity-70 transition-all">
                                Ubah
                            </button>
                        </div>
                        
                        <p class="text-[12px] font-inter text-[#000000] leading-relaxed mt-2">
                            {{ $addr->full_address }}, {{ $addr->district }}, {{ $addr->city }}, {{ $addr->postal_code }}
                        </p>
                        
                        <div class="flex gap-3 mt-3">
                            @if($addr->is_primary)
                                <span class="text-[10px] font-semibold font-inter text-[#EC4899] bg-[#FAECEC] px-3 py-1 rounded-full">Utama</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-[14px] font-inter text-[#64748B]">Belum ada alamat tersimpan</p>
                    </div>
                @endforelse
            </div>
        </form>

        {{-- Tombol Tambah Alamat Baru --}}
        <button @click="openAddAddressModal = true" 
                class="w-full py-3 rounded-[15px] border-2 border-dashed border-[#EC4899] text-[#EC4899] font-semibold font-inter text-[14px] hover:bg-pink-50 transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Alamat Baru
        </button>
    </div>
</div>

            {{-- MODAL TAMBAH ALAMAT (Checkout) --}}
            <div x-show="openAddAddressModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white rounded-[30px] w-full max-w-[500px] p-6 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="openAddAddressModal = false">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-[20px] font-bold font-gotham text-[#000000]">Tambah Alamat Baru</h2>
                        <button @click="openAddAddressModal = false" class="hover:opacity-70">
                            <img src="{{ asset('images/silang.svg') }}" class="w-5 h-5" alt="Close">
                        </button>
                    </div>

                    <form action="{{ route('addresses.store') }}" method="POST" id="formAddAddress">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Nama Penerima</label>
                                <input type="text" name="recipient_name" placeholder="Nama Penerima" class="modal-input" required>
                            </div>
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Nomor HP</label>
                                <input type="text" name="phone_number" placeholder="Contoh: 0812345678" class="modal-input" required>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Provinsi</label>
                                <select name="province" x-model="selectedProvince" @change="fetchCities" class="modal-input" required>
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="p in provinces" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Kota/Kabupaten</label>
                                <select name="city" x-model="selectedCity" @change="fetchDistricts" class="modal-input" required>
                                    <option value="">Pilih Kota</option>
                                    <template x-for="c in cities" :key="c.id">
                                        <option :value="c.name" x-text="c.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Kecamatan</label>
                                <select name="district" x-model="selectedDistrict" @change="fetchPostalCodes" class="modal-input" required>
                                    <option value="">Pilih Kecamatan</option>
                                    <template x-for="d in districts" :key="d.name">
                                        <option :value="d.name" x-text="d.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[#000000] font-semibold text-[12px] mb-2">Kode Pos</label>
                                <select name="postal_code" class="modal-input" required>
                                    <option value="">Pilih Kode Pos</option>
                                    <template x-for="k in postalCodes" :key="k.id">
                                        <option :value="k.kodepos" x-text="k.kodepos + ' (' + k.kelurahan + ')'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-[#000000] font-semibold text-[12px] mb-2">Alamat Lengkap</label>
                            <textarea name="full_address" rows="3" class="modal-input resize-none" placeholder="Nama jalan, RT/RW, nomor rumah" required></textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-[#EC4899] text-white font-bold py-3 rounded-xl hover:opacity-90">Simpan Alamat</button>
                            <button type="button" @click="openAddAddressModal = false" class="w-full bg-white border border-[#FFDDDD] text-[#8F8F8F] py-3 rounded-xl">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ambil nama kota dari element alamat (sesuaikan selectornya dengan HTML-mu)
        // Pastikan ini mengambil teks seperti "KOTA SURABAYA"
        let cityName = "{{ $address->city ?? '' }}"; 

        if (cityName !== "") {
            $.ajax({
                url: "{{ route('get-shipping-cost') }}", 
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    city_name: cityName
                },
                success: function(response) {
                    if (response.success) {
                        let subtotal = {{ $subtotal }};
                        let pajak = 3000;
                        let ongkir = parseInt(response.cost);
                        let totalAkhir = subtotal + pajak + ongkir;

                        // 1. Update Tampilan (UI)
                        $('#shipping-display').text('Rp ' + ongkir.toLocaleString('id-ID'));
                        $('#total-display').text('Rp ' + totalAkhir.toLocaleString('id-ID'));

                        // 2. Update Input Hidden (Agar data yang terkirim ke Midtrans benar)
                        $('#hidden-shipping-input').val(ongkir);
                        $('#hidden-total-input').val(totalAkhir);
                    }
                },
                error: function() {
                    console.log("Gagal mengambil data ongkir.");
                }
            });
        }
    });
</script>

@endsection
