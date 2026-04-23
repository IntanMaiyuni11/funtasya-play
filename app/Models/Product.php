<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'category', // Sesuai kolom di database kamu
        'slug', 
        'description', 
        'short_description', // Sesuai kolom di database kamu
        'age_range', // Sesuai kolom di database kamu
        'price',
        'price_max', 
        'stock', 
        'weight', // Sesuai kolom di database kamu
        'image',
        'gallery', // Sesuai kolom di database kamu
        'variations', // Kolom baru untuk list variasi
        'features'    // Kolom baru untuk list keunggulan
    ];

    /**
     * Casting atribut agar otomatis menjadi array saat dipanggil di Blade.
     * Sangat penting untuk kolom bertipe JSON agar bisa di-foreach.
     */
    protected $casts = [
        'gallery' => 'array',
        'variations' => 'array',
        'features' => 'array',
    ];

    /**
     * Relasi ke tabel Categories
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}