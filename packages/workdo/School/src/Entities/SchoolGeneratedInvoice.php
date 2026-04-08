<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Workdo\School\Entities\SchoolFeeSetup;
use Workdo\School\Entities\SchoolStudent;
use Workdo\ProductService\Entities\ProductService;



class SchoolGeneratedInvoice extends Model
{
    protected $fillable = [
        'fee_setup_id',
        'student_id',
        'invoice_id',
        'amount',
        'status',
        'email_sent',
        'sms_sent',
        'due_date',
        'workspace',
        'created_by'
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'sms_sent' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public static $status = [
        'Generated' => 'Generated',
        'Sent' => 'Sent',
        'Paid' => 'Paid',
        'Overdue' => 'Overdue'
    ];

    // ✅ Relationships
    public function feeSetup()
    {
        return $this->belongsTo(SchoolFeeSetup::class, 'fee_setup_id');
    }

    public function student()
    {
        return $this->belongsTo(SchoolStudent::class, 'student_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    
    public function items()
    {
        return $this->hasMany(InvoiceProduct::class, 'invoice_id', 'invoice_id');
    }
    public function product()
{
    return $this->belongsTo(ProductService::class, 'product_id');
}

}
