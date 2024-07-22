<?php

namespace App\Models;

class Wishlist extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }
}
