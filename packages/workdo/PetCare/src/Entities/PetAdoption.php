<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetAdoption extends Model
{
    use HasFactory;

    protected $fillable = [
        'adoption_number',
        'pet_name',
        'species',
        'breed',
        'adoption_amount',
        'date_of_birth',
        'gender',
        'availability',
        'health_status',
        'classification_tags',
        'pet_image',
        'description',
        'workspace',
        'created_by',
    ];

    public static $availability = [
        'available_now' => 'Available Now',
        'coming_soon' => 'Coming Soon',
        'adopted' => 'Adopted',
        'not_available' => 'Not Available',
    ];

    public static $health_status = [
        'health_checked'           => 'Health Checked',
        'under_observation'        => 'Under Observation',
        'special_needs'            => 'Special Needs',
        'not_available'            => 'Not Available',
        'healthy'                  => 'Healthy',
        'vaccinated'               => 'Vaccinated',
        'neutered_spayed'          => 'Neutered/Spayed',
        'partially_vaccinated'     => 'Partially Vaccinated',
        'under_treatment'          => 'Under Treatment',
        'injured'                  => 'Injured',
        'senior_conditions'        => 'Senior - Age-related Conditions',
        'needs_regular_medication' => 'Needs Regular Medication',
        'visually_impaired'        => 'Visually Impaired',
        'hearing_impaired'         => 'Hearing Impaired',
        'recently_recovered'       => 'Recently Recovered',
    ];

    public static function petAdoptionNumberFormat($number)
    {
        $data = '#PAD';
        return $data. sprintf("%05d", $number);
    }
}

