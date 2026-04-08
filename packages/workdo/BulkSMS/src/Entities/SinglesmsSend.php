<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinglesmsSend extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile_no',
        'sms',
        'status',
        'workspace',
        'created_by'
    ];
}
