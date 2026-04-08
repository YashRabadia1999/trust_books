<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolTransportRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'start_location',
        'end_location',
        'bus_id'
    ];

    public function bus()
    {
        return $this->hasOne(SchoolBus::class, 'id' , 'bus_id');
    }
}
