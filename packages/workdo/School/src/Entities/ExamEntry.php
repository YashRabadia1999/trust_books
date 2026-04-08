<?php

namespace Workdo\School\Entities;
use Illuminate\Database\Eloquent\Model;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\Exam;

class ExamEntry extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'academic_year_id',
        'term_id',
        'marks_obtained',
         'assignment_marks',
         'user_id'
    ];

    public function student() {
        return $this->belongsTo(SchoolStudent::class);
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }
    
public function academicYear()
{
    return $this->belongsTo(\Workdo\School\Entities\AcademicYear::class, 'academic_year_id');
}

}
