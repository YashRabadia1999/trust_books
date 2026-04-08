<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'student_id',
        'issue_date',
        'due_date',
        'status',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingInvoiceFactory::new();
    }

    public static function invoiceNumberFormat($number, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id);
        } elseif (!empty($company_id) && !empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id, $workspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $data = !empty($company_settings['invoice_prefix']) ? $company_settings['invoice_prefix'] : '#INVO0';

        return $data . sprintf("%05d", $number);
    }

    public function student()
    {
        return $this->hasOne(DrivingStudent::class, 'id', 'student_id');
    }
    public function invoicestudent()
    {
        return $this->hasOne(DrivingInvoice::class, 'id', 'student_id');
    }

    public static $statues = [
        'Draft',
        'Posted',
        'Paid',
    ];

    public function getTotal()
    {
        $subTotal = 0;
        foreach ($this->items as $product) {
            $subTotal += ($product->fees * $product->quantity);
        }
        return $subTotal;
    }

    public function getDue()
    {
        $amount = DrivingInvoicePayment::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('invoice_id', $this->id)->sum('amount');
        $dueAmount = $this->getTotal() - $amount;
        return $dueAmount;
    }

    public function items()
    {
        return $this->hasMany(DrivingInvoiceItem::class, 'invoice_id', 'id');
    }
}
