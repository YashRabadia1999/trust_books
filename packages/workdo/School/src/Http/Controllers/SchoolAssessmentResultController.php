<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\AssessmentResultDataTable;
use Workdo\School\Entities\SchoolAssessment;
use Workdo\School\Entities\SchoolAssessmentResult;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Events\CreateSchoolAssessmentResult;
use Workdo\School\Events\DestroySchoolAssessmentResult;
use Workdo\School\Events\UpdateSchoolAssessmentResult;

class SchoolAssessmentResultController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(AssessmentResultDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_assessment_result manage')) {
            return $dataTable->render('school::assessment-result.index');
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
        if (Auth::user()->isAbleTo('school_assessment_result create')) {
            $students    = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $assessments = SchoolAssessment::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('title','id');
            return view('school::assessment-result.create' , compact('students' , 'assessments'));
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
        if (Auth::user()->isAbleTo('school_assessment_result create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'      => 'required',
                    'assessment_id'   => 'required',
                    'marks_obtained'  => 'required|numeric',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $result                  = new SchoolAssessmentResult();
            $result->student_id      = $request->student_id;
            $result->assessment_id   = $request->assessment_id;
            $result->marks_obtained  = $request->marks_obtained;
            $result->grade           = $request->grade;
            $result->created_by      = creatorId();
            $result->workspace       = getActiveWorkSpace();
            $result->save();

            event(new CreateSchoolAssessmentResult($request, $result));

            return redirect()->back()->with('success', __('The assessment result has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_assessment_result edit')) {
            $id          = Crypt::decrypt($id);
            $result  = SchoolAssessmentResult::find($id);
            $students    = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $assessments = SchoolAssessment::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('title','id');

            return view('school::assessment-result.edit', compact('result','students','assessments'));
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
        if (Auth::user()->isAbleTo('school_assessment_result edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'      => 'required',
                    'assessment_id'   => 'required',
                    'marks_obtained'  => 'required|numeric',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $result                  = SchoolAssessmentResult::find($id);
            $result->student_id      = $request->student_id;
            $result->assessment_id   = $request->assessment_id;
            $result->marks_obtained  = $request->marks_obtained;
            $result->grade           = $request->grade;
            $result->update();
            event(new UpdateSchoolAssessmentResult($request, $result));

            return redirect()->back()->with('success', __('The assessment result details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_assessment_result delete')) {
            $result = SchoolAssessmentResult::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolAssessmentResult($result));
            $result->delete();
            return redirect()->back()->with('success', __('The assessment result has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
