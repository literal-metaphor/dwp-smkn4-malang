<?php

namespace App\Models;

class CommentPhoto extends Model
{
    public function comment() {
        $this->belongsTo(Comment::class);
    }

    public function file() {
        $this->belongsTo(File::class);
    }
}
