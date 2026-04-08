<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulksmsGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile_no',
        'workspace',
        'created_by'
    ];
}
