<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class DrivingVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'teacher_id',
        'location',
        'chassis_number',
        'odometer',
        'model_year',
        'engine_transmission',
        'workspace',
        'created_by',
    ];


    public function Teacher()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }
}
