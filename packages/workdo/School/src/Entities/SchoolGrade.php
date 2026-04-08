<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_name',
        'min_marks',
        'max_marks',
        'remarks',
        'workspace',
        'created_by'
    ];

    public function schoolStudents()
    {
        return $this->hasMany(SchoolStudent::class, 'grade_name');
    }
}
