<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function allowedIncludes() {
        return [
            'user'
        ];
    }
}
