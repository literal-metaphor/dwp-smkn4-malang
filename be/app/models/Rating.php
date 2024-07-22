<?php

namespace App\Models;

class Rating extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }

    public function product() {
        $this->belongsTo(Product::class);
    }
}
