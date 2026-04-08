<?php

namespace Workdo\School\Entities;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'date',
        'student_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'state',
        'city',
        'zip_code',
        'phone',
        'email',
        'password',
        'previous_school',
        'student_image',
        'medical_history',
        'father_name',
        'father_number',
        'father_occupation',
        'father_email',
        'father_password',
        'father_address',
        'father_image',
        'mother_name',
        'mother_number',
        'mother_occupation',
        'mother_email',
        'mother_password',
        'mother_address',
        'mother_image',
        'guardian',
        'leaving_certificate',
        'marksheet',
        'birth_certificate',
        'address_proof',
        'bonafide_certificate',
        'converted_student_id',
        'created_by',
        'workspace',
        'previous_school_certificate',
        'gov_issued_id',
        'education_level'
    ];
    public static function admissionNumberFormat($number,$company_id = null,$workspace = null)
    {
        if(!empty($company_id) && empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id);
        }
        elseif(!empty($company_id) && !empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id,$workspace);
        }
        else
        {
            $company_settings = getCompanyAllSetting();
        }
        $data = !empty($company_settings['admission_prefix']) ? $company_settings['admission_prefix'] : '#ADMI00';

        return $data. sprintf("%05d", $number);
    }
    public static function starting_number($id)
    {
        $key = 'admission_starting_number';
        if(!empty($key)){

            $data = [
                'key' => $key,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];
            Setting::updateOrInsert($data, ['value' => $id]);
            // Settings Cache forget
            comapnySettingCacheForget();
            return true;
        }
        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'converted_student_id');
    }
}
