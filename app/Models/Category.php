<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\QueryBuilder\AllowedFilter;

class Category extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function childrenProducts(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Category::class, 'parent_id', 'category_id');
    }


    public static function allowedFilters(): array
    {
        return [
            'id',
            'name',
            AllowedFilter::exact('parentCategory', 'parent_id')->nullable(),
            AllowedFilter::callback('hasParent', function ($query, $value) {
                if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                    $query->whereNotNull('parent_id');
                } else {
                    $query->whereNull('parent_id');
                }
            }),
        ];
    }

    public static function allowedIncludes(): array
    {
        return [
            'parent',
            'children'
        ];
    }
}
