<?php

namespace Workdo\SmsCredit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsCreditBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'workspace',
        'total_credits',
        'used_credits',
        'remaining_credits'
    ];

    protected $casts = [
        'total_credits' => 'integer',
        'used_credits' => 'integer',
        'remaining_credits' => 'integer',
    ];

    public static function getBalance($clientId, $workspace)
    {
        return self::firstOrCreate(
            ['client_id' => $clientId, 'workspace' => $workspace],
            ['total_credits' => 0, 'used_credits' => 0, 'remaining_credits' => 0]
        );
    }

    public function addCredits($credits, $description = null)
    {
        $this->total_credits += $credits;
        $this->remaining_credits += $credits;
        $this->save();

        // Log transaction
        SmsCreditTransaction::create([
            'client_id' => $this->client_id,
            'workspace' => $this->workspace,
            'credits' => $credits,
            'type' => 'purchase',
            'description' => $description ?? 'Credits purchased'
        ]);
    }

    public function useCredits($credits, $description = null)
    {
        if ($this->remaining_credits < $credits) {
            return false;
        }

        $this->used_credits += $credits;
        $this->remaining_credits -= $credits;
        $this->save();

        // Log transaction
        SmsCreditTransaction::create([
            'client_id' => $this->client_id,
            'workspace' => $this->workspace,
            'credits' => -$credits,
            'type' => 'usage',
            'description' => $description ?? 'Credits used for SMS'
        ]);

        return true;
    }
}
