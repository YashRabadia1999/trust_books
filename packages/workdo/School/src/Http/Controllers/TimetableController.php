<?php

namespace Workdo\School\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Entities\Subject;
use Workdo\School\Entities\Timetable;
use Workdo\School\Events\CreateTimetable;
use Workdo\School\Events\DestorySubject;
use Workdo\School\Events\DestoryTimetable;
use Workdo\School\Events\UpdateTimetable;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('school_timetable manage')) {
            if(Auth::user()->type == 'student'){
                $student = SchoolStudent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id',Auth::user()->id)->first();
                $classroom = Classroom::where('id',$student->class_name)->first();
                $timetables = Timetable::where('timetables.class_id' , $classroom->id)
                ->where('timetables.workspace', getActiveWorkSpace())
                ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name')
                ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                ->leftJoin('subjects', function ($join) {
                    $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                })
                ->groupBy('classrooms.id') // Assuming classrooms.id is the primary key
                ->get();
            }elseif (Auth::user()->type == 'parent') {
                $parent = SchoolParent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id',Auth::user()->id)->first();
                $student = SchoolStudent::whereRaw("FIND_IN_SET($parent->user_id, parent_id)")->first();
                $classroom = Classroom::where('id',$student->class_name)->first();
                $timetables = Timetable::where('timetables.class_id' , $classroom->id)
                ->where('timetables.workspace', getActiveWorkSpace())
                ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name')
                ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                ->leftJoin('subjects', function ($join) {
                    $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                })
                ->groupBy('classrooms.id') // Assuming classrooms.id is the primary key
                ->get();
            }elseif (Auth::user()->type == 'staff') {
               $subject = Subject::where('workspace', getActiveWorkSpace())->where('teacher',Auth::user()->id)->pluck('id');

                $timetables = Timetable::whereIn('timetables.subject_ids' , $subject)
                ->where('timetables.workspace', getActiveWorkSpace())
                ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name')
                ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                ->leftJoin('subjects', function ($join) {
                    $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                })
                ->groupBy('classrooms.id') // Assuming classrooms.id is the primary key
                ->get();
            }
            else{
            $timetables = Timetable::where('timetables.workspace', getActiveWorkSpace())
                ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name')
                ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                ->leftJoin('subjects', function ($join) {
                    $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                })
                ->groupBy('classrooms.id') // Assuming classrooms.id is the primary key
                ->get();
            }
            return view('school::timetable.index', compact('timetables'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_timetable create')) {
            $timetable = Timetable::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_id');
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->whereNotIn('id', $timetable)->get()->pluck('class_name', 'id');
            $subjects = Subject::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('subject_name', 'subject_name');
            $week_days = Timetable::$week_days;

            return view('school::timetable.create', compact('classRoom', 'week_days', 'subjects'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_timetable create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'class_id'   => 'required',
                    'start_time' => 'required',
                    'end_time'   => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $times = [];

            $week_days = Timetable::$week_days;

            foreach ($week_days as $key => $week_day) {
                foreach ($request->subject as $key => $sub) {
                    $first_time = $request->first_time[$key][$week_day];
                    $last_time = $request->last_time[$key][$week_day];

                    $times[$week_day][$sub]['first_time']  = $first_time;
                    $times[$week_day][$sub]['last_time']  = $last_time;
                }
            }

            $timetable = new Timetable();
            $timetable->class_id = $request->class_id;
            $timetable->start_time = $request->start_time;
            $timetable->end_time = $request->end_time;
            $classSubjects = $request->subject;
            $subjectIds = [];

            foreach ($classSubjects as $subject) {
                $subjectModel = Subject::find($subject);
                if ($subjectModel) {
                    $subjectIds[] = $subjectModel->id;
                }
            }

            $timetable->subject_ids = implode(',', $subjectIds);
            $jsonAllTimeData = json_encode($times);
            $timetable->all_time = $jsonAllTimeData;
            $timetable->created_by   = creatorId();
            $timetable->workspace    = getActiveWorkSpace();
            $timetable->save();
            event(new CreateTimetable($request,$timetable));

            return redirect()->route('timetable.index')->with('success', 'The timetable has been created successfully.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('school_timetable edit')) {

            $ids = decrypt($id);
            $timetables = Timetable::find($ids);
            $week_days = Timetable::$week_days;
            $subjects = Subject::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('class_id', $timetables->class_id)->get();
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');


            $totalTimePerDays = [];
            foreach ($week_days as $day) {
                $startTimeObj = \Carbon\Carbon::parse($timetables->start_time);
                $endTimeObj = \Carbon\Carbon::parse($timetables->end_time);
                $totalTimePerDays[$day] = $endTimeObj->diff($startTimeObj)->format('%H:%I');
            }

            return view('school::timetable.edit', compact('timetables', 'classRoom', 'week_days', 'subjects', 'totalTimePerDays'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('school_timetable edit')) {
            $times = [];

            $week_days = Timetable::$week_days;

            foreach ($week_days as $key => $week_day) {
                foreach ($request->subject as $key => $sub) {
                    $first_time = $request->first_time[$key][$week_day];
                    $last_time = $request->last_time[$key][$week_day];

                    $times[$week_day][$sub]['first_time']  = $first_time;
                    $times[$week_day][$sub]['last_time']  = $last_time;
                }
            }

            $timetable = Timetable::find($id);

            if (!$timetable) {
                return redirect()->back()->with('error', 'Timetable not found.');
            }

            $timetable->class_id = $request->class_id;
            $timetable->start_time = $request->start_time;
            $timetable->end_time = $request->end_time;

            $classSubjects = $request->subject;
            $subjectIds = [];

            foreach ($classSubjects as $subject) {
                $subjectModel = Subject::find($subject);
                if ($subjectModel) {
                    $subjectIds[] = $subjectModel->id;
                }
            }

            $timetable->subject_ids = implode(',', $subjectIds);
            $jsonAllTimeData = json_encode($times);
            $timetable->all_time = $jsonAllTimeData;

            $timetable->save();
            event(new UpdateTimetable($request,$timetable));

            return redirect()->route('timetable.index')->with('success', 'The timetable details are updated successfully.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('school_timetable delete')) {
            $Ids = decrypt($id);
            $timetable = Timetable::find($Ids);
            event(new DestoryTimetable($timetable));
            $timetable->delete();

            return redirect()->route('timetable.index')->with('success', 'The timetable has been deleted.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function getsubject(Request $request)
    {
        $subjects = Subject::where('class_id', $request->subject_id)->get();
        $htmlContent = '';
        $week_days = Timetable::$week_days;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        $totalTimePerDays = [];
        $totalTime = 0;
        foreach ($week_days as $day) {
            $startTimeObj = \Carbon\Carbon::parse($startTime);
            $endTimeObj = \Carbon\Carbon::parse($endTime);
            $totalTimePerDays[$day] = $endTimeObj->diff($startTimeObj)->format('%H:%I');
        }

        $htmlContent .= view('school::timetable.append', compact('subjects', 'week_days', 'totalTimePerDays'))->render();

        $responseData = [
            'is_success' => true,
            'message' => '',
            'html' => $htmlContent,
            'subjects'=> $subjects->count()
        ];

        return response()->json($responseData);
    }
}
