<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\Review; 
use App\Models\Category; // 1. Pastikan import model Category

class HomeController extends Controller
{
   public function index()
{
    // Menggunakan take(3) untuk membatasi hasil menjadi 3 produk saja
    // Menggunakan latest() agar yang muncul adalah 3 produk terbaru
    $products = Product::with('category')->latest()->take(3)->get(); 

    // 3. Ambil semua kategori untuk menu filter
    $categories = Category::all();

    // 4. Ambil review
    $reviews = Review::latest()->get();

    // 5. Kirim semua variabel ke view
    return view('pages.home', compact('products', 'categories', 'reviews'));
}
}
