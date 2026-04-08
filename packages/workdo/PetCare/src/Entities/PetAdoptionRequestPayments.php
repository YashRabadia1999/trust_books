<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetAdoptionRequestPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'adoption_request_id',
        'payer_name',
        'payment_date',
        'amount',
        'reference',
        'description',
        'payment_receipt',
        'payment_method',
        'payment_status',
        'workspace',
        'created_by'
    ];

    public static $payment_method = [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'cheque' => 'Cheque',
        'other' => 'Other',
    ];
}
