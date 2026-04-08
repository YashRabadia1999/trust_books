<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class HostelStudent extends Model
{
    protected $fillable = [
        'student_id',
        'hostel_id',
        'room_id'
    ];

    public function studentName()
    {
        return $this->hasOne(SchoolStudent::class , 'id' , 'student_id');
    }

    public function hostel()
    {
        return $this->hasOne(SchoolHostel::class , 'id' , 'hostel_id');
    }

    public function room()
    {
        return $this->hasOne(SchoolRoom::class , 'id' , 'room_id');
    }
}
