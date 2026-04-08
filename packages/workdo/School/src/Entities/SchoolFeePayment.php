<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolFeePayment extends Model
{
    protected $fillable = [
        'fee_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
        'notes',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public static $payment_methods = [
        'Cash' => 'Cash',
        'Bank Transfer' => 'Bank Transfer',
        'Check' => 'Check',
        'Credit Card' => 'Credit Card',
        'Online Payment' => 'Online Payment',
        'Other' => 'Other'
    ];

    // Relationships
    public function fee()
    {
        return $this->belongsTo(SchoolFees::class, 'fee_id');
    }

    public function student()
    {
        return $this->hasOneThrough(
            SchoolStudent::class,
            SchoolFees::class,
            'id', // Foreign key on SchoolFees table
            'id', // Foreign key on SchoolStudent table
            'fee_id', // Local key on SchoolFeePayment table
            'student_id' // Local key on SchoolFees table
        );
    }
}
