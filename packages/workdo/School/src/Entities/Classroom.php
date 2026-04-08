<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'class_capacity',
        'grade_name',
        'created_by',
        'workspace'
    ];
    
    public function grade(){
        return $this->hasOne(SchoolGrade::class,'id','grade_name');
    }
}
