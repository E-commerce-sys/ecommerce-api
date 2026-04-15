<?php

namespace App\Models;

use App\Models\Address;
use App\States\OrderState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\QueryBuilder\AllowedFilter;

class Order extends Model
{
    use HasStates;

    protected $guarded = [];

    protected $casts = [
        'status' => OrderState::class,
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public static function allowedIncludes() {
        return [
            'user',
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
