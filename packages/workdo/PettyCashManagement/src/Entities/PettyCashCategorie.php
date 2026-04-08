<?php

namespace Workdo\PettyCashManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PettyCashCategorie extends Model
{
    use HasFactory;

    protected $fillable = ['name','workspace','created_by'];
}
