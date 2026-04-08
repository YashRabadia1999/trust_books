<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'created_by',
        'workspace'
    ];
}
