<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingInvoiceItem extends Model
{
    use HasFactory;

    protected $table='driving_invoices_items';
    protected $fillable = [
        'invoice_id',
        'driving_class_id',
        'quantity',
        'fees',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingInvoiceItemFactory::new();
    }
}
