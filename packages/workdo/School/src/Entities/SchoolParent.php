<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'client',
        'student',
        'user_id',
        'name',
        'gender',
        'date_of_birth',
        'relation',
        'address',
        'state',
        'city',
        'zip_code',
        'contact',
        'email',
        'password',
        'parent_image',
        'workspace',
        'created_by'
    ];
    
    public static $relation   = [
        'father' => "Father",
        'mother' => "Mother"
    ];
}
