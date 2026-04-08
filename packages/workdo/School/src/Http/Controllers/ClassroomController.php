<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolGrade;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Events\CreateClassroom;
use Workdo\School\Events\DestoryClassroom;
use Workdo\School\Events\UpdateClassroom;
use Workdo\School\DataTables\ClassDataTable;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ClassDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_classroom manage')) {

            return $dataTable->render('school::classroom.index');
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
        if (Auth::user()->isAbleTo('school_classroom create')) {
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
            return view('school::classroom.create', compact('grade'));
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
        if (Auth::user()->isAbleTo('school_classroom create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'class_name' => 'required',
                    'class_capacity' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $classroom                 = new Classroom();
            $classroom->class_name     = $request->class_name;
            $classroom->class_capacity = $request->class_capacity;
            $classroom->grade_name     = $request->grade_name;
            $classroom->created_by     = creatorId();
            $classroom->workspace      = getActiveWorkSpace();
            $classroom->save();
            event(new CreateClassroom($request, $classroom));

            return redirect()->back()->with('success', __('The class has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_classroom edit')) {
            if (Auth::user()->type == 'student') {
                $student = SchoolStudent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id', Auth::user()->id)->first();
                $classroom = Classroom::where('id', $student->class_name)->first();
            } elseif (Auth::user()->type == 'parent') {
                $parent = SchoolParent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id', Auth::user()->id)->first();
                $student = SchoolStudent::whereRaw("FIND_IN_SET($parent->user_id, parent_id)")->first();
                $classroom = Classroom::where('id', $student->class_name)->first();
            } else {
                $classroom = Classroom::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
            }
            return view('school::classroom.edit', compact('classroom','grade'));
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
        if (Auth::user()->isAbleTo('school_classroom edit')) {
            $classroom = Classroom::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $classroom->class_name     = $request->class_name;
            $classroom->grade_name     = $request->grade_name;
            $classroom->class_capacity = $request->class_capacity;
            $classroom->update();
            event(new UpdateClassroom($request, $classroom));

            return redirect()->route('classroom.index')->with('success', 'The class details are updated successfully.');
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
        if (Auth::user()->isAbleTo('school_classroom delete')) {
            $classroom = Classroom::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            event(new DestoryClassroom($classroom));
            if(!empty($classroom))
            {
                $classroom->delete();
            }

            return redirect()->back()->with('success', __('The class has been deleted.'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
