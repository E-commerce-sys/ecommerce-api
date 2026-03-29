<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $table = 'product_colors';

    protected $fillable = [
        'name',
        'hex_code',
        'product_id',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
