<?php

namespace Workdo\SmsCredit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsCreditPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'workspace',
        'created_by',
        'credits_purchased',
        'amount_paid',
        'payment_method',
        'transaction_id',
        'mobile_number',
        'status',
        'payment_response'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'credits_purchased' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
