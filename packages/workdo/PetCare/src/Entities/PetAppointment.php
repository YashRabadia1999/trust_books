<?php

namespace Workdo\PetCare\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'appointment_number',
        'pet_owner_id',
        'pet_id',
        'assigned_staff_id',
        'appointment_date',
        'appointment_time',
        'appointment_status',
        'total_service_package_amount',
        'notes',
        'workspace',
        'created_by',
    ];

    public static $pet_appointment_status = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
    ];

    public static function petAppointmentNumberFormat($number)
    {
        $data = '#APP0';
        return $data . sprintf("%05d", $number);
    }

    public function petOwner()
    {
        return $this->belongsTo(PetOwner::class, 'pet_owner_id', 'id');
    }

    public function pet()
    {
        return $this->belongsTo(Pets::class, 'pet_id', 'id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id', 'id');
    }

    public function appointmentServices()
    {
        return $this->belongsToMany(PetService::class, 'pet_appointment_services', 'appointment_id', 'service_id');
    }

    public function appointmentPackages()
    {
        return $this->belongsToMany(PetGroomingPackage::class, 'pet_appointment_packages', 'appointment_id', 'package_id');
    }

    public function petcarePayments()
    {
        return $this->hasMany(PetCareBillingPayments::class, 'appointment_id', 'id');
    }

    public function getDueAmount()
    {
        $due = 0;

        foreach ($this->petcarePayments as $payment) {
            $due += $payment->amount;
        }

        return ($this->total_service_package_amount - $due);
    }
}
