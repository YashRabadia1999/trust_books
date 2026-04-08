<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareFaqQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'faq_id',
        'question',        
        'answer',
        'workspace',
        'created_by'
    ];
}
