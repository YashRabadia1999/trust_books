<?php

namespace Workdo\PettyCashManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PettyCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'opening_balance',
        'added_amount',
        'total_balance',
        'total_expense',
        'closing_balance',
        'remarks',
        'workspace',
        'created_by',
    ];
}
