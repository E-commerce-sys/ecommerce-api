<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;
    
    public static $freeShippingLimit = 140;
    public static $shippingCost = 10;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public static function allowedIncludes() {
        return [
                'cartItems',
                'cartItems.productVariant.product',
                'cartItems.productVariant.product.images',
                'cartItems.productVariant.product.productColors',
                'cartItems.productVariant.product.productSizes',
                'cartItems.productVariant.size',
                'cartItems.productVariant.color',
            ];
    }
}
