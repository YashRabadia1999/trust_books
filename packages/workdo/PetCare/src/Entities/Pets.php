<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pets extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pet_owner_id',
        'pet_name',
        'species',
        'breed',
        'date_of_birth',
        'gender',
        'workspace',
        'created_by',
    ];
}
