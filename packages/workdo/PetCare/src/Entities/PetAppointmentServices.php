<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetAppointmentServices extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id','service_id'];
}
