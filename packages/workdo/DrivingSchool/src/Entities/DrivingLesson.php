<?php

namespace Workdo\DrivingSchool\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Events\CreateDrivingLesson;
use Workdo\DrivingSchool\Events\UpdateDrivingLesson;

class DrivingLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'driving_lesson_id',
        'class_id',
        'name',
        'start_date_time',
        'end_date_time',
        'student_id',
        'present_student_id',
        'absent_student_id',
        'status',
        'workspace',
        'created_by',
    ];
    public static $statues = [
        'Draft',
        'Start',
        'Complete',
        'Cancel',
    ];

    protected static function newFactory()
    {
        return \Workdo\DrivingSchool\Database\factories\DrivingLessonFactory::new();
    }
    public static function lessonNumberFormat($number)
    {
        $company_settings = getCompanyAllSetting();
        $data = !empty($company_settings['lesson_prefix']) ? $company_settings['lesson_prefix'] : '#Less00';

        return $data . sprintf("%03d", $number);
    }
    public function teacherName()
    {
        return $this->hasOne(\App\Models\User::class,'id','teacher_id');
    }

    public static function getFirstSeventhWeekDay($week)
    {
        $first_day = $seventh_day = null;

        if (isset($week)) {
            $first_day   = Carbon::now()->addWeeks($week)->startOfWeek();
            $seventh_day = Carbon::now()->addWeeks($week)->endOfWeek();
        }

        $dateCollection['first_day']   = $first_day;
        $dateCollection['seventh_day'] = $seventh_day;

        $period = CarbonPeriod::create($first_day, $seventh_day);

        foreach ($period as $key => $dateobj) {
            $dateCollection['datePeriod'][$key] = $dateobj;
        }
        return $dateCollection;
    }

    public static function createDrivingLesson($class, $lessonDate, $lessonCount)
    {
        $lesson = new DrivingLesson();
        $lesson->driving_lessons_id = $lessonCount;
        $lesson->class_id = $class->id;
        $lesson->name = $class->name;
        $lesson->start_date_time = $lessonDate->copy();
        $lesson->end_date_time = $lessonDate->copy()->addHour();
        $lesson->student_id = is_array($class->student_id) ? explode(',', $class->student_id) : $class->student_id;
        $lesson->status = 0;
        $lesson->workspace = getActiveWorkSpace();
        $lesson->created_by = Auth::user()->id;
        $lesson->save();

        event(new CreateDrivingLesson($lesson));
    }

    public static function updateLessons(DrivingClass $drivingclass)
    {
        $existingLessons = DrivingLesson::where('class_id', $drivingclass->id)->get();

        foreach ($existingLessons as $lessonData) {
            $lessonData->class_id = $drivingclass->id;
            $lessonData->name = $drivingclass->name;
            $lessonData->start_date_time = $drivingclass->start_date_time;
            $lessonData->end_date_time = $drivingclass->end_date_time;
            $lessonData->student_id = is_array($drivingclass->student_id) ? implode(',', $drivingclass->student_id) : $drivingclass->student_id;
            $lessonData->status = 0;
            $lessonData->workspace = getActiveWorkSpace();
            $lessonData->created_by = Auth::user()->id;
            $lessonData->save();

            event(new UpdateDrivingLesson($lessonData));
        }
    }
}