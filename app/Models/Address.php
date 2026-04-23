<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone_number',
        'full_address',
        'province',
        'city',
        'district',
        'postal_code',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope untuk mendapatkan alamat utama
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', 1);
    }
}