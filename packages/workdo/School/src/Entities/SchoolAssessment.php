<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolAssessment extends Model
{
    protected $fillable = [
        'title',
        'class_id',
        'subject_id',
        'due_date',
        'description'
    ];

    public function class()
    {
        return $this->hasOne(Classroom::class , 'id' , 'class_id');
    }

    public function subject()
    {
        return $this->hasOne(Subject::class , 'id' , 'subject_id');
    }
}
