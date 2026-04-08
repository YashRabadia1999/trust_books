<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetCareContact extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','subject','message','status','workspace', 'created_by'];

    public static $Status = [
        'new' => 'New',
        'in_progress' => 'In Progress',
        'replied' => 'Replied',
        'closed' => 'Closed',
        'spam' => 'Spam',
    ];
}
