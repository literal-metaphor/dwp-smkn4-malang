<?php

namespace App\Models;

class Comment extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }

    public function product() {
        $this->belongsTo(Product::class);
    }
}
