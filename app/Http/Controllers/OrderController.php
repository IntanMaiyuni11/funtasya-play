<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function detail($orderCode)
    {
        // Ambil order dengan relasi yang diperlukan
        $order = Order::with(['items.product', 'address', 'user'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

         return view('pages.order.detail', compact('order'));
    }

    // Optional: Untuk testing, buat method untuk generate sample order
    public function createSampleOrder()
    {
        // Hanya untuk testing - jangan gunakan di production
        $order = Order::create([
            'order_code' => 'FP-' . date('Ymd') . '-' . rand(100, 999),
            'user_id' => Auth::id(),
            'customer_name' => Auth::user()->name ?? 'Budi Santoso',
            'customer_phone' => '081234567890',
            'customer_address' => 'Jl. Contoh No. 123, Jakarta',
            'status' => 'complete', // atau 'process'
            'total_price' => 405000,
            'shipping_cost' => 35000,
            'courier' => 'J&T Express',
            'tracking_number' => 'JNT' . rand(100000, 999999),
            'packed_at' => now()->subDays(5),
            'shipped_at' => now()->subDays(3),
            'transit_at' => now()->subDays(2),
            'delivered_at' => now(),
        ]);

        // Tambah order items (contoh)
        // $order->items()->create([...]);

        return redirect()->route('order.detail', $order->order_code);
    }
}