<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\AssessmentDataTable;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolAssessment;
use Workdo\School\Entities\Subject;
use Workdo\School\Events\CreateSchoolAssessment;
use Workdo\School\Events\DestroySchoolAssessment;
use Workdo\School\Events\UpdateSchoolAssessment;

class SchoolAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(AssessmentDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_assessment manage')) {
            return $dataTable->render('school::assessment.index');
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
        if (Auth::user()->isAbleTo('school_assessment create')) {
            $classes  = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('class_name','id');
            $subjects = Subject::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('subject_name','id');
            return view('school::assessment.create' , compact('classes' , 'subjects'));
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
        if (Auth::user()->isAbleTo('school_assessment create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'        => 'required',
                    'class_id'     => 'required',
                    'subject_id'   => 'required',
                    'due_date'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $assessment                = new SchoolAssessment();
            $assessment->title         = $request->title;
            $assessment->class_id      = $request->class_id;
            $assessment->subject_id    = $request->subject_id;
            $assessment->due_date      = $request->due_date;
            $assessment->description   = $request->description;
            $assessment->created_by    = creatorId();
            $assessment->workspace     = getActiveWorkSpace();
            $assessment->save();

            event(new CreateSchoolAssessment($request, $assessment));

            return redirect()->back()->with('success', __('The assessment has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_assessment manage')) {
            $assessment = SchoolAssessment::find($id);
            return view('school::assessment.description' ,compact('assessment'));
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
        if (Auth::user()->isAbleTo('school_assessment edit')) {
            $id          = Crypt::decrypt($id);
            $assessment  = SchoolAssessment::find($id);
            $classes     = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('class_name','id');
            $subjects    = Subject::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('subject_name','id');

            return view('school::assessment.edit', compact('assessment','classes','subjects'));
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
        if (Auth::user()->isAbleTo('school_assessment edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title'        => 'required',
                    'class_id'     => 'required',
                    'subject_id'   => 'required',
                    'due_date'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $assessment                = SchoolAssessment::find($id);
            $assessment->title         = $request->title;
            $assessment->class_id      = $request->class_id;
            $assessment->subject_id    = $request->subject_id;
            $assessment->due_date      = $request->due_date;
            $assessment->description   = $request->description;
            $assessment->update();
            event(new UpdateSchoolAssessment($request, $assessment));

            return redirect()->back()->with('success', __('The assessment details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_assessment delete')) {
            $assessment = SchoolAssessment::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolAssessment($assessment));
            $assessment->delete();
            return redirect()->back()->with('success', __('The assessment has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
