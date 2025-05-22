<?php
namespace Modules\Designation\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Designation\Database\Factories\ProductFactory;






class Product extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
    */

    protected static function newFactory()
    {

        return ProductFactory::new();

    }
}
