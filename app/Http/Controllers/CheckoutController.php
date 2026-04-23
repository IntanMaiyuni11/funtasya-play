<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\CoreApi;

class CheckoutController extends Controller
{
    /**
     * Halaman Checkout
     */
   public function index(Request $request)
{
    $userId = Auth::id();
    
    // Inisialisasi variabel pendukung
    $cart = collect([]); 
    $subtotal = 0;
    $cartCount = 0;
    $product = null;
    $quantity = 0;
    $variation = null;

    // 2. Ambil semua alamat user untuk modal pilih alamat
        $addresses = Address::where('user_id', $userId)->get();

    // 3. Cek apakah ada product_id di URL
        $isInstant = $request->filled('product_id');

    if ($isInstant) {
        // --- MODE BELI SEKARANG ---
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->input('quantity', 1);
        $variation = $request->input('variation');
        
        $subtotal = $product->price * $quantity;
        $cartCount = $quantity;

        // PENTING: Bungkus produk instant ke dalam format yang sama dengan Cart
        // agar di halaman Blade kamu bisa looping satu variabel saja tanpa error.
        $cart = collect([(object)[
            'product' => $product,
            'quantity' => $quantity,
            'variation' => $variation
        ]]);
        
    } else {
        // --- MODE KERANJANG ---
        $cart = Cart::with('product')->where('user_id', $userId)->get();
        
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        foreach ($cart as $item) {
            $subtotal += ($item->product->price * $item->quantity);
        }
        $cartCount = $cart->sum('quantity');
    }

     // 4. Ambil alamat yang dipilih (dari session atau primary)
    $address = null;
        
        // Cek apakah ada alamat yang dipilih di session
        if (session()->has('selected_address_id')) {
            $address = Address::where('user_id', $userId)
                ->where('id', session('selected_address_id'))
                ->first();
        }
        
        // Jika tidak ada alamat yang dipilih, ambil alamat utama
        if (!$address) {
            $address = Address::where('user_id', $userId)->where('is_primary', 1)->first();
        }
        
        // Jika masih tidak ada, ambil alamat pertama
        if (!$address && $addresses->count() > 0) {
            $address = $addresses->first();
        }

        return view('pages.checkout', compact(
            'address', 'addresses', 'cart', 'subtotal', 'cartCount', 
            'isInstant', 'product', 'quantity', 'variation'
        ));
    }

    /**
     * Instant Buy
     */
    public function instantBuy(Request $request)
    {
        // Cukup validasi dan redirect dengan parameter GET
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'variation'  => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu');
        }

        Cart::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'product_id' => $request->product_id,
                'variation'  => $request->variation,
            ],
            [
                'quantity' => $request->quantity ?? 1
            ]
        );

    return redirect()->route('checkout.index', [
        'product_id' => $request->product_id,
        'quantity'   => $request->quantity,
        'variation'  => $request->variation
    ]);
}

    /**
     * Proses Checkout ke Midtrans
     */
   public function process(Request $request)
{
    $request->validate([
        'payment_method' => 'required',
        'total_amount'   => 'required',
        'address_id'     => 'required',
        'shipping_price' => 'required|numeric'
    ]);

    try {
        DB::beginTransaction();
        $userId = Auth::id();
        $orderCode = 'FT-' . time();
        $method = strtoupper(str_replace(' ', '', $request->payment_method));

        // 1. TENTUKAN ITEM YANG DIBELI
        $orderItems = [];
        if ($request->input('is_instant') == '1') {
            // MODE: INSTANT BUY
            $product = \App\Models\Product::findOrFail($request->product_id);
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'variation'  => $request->variation,
                'price'      => $product->price,
            ];
        } else {
            // MODE: KERANJANG
            $cartItems = Cart::where('user_id', $userId)->get();
            if ($cartItems->isEmpty()) throw new \Exception('Keranjang belanja kosong');

            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'variation'  => $item->variation,
                    'price'      => $item->product->price,
                ];
            }
        }

        // =========================
        // MIDTRANS CONFIG
        // =========================
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // =========================
        // BASE PARAMS
        // =========================
        $params = [
            'transaction_details' => [
                'order_id'     => $orderCode,
                'gross_amount' => (int) $request->total_amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email'      => Auth::user()->email,
            ],
        ];
        
        // =========================
        // Mapping Payment Method (BRI, BCA, GOPAY, dll)
        // =========================
        $method = strtoupper(str_replace(' ', '', $request->payment_method));

        if (in_array($method, ['BRI', 'BCA'])) {

        $params['payment_type'] = 'bank_transfer';
        $params['bank_transfer'] = [
            'bank' => strtolower($method)
        ];

            } 
            // =========================
            // MANDIRI (WAJIB ECHANNEL)
            // =========================
            elseif (str_contains($method, 'MANDIRI')) {

            $params['payment_type'] = 'echannel';

            $params['echannel'] = [
                "bill_info1" => "Payment:",
                "bill_info2" => "Online purchase"
            ];

            // =========================
            // GOPAY
            // =========================
        } elseif ($method == 'GOPAY') {

            $params['payment_type'] = 'gopay';

            // =========================
            // DEFAULT QRIS
            // =========================
        } elseif ($method == 'SHOPEEPAY') {

            // fallback QRIS
            $params['payment_type'] = 'qris';

        } elseif ($method == 'DANA') {

            $params['payment_type'] = 'qris';

        } elseif ($method == 'QRIS') {

            $params['payment_type'] = 'qris';
        }
        
        // =========================
        // REQUEST KE MIDTRANS
        // =========================
        $midtransResponse = CoreApi::charge($params);
        

        // =========================
        // VALIDASI RESPONSE
        // =========================
        if (!$midtransResponse) {
        throw new \Exception('Response Midtrans kosong');
        }

        // 3. SIMPAN KE TABEL ORDERS
        $order = \App\Models\Order::create([
            'order_code'        => $orderCode,
            'user_id'           => $userId,
            'address_id'        => $request->address_id,
            'total_price'       => $request->total_amount,
            'status'            => 'process',
            'payment_method'    => $method,
            'shipping_cost'     => $request->input('shipping_price', 0),
            'va_number' => null, // Pindahkan ke helper method
            'midtrans_response' => json_encode((array) $midtransResponse)
        ]);

        // 4. SIMPAN KE TABEL ORDER_ITEMS (Detail Barang)
        foreach ($orderItems as $item) {
            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'qty' => $item['quantity'],
                'variation'  => $item['variation'],
                'price'      => $item['price'],
            ]);
        }

        // 5. BERSIHKAN DATA (Hanya jika bukan instant buy, atau hapus item spesifik saja)
        if ($request->input('is_instant') == '1') {
            // Jika instant buy, hapus produk tersebut saja dari cart (jika ada)
            Cart::where('user_id', $userId)
                ->where('product_id', $request->product_id)
                ->delete();
        } else {
            // Jika checkout biasa, hapus semua isi cart
            Cart::where('user_id', $userId)->delete();
        }
  
        // =========================
        // RESPONSE
        // =========================
        DB::commit();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'redirect_url' => route('payment.show', $orderCode)
            ]);
        }

        return redirect()->route('payment.show', $orderCode);

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

// Helper untuk ambil VA Number
private function extractVaNumber($response) {
    if (isset($response->va_numbers[0]->va_number)) return $response->va_numbers[0]->va_number;
    if (isset($response->bill_key)) return $response->bill_key; // Mandiri
    if (isset($response->payment_code)) return $response->payment_code; // Indomaret/Alfamart
    return null;
}

/**
     * Change Address di halaman checkout
     */
    public function changeAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id'
        ]);
        
        $address = Address::where('user_id', Auth::id())
            ->where('id', $request->address_id)
            ->firstOrFail();
        
        session(['selected_address_id' => $address->id]);
        
        return redirect()->back()->with('success', 'Alamat berhasil diubah');
    }
}