<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'owner_name',
        'email',
        'contact_number',
        'address',
        'workspace',
        'created_by',
    ];
}
