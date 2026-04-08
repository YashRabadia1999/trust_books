<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolRoom extends Model
{
    protected $fillable = [
        'hostel_id',
        'room_number',
        'capacity'
    ];

    public function hostel()
    {
        return $this->hasOne(SchoolHostel::class, 'id' , 'hostel_id');
    }
}
