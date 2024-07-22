<?php

namespace App\Models;

class Transaction extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }

    public function transaction_items() {
        $this->hasMany(TransactionItem::class);
    }
}
