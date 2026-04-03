<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function productVariant() {
        return $this->belongsTo(ProductVariant::class);
    }
}
