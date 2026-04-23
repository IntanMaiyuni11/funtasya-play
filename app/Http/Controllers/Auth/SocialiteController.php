<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Mengarahkan user ke halaman login Provider (Google/Facebook)
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Menangani data yang dikembalikan oleh Provider
     */
    public function callback($provider)
    {
        try {
            // Ambil data user dari social media
            $socialUser = Socialite::driver($provider)->user();

            // Cek apakah user sudah ada di database berdasarkan email
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Jika belum ada, buatkan akun baru (seperti di Gramedia)
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Password acak karena login via social
                    'email_verified_at' => now(), // Otomatis terverifikasi
                ]);
            }

            // Login-kan user ke aplikasi
            Auth::login($user);

            // Arahkan ke dashboard atau home
            return redirect()->intended('/home');

        } catch (Exception $e) {
            // Jika ada error (misal user membatalkan login)
            return redirect('/login')->with('error', 'Gagal login menggunakan ' . $provider);
        }
    }
}