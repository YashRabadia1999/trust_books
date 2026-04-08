<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareFAQ extends Model
{
    use HasFactory;

    protected $table = 'pet_care_faqs';

    protected $fillable = [
        'id',
        'faq_icon',        
        'faq_topic',
        'workspace',
        'created_by'
    ];

    public function questionAnswers()
    {
        return $this->hasMany(PetCareFaqQuestionAnswer::class, 'faq_id');
    }
}
