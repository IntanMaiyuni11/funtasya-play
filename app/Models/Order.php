<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'order_code',
    'user_id',
    'address_id',
    'total_price',
    'status',
    'payment_method',
    'va_number',
    'midtrans_response',
    'shipping_cost',
    'shipping_status',
    'tracking_number',
    'courier_name',
    'shipped_at',
    'completed_at',
];

    protected $casts = [
    'shipped_at' => 'datetime',
    'completed_at' => 'datetime',
];

    // Relasi ke User 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Address (Alamat Pengiriman)
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Relasi ke Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Menghitung total quantity item yang dibeli
    public function getTotalQuantityAttribute()
    {
    return $this->items()->count();
    }

    // Label Status untuk Tampilan
    public function getStatusLabelAttribute()
    {
        return [
            'process' => 'Menunggu Pembayaran',
            'complete' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ][$this->status] ?? $this->status;
    }

    // Warna Status untuk Tailwind
    public function getStatusColorAttribute()
    {
        return [
            'process' => 'bg-[#FFF8ED] text-[#E5A94D]', // Kuning (gambar 7)
            'complete' => 'bg-[#EEF9F1] text-[#78C28D]', // Hijau (gambar 7)
            'cancelled' => 'bg-red-50 text-red-400',
        ][$this->status] ?? 'bg-gray-100 text-gray-500';
    }
}