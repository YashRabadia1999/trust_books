<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\SchoolGrade;
use Illuminate\Support\Facades\Crypt;
use Workdo\School\Events\CreateSchoolGrade;
use Workdo\School\Events\DestorySchoolEmployee;
use Workdo\School\Events\DestorySchoolGrade;
use Workdo\School\Events\UpdateSchoolGrade;
use Workdo\School\DataTables\GradeDataTable;

class SchoolGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(GradeDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_grade manage')) {

            return $dataTable->render('school::grade.index');
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
        if (Auth::user()->isAbleTo('school_grade create')) {

            return view('school::grade.create');
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
        if (Auth::user()->isAbleTo('school_grade create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'grade_name' => 'required|string|max:255',
                    'min_marks' => 'required|integer|min:0|max:100',
                    'max_marks' => 'required|integer|min:0|max:100|gte:min_marks',
                    'remarks' => 'nullable|string|max:1000',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $grade             = new SchoolGrade();
            $grade->grade_name = $request->grade_name;
            $grade->min_marks  = $request->min_marks;
            $grade->max_marks  = $request->max_marks;
            $grade->remarks    = $request->remarks;
            $grade->created_by = creatorId();
            $grade->workspace  = getActiveWorkSpace();
            $grade->save();
            event(new CreateSchoolGrade($request, $grade));

            return redirect()->back()->with('success', __('The grade has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_grade edit')) {
            $id = Crypt::decrypt($id);

            $grade = SchoolGrade::find($id);

            return view('school::grade.edit', compact('grade'));
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
        if (Auth::user()->isAbleTo('school_grade edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'grade_name' => 'required|string|max:255',
                    'min_marks' => 'required|integer|min:0|max:100',
                    'max_marks' => 'required|integer|min:0|max:100|gte:min_marks',
                    'remarks' => 'nullable|string|max:1000',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $grade = SchoolGrade::find($id);
            $grade->grade_name = $request->grade_name;
            $grade->min_marks  = $request->min_marks;
            $grade->max_marks  = $request->max_marks;
            $grade->remarks    = $request->remarks;
            $grade->update();
            event(new UpdateSchoolGrade($request, $grade));

            return redirect()->back()->with('success', __('The grade details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_grade delete')) {
            $grade = SchoolGrade::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestorySchoolGrade($grade));
            $grade->delete();
            return redirect()->back()->with('error', __('The grade has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

}
