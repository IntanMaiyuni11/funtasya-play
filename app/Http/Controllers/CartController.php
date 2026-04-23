<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;   

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')
                    ->where('user_id', Auth::id())
                    ->get();

        $relatedProducts = Product::where('stock', '>', 0)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('pages.cart', compact('cart', 'relatedProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'variation'  => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu');
        }

        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $request->product_id)
                        ->where('variation', $request->variation)
                        ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id'    => $userId,
                'product_id' => $request->product_id,
                'variation'  => $request->variation,
                'quantity'   => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Berhasil masuk ke keranjang!');
    }

    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();
        
        if($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    /**
     * Method baru untuk menangani klik "Belanja Sekarang"
     */
    public function checkout()
    {
        // 1. Ambil semua item keranjang user ini
        $cartItems = Cart::where('user_id', Auth::id());

        if ($cartItems->count() > 0) {
            // 2. Hapus semua isi keranjang di database
            $cartItems->delete();

            // 3. Arahkan ke halaman sukses atau invoice
            // Sementara kita arahkan ke halaman checkout.index sesuai route kamu sebelumnya
            return redirect()->route('checkout.index')->with('success', 'Pesanan diproses, keranjang dikosongkan!');
        }

        return redirect()->route('catalog')->with('error', 'Keranjang kamu kosong!');
    }
}