<?php

namespace App\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class Order extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress() {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public static function allowedIncludes() {
        return [
            'user',
            'shippingAddress',
            'orderItems',
            'orderItems.productVariant.product',
            'orderItems.productVariant.product.images',
            'orderItems.productVariant.product.productColors',
            'orderItems.productVariant.product.productSizes',
            'orderItems.productVariant.size',
            'orderItems.productVariant.color',
        ];
    }

    public static function allowedFilters() {
        return [
            'id',
            'user_id',
            'address_id',
            AllowedFilter::exact('status', 'status'),
        ];
    }
}
