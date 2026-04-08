<?php

namespace Workdo\PettyCashManagement\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PettyCashRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categorie_id',
        'requested_amount',
        'status',
        'remarks',
        'approved_at',
        'approved_by',
        'workspace',
        'created_by',
    ];

    public function userName()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoryName()
    {
        return $this->belongsTo(PettyCashCategorie::class, 'categorie_id');

    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
