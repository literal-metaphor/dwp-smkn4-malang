<?php

namespace App\Models;

class ReviewPhoto extends Model
{
    public function review() {
        $this->belongsTo(Review::class);
    }

    public function file() {
        $this->belongsTo(File::class);
    }
}
