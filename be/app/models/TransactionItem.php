<?php

namespace App\Models;

class TransactionItem extends Model
{
    public function transaction() {
        $this->belongsTo(Transaction::class);
    }

    public function product() {
        $this->belongsTo(Product::class);
    }
}
