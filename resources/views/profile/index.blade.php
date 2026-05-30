@extends('layouts.main')

@section('content')
<div x-data="addressApp()" class="min-h-screen bg-[#F8F9FB] py-12">
    <div class="max-w-6xl mx-auto px-6">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Akun Saya</h1>
                <p class="text-slate-500 mt-1">Kelola detail pribadi dan lihat aktivitas belanja Anda.</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Profile Card --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-20 h-20 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-slate-400">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="openEditProfileModal()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-medium hover:bg-slate-800 transition-all text-sm">Edit Profil</button>
                    <button @click="openUbahPasswordModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-all text-sm">Ganti Password</button>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-gradient-to-br from-indigo-600 to-violet-600 p-8 rounded-3xl text-white shadow-xl shadow-indigo-200 flex flex-col justify-between">
                <div>
                    <h3 class="font-medium opacity-80">Total Pesanan</h3>
                    <div class="text-5xl font-extrabold mt-2">{{ $orders->count() }}</div>
                </div>
                <p class="text-indigo-100 text-sm mt-4">Aktif di sistem kami sejak {{ $user->created_at->format('Y') }}</p>
            </div>

            {{-- Address Section --}}
            <div class="lg:col-span-3">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Buku Alamat</h3>
                    <button @click="openModal = true" class="text-indigo-600 font-semibold text-sm hover:underline">+ Tambah Baru</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($addresses as $address)
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="flex justify-between mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $address->is_primary ? 'text-indigo-600 bg-indigo-50' : 'text-slate-400 bg-slate-50' }} px-2 py-1 rounded">{{ $address->is_primary ? 'Utama' : 'Sekunder' }}</span>
                            <button @click="editAddress({{ $address->id }})" class="text-slate-400 hover:text-indigo-600"><i class="fa-solid fa-pen-to-square"></i></button>
                        </div>
                        <h4 class="font-bold text-slate-900">{{ $address->recipient_name }}</h4>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $address->full_address }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Transaction History --}}
            <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h3 class="text-xl font-bold text-slate-900">Riwayat Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase">
                            <tr>
                                <th class="px-8 py-4">Kode Pesanan</th>
                                <th class="px-8 py-4">Tanggal</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Total</th>
                                <th class="px-8 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-semibold text-slate-700">#{{ $order->order_code }}</td>
                                <td class="px-8 py-6 text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 text-[11px] font-bold rounded-full {{ $order->status == 'complete' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right font-bold text-slate-900">Rp{{ number_format($order->total_price, 0) }}</td>
                                <td class="px-8 py-6 text-center">
                                    <a href="/order/detail/{{ $order->order_code }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">Lihat Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

                {{-- MODAL TAMBAH ALAMAT --}}
            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                <div class="bg-white rounded-[30px] w-full max-w-[500px] p-8 relative mx-4 max-h-[90vh] overflow-y-auto" @click.away="openModal = false">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/address.svg') }}" class="w-6 h-6">
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
                        <img src="{{ asset('images/address.svg') }}" class="w-6 h-6">
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
                            <img src="{{ asset('images/icon-profile.svg') }}" class="w-6 h-6">
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
                                    <img src="{{ asset('images/profile-username.svg') }}" class="w-5 h-5">
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
                                    <img src="{{ asset('images/email.svg') }}" class="w-5 h-5">
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
                                    <img src="{{ asset('images/phone.svg') }}" class="w-5 h-5">
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
                            <img src="{{ asset('images/ubah-password.svg') }}" class="w-6 h-6">
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
                                    <img src="{{ asset('images/eye-ubah-password.svg') }}" class="w-5 h-5">
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
                                    <img src="{{ asset('images/eye-ubah-password.svg') }}" class="w-5 h-5">
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
                                    <img src="{{ asset('images/eye-ubah-password.svg') }}" class="w-5 h-5">
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
