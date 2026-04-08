<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetGroomingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'package_name',
        'package_icon',
        'package_features',
        'description',
        'total_package_amount',
        'workspace',
        'created_by',
    ];

    public function services()
    {
        return $this->belongsToMany(PetService::class, 'pet_package_service', 'package_id', 'service_id')
                    ->withPivot('service_price')
                    ->orderBy('id', 'ASC');
    }

    public function vaccines()
    {
        return $this->belongsToMany(PetVaccine::class, 'pet_package_vaccine', 'package_id', 'vaccine_id')
                    ->withPivot('vaccine_price')
                    ->orderBy('id', 'ASC');
    }

}
