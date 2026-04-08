<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\DataTables\DrivingLessonDatatable;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Workdo\DrivingSchool\Entities\DrivingLesson;
use Workdo\DrivingSchool\Entities\DrivingStudent;

class DrivingLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingLessonDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('drivinglesson manage')) {
            $status = DrivingLesson::$statues;
            return $dataTable->render('driving-school::lesson.index',compact('status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('driving-school::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('drivingclass show') && Auth::user()->type == 'company' || Auth::user()->type == 'staff' || Auth::user()->isAbleTo('drivingclass show') && Auth::user()->type == 'driving student') {
            $id       = Crypt::decrypt($id);
            $lesson = DrivingLesson::where('id', $id)->where('workspace', getActiveWorkSpace())->first();
            $present = explode(',', $lesson->present_student_id);
            $absent = explode(',', $lesson->absent_student_id);
            $class = DrivingClass::where('id', $lesson->class_id)->where('workspace', getActiveWorkSpace())->first();
            $status = DrivingLesson::$statues;
            return view('driving-school::lesson.show', compact('lesson', 'class', 'present', 'absent', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('driving-school::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function storeAttendance(Request $request)
    {
        $lesson_id = DrivingLesson::find($request->lesson_id);
        $lesson_id->present_student_id = str_replace($request->student_id, '', $lesson_id->present_student_id);

        $lesson_id->absent_student_id = str_replace($request->student_id, '', $lesson_id->absent_student_id);

        $lesson_id->present_student_id = trim(preg_replace('/,+/', ',', $lesson_id->present_student_id), ',');
        $lesson_id->absent_student_id = trim(preg_replace('/,+/', ',', $lesson_id->absent_student_id), ',');
        if ($request->status == 'present') {

            if (empty($lesson_id->present_student_id)) {
                $present_student_id = $request->student_id;
            } else {
                $result = explode(',', $lesson_id->present_student_id);
                $results = explode(',', $request->student_id);

                $lessons = array_merge($result, $results);
                $present_student_id = implode(',', $lessons);
            }
            $lesson_id->present_student_id = $present_student_id;
        } else {

            if (empty($lesson_id->absent_student_id)) {
                $absent_student_id = $request->student_id;
            } else {
                $result = explode(',', $lesson_id->absent_student_id);
                $results = explode(',', $request->student_id);

                $lessons = array_merge($result, $results);
                $absent_student_id = implode(',', $lessons);
            }
            $lesson_id->absent_student_id = $absent_student_id;
        }

        $lesson_id->save();

        return response()->json(['success' => true]);
    }

    public function statusChange(Request $request, $id)
    {
        $status           = $request->status;
        $lesson         = DrivingLesson::find($id);
        $lesson->status = $status;
        $lesson->save();

        return redirect()->back()->with('success', __('The status has been changed successfully'));
    }
}
