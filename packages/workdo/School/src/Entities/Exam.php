<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = [
        'academic_year_id',
        'term_id',
        'classroom_id',
        'exam_name',
        'created_by',
        'user_id'
    ];

    // Relationship to AcademicYear
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    // Relationship to Term
    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    // Relationship to Classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    // Relationship to exam entries
    public function entries()
    {
        return $this->hasMany(ExamEntry::class, 'exam_id');
    }
}
