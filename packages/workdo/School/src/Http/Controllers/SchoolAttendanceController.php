<?php

namespace Workdo\School\Http\Controllers;

use App\Models\User;
use Google\Service\Forms\Grade;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolAttendance;
use Workdo\School\Entities\SchoolGrade;
use Workdo\School\Events\CreateSchoolMarkAttendance;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\SchoolHomework;
use Workdo\School\Events\DestroySchoolMarkAttendance;
use Workdo\School\Events\UpdateSchoolBulkAttendance;
use Workdo\School\Events\UpdateSchoolMarkAttendance;
use Workdo\School\DataTables\MarkAttendanceDataTable;

class SchoolAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(MarkAttendanceDataTable $dataTable)
    {
        $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
        $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');



        return $dataTable->render('school::attendance.index', compact('grade', 'classRoom'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_attendance create')) {
            $student = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'student')->get()->pluck('name', 'id');
            $student->prepend('Select Student', '');
            return view('school::attendance.create', compact('student'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_attendance create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'student_id' => 'required',
                    'date' => 'required',
                    'clock_in' => 'required',
                    'clock_out' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $attendance = SchoolAttendance::where('student_id', '=', $request->student_id)->where('workspace', getActiveWorkSpace())->where('date', '=', $request->date)->where('clock_out', '=', '00:00:00')->get()->toArray();
            if ($attendance) {
                return redirect()->route('school-attendance.index')->with('error', __('Student Attendance Already Created.'));
            } else {
                $employeeAttendance                = new SchoolAttendance();
                $employeeAttendance->student_id   = $request->student_id;
                $employeeAttendance->date          = $request->date;
                $employeeAttendance->status        = 'Present';
                $employeeAttendance->clock_in      = $request->clock_in . ':00';
                $employeeAttendance->clock_out     = $request->clock_out . ':00';
                $employeeAttendance->total_rest    = '00:00:00';
                $employeeAttendance->workspace     = getActiveWorkSpace();
                $employeeAttendance->created_by    = \Auth::user()->id;
                $employeeAttendance->save();

                event(new CreateSchoolMarkAttendance($request, $employeeAttendance));

                return redirect()->route('school-attendance.index')->with('success', __('The student attendance has been created successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('school_attendance create')) {
            $attendance = SchoolAttendance::where('id', $id)->first();
            $student = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'student')->get()->pluck('name', 'id');
            return view('school::attendance.edit', compact('student', 'attendance'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->isAbleTo('school_attendance edit')) {

            $company_settings = getCompanyAllSetting();
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {

                if (!empty($company_settings['defult_timezone'])) {
                    date_default_timezone_set($company_settings['defult_timezone']);
                }
                $time = date("H:i");
                $attendance                = SchoolAttendance::find($id);
                $attendance->clock_out     = $time;
                $attendance->save();

                return redirect()->back()->with('success', __('Student Successfully Clock Out.'));
            } else {
                $attendance                = SchoolAttendance::find($id);
                $attendance->student_id   = $request->student_id;
                $attendance->date          = $request->date;
                $attendance->clock_in      = $request->clock_in;
                $attendance->clock_out     = $request->clock_out;
                $attendance->total_rest    = '00:00:00';

                $attendance->save();
            }
            event(new UpdateSchoolMarkAttendance($request, $attendance));

            return redirect()->route('school-attendance.index')->with('success', __('The student attendance details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('school_attendance delete')) {
            $attendance = SchoolAttendance::find($id);
            event(new DestroySchoolMarkAttendance($attendance));
            $attendance->delete();
            return redirect()->route('school-attendance.index')->with('success', __('The student attendance has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getClass(Request $request)
    {
        if ($request->grade_id == 0) {
            $classes = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('class_name', 'id')->toArray();
        } else {
            $classes = Classroom::where('grade_name', $request->grade_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('class_name', 'id')->toArray();
        }
        return response()->json($classes);
    }

    public function bulkAttendance(Request $request)
    {
        if (Auth::user()->isAbleTo('school_bulkattendance manage')) {
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
            $grade->prepend('Select Grade', '');
            $classroom = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('class_name', 'id');
            $classroom->prepend('Select Class', '');
            $students = [];
            if (!empty($request->grade) && !empty($request->classRoom)) {

                $students = User::where('workspace_id', getActiveWorkSpace())
                    ->leftjoin('school_students', 'users.id', '=', 'school_students.user_id')
                    ->where('users.created_by', creatorId())
                    ->where('users.type', 'student')
                    ->where('school_students.grade_name', $request->grade)
                    ->where('school_students.class_name', $request->classRoom)
                    ->select('users.*', 'users.id as ID', 'school_students.*', 'users.name as name', 'users.email as email', 'users.id as id')
                    ->get();
            }
            return view('school::attendance.bulk', compact('classroom', 'grade', 'students'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BulkAttendanceData(Request $request)
    {
        if (Auth::user()->isAbleTo('school_bulkattendance manage')) {
            if (!empty($request->grade) && !empty($request->classRoom)) {
                $students = $request->student_id;
                $atte      = [];
                if ($students) {
                    foreach ($students as $studentId) {
                        $present = 'present-' . $studentId;
                        $in      = 'in-' . $studentId;
                        $out     = 'out-' . $studentId;
                        $atte[]  = $present;

                        if ($request->$present == 'on') {
                            $in  = date("H:i:s", strtotime($request->$in));
                            $out = date("H:i:s", strtotime($request->$out));

                            $attendance = SchoolAttendance::where('student_id', $studentId)
                                ->where('workspace', getActiveWorkSpace())
                                ->where('date', $request->date)
                                ->first();

                            if (!empty($attendance)) {
                                $employeeAttendance = $attendance;
                            } else {
                                $employeeAttendance = new SchoolAttendance();
                                $employeeAttendance->student_id = $studentId;
                                $employeeAttendance->created_by  = \Auth::user()->id;
                                $employeeAttendance->workspace   = getActiveWorkSpace();
                            }

                            $employeeAttendance->date          = $request->date;
                            $employeeAttendance->status        = 'Present';
                            $employeeAttendance->clock_in      = $in;
                            $employeeAttendance->clock_out     = $out;
                            $employeeAttendance->total_rest    = '00:00:00';
                            $employeeAttendance->save();
                            event(new UpdateSchoolBulkAttendance($request, $employeeAttendance));
                        } else {
                            $attendance = SchoolAttendance::where('student_id', '=', $students)->where('workspace', getActiveWorkSpace())->where('date', '=', $request->date)->first();
                            if (!empty($attendance)) {
                                $employeeAttendance = $attendance;
                            } else {
                                $employeeAttendance              = new SchoolAttendance();
                                $employeeAttendance->employee_id = $students;
                                $employeeAttendance->created_by  = \Auth::user()->id;
                                $employeeAttendance->workspace     = getActiveWorkSpace();
                            }
                            $employeeAttendance->status        = 'Leave';
                            $employeeAttendance->date          = $request->date;
                            $employeeAttendance->clock_in      = '00:00:00';
                            $employeeAttendance->clock_out     = '00:00:00';
                            $employeeAttendance->total_rest    = '00:00:00';
                            $employeeAttendance->save();

                            event(new UpdateSchoolBulkAttendance($request, $employeeAttendance));
                        }
                    }
                }
                return redirect()->back()->with('success', __('The student attendance has been created successfully.'));
            } else {
                return redirect()->back()->with('error', __('Grade & Class field required.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
