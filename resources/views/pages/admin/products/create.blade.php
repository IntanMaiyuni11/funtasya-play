@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container mx-auto px-6 py-8 font-sans">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('superadmin.products.index') }}" class="text-[#EC4899] font-black text-xs uppercase tracking-widest flex items-center gap-2 mb-2 hover:gap-3 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Katalog
        </a>
        <h2 class="text-3xl font-black tracking-tight text-gray-800">Tambah Produk Baru</h2>
    </div>

    <form action="{{ route('superadmin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Bagian Kiri: Detail Produk --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-sm p-8 border border-gray-100">
                    <h4 class="text-lg font-black mb-6 text-gray-800 flex items-center gap-2 uppercase tracking-tight">
                        <span class="w-2 h-6 bg-[#EC4899] rounded-full"></span>
                        Informasi Utama
                    </h4>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Nama Produk</label>
                            <input type="text" name="name" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner" placeholder="Masukkan nama mainan/produk..." required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Kategori</label>
                                <select name="categories_id" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner appearance-none cursor-pointer" required>
                                    <option value="">Pilih Kategori...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Harga (Rp)</label>
                                <input type="number" name="price" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-bold p-4 shadow-inner" placeholder="Contoh: 150000" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Produk</label>
                            <textarea name="description" id="editor" rows="5" class="w-full border-gray-100 bg-gray-50 rounded-[1.5rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-sm font-medium p-4 shadow-inner" placeholder="Jelaskan detail produk..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Inventaris & Foto --}}
            <div class="space-y-6">
                {{-- Stok --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm p-8 border border-gray-100">
                    <h4 class="text-lg font-black mb-6 text-gray-800 flex items-center gap-2 uppercase tracking-tight">
                        <span class="w-2 h-6 bg-[#EC4899] rounded-full"></span>
                        Inventaris
                    </h4>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Jumlah Stok</label>
                        <input type="number" name="stock" class="w-full border-gray-100 bg-gray-50 rounded-[1.2rem] focus:ring-[#EC4899] focus:border-[#EC4899] text-xl font-black p-4 shadow-inner" value="0" required>
                    </div>
                </div>

                {{-- Foto Utama --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm p-8 border border-gray-100">
                    <h4 class="text-lg font-black mb-6 text-gray-800 flex items-center gap-2 uppercase tracking-tight">
                        <span class="w-2 h-6 bg-[#EC4899] rounded-full"></span>
                        Media
                    </h4>
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-100 rounded-[1.5rem] p-4 text-center hover:border-[#EC4899] transition-colors group cursor-pointer relative">
                            <input type="file" name="photos" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)">
                            <div id="preview-container" class="space-y-2">
                                <svg class="w-10 h-10 mx-auto text-gray-300 group-hover:text-[#EC4899]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Foto Utama</p>
                            </div>
                            <img id="image-preview" class="hidden w-full aspect-square object-cover rounded-2xl shadow-md">
                        </div>
                        <p class="text-[9px] text-gray-400 italic text-center">*Format: JPG, PNG, atau WEBP. Maks 2MB.</p>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full bg-[#EC4899] text-white py-5 rounded-[1.8rem] font-black uppercase tracking-widest hover:bg-pink-700 transition shadow-xl shadow-pink-100 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('addon-script')
{{-- Library untuk Rich Text Editor (Opsional, agar deskripsi bisa di-bold/list) --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');

    function previewImage(event) {
        const reader = new FileReader();
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');

        reader.onload = function() {
            if (reader.readyState === 2) {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                container.classList.add('hidden');
            }
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush