<?php

namespace App\Models;

class Admin extends Model
{
    public function user() {
        $this->belongsTo(User::class);
    }
}
