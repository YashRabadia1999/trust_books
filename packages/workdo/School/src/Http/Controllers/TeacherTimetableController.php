<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Subject;
use Workdo\School\Entities\Timetable;

class TeacherTimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('school_teachertimetable manage')) {
            if (Auth::user()->type == 'staff') {
                $subject = Subject::where('workspace', getActiveWorkSpace())->where('teacher', Auth::user()->id)->pluck('id');
                $teacherTimetables = Timetable::whereIn('timetables.subject_ids', $subject)
                    ->where('timetables.workspace', getActiveWorkSpace())
                    ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                    ->leftJoin('subjects', function ($join) {
                        $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                    })
                    ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name', 'users.name as teacher_name')
                    ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                    ->leftJoin('users', 'users.id', '=', 'subjects.teacher')
                    ->groupBy('classrooms.id')
                    ->get();
            } else {
                $teacherTimetables = Timetable::where('timetables.workspace', getActiveWorkSpace())
                    ->leftJoin('classrooms', 'classrooms.id', '=', 'timetables.class_id')
                    ->leftJoin('subjects', function ($join) {
                        $join->on(\DB::raw('FIND_IN_SET(subjects.id, timetables.subject_ids)'), '>', \DB::raw('0'));
                    })
                    ->select('classrooms.*', 'timetables.class_id as ID', 'timetables.*', 'classrooms.class_name as class_name', 'users.name as teacher_name')
                    ->addSelect(\DB::raw('GROUP_CONCAT(subjects.subject_name) as subjects_name'))
                    ->leftJoin('users', 'users.id', '=', 'subjects.teacher')
                    ->groupBy('classrooms.id')
                    ->get();
            }
            return view('school::teacher-timetable.index', compact('teacherTimetables'));
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
        if (Auth::user()->isAbleTo('school_teachertimetable create')) {

            return view('school::teacher-timetable.create');
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
        if (Auth::user()->isAbleTo('school_teachertimetable create')) {

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
        if (Auth::user()->isAbleTo('school_teachertimetable edit')) {

            return view('school::teacher-timetable.edit');
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
        if (Auth::user()->isAbleTo('school_teachertimetable edit')) {

            return view('school::teacher-timetable.edit');
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
        if (Auth::user()->isAbleTo('school_teachertimetable delete')) {

        } else {
            return redirect()->back()->with('error', 'permission Denied');

        }
    }
}
