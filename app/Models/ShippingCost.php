<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    use HasFactory;

    // Nama tabel di database (pastikan sesuai, biasanya jamak 'shipping_costs')
    protected $table = 'shipping_costs';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'province',
        'city',
        'cost',
    ];

    /**
     * Opsional: Format rupiah untuk tampilan cost
     */
    public function getFormattedCostAttribute()
    {
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }
}