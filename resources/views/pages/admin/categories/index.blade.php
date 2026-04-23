@extends('layouts.admin')

@section('title', 'Kategori Produk')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-[#EC4899]">Kategori Produk</h2>
            <p class="text-gray-500 font-medium italic">Kelola pengelompokan produk Funtasya Play</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form Tambah Kategori (Sticky di samping) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] shadow-sm p-8 border border-gray-100 sticky top-24">
                <h4 class="text-lg font-black mb-6 text-gray-800 flex items-center gap-2 uppercase tracking-tight">
                    <span class="w-2 h-6 bg-[#EC4899] rounded-full"></span>
                    Kategori Baru
                </h4>
                
                <form action="{{ route('superadmin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Nama Kategori</label>
                        <input type="text" name="name" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner" placeholder="Contoh: Mainan Edukasi" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Foto Kategori</label>
                        <div class="relative border-2 border-dashed border-gray-100 rounded-[1.2rem] p-4 text-center hover:border-[#EC4899] transition-all group">
                            <input type="file" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="previewCategory(event)">
                            <div id="cat-preview-text">
                                <svg class="w-8 h-8 mx-auto text-gray-300 group-hover:text-[#EC4899] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Klik untuk Upload</p>
                            </div>
                            <img id="cat-img-preview" class="hidden w-full h-32 object-cover rounded-xl shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#EC4899] text-white py-4 rounded-[1.5rem] font-black uppercase tracking-widest hover:bg-pink-700 transition shadow-xl shadow-pink-100 flex items-center justify-center gap-2">
                        Simpan Kategori
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Tabel Kategori --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <th class="px-8 py-5">Foto</th>
                            <th class="px-8 py-5">Nama Kategori</th>
                            <th class="px-8 py-5">Slug</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-pink-50/20 transition-colors group">
                            <td class="px-8 py-4">
                                <img src="{{ asset('storage/'.$category->photo) }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-white shadow-sm">
                            </td>
                            <td class="px-8 py-4">
                                <span class="font-black text-gray-800">{{ $category->name }}</span>
                            </td>
                            <td class="px-8 py-4 text-xs font-medium text-gray-400">
                                {{ $category->slug }}
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('superadmin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.85L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold italic">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    function previewCategory(event) {
        const reader = new FileReader();
        const preview = document.getElementById('cat-img-preview');
        const text = document.getElementById('cat-preview-text');

        reader.onload = function() {
            if (reader.readyState === 2) {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                text.classList.add('hidden');
            }
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush