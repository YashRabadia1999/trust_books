<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'start_time',
        'end_time',
        'all_time',
        'created_by',
        'workspace'
    ];

    public static $week_days = [

        'mon' => 'Monday',
        'tue' => 'Tuesday',
        'wed' => 'Wednesday',
        'thu' => 'Thursday',
        'fri' => 'Friday',
        'sat' => 'Saturday',
        'sun' => 'Sunday',
    ];

    public function class_name()
    {
        return $this->hasOne('Workdo\School\Entities\Classroom', 'id', 'class_id');
    }

    public function subject_name()
    {
        return $this->hasOne('Workdo\School\Entities\Subject', 'id', 'class_id');
    }
    
}
