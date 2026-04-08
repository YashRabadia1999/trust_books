<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetServiceProcessSteps extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'service_id',
        'process_name',
        'process_description',
        'workspace',
        'created_by',
    ];
}
