<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function size() {
        return $this->belongsTo(ProductSize::class);
    }

    public function color() {
        return $this->belongsTo(ProductColor::class);
    }

    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }
}
