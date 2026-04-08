<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'workspace',
        'created_by'
    ];
}
