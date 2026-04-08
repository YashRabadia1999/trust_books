<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetServiceReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_name',
        'reviewer_email',
        'service_id',
        'rating',
        'display_status',
        'review_status',
        'review',
        'workspace',
        'created_by',
    ];

    public function service()
    {
        return $this->belongsTo(PetService::class,'service_id','id');
    }
}
