<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review; // Tambahkan import Model Review di sini
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED USER (3 Role Utama)
        User::create([
            'name' => 'Super Admin Funtasya',
            'email' => 'superadmin@funtasya.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'phone' => '081234567890'
        ]);

        User::create([
            'name' => 'Admin Operasional',
            'email' => 'admin@funtasya.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567891'
        ]);

        User::create([
            'name' => 'Customer Funtasya',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '081234567892'
        ]);

        // 2. SEED KATEGORI
        $category = Category::create([
            'name' => 'Wipe and Clean',
            'slug' => 'wipe-and-clean'
        ]);

        // 3. SEED PRODUK
        $products = [
            [
                'name' => 'Pretend Play Membuat Sandwich',
                'price' => 20000,
                'image' => 'Pretend Play Membuat Sandwich.png', 
            ],
            [
                'name' => '23+ Aktivitas Ramadhan Islami untuk Anak',
                'price' => 119000,
                'price_max' => 250000,
                'image' => 'ramadhan.png',
            ],
            [
                'name' => 'Poster Kata Dengan Gambar Asli',
                'price' => 12000,
                'image' => 'Poster Kata Dengan Gambar Asli.png',
            ],
            [
                'name' => 'Busy Page Mengelompokkan Warna....png',
                'price' => 79000,
                'image' => 'Busy Page Mengelompokkan Warna....png',
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => 'Produk edukasi berkualitas dari Funtasya Play untuk membantu tumbuh kembang si kecil.',
                'price' => $item['price'],
                'price_max' => $item['price_max'] ?? null,
                'stock' => 50,
                'image' => $item['image'],
            ]);
        }

        // 4. SEED REVIEW (Dijalankan terakhir agar User & Product sudah ada)
        Review::create([
            'user_id' => 3, 
            'product_id' => 1,
            'rating' => 5,
            'comment' => 'Anak saya langsung jatuh cinta sama buku ceritanya! Warnanya cerah.',
            'user_name_display' => 'Dina, Ibu dari Kayla',
            'user_avatar' => 'user-1.png',
            'card_color' => '#A64B9A',
            'is_featured' => true,
        ]);

        Review::create([
            'user_id' => 3,
            'product_id' => 2,
            'rating' => 5,
            'comment' => 'Puzzle-nya bikin anak saya fokus lebih lama. Edukatif tapi tetap fun!',
            'user_name_display' => 'Reza, Ayah dari Aidan',
            'user_avatar' => 'user-2.png',
            'card_color' => '#00A991',
            'is_featured' => true,
        ]);
    }
}