<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetServiceIncludedFeatures extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'service_id',
        'feature_icon',
        'feature_name',
        'feature_description',
        'workspace',
        'created_by',
    ];

}
