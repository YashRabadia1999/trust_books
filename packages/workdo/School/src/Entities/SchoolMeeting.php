<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolMeeting extends Model
{
    protected $fillable = [
        'parent_id',
        'teacher_id',
        'meeting_date',
        'agenda'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class , 'id' , 'teacher_id');
    }

    public function parent()
    {
        return $this->hasOne(SchoolParent::class , 'id' , 'parent_id');
    }
}
