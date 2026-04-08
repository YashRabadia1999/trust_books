<?php

namespace Workdo\School\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\Subject;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Entities\Employee;
use Workdo\School\Entities\SchoolGrade;
use Workdo\School\Events\CreateSubject;
use Workdo\School\Events\DestorySubject;
use Workdo\School\Events\UpdateSubject;
use Workdo\School\DataTables\SubjectDataTable;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SubjectDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_subject manage')) {
            return $dataTable->render('school::subject.index');
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
        if (Auth::user()->isAbleTo('school_subject create')) {
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            $user      = User::where('workspace_id', getActiveWorkSpace())->where('type', '=', 'staff')->get()->pluck('name', 'id');
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');

            return view('school::subject.create', compact('classRoom', 'user', 'grade'));
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

        if (Auth::user()->isAbleTo('school_subject create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subject_code' => 'required',
                    'subject_name' => 'required',
                    'class'        => 'required',
                    'teacher'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $subject = new Subject();
            $subject->class_id     = $request->class;
            $subject->subject_code = $request->subject_code;
            $subject->subject_name = $request->subject_name;
            $subject->grade_name   = $request->grade_name;
            $subject->teacher      = $request->teacher;
            $subject->created_by   = creatorId();
            $subject->workspace    = getActiveWorkSpace();
            $subject->save();
            event(new CreateSubject($request, $subject));

            return redirect()->back()->with('success', __('The subject has been created successfully.'));
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
        return view('school::subject.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('school_subject edit')) {
            $subject   = Subject::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            $user      = User::where('workspace_id', getActiveWorkSpace())->where('type', '=', 'staff')->get()->pluck('name', 'id');
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');

            return view('school::subject.edit', compact('subject', 'classRoom', 'user', 'grade'));
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
        if (Auth::user()->isAbleTo('school_subject edit')) {

            $subject = Subject::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $subject->class_id     = $request->class;
            $subject->subject_code = $request->subject_code;
            $subject->subject_name = $request->subject_name;
            $subject->grade_name   = $request->grade_name;
            $subject->teacher      = $request->teacher;
            $subject->update();
            event(new UpdateSubject($request, $subject));

            return redirect()->route('subject.index')->with('success', 'The subject details are updated successfully.');
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
        if (Auth::user()->isAbleTo('school_subject delete')) {
            $subject = Subject::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            event(new DestorySubject($subject));
            $subject->delete();

            return redirect()->back()->with('success', __('The subject has been deleted.'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
