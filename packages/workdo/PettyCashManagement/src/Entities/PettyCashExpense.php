<?php

namespace Workdo\PettyCashManagement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PettyCashExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'type',
        'amount',
        'remarks',
        'status',
        'approved_at',
        'approved_by',
        'workspace',
        'created_by',
    ];

    public function pettyCashRequest()
    {
        return $this->belongsTo(PettyCashRequest::class, 'request_id');
    }

    public function reimbursement()
    {
        return $this->belongsTo(Reimbursement::class, 'request_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
