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

    function wishList()
    {
        return $this->belongsTo(WishList::class);
    }
}
