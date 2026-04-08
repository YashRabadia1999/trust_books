<?php

namespace Workdo\SmsCredit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsCreditTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'workspace',
        'credits',
        'type',
        'description',
        'reference'
    ];

    protected $casts = [
        'credits' => 'integer',
    ];
}
