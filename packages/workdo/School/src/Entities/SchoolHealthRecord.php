<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolHealthRecord extends Model
{
    protected $fillable = [
        'student_id',
        'checkup_date',
        'doctor_name',
        'diagnosis',
        'treatment',
        'vaccination_status',
        'allergies',
        'chronic_conditions'
    ];

    public static $status   = [
        'Completed'  => "Completed",
        'Pending'    => "Pending"
    ];

    public function student(){
        return $this->hasOne(SchoolStudent::class,'id','student_id');
    }
}
