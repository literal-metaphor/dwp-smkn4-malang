<?php

namespace App\Models;

class ProductPhoto extends Model
{
    public function product() {
        $this->belongsTo(Product::class);
    }

    public function file() {
        $this->belongsTo(File::class);
    }
}
