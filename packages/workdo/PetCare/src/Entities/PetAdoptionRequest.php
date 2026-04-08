<?php

namespace Workdo\PetCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetAdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_adoption_id',
        'adoption_request_number',
        'adopter_name',
        'email',
        'contact_number',
        'address',
        'reason_for_adoption',
        'request_status',
        'workspace',
        'created_by',
    ];

    public static $adoption_request_status = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
    ];

    public static function petAdoptionRequestNumberFormat($number)
    {
        $data = '#ADR0';
        return $data. sprintf("%05d", $number);
    }

    public function petAdoption()
    {
        return $this->belongsTo(PetAdoption::class, 'pet_adoption_id', 'id');
    }

    public function petAdoptionRequestPayments()
    {
        return $this->hasMany(PetAdoptionRequestPayments::class, 'adoption_request_id', 'id');
    }

    public function getAdoptionRequestDueAmount()
    {
        $due = 0;

        foreach ($this->petAdoptionRequestPayments as $payment) {
            $due += $payment->amount;
        }

        return ($this->petAdoption->adoption_amount - $due);
    }
}
