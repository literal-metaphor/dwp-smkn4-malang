<?php

namespace App\Models;

class Product extends Model
{
    public function owner() {
        $this->belongsTo(User::class);
    }

    public function ratings() {
        $this->hasMany(Rating::class);
    }

    public function comments() {
        $this->hasMany(Comment::class);
    }

    public function product_photos() {
        $this->hasMany(ProductPhoto::class);
    }

    public function transaction_items() {
        $this->hasMany(TransactionItem::class);
    }
}
