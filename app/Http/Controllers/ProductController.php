<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; 
use Illuminate\Http\Request; 

class ProductController extends Controller
{
    /**
     * Menampilkan Halaman Catalog dengan Filter
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Ambil semua kategori dari database untuk tombol tab
        $categories = Category::all(); 

        // 2. Logika Pencarian (Search) dari Navbar
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Filter produk berdasarkan kategori (berdasarkan slug)
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Ambil data produk dengan pagination (tetap terbaru di atas)
        $products = $query->latest()->paginate(12);

        // 5. Kirim data ke view pages.catalog
        return view('pages.catalog', compact('products', 'categories'));
    }

    /**
     * Menampilkan Detail Produk berdasarkan Slug
     */
    public function show($slug)
    {
        // Cari produk berdasarkan slug
        $product = Product::where('slug', $slug)->firstOrFail();

        // Ambil produk terkait berdasarkan kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }
}