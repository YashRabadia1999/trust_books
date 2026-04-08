<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolTransportFee extends Model
{
    protected $fillable = [
        'student_id',
        'route_id',
        'amount',
        'status'
    ];

    public function student()
    {
        return $this->hasOne(SchoolStudent::class, 'id' , 'student_id');
    }

    public function route()
    {
        return $this->hasOne(SchoolTransportRoute::class, 'id' , 'route_id');
    }

    public static $status   = [
        'Paid'   => "Paid",
        'Unpaid' => "Unpaid"
    ];
}
