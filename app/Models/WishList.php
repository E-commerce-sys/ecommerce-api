<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $guarded = [];
    protected $table = 'wish_lists';

    public function items()
    {
        return $this->hasMany(WishListItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function allowedIncludes() {
        return [
            'items',
            'user'
        ];
    }
}
