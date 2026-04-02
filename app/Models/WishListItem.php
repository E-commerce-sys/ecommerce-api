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

    public static function getSimilarProducts($categoryIds)
    {
        $categoryIds = array_filter(array_map('intval', explode(',', $categoryIds)));

        if (empty($categoryIds)) {
            return collect();
        }

        $user = auth('sanctum')->user();

        $userWishListItemProductIds = $user
            ? $user->wishListItems()->pluck('product_id')->toArray()
            : [];

        $query = Product::whereIn('category_id', $categoryIds);

        if (!empty($userWishListItemProductIds)) {
            $query->whereNotIn('id', $userWishListItemProductIds);
        }

        return $query
            ->inRandomOrder()
            ->limit(4)
            ->with('images')
            ->get();
    }
}
