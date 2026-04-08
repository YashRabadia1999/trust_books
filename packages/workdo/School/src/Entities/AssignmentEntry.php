<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class AssignmentEntry extends Model
{
    protected $fillable = [
        'class_id',
        'subject_id',
        'students'
    ];

    protected $casts = [
        'students' => 'array', // auto convert JSON ↔ array
    ];

    public function classroom()
    {
        return $this->belongsTo(\Workdo\School\Entities\Classroom::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(\Workdo\School\Entities\Subject::class, 'subject_id');
    }
}
