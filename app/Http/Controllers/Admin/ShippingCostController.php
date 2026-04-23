<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCost; // Pastikan kamu sudah punya model ShippingCost
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingCostController extends Controller
{
    public function index()
    {
        $shippingCosts = ShippingCost::latest()->paginate(15);
        
        // Cek jika user adalah super_admin, arahkan ke view CRUD
        // Jika admin biasa, arahkan ke view "Read Only"
        if (Auth::user()->role == 'super_admin') {
            return view('pages.admin.shipping.index', compact('shippingCosts'));
        }

        return view('pages.admin.shipping.index-readonly', compact('shippingCosts'));
    }

    // Hanya dijalankan oleh Super Admin (karena proteksi di web.php)
    public function create()
    {
        return view('pages.admin.shipping.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'province' => 'required',
            'city' => 'required',
            'cost' => 'required|numeric'
        ]);

        ShippingCost::create($request->all());
        return redirect()->route('superadmin.shipping.index')->with('success', 'Biaya ongkir berhasil ditambah');
    }

    public function edit($id)
    {
        $shipping = ShippingCost::findOrFail($id);
        return view('pages.admin.shipping.edit', compact('shipping'));
    }

    public function update(Request $request, $id)
    {
        $shipping = ShippingCost::findOrFail($id);
        $shipping->update($request->all());
        return redirect()->route('superadmin.shipping.index')->with('success', 'Biaya ongkir berhasil diupdate');
    }

    public function destroy($id)
    {
        ShippingCost::findOrFail($id)->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}