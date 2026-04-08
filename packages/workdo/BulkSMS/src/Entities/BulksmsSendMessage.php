<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulksmsSendMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id',
        'sms',
        'status',
        'workspace',
        'created_by'
    ];
}
