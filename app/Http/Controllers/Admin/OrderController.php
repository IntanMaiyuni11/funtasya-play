<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN BARIS INI

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('pages.admin.orders.index', compact('orders'));
    }

    public function show($id)
{
    // Ganti orderItems menjadi items
    $order = Order::with(['items.product', 'user', 'address'])->findOrFail($id);
    return view('pages.admin.orders.show', compact('order'));
}

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'resi' => 'required|string',
            'courier' => 'required'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'shipping_status' => 'dikirim',
            'tracking_number' => $request->resi,
            'courier_name'    => $request->courier,
            'shipped_at'      => now(),
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function detail($orderCode)
    {
        $order = Order::with(['orderItems.product', 'address', 'user'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id()) // Sekarang Auth sudah dikenali
            ->firstOrFail();

         return view('pages.order.detail', compact('order'));
    }

    public function updateToCompleted($id)
{
    $order = Order::findOrFail($id);
    
    $order->update([
        'shipping_status' => 'selesai',
        'status'          => 'complete',
        'completed_at'    => now(),
    ]);

    return back()->with('success', 'Pesanan telah dinyatakan selesai!');
}
}