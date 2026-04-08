<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Workdo\DrivingSchool\Entities\DrivingLesson;

class DrivingClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'driving_class_id',
        'name',
        'start_date_time',
        'end_date_time',
        'vehicle_id',
        'teacher_id',
        'student_id',
        'location',
        'fees',
        'schedule', 
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingClassFactory::new();
    }
    public static function classNumberFormat($number)
    {
        $company_settings = getCompanyAllSetting();
        $data = !empty($company_settings['class_prefix']) ? $company_settings['class_prefix'] : '#Class00';

        return $data . sprintf("%03d", $number);
    }
    public function lessons()
    {
        return $this->hasMany(DrivingLesson::class, 'class_id');
    }
    public function teacherName()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'teacher_id');
    }

}
