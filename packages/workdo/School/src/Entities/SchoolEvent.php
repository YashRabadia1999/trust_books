<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    protected $fillable = [
        'student_id',
        'event_name',
        'event_date',
        'location',
        'description'
    ];

    public function student(){
        return $this->hasOne(SchoolStudent::class,'id','student_id');
    }
}
