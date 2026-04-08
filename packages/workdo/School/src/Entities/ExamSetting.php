<?php

namespace Workdo\School\Entities;

use Illuminate\Database\Eloquent\Model;

class ExamSetting extends Model
{
    protected $table = 'exam_settings';

    protected $fillable = [
        'assignment_percentage',
        'exam_percentage',
        
    ];
}
