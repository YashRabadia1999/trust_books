<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\AluminiDataTable;
use Workdo\School\Entities\SchoolAlumini;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Events\CreateSchoolAlumini;
use Workdo\School\Events\DestroySchoolAlumini;
use Workdo\School\Events\UpdateSchoolAlumini;

class SchoolAluminiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(AluminiDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_alumni manage')) {
            return $dataTable->render('school::alumini.index');
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
        if (Auth::user()->isAbleTo('school_alumni create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            return view('school::alumini.create' , compact('students'));
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
        if (Auth::user()->isAbleTo('school_alumni create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'        => 'required',
                    'batch_year'        => 'required',
                    'current_position'  => 'required',
                    'contact'           => 'required',
                    'email'             => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $alumini                   = new SchoolAlumini();
            $alumini->student_id       = $request->student_id;
            $alumini->batch_year       = $request->batch_year;
            $alumini->current_position = $request->current_position;
            $alumini->contact          = $request->contact;
            $alumini->email            = $request->email;
            $alumini->created_by       = creatorId();
            $alumini->workspace        = getActiveWorkSpace();
            $alumini->save();

            event(new CreateSchoolAlumini($request, $alumini));

            return redirect()->back()->with('success', __('The alumini has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_alumni edit')) {
            $id       = Crypt::decrypt($id);
            $alumini  = SchoolAlumini::find($id);
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');

            return view('school::alumini.edit', compact('alumini','students'));
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
        if (Auth::user()->isAbleTo('school_alumni edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'        => 'required',
                    'batch_year'        => 'required',
                    'current_position'  => 'required',
                    'contact'           => 'required',
                    'email'             => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $alumini                   = SchoolAlumini::find($id);
            $alumini->student_id       = $request->student_id;
            $alumini->batch_year       = $request->batch_year;
            $alumini->current_position = $request->current_position;
            $alumini->contact          = $request->contact;
            $alumini->email            = $request->email;
            $alumini->created_by       = creatorId();
            $alumini->workspace        = getActiveWorkSpace();
            $alumini->update();

            event(new UpdateSchoolAlumini($request, $alumini));

            return redirect()->back()->with('success', __('The alumini details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_alumni delete')) {
            $alumini = SchoolAlumini::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolAlumini($alumini));
            $alumini->delete();
            return redirect()->back()->with('success', __('The alumini has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getStudentInfo(Request $request)
    {
        if($request->student_id != null){
            $students = SchoolStudent::find($request->student_id);
        }
        return response()->json($students);
    }
}
