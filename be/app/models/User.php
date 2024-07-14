<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

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
