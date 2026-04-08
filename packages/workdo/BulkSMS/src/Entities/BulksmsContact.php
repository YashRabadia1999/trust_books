<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulksmsContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'city',
        'state',
        'zip',
        'workspace',
        'created_by'
    ];
}
