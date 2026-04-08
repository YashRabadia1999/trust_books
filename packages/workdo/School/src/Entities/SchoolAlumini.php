<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolAlumini extends Model
{
    protected $fillable = [
        'student_id',
        'batch_year',
        'current_position',
        'contact',
        'email'
    ];

    public function student()
    {
        return $this->hasOne(SchoolStudent::class, 'id' , 'student_id');
    }
}
