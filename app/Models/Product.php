<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class Product extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
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

            AllowedFilter::scope('minPrice'),
            AllowedFilter::scope('maxPrice'),
            AllowedFilter::scope('priceBetween'),
        ];
    }

    public function scopeMinPrice($query, $price) {
        return $query->where('price', '>=', $price);
    }

    public function scopeMaxPrice($query, $price) {
        return $query->where('price', '<=', $price);
    }

    public function scopePriceBetween($query, ...$prices) {
        return $query->whereBetween('price', $prices);
    }

    public static function allowedIncludes() {
        return [
            'category'
        ];
    }
}
