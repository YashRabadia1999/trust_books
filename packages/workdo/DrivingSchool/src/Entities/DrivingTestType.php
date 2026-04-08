<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingTestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'workspace',
        'created_by',
    ];
}
