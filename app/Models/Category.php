<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'icon',
        'parent_id',
    ];
    
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public static function allowedFilters(): array
    {
        return [
            'id',
            'name',
            AllowedFilter::exact('parentCategory', 'parent_id')->nullable(),
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
