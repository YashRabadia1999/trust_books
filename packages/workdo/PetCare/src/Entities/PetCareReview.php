<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_name',
        'reviewer_email',
        'rating',
        'display_status',
        'review_status',
        'review',
        'workspace',
        'created_by',
    ];
}
