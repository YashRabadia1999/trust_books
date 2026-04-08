<?php

namespace Workdo\BulkSMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulksmsSend extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'mobile_no',   
        'sms',    
        'workspace',
        'created_by'
    ];
    public function group()
    {
        return $this->belongsTo(BulksmsGroup::class, 'group_id');
    }
}
