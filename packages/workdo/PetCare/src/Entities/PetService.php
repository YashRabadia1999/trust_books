<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetService extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'service_name',
        'service_icon',
        'description',
        'price',
        'duration',
        'service_image',
        'workspace',
        'created_by',
    ];

    public function serviceIncludedFeatures()
    {
        return $this->hasMany(PetServiceIncludedFeatures::class, 'service_id');
    }

    public function serviceProcessSteps()
    {
        return $this->hasMany(PetServiceProcessSteps::class, 'service_id');
    }

    public function reviews()
    {
        return $this->hasMany(PetServiceReview::class, 'service_id')
        ->where('display_status', 'on')
        ->where('review_status', 'approved');
    }
}
