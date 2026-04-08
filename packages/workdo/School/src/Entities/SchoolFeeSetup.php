<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class SchoolFeeSetup extends Model
{
    protected $fillable = [
        'name',
        'academic_year_id',
        'term_id',
        'class_id',
        'total_amount',
        'discount_amount',
        'auto_invoice',
        'send_email',
        'send_sms',
        'status',
        'due_date',
        'items',
        'description',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'items' => 'array',
        'auto_invoice' => 'boolean',
        'send_email' => 'boolean',
        'send_sms' => 'boolean',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public static $status = [
        'Active' => 'Active',
        'Inactive' => 'Inactive'
    ];

    // Relationships
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(SchoolStudent::class, 'class_id', 'class_id');
    }

    public function generatedInvoices()
    {
        return $this->hasMany(SchoolGeneratedInvoice::class, 'fee_setup_id');
    }

    // Helper methods
    public function calculateTotalAmount()
    {
        $total = 0;
        if (!empty($this -> items)) {
            foreach ($this -> items as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        }
        return $total - $this -> discount_amount;
    }

    public function getTotalActiveStudents()
    {
        return $this -> students() -> count();
    }
}
