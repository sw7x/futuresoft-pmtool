<?php

namespace Modules\Reporting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Reporting\Database\Factories\ReportFactory;

class Report extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
    */
    protected static function newFactory()
    {
        return ReportFactory::new();
    }
}













