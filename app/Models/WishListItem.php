<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishListItem extends Model
{
    protected $table = 'wish_list_items';
    protected $guarded = [];

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function allowedIncludes() {
        return [
            'product',
            'user',
            'product.images'
        ];
    }

    public static function getSimilarProducts($categoryIds) {
        $categoryIds = array_map('intval', explode(',', $categoryIds));
        return Product::whereIn(
            'category_id', $categoryIds
        )
        ->limit(4)
        ->inRandomOrder()
        ->with('images')
        ->get();
    }
}
