<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingTestHub extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'student_id',
        'teacher_id',
        'test_type_id',
        'test_date',
        'test_score',
        'test_result',
        'remarks',
        'workspace',
        'created_by',
    ];

    public function testTypeName()
    {
        return $this->hasMany(DrivingTestType::class, 'test_type_id');
    }
    public function teacherName()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'teacher_id');
    }

}
