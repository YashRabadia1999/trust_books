<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolFees extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'date',
        'status',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function student(){
        return $this->hasOne(SchoolStudent::class,'id','student_id');
    }

    public function payments(){
        return $this->hasMany(SchoolFeePayment::class, 'fee_id');
    }

    public static $status   = [
        'Paid'   => "Paid",
        'Unpaid' => "Unpaid"
    ];
}
