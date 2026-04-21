<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\QueryBuilder\AllowedFilter;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    protected $guarded = [];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function wishListItems() {
        return $this->hasMany(WishListItem::class);
    }

    public function cart() {
        return $this->hasOne(Cart::class);
    }

    public function addresses() {
        return $this->hasMany(Address::class);
    }

    public function mainAddress() {
        return $this->belongsTo(Address::class, 'main_address_id');
    }


    public function orders() {
        return $this->hasMany(Order::class);
    }

    public static function allowedIncludes() {
        return [
            'mainAddress'
        ];
    }

    public static function allowedFilters() {
        return [
            AllowedFilter::partial('email', 'email'),
            AllowedFilter::partial('firstName', 'first_name'),
            AllowedFilter::partial('lastName', 'last_name'),
        ];
    }
}
