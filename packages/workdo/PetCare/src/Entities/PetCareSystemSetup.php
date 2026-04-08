<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareSystemSetup extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'workspace', 'created_by'];
}
