<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrivingLicenceTraking extends Model
{
    use HasFactory;
    protected $table = 'driving_licence_trackings';

    protected $fillable = [
        'id',
        'student_id',
        'licence_type_id',
        'application_date',
        'test_date',
        'test_result',
        'licence_issue_date',
        'licence_number',
        'licence_expiry_date',
        'workspace',
        'created_by',
    ];
}
