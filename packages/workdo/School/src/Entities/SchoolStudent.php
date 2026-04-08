<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'parent_id',
        'class_name',
        'grade_name',
        'roll_number',
        'name',
        'student_gender',
        'std_date_of_birth',
        'std_address',
        'std_state',
        'std_city',
        'std_zip_code',
        'contact',
        'email',
        'password',
        'student_image',
        'father_name',
        'father_number',
        'father_occupation',
        'father_email',
        'father_password',
        'father_address',
        'father_image',
        'mother_name',
        'mother_number',
        'mother_occupation',
        'mother_email',
        'mother_password',
        'mother_address',
        'mother_image',
        'blood_group',
        'allergies',
        'chronic_conditions',
        'emergency_contact',
        'last_checkup',
        'client',
        'attachments',
        'workspace',
        'created_by'
    ];

    public function class(){
        return $this->hasOne(Classroom::class,'id','class_name');
    }
    public function grade(){
        return $this->belongsTo(SchoolGrade::class ,'grade_name');
    }

    public function schoolStudents()
    {
        return $this->hasMany(SchoolStudent::class, 'class_name');
    }

    public function fees(){
        return $this->hasMany(SchoolFees::class, 'student_id');
    }

    public function healthRecords(){
        return $this->hasMany(SchoolHealthRecord::class, 'student_id');
    }
    public function examEntries()
    {
        return $this->hasMany(ExamEntry::class, 'student_id', 'id');
    }
    public function assignmentEntries()
    {
        return $this->hasMany(AssignmentEntry::class, 'student_id', 'id');
    }
}
