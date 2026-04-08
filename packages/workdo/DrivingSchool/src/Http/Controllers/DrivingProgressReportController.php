<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\DataTables\DrivingProgressReportDataTable;
use Workdo\DrivingSchool\Entities\DrivingClass;
use Workdo\DrivingSchool\Entities\DrivingProgressReport;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Events\CreateDrivingProgressReport;
use Workdo\DrivingSchool\Events\DestroyDrivingProgressReport;
use Workdo\DrivingSchool\Events\UpdateDrivingProgressReport;

class DrivingProgressReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingProgressReportDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('progress report manage')) {

            return $dataTable->render('driving-school::progress_report.index');
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
        if (Auth::user()->isAbleTo('progress report create')) {
            $student = DrivingStudent::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $teacher = User::where('type','staff')->where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::progress_report.create', compact('student','teacher'));
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
        if (Auth::user()->isAbleTo('progress report create')) {
            $validator = \Validator::make($request->all(), [
                'student_id' => 'required',
                'class_id' => 'required',
                'teacher_id' => 'required',
                'progress_date' => 'required',
                'skills_assessed' => 'required',
                'comments' => 'required',
                'rating' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->route('progress_report.index')->with('error', $validator->errors()->first());
            }

            $progress_report                  = new DrivingProgressReport;
            $progress_report->student_id      = $request->student_id;
            $progress_report->class_id       = $request->class_id;
            $progress_report->teacher_id      = $request->teacher_id;
            $progress_report->progress_date   = $request->progress_date;
            $progress_report->skills_assessed = $request->skills_assessed;
            $progress_report->comments        = $request->comments;
            $progress_report->rating          = $request->rating;
            $progress_report->workspace       = getActiveWorkSpace();
            $progress_report->created_by      = Auth::user()->id;
            $progress_report->save();

            event(new CreateDrivingProgressReport($request, $progress_report));

            return redirect()->back()->with('success', __('The progress report has been created successfully'));
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
        if (Auth::user()->isAbleTo('progress report show')) {
            try {
                $id      = Crypt::decrypt($id);
                $report  = DrivingProgressReport::where('id', $id)->where('workspace', getActiveWorkSpace())->firstOrFail();
                $student = DrivingStudent::select('name')->where('id',$report->student_id)->where('workspace', '=', getActiveWorkSpace())->first();
                $class   = DrivingClass::where('id', $report->class_id)->where('workspace', getActiveWorkSpace())->first();
                $teacher = User::where('type','staff')->where('id', $report->teacher_id)->where('workspace_id', getActiveWorkSpace())->first();

                return view('driving-school::progress_report.show', compact('report', 'student', 'class', 'teacher'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Progress report not found.'));
            }
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
        if (Auth::user()->isAbleTo('progress report edit')) {

            $report  = DrivingProgressReport::find($id);
            $student = DrivingStudent::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $class   = DrivingClass::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $teacher = User::where('type','staff')->where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::progress_report.edit', compact('report','student','class','teacher'));

        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if (Auth::user()->isAbleTo('progress report edit')) {

            $validator = \Validator::make($request->all(), [
                'student_id' => 'required',
                'class_id' => 'required',
                'teacher_id' => 'required',
                'progress_date' => 'required',
                'skills_assessed' => 'required',
                'comments' => 'required',
                'rating' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $progress_report                  = DrivingProgressReport::find($id);
            $progress_report->student_id      = $request->student_id;
            $progress_report->class_id       = $request->class_id;
            $progress_report->teacher_id      = $request->teacher_id;
            $progress_report->progress_date   = $request->progress_date;
            $progress_report->skills_assessed = $request->skills_assessed;
            $progress_report->comments        = $request->comments;
            $progress_report->rating          = $request->rating;
            $progress_report->save();

            event(new UpdateDrivingProgressReport($request, $progress_report));

            return redirect()->back()->with('success', __('The progress report details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('progress report delete')) {

            $report = DrivingProgressReport::find($id);
            if ($report->created_by == creatorId() && $report->workspace == getActiveWorkSpace()) {

                event(new DestroyDrivingProgressReport($report));
                $report->delete();
                return redirect()->back()->with('success', __('The progress report has been deleted.'));

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function class(Request $request)
    {
        $class = DrivingClass::whereRaw("FIND_IN_SET(?, student_id)", [$request->type])->where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get();
        return $class;
    }
}
