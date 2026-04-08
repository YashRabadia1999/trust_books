<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'clock_in',
        'clock_out',
        'total_rest',
        'workspace',
        'created_by',
    ];
    public function student()
    {
        return $this->hasOne('App\Models\User', 'id', 'student_id');
    }
    public static function present_status($student_id, $data)
    {
        return SchoolAttendance::where('student_id', $student_id)->where('date', $data)->first();
    }
}
