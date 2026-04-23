<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan dashboard profil utama dengan riwayat pesanan (Paginasi)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil alamat milik user
        $addresses = Address::where('user_id', $user->id)->get();
        
        // Menggunakan paginate(5) agar method ->links() di Blade bisa bekerja
        // latest() otomatis mengurutkan berdasarkan created_at desc
        $orders = Order::where('user_id', $user->id)
                    ->withCount('items')
                    ->latest()
                    ->paginate(5); 

        return view('profile.index', compact('user', 'orders', 'addresses'));
    }

    /**
     * Menampilkan halaman profil
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Ambil alamat agar view tetap memiliki data yang dibutuhkan
        $addresses = Address::where('user_id', $user->id)->get();

        // Tetap gunakan paginate agar tidak error saat memanggil {{ $orders->links() }}
        $orders = Order::where('user_id', $user->id)
                    ->withCount('items')
                    ->latest()
                    ->paginate(5);
        
        return view('profile.index', compact('user', 'orders', 'addresses'));
    }

    /**
     * Update data profil (termasuk avatar)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $user = Auth::user();
        
        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan bukan default
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Upload avatar baru
            $avatar = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi!',
            'password.required' => 'Password baru wajib diisi!',
            'password.min' => 'Password baru minimal 8 karakter!',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai!',
        ]);

        $user = Auth::user();

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini salah!');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update data profil (method lama untuk kompatibilitas)
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Hapus akun
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Hapus avatar jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun Anda telah dihapus.');
    }
}