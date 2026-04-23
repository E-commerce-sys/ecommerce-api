<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class Coupon extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function allowedFilters() {
        return [
            AllowedFilter::exact('code', 'code'),
            AllowedFilter::exact('user_id', 'user_id'),
            AllowedFilter::exact('discountPercentage', 'discount_percentage'),
            AllowedFilter::exact('isActive', 'is_active'),
        ];
    }

    public static function allowedIncludes() {
        return [
            'user'
        ];
    }

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function apply(string $price) : string {
        return $price - ($price * ($this->discount_percentage / 100));
    }
}
