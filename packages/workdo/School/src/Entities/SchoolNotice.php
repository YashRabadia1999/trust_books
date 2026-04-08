<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolNotice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'posted_by',
        'date_posted',
        'target_audience'
    ];

    public static $audiences   = [
        'staff'    => "staff",
        'parent'   => "parent",
        'student'  => "student"
    ];

    public function postedBy()
    {
        return $this->hasOne(Employee::class , 'id' , 'posted_by');
    }
}
