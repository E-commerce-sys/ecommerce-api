<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class Product extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productColors() {
        return $this->hasMany(ProductColor::class);
    }

    public function productSizes() {
        return $this->hasMany(ProductSize::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function wishListItems() {
        return $this->hasMany(WishListItem::class);
    }

    public function CartItems() {
        return $this->hasMany(CartItem::class);
    }

    public static function allowedFilters() {
        return [
            AllowedFilter::exact('nameEn', 'name_en'),
            AllowedFilter::partial('nameEnContains', 'name_en'),
            AllowedFilter::beginsWithStrict('nameEnStartsWith', 'name_en'),
            AllowedFilter::endsWithStrict('nameEnEndsWith', 'name_en'),

            AllowedFilter::exact('nameAr', 'name_ar'),
            AllowedFilter::partial('nameArContains', 'name_ar'),
            AllowedFilter::beginsWithStrict('nameArStartsWith', 'name_ar'),
            AllowedFilter::endsWithStrict('nameArEndsWith', 'name_ar'),

            AllowedFilter::exact('nameKu', 'name_ku'),
            AllowedFilter::partial('nameKuContains', 'name_ku'),
            AllowedFilter::beginsWithStrict('nameKuStartsWith', 'name_ku'),
            AllowedFilter::endsWithStrict('nameKuEndsWith', 'name_ku'),

            AllowedFilter::exact('category', 'category_id'),

            AllowedFilter::exact('isBestSelling', 'is_best_selling'),
            AllowedFilter::exact('isFeatured', 'is_featured'),
            AllowedFilter::exact('isNewArrival', 'is_new_arrival'),
            AllowedFilter::exact('isNew', 'is_new'),
            AllowedFilter::exact('hasDiscount', 'has_discount'),

            AllowedFilter::scope('minPrice'),
            AllowedFilter::scope('maxPrice'),
            AllowedFilter::scope('priceBetween'),

            AllowedFilter::exact('discountPercentage', 'discount_percentage'),

            AllowedFilter::scope('minAverageRating'),
            AllowedFilter::scope('maxAverageRating'),
            AllowedFilter::scope('averageRatingBetween'),
        ];
    }

    public function scopeMinPrice($query, $price) {
        return $query->where('effective_price', '>=', $price);
    }

    public function scopeMaxPrice($query, $price) {
        return $query->where('effective_price', '<=', $price);
    }

    public function scopePriceBetween($query, ...$prices) {
        return $query->whereBetween('effective_price', $prices);
    }
    
    public function scopeMinAverageRating($query, $average_rating) {
        return $query->where('average_rating', '>=', $average_rating);
    }

    public function scopeMaxAverageRating($query, $average_rating) {
        return $query->where('average_rating', '<=', $average_rating);
    }

    public function scopeAverageRatingBetween($query, ...$ratings) {
        return $query->whereBetween('average_rating', $ratings);
    }

    public static function allowedSorts() {
        return [
            AllowedSort::field('averageRating', 'average_rating'),
            AllowedSort::field('price', 'effective_price'),
        ];
    }

    public static function allowedIncludes() {
        return [
            'category',
            'productColors'
        ];
    }
}
