<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk (Katalog)
     */
    public function index()
    {
        // Menggunakan pagination dan eager loading untuk performa
        $products = Product::with('category')->latest()->paginate(12);
        
        // Sesuaikan path view ke folder pages
        return view('pages.admin.products.index', compact('products'));
    }

    /**
     * Menampilkan form tambah produk
     */
    public function create()
    {
        $categories = Category::all();
        
        // Sesuaikan path view ke folder pages
        return view('pages.admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'categories_id' => 'required|exists:categories,id',
            'price'         => 'required|numeric',
            'stock'         => 'required|integer',
            'photos'        => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description'   => 'required'
        ]);

        // Upload Gambar
        $imagePath = $request->file('photos')->store('products', 'public');

        // Simpan Data
        Product::create([
            'categories_id' => $request->categories_id,
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'description'   => $request->description,
            'price'         => $request->price,
            'stock'         => $request->stock,
            'image'         => $imagePath, // Sesuaikan dengan kolom tabel kamu (image/photos)
            'weight'        => 500,        // Default berat (gr) untuk ongkir
            'product_type'  => 'Fisik'
        ]);

        return redirect()->route('superadmin.products.index')->with('success', 'Produk Funtasya Play berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit produk
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        
        return view('pages.admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Menghapus produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus file gambar dari storage jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil dihapus dari katalog!');
    }
}