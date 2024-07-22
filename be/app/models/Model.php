<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Base Model
 * ---
 * The base model provides a space to set atrributes
 * that are common to all models
 */
class Model extends EloquentModel
{
    protected $guarded = [];

    public $incrementing = false;
}
