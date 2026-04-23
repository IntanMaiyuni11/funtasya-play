<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            Address::create([
                'user_id'        => $user->id,
                'recipient_name' => 'Intan Maiyuni',
                'phone_number'   => '081234567890',
                'full_address'   => 'Jl. Kenangan No. 11, Kota Surabaya, Jawa Timur',
                'city'           => 'Surabaya',
                'postal_code'    => '60123',
                'is_primary'     => 1, // Berdasarkan screenshot tipe datanya tinyint
                'is_default'     => 1, // Ditambahkan karena ada di struktur tabelmu
            ]);
        }
    }
}