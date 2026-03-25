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
            'user'
        ];
    }
}
