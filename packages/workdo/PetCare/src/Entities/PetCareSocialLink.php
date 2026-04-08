<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareSocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['social_media_name',
                            'social_media_icon',
                            'social_media_link',
                            'workspace',
                            'created_by',
                          ];

}
