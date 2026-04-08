<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingProgressReport extends Model
{
    use HasFactory;
    protected $table = 'driving_progress_reports';

    protected $fillable = [
        'id',
        'student_id',
        'class_id',
        'teacher_id',
        'progress_date',
        'skills_assessed',
        'comments',
        'rating',
        'workspace',
        'created_by',
    ];
}
