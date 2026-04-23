<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
    'user_id', 'product_id', 'rating', 'comment', 
    'user_name_display', 'user_avatar', 'card_color', 'is_featured'
];

public function product() {
    return $this->belongsTo(Product::class);
}
}
