@extends('layouts.main')

@section('content')
<style>
    /* CSS Tambahan untuk Input Modal */
    .modal-input {
        width: 100%;
        background-color: #FAECEC;
        border: 1px solid #FFDDDD;
        border-radius: 12px;
        padding: 12px;
        color: #444444;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
    }
    .modal-input:focus {
        border-color: #EC4899;
    }
    [x-cloak] { display: none !important; }
</style>

        {{-- Pastikan x-data membungkus bagian yang menggunakan modal --}}
        <div x-data="addressApp()" class="bg-gray-50 min-h-screen pb-20">
            <div class="max-w-7xl mx-auto px-6 pt-10">

                {{-- Header Profil --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-10 bg-white p-6 rounded-[30px] shadow-sm">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center overflow-hidden border-4 border-white shadow-sm">
                            @if(auth()->user()->avatar)
                                {{-- Tampilkan foto yang diupload customer --}}
                              <img src="{{ asset(auth()->user()->avatar ?? 'avatars/default-user.png') }}" class="w-full h-full object-cover">
                            @else
                                {{-- Tampilkan default avatar dari UI Avatars --}}
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ec4899&color=fff&bold=true&length=2&size=80" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-[#222222]">Profil Saya</h1>
                            <p class="text-gray-500 font-medium">Kelola informasi profil, alamat, dan pesanan Anda.</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-4 md:mt-0">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-2 border-2 border-[#FFDDDD] bg-[#FAECEC] text-[#EC4899] px-6 py-2 rounded-[15px] font-bold hover:opacity-80 transition-all shadow-sm">
                            <img src="{{ asset('images/Logout.svg') }}" alt="Icon Keluar" class="w-6 h-6"> 
                            <span class="text-[18px]">Keluar</span>
                        </button>
                    </form>
                </div>

                <div class="space-y-10">

                    {{-- Bagian 1: Informasi Akun --}}
                    <section>
                        <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/icon profile.svg') }}" alt="Icon Profile" class="w-7 h-7 object-contain">
                            <h2 class="text-xl font-bold text-[#222222]">Informasi Akun</h2>
                        </div>
                    <div class="bg-white p-8 rounded-[35px] shadow-sm border border-[#D9D9D9]">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="bg-[#FAECEC] p-5 rounded-[20px] border border-[#FFDDDD]">
                                    <label class="block text-[12px] uppercase font-medium text-[#EC4899] mb-1 font-inter">USERNAME</label>
                                    <p class="text-[16px] font-semibold text-[#000000] font-inter">{{ $user->name }}</p>
                                </div>

                                <div class="bg-[#FFF7ED] p-5 rounded-[20px] border border-[#FFEED9]">
                                    <label class="block text-[12px] uppercase font-medium text-[#F8A410] mb-1 font-inter">EMAIL</label>
                                    <p class="text-[16px] font-semibold text-[#000000] font-inter">{{ $user->email }}</p>
                                </div>

                                <div class="bg-[#EEF4FF] p-5 rounded-[20px] border border-[#DDE9FF]">
                                    <label class="block text-[12px] uppercase font-medium text-[#0093F5] mb-1 font-inter">NOMOR HP</label>
                                    <p class="text-[16px] font-semibold text-[#000000] font-inter">{{ $user->phone ?? '-' }}</p>
                                </div>
                            </div>

                                {{-- TOMBOL EDIT PROFILE DAN UBAH PASSWORD --}}
                                <div class="flex gap-4">
                                <button @click="openEditProfileModal()" 
                                        class="flex items-center gap-2 bg-[#EC4899] text-white px-6 py-2.5 rounded-[15px] font-bold shadow-sm hover:opacity-90 transition-all">
                                    <i class="fa-solid fa-pencil text-sm"></i>
                                    Edit Profil
                                </button>

                                <button @click="openUbahPasswordModal()" 
                                        class="flex items-center gap-2 border-2 border-gray-200 text-[#444444] px-6 py-2.5 rounded-[15px] font-bold hover:bg-gray-50 transition-all">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                    Ubah Password
                                </button>
                            </div>
                            </div>
                    </section>

                    {{-- Bagian 2: Alamat Pengiriman --}}
                    <section class="mt-10">
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('images/Address.svg') }}" alt="Icon Alamat" class="w-7 h-7 object-contain">
                            <h2 class="text-xl font-bold text-[#222222]">Alamat Pengiriman</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Looping alamat dari tabel addresses --}}
                            @forelse($addresses as $address)
                                <div class="bg-white p-6 rounded-[40px] border border-[#D9D9D9] relative">
                                    
                                    {{-- Badge Utama --}}
                                    @if($address->is_primary)
                                        <div class="absolute top-6 right-6 bg-[#FAECEC] text-[#EC4899] text-[12px] font-semibold px-3 py-1 rounded-md uppercase">
                                            Utama
                                        </div>
                                    @endif
                                    
                                    <div class="mb-6">
                                        <h3 class="text-[18px] font-bold text-[#222222]">{{ $address->recipient_name }}</h3>
                                        <p class="text-[#64748B] text-[14px] mt-1">{{ $address->phone_number }}</p>
                                        <p class="text-[#64748B] text-[14px] leading-relaxed mt-2">
                                            {{ $address->full_address }}, {{ $address->city }}, {{ $address->postal_code }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex gap-3">

                                        {{-- Tombol Edit --}}
                                        <button @click="editAddress({{ $address->id }})" 
                                                class="flex items-center gap-2 bg-[#EC4899] text-white px-5 py-2 rounded-[15px] font-bold text-[14px] hover:opacity-90 transition-all">
                                            <i class="fa-solid fa-pencil text-[12px]"></i>
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-2 border border-[#D9D9D9] text-[#444444] px-5 py-2 rounded-[15px] font-bold text-[14px] hover:bg-gray-50 transition-all">
                                            <i class="fa-solid fa-trash-can text-[12px]"></i>
                                            Hapus
                                        </button>
                                    </form>
                                    </div>
                                </div>
                            @empty

                            {{-- Tombol saat alamat kosong --}}
                                <button @click="openModal = true" class="border-2 border-dashed border-[#D9D9D9] rounded-[35px] p-10 flex flex-col items-center justify-center hover:border-[#FFDDDD] transition-all group">
                                    <i class="fa-solid fa-plus text-[#94A3B8] text-2xl mb-2 group-hover:text-[#EC4899]"></i>
                                    <span class="text-[#94A3B8] font-bold group-hover:text-[#EC4899]">Tambah Alamat Baru</span>
                                </button>
                            @endforelse
                                {{--  Tombol Tambah Alamat Lain --}}
                            @if($addresses->count() > 0)
                                <button @click="openModal = true" class="border-2 border-dashed border-[#EC4899] rounded-[40px] p-8 flex flex-col items-center justify-center bg-white hover:bg-pink-50 transition-all group min-h-[220px]">
                                    <div class="w-12 h-12 bg-[#EC4899] text-white rounded-full flex items-center justify-center mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-plus text-xl"></i>
                                    </div>
                                    <span class="text-[#EC4899] font-bold text-[16px]">Tambah Alamat Baru</span>
                                </button>
                            @endif
                        </div>
                    </section>

                {{-- Bagian 3: Riwayat Pesanan --}}
                <section class="mt-10">
                    <div class="flex items-center gap-3 mb-6">
                        {{-- Ikon Shopping Bag --}}
                        <img src="{{ asset('images/Shopping Bag.svg') }}" alt="Icon Riwayat" class="w-7 h-7">
                        <h2 class="text-xl font-bold text-[#222222]">Riwayat Pesanan</h2>
                    </div>

                    <div class="space-y-4">
                        @forelse($orders as $order)
                        <div class="bg-white p-5 rounded-[25px] border border-[#D9D9D9] flex flex-col md:flex-row justify-between items-center gap-4 transition-all hover:shadow-sm">
                            <div class="flex items-center gap-5 w-full">
                                <div class="w-20 h-20 bg-[#F7F0F0] rounded-[22px] flex items-center justify-center">
                                    <img src="{{ asset('images/Big Parcel.svg') }}" alt="Order Icon" class="w-12 h-12">
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-[16px] text-[#000000]">#{{ $order->order_code }}</h4>
                                    <div class="flex items-center gap-2 font-light text-[12px] text-[#000000] mt-0.5">
                                        <span>{{ $order->created_at->format('d F Y') }}</span>
                                        <span class="text-[8px]">●</span>
                                        <span>{{ $order->items_count }} Item</span>
                                    </div>
                                    <p class="text-[#EC4899] font-semibold text-[16px] mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                                {{-- Status Selesai --}}
                                @if($order->status == 'complete')
                                    <span class="bg-[#EEF9F1] text-[#78C28D] px-6 py-2.5 rounded-xl text-[14px] font-bold">
                                        Selesai
                                    </span>

                                {{-- Status Menunggu Pembayaran --}}
                                @elseif($order->status == 'process')
                                    <span class="bg-[#FFF8ED] text-[#E5A94D] px-6 py-2.5 rounded-xl text-[14px] font-bold">
                                        Menunggu Pembayaran
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-400 px-6 py-2.5 rounded-xl text-[14px] font-bold">
                                        Dibatalkan
                                    </span>
                                @endif

                                {{-- Tombol Detail Pesanan --}}
                                <a href="/order/detail/{{ $order->order_code }}" 
                                class="border-2 border-[#F0F0F0] text-[#D46097] px-6 py-2.5 rounded-xl text-[14px] font-bold hover:bg-pink-50 transition-all whitespace-nowrap">
                                    Detail Pesanan
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white p-12 rounded-[35px] text-center border border-dashed border-gray-200">
                            <p class="text-gray-400 font-medium">Belum ada riwayat pesanan.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Paginasi --}}
                    <div class="mt-10 flex justify-center">
                        @if ($orders->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-2">
                                {{-- Tombol Previous --}}
                                @if ($orders->onFirstPage())
                                    <span class="px-4 py-2 text-gray-300 border border-[#D9D9D9] rounded-[15px] cursor-not-allowed">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </span>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}" class="px-4 py-2 text-[#EC4899] border border-[#D9D9D9] rounded-[15px] hover:bg-pink-50 transition-all">
                                        <i class="fa-solid fa-chevron-left text-xs"></i>
                                    </a>
                                @endif

                                {{-- Nomor Halaman --}}
                                @foreach ($orders->links()->elements[0] as $page => $url)
                                    @if ($page == $orders->currentPage())
                                        <span class="px-4 py-2 bg-[#EC4899] text-white rounded-[15px] font-bold shadow-sm">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="px-4 py-2 text-[#444444] border border-[#D9D9D9] rounded-[15px] hover:border-[#EC4899] hover:text-[#EC4899] transition-all font-medium">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}" class="px-4 py-2 text-[#EC4899] border border-[#D9D9D9] rounded-[15px] hover:bg-pink-50 transition-all">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </a>
                                @else
                                    <span class="px-4 py-2 text-gray-300 border border-[#D9D9D9] rounded-[15px] cursor-not-allowed">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </span>
                                @endif
                            </nav>
                        @endif
                    </div>
                </section>
                </div>

                {{-- MODAL TAMBAH ALAMAT --}}
            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white rounded-[30px] w-full max-w-[500px] p-8 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="openModal = false">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/Address.svg') }}" class="w-6 h-6">
                            <h2 class="text-black font-semibold text-[16px]">Tambah Alamat Pengiriman</h2>
                        </div>
                        <button @click="openModal = false"><img src="{{ asset('images/silang.svg') }}" class="w-4 h-4"></button>
                    </div>

                    <form action="{{ route('addresses.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Nama Penerima</label>
                                <input type="text" name="recipient_name" placeholder="Nama Penerima" class="modal-input" required>
                            </div>
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Nomor HP</label>
                                <input type="text" name="phone_number" placeholder="Contoh: 0812345678" class="modal-input" required>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Provinsi</label>
                                <select name="province" x-model="selectedProvince" @change="fetchCities" class="modal-input" required>
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="p in provinces" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Kota/Kabupaten</label>
                            {{--  dropdown Kota/Kabupaten --}}
                            <select name="city" x-model="selectedCity" @change="fetchDistricts" class="modal-input" required>
                                <option value="">Pilih Kota</option>
                                <template x-for="c in cities" :key="c.id">
                                    {{-- UBAH: Gunakan c.name sebagai value jika fetchDistricts kamu mencari berdasarkan nama --}}
                                    <option :value="c.name" x-text="c.name"></option>
                                </template>
                            </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Kecamatan</label>
                                <select name="district" x-model="selectedDistrict" @change="fetchPostalCodes" class="modal-input" required>
                                    <option value="">Pilih Kecamatan</option>
                                    <template x-for="d in districts" :key="d.name">
                                        <option :value="d.name" x-text="d.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-black font-semibold text-[12px] mb-2">Kode Pos</label>
                                <select name="postal_code" class="modal-input" required>
                                    <option value="">Pilih Kode Pos</option>
                                    <template x-for="k in postalCodes" :key="k.id">
                                        <option :value="k.kodepos" x-text="k.kodepos + ' (' + k.kelurahan + ')'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2">Alamat Lengkap</label>
                            <textarea name="full_address" rows="3" class="modal-input resize-none" placeholder="Nama jalan, RT/RW, nomor rumah" required></textarea>
                        </div>

                        <div class="flex items-center gap-3 mb-6">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" 
                                name="is_primary" 
                                value="1" 
                                id="is_primary"
                                class="peer w-5 h-5 appearance-none border-2 border-gray-300 rounded-full checked:bg-[#EC4899] checked:border-[#EC4899] transition-all cursor-pointer">
                            
                            {{-- Ikon Ceklis Putih (Hanya muncul saat di-check) --}}
                            <i class="fa-solid fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                        </div>
                        <label for="is_primary" class="text-gray-500 font-medium text-[12px] cursor-pointer select-none">
                            Jadikan sebagai alamat utama
                        </label>
                    </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-[#EC4899] text-white font-bold py-3 rounded-xl hover:opacity-90">Simpan Alamat</button>
                            <button type="button" @click="openModal = false" class="w-full bg-white border border-[#FFDDDD] text-gray-400 py-3 rounded-xl">Batal</button>
                        </div>
                    </form>
                </div>
            </div> 

            {{-- MODAL EDIT ALAMAT --}}
        <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-[30px] w-full max-w-[500px] p-8 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="editModalOpen = false">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Address.svg') }}" class="w-6 h-6">
                        <h2 class="text-black font-semibold text-[16px]">Edit Alamat Pengiriman</h2>
                    </div>
                    <button @click="editModalOpen = false">
                        <img src="{{ asset('images/silang.svg') }}" class="w-4 h-4">
                    </button>
                </div>

                {{-- Gunakan route PUT yang benar --}}
                <form :action="`{{ url('addresses') }}/${editAddressId}`" method="POST">
                    @csrf
                    @method('PUT')  {{-- ← Ini penting untuk method PUT --}}
                    
                    <!-- Form fields sama seperti sebelumnya -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Nama Penerima</label>
                            <input type="text" name="recipient_name" x-model="editForm.recipient_name" placeholder="Nama Penerima" class="modal-input" required>
                        </div>
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Nomor HP</label>
                            <input type="text" name="phone_number" x-model="editForm.phone_number" placeholder="Contoh: 0812345678" class="modal-input" required>
                        </div>
                    </div>
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Provinsi</label>
                            <select name="province" x-model="editForm.province" @change="fetchEditCities" class="modal-input" required>
                                <option value="">Pilih Provinsi</option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Kota/Kabupaten</label>
                            <select name="city" x-model="editForm.city" @change="fetchEditDistricts" class="modal-input" required>
                                <option value="">Pilih Kota</option>
                                <template x-for="c in editCities" :key="c.id">
                                    <option :value="c.name" x-text="c.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Kecamatan</label>
                            <select name="district" x-model="editForm.district" @change="fetchEditPostalCodes" class="modal-input" required>
                                <option value="">Pilih Kecamatan</option>
                                <template x-for="d in editDistricts" :key="d.name">
                                    <option :value="d.name" x-text="d.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-black font-semibold text-[12px] mb-2">Kode Pos</label>
                            <select name="postal_code" x-model="editForm.postal_code" class="modal-input" required>
                                <option value="">Pilih Kode Pos</option>
                                <template x-for="k in editPostalCodes" :key="k.id">
                                    <option :value="k.kodepos" x-text="k.kodepos + ' (' + k.kelurahan + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-black font-semibold text-[12px] mb-2">Alamat Lengkap</label>
                        <textarea name="full_address" x-model="editForm.full_address" rows="3" class="modal-input resize-none" placeholder="Nama jalan, RT/RW, nomor rumah" required></textarea>
                    </div>

                    <div class="flex items-center gap-3 mb-6">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" 
                                name="is_primary" 
                                value="1" 
                                id="edit_is_primary"
                                x-model="editForm.is_primary"
                                class="peer w-5 h-5 appearance-none border-2 border-gray-300 rounded-full checked:bg-[#EC4899] checked:border-[#EC4899] transition-all cursor-pointer">
                            <i class="fa-solid fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                        </div>
                        <label for="edit_is_primary" class="text-gray-500 font-medium text-[12px] cursor-pointer select-none">
                            Jadikan sebagai alamat utama
                        </label>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full bg-[#EC4899] text-white font-bold py-3 rounded-xl hover:opacity-90">Simpan Perubahan</button>
                        <button type="button" @click="editModalOpen = false" class="w-full bg-white border border-[#FFDDDD] text-gray-400 py-3 rounded-xl">Batal</button>
                    </div>
                </form>
            </div>
        </div>

           {{-- MODAL EDIT PROFIL --}}
            <div x-show="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white rounded-[30px] w-full max-w-[500px] p-8 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="editProfileModal = false">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/icon profile.svg') }}" class="w-6 h-6">
                            <h2 class="text-black font-bold text-[18px]" style="font-family: 'Gotham Rounded', sans-serif;">Edit Profil</h2>
                        </div>
                        <button @click="editProfileModal = false">
                            <img src="{{ asset('images/silang.svg') }}" class="w-4 h-4">
                        </button>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Foto Profil --}}
                        <div class="flex justify-center mb-6">
                            <div class="relative">
                                <div class="w-24 h-24 bg-[#FAECEC] rounded-full flex items-center justify-center overflow-hidden border-4 border-white shadow-sm">
                                    <img id="profilePreview" src="{{ asset('storage/' . auth()->user()->avatar) ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=ec4899&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <label for="avatar_input" class="absolute bottom-0 right-0 bg-[#EC4899] text-white p-1.5 rounded-full cursor-pointer hover:opacity-90 transition-all">
                                    <i class="fa-solid fa-camera text-xs"></i>
                                </label>
                                <input type="file" id="avatar_input" name="avatar" accept="image/*" class="hidden" @change="previewAvatar">
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">USERNAME</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <img src="{{ asset('images/profile username.svg') }}" class="w-5 h-5">
                                </div>
                                <input type="text" name="name" x-model="editProfileForm.name" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-10 pr-4 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" required>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">EMAIL</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <img src="{{ asset('images/Email.svg') }}" class="w-5 h-5">
                                </div>
                                <input type="email" name="email" x-model="editProfileForm.email" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-10 pr-4 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" required>
                            </div>
                        </div>

                        {{-- Nomor HP --}}
                        <div class="mb-6">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">NOMOR HP</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <img src="{{ asset('images/Phone.svg') }}" class="w-5 h-5">
                                </div>
                                <input type="tel" name="phone" x-model="editProfileForm.phone" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-10 pr-4 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" placeholder="Masukkan nomor HP">
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-[#EC4899] border border-[#FFDDDD] text-white font-semibold text-[16px] py-3 rounded-xl hover:opacity-90 transition-all" style="font-family: 'Inter', sans-serif;">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="editProfileModal = false" class="w-full bg-white border border-[#FFDDDD] text-[#8F8F8F] font-semibold text-[16px] py-3 rounded-xl hover:bg-gray-50 transition-all" style="font-family: 'Inter', sans-serif;">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div> 

            {{-- MODAL UBAH PASSWORD --}}
            <div x-show="ubahPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white rounded-[30px] w-full max-w-[500px] p-8 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="ubahPasswordModal = false">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/ubah password.svg') }}" class="w-6 h-6">
                            <h2 class="text-black font-bold text-[18px]" style="font-family: 'Gotham Rounded', sans-serif;">Ubah Password</h2>
                        </div>
                        <button @click="ubahPasswordModal = false">
                            <img src="{{ asset('images/silang.svg') }}" class="w-4 h-4">
                        </button>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Password Saat Ini --}}
                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">Password Saat Ini</label>
                            <div class="relative">
                                <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" x-model="passwordForm.current_password" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-4 pr-10 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" placeholder="Masukkan kata sandi lama" required>
                                <button type="button" @click="showCurrentPassword = !showCurrentPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <img src="{{ asset('images/Eye ubah password.svg') }}" class="w-5 h-5">
                                </button>
                            </div>
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">Password Baru</label>
                            <div class="relative">
                                <input :type="showNewPassword ? 'text' : 'password'" name="password" x-model="passwordForm.password" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-4 pr-10 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" placeholder="Masukkan kata sandi baru" required>
                                <button type="button" @click="showNewPassword = !showNewPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <img src="{{ asset('images/Eye ubah password.svg') }}" class="w-5 h-5">
                                </button>
                            </div>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-4">
                            <label class="block text-black font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" x-model="passwordForm.password_confirmation" 
                                    class="w-full bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px] pl-4 pr-10 py-3 text-black text-[12px] focus:outline-none focus:border-[#EC4899] transition-all"
                                    style="font-family: 'Inter', sans-serif;" placeholder="Konfirmasi kata sandi baru" required>
                                <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <img src="{{ asset('images/Eye ubah password.svg') }}" class="w-5 h-5">
                                </button>
                            </div>
                        </div>

                        {{-- Tips Keamanan --}}
                        <div class="mb-6 p-4 bg-[#FAECEC] border border-[#FFDDDD] rounded-[12px]">
                            <h3 class="text-[#EC4899] font-semibold text-[12px] mb-2" style="font-family: 'Inter', sans-serif;">TIPS KEAMANAN</h3>
                            <ul class="space-y-1">
                                <li class="text-black text-[12px]" style="font-family: 'Inter', sans-serif;">• Gunakan minimal 8 karakter</li>
                                <li class="text-black text-[12px]" style="font-family: 'Inter', sans-serif;">• Gunakan kombinasi huruf besar, kecil, & angka</li>
                                <li class="text-black text-[12px]" style="font-family: 'Inter', sans-serif;">• Jangan gunakan tanggal lahir kamu</li>
                            </ul>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" class="w-full bg-[#EC4899] border border-[#FFDDDD] text-white font-semibold text-[16px] py-3 rounded-xl hover:opacity-90 transition-all" style="font-family: 'Inter', sans-serif;">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="ubahPasswordModal = false" class="w-full bg-white border border-[#FFDDDD] text-[#8F8F8F] font-semibold text-[16px] py-3 rounded-xl hover:bg-gray-50 transition-all" style="font-family: 'Inter', sans-serif;">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        </div>
@endsection

@push('addon-script')
<script>
function addressApp() {
    return {
        // Modal tambah alamat
        openModal: false,
        
        // Modal edit alamat
        editModalOpen: false,
        editAddressId: null,
        editForm: {
            recipient_name: '',
            phone_number: '',
            province: '',
            city: '',
            district: '',
            postal_code: '',
            full_address: '',
            is_primary: false
        },
        
        // Modal Edit Profil
        editProfileModal: false,
        editProfileForm: {
            name: '{{ $user->name }}',
            email: '{{ $user->email }}',
            phone: '{{ $user->phone ?? '' }}'
        },
        
        // Modal Ubah Password
        ubahPasswordModal: false,
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,
        passwordForm: {
            current_password: '',
            password: '',
            password_confirmation: ''
        },
        
        // Data wilayah
        provinces: [],
        cities: [],
        districts: [],
        postalCodes: [],
        
        // Data untuk edit
        editCities: [],
        editDistricts: [],
        editPostalCodes: [],
        
        // Selected values untuk tambah
        selectedProvince: '',
        selectedCity: '',
        selectedDistrict: '',

        async init() {
            try {
                const response = await fetch('/api/provinces');
                const data = await response.json();
                this.provinces = data;
                console.log('Provinsi loaded:', this.provinces.length);
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
                const data = await response.json();
                this.cities = Array.isArray(data) ? data : [];
                console.log('Cities loaded:', this.cities.length);
            } catch (error) {
                console.error("Gagal load kota:", error);
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
                    const data = await response.json();
                    this.districts = Array.isArray(data) ? data : [];
                    console.log('Districts loaded:', this.districts.length);
                } catch (error) {
                    console.error("Gagal load kecamatan:", error);
                }
            }
        },

        async fetchPostalCodes() {
            this.postalCodes = [];
            
            if (!this.selectedDistrict) return;
            
            try {
                const response = await fetch(`/api/postalcodes?district_name=${this.selectedDistrict}`);
                const data = await response.json();
                this.postalCodes = Array.isArray(data) ? data : [];
                console.log('Postal codes loaded:', this.postalCodes.length);
            } catch (error) {
                console.error("Gagal load kode pos:", error);
            }
        },

        // FUNGSI EDIT ALAMAT
        async editAddress(id) {
            console.log('Edit address dipanggil untuk ID:', id);
            
            this.editModalOpen = true;
            this.editAddressId = id;
            
            try {
                const response = await fetch(`/addresses/${id}/edit`);
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const address = await response.json();
                console.log('Data alamat:', address);
                
                this.editForm.recipient_name = address.recipient_name;
                this.editForm.phone_number = address.phone_number;
                this.editForm.province = address.province;
                this.editForm.city = address.city;
                this.editForm.district = address.district;
                this.editForm.postal_code = address.postal_code;
                this.editForm.full_address = address.full_address;
                this.editForm.is_primary = address.is_primary == 1;
                
                if (address.province) {
                    const citiesResponse = await fetch(`/api/cities/${address.province}`);
                    this.editCities = await citiesResponse.json();
                }
                
                if (address.city) {
                    const city = this.editCities.find(c => c.name === address.city);
                    if (city) {
                        const districtsResponse = await fetch(`/api/districts/${city.id}`);
                        this.editDistricts = await districtsResponse.json();
                    }
                }
                
                if (address.district) {
                    const postalResponse = await fetch(`/api/postalcodes?district_name=${address.district}`);
                    this.editPostalCodes = await postalResponse.json();
                }
                
            } catch (error) {
                console.error('Error loading address data:', error);
                alert('Gagal memuat data alamat: ' + error.message);
                this.editModalOpen = false;
            }
        },
        
        async fetchEditCities() {
            if (!this.editForm.province) {
                this.editCities = [];
                return;
            }
            
            try {
                const response = await fetch(`/api/cities/${this.editForm.province}`);
                this.editCities = await response.json();
                this.editForm.city = '';
                this.editDistricts = [];
                this.editForm.district = '';
                this.editPostalCodes = [];
                this.editForm.postal_code = '';
            } catch (error) {
                console.error("Gagal load kota untuk edit:", error);
            }
        },
        
        async fetchEditDistricts() {
            if (!this.editForm.city) {
                this.editDistricts = [];
                return;
            }
            
            const city = this.editCities.find(c => c.name === this.editForm.city);
            if (city) {
                try {
                    const response = await fetch(`/api/districts/${city.id}`);
                    this.editDistricts = await response.json();
                    this.editForm.district = '';
                    this.editPostalCodes = [];
                    this.editForm.postal_code = '';
                } catch (error) {
                    console.error("Gagal load kecamatan untuk edit:", error);
                }
            }
        },
        
        async fetchEditPostalCodes() {
            if (!this.editForm.district) {
                this.editPostalCodes = [];
                return;
            }
            
            try {
                const response = await fetch(`/api/postalcodes?district_name=${this.editForm.district}`);
                this.editPostalCodes = await response.json();
            } catch (error) {
                console.error("Gagal load kode pos untuk edit:", error);
            }
        },

        // FUNGSI EDIT PROFIL
        openEditProfileModal() {
            this.editProfileForm.name = '{{ $user->name }}';
            this.editProfileForm.email = '{{ $user->email }}';
            this.editProfileForm.phone = '{{ $user->phone ?? '' }}';
            this.editProfileModal = true;
        },
        
        // FUNGSI UBAH PASSWORD
        openUbahPasswordModal() {
            this.passwordForm = {
                current_password: '',
                password: '',
                password_confirmation: ''
            };
            this.showCurrentPassword = false;
            this.showNewPassword = false;
            this.showConfirmPassword = false;
            this.ubahPasswordModal = true;
        },
        
        // PREVIEW AVATAR
        previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>
@endpush
