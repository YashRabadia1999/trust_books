<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolHomework extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'classroom',
        'subject',
        'submission_date',
        'homework',
        'content',
        'student_homework',
        'workspace',
        'created_by'
    ];

    public function className()
    {
        return $this->hasOne('Workdo\School\Entities\Classroom', 'id', 'classroom');
    }

    public function subjectName()
    {
        return $this->hasOne('Workdo\School\Entities\Subject', 'id', 'subject');
    }
}
