@extends('layouts.admin')

@section('title', 'Manajemen Ongkir')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-[#EC4899]">Manajemen Ongkir</h2>
            <p class="text-gray-500 font-medium italic">Dashboard Master Data Pengiriman — Super Admin Panel</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- Form Tambah Cepat --}}
    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 mb-8">
        <h4 class="text-lg font-black mb-6 text-gray-800 flex items-center gap-2 uppercase tracking-tight">
            <span class="w-2 h-6 bg-[#EC4899] rounded-full"></span>
            Tambah Wilayah & Biaya
        </h4>
        <form action="{{ route('superadmin.shipping-costs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Provinsi</label>
                <select name="province" id="province-select" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner appearance-none transition-all cursor-pointer" required>
                    <option value="">Pilih Provinsi...</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Kota / Kabupaten</label>
                <select name="city" id="city-select" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner appearance-none transition-all cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" required disabled>
                    <option value="">Pilih Kota...</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Nominal Ongkir</label>
                <input type="number" name="cost" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner" placeholder="Contoh: 20000" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#EC4899] text-white py-4 rounded-[1.5rem] font-black uppercase tracking-widest hover:bg-pink-700 transition shadow-xl shadow-pink-100 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Pencarian & Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Tabel & Filter --}}
        <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-xl font-black text-gray-800">Daftar Biaya Ongkir</h3>
            <div class="relative w-full md:w-80">
                <input type="text" id="table-search" placeholder="Cari kota atau provinsi..." 
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-pink-200 transition-all">
                <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left" id="shipping-table">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                        <th class="px-8 py-5">Wilayah</th>
                        <th class="px-8 py-5">Biaya Pengiriman</th>
                        <th class="px-8 py-5 text-center">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($shippingCosts as $item)
                    <tr class="hover:bg-pink-50/20 transition-colors group table-row-item">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-[#EC4899] uppercase tracking-tighter province-name">{{ $item->province }}</span>
                                <span class="text-lg font-black text-gray-900 city-name">{{ $item->city }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xl font-black text-gray-800">Rp {{ number_format($item->cost, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('superadmin.shipping-costs.edit', $item->id) }}" class="p-3 bg-gray-50 text-gray-400 hover:text-blue-500 hover:bg-blue-50 rounded-2xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('superadmin.shipping-costs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ongkir {{ $item->city }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.85L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center">
                            <p class="text-gray-400 font-bold italic">Belum ada data ongkir.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="no-results" class="hidden px-8 py-20 text-center border-t border-gray-50">
            <p class="text-gray-400 font-bold italic">Wilayah tidak ditemukan.</p>
        </div>

        @if($shippingCosts->hasPages())
        <div id="pagination-container" class="px-8 py-6 bg-gray-50">
            {{ $shippingCosts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('addon-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province-select');
        const citySelect = document.getElementById('city-select');
        const searchInput = document.getElementById('table-search');
        const tableRows = document.querySelectorAll('.table-row-item');
        const noResults = document.getElementById('no-results');
        const pagination = document.getElementById('pagination-container');

        // --- 1. LOGIKA DROPDOWN WILAYAH ---
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json`)
            .then(response => response.json())
            .then(provinces => {
                provinces.sort((a, b) => a.name.localeCompare(b.name)).forEach(province => {
                    let option = document.createElement('option');
                    option.value = province.name;
                    option.dataset.id = province.id;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            });

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.options[this.selectedIndex].dataset.id;
            citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
            citySelect.disabled = true;

            if (provinceId) {
                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                    .then(response => response.json())
                    .then(regencies => {
                        regencies.sort((a, b) => a.name.localeCompare(b.name)).forEach(regency => {
                            let option = document.createElement('option');
                            option.value = regency.name;
                            option.textContent = regency.name;
                            citySelect.appendChild(option);
                        });
                        citySelect.disabled = false;
                    });
            }
        });

        // --- 2. LOGIKA SEARCH TABEL (REALTIME) ---
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            let visibleCount = 0;

            tableRows.forEach(row => {
                const province = row.querySelector('.province-name').textContent.toLowerCase();
                const city = row.querySelector('.city-name').textContent.toLowerCase();
                
                if (province.includes(filter) || city.includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            noResults.classList.toggle('hidden', visibleCount > 0);
            
            // Sembunyikan pagination saat mencari agar tidak membingungkan
            if (pagination) {
                pagination.style.display = filter === "" ? "block" : "none";
            }
        });
    });
</script>
@endpush