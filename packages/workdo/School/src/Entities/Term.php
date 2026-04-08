<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'start_date', 'end_date', 'created_by', 'workspace'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
