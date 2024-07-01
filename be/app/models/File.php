<?php

namespace App\Models;

class File extends Model
{
    public function review_photo() {
        $this->hasMany(ReviewPhoto::class);
    }

    public function product_photo() {
        $this->hasMany(ProductPhoto::class);
    }
}
