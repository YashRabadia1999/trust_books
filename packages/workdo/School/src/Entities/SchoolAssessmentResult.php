<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolAssessmentResult extends Model
{
    protected $fillable = [
        'assessment_id',
        'student_id',
        'marks_obtained',
        'grade'
    ];

    public function student()
    {
        return $this->hasOne(SchoolStudent::class , 'id' , 'student_id');
    }

    public function assessment()
    {
        return $this->hasOne(SchoolAssessment::class , 'id' , 'assessment_id');
    }
}
