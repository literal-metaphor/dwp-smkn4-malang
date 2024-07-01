<?php

namespace App\Models;

class Product extends Model
{
    public function shop() {
        $this->belongsTo(Shop::class);
    }

    public function reviews() {
        $this->hasMany(Review::class);
    }

    public function product_photos() {
        $this->hasMany(ProductPhoto::class);
    }

    public function transaction_items() {
        $this->hasMany(TransactionItem::class);
    }
}
