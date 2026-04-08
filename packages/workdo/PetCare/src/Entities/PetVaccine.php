<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetVaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vaccine_name',
        'description',
        'price',
        'workspace',
        'created_by',
    ];
}
