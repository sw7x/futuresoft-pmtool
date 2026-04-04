<?php

namespace Modules\TaskManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Timesheet\Database\Factories\TimesheetFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
    */
    protected static function newFactory()
    {
        return TimesheetFactory::new();
    }
}













