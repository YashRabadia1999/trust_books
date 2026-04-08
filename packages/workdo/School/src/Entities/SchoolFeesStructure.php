<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolFeesStructure extends Model
{
    protected $fillable = [
        'class_id',
        'fee_type',
        'amount',
        'due_date'
    ];

    public function class(){
        return $this->hasOne(Classroom::class,'id','class_id');
    }
}
