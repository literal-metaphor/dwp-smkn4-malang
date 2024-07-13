<?php

namespace App\Models;

class User extends Model
{
    protected $hidden = [
        'password',
        'banned',
        'is_admin',
    ];

    public function transactions() {
        $this->hasMany(Transaction::class);
    }

    public function wishlists() {
        $this->hasMany(Wishlist::class);
    }

    public function reviews() {
        $this->hasMany(Review::class);
    }
}
