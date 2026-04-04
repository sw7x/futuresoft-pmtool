<?php

namespace Modules\Designation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Designation\Database\Factories\DesignationFactory;

class Designation extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
    */
    protected static function newFactory()
    {
        return DesignationFactory::new();
    }
}













