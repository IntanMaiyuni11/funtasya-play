<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\Review; 


class HomeController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk dari database
        $products = Product::all();

        // Ambil review yang ditandai featured untuk carousel
        $reviews = Review::latest()->get();

        // Mengirim data ke view. 
        // PASTIKAN 'reviews' dimasukkan ke dalam compact()
        return view('pages.home', compact('products', 'reviews'));
    }
}