<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'employee_id',
        'branch_id',
        'department_id',
        'designation_id',
        'company_doj',
        'documents',
        'account_holder_name',
        'account_number',
        'bank_name',
        'bank_identifier_code',
        'branch_location',
        'tax_payer_id',
        'salary_type',
        'salary',
        'is_active',
        'workspace',
        'created_by',
    ];

    public static function employeeIdFormat($number)
    {
        $company_settings = getCompanyAllSetting();

        $employee_prefix = isset($company_settings['employee_prefix']) ? $company_settings['employee_prefix'] : '#EMP000';
        return $employee_prefix . sprintf("%05d", $number);
    }
    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

}
