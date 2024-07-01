<?php

namespace App\Models;

class User extends Model
{
    public function admin() {
        $this->hasOne(Admin::class);
    }

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
