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
            AllowedFilter::callback('status', function ($query, $value) {
                $map = OrderState::map();

                if (is_array($value)) {
                    $statuses = [];
                    foreach($value as $key => $status) {
                        if (!isset($map[$status])) {
                            abort(400, $status . 'is an invalid status');
                        }
                        $statuses[] = $map[$status];
                    }
                    $query->whereIn('status', $statuses);
                    return;
                }

                if (!isset($map[$value])) {
                    abort(400, $value . 'is an invalid status');
                }

                $query->where('status', $map[$value]);
            })
        ];
    }
}
