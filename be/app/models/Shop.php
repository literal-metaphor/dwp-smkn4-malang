<?php

namespace App\Models;

class Shop extends Model
{
    public function products() {
        $this->hasMany(Product::class);
    }
}
