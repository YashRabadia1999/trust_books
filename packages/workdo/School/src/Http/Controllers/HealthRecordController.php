<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\HealthRecordDataTable;
use Workdo\School\Entities\SchoolHealthRecord;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Events\CreateHealthRecord;
use Workdo\School\Events\DestroyHealthRecord;
use Workdo\School\Events\UpdateHealthRecord;

class HealthRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HealthRecordDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_health_record manage')) {
            return $dataTable->render('school::health-record.index');
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
        if (Auth::user()->isAbleTo('school_health_record create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $status    = SchoolHealthRecord::$status;
            return view('school::health-record.create' , compact('students' , 'status'));
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
        if (Auth::user()->isAbleTo('school_health_record create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'         => 'required',
                    'checkup_date'       => 'required',
                    'doctor_name'        => 'nullable',
                    'vaccination_status' => 'nullable',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $record                      = new SchoolHealthRecord();
            $record->student_id          = $request->student_id;
            $record->checkup_date        = $request->checkup_date;
            $record->doctor_name         = $request->doctor_name;
            $record->vaccination_status  = $request->vaccination_status;
            $record->diagnosis           = $request->diagnosis;
            $record->treatment           = $request->treatment;
            $record->allergies           = $request->allergies;
            $record->chronic_conditions   = $request->chronic_conditions;
            $record->created_by          = creatorId();
            $record->workspace           = getActiveWorkSpace();
            $record->save();
            
            // Also save health data to student record
            $student = SchoolStudent::find($request->student_id);
            if ($student) {
                $student->allergies = $request->allergies;
                $student->chronic_conditions = $request->chronic_conditions;
                $student->last_checkup = $request->checkup_date;
                $student->update();
            }

            event(new CreateHealthRecord($request, $record));

            return redirect()->back()->with('success', __('The health record has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_health_record edit')) {
            $id        = Crypt::decrypt($id);
            $record    = SchoolHealthRecord::find($id);
            $students  = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $status    = SchoolHealthRecord::$status;
            return view('school::health-record.edit', compact('record','students' , 'status'));
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
        if (Auth::user()->isAbleTo('school_health_record edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'student_id'         => 'required',
                    'checkup_date'       => 'required',
                    'doctor_name'        => 'nullable',
                    'vaccination_status' => 'nullable',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $record                      = SchoolHealthRecord::find($id);
            $record->student_id          = $request->student_id;
            $record->checkup_date        = $request->checkup_date;
            $record->doctor_name         = $request->doctor_name;
            $record->vaccination_status  = $request->vaccination_status;
            $record->diagnosis           = $request->diagnosis;
            $record->treatment           = $request->treatment;
            $record->allergies           = $request->allergies;
            $record->chronic_conditions   = $request->chronic_conditions;
            $record->update();
            
            // Update student health data as well
            $student = SchoolStudent::find($request->student_id);
            if ($student) {
                $student->allergies = $request->allergies;
                $student->chronic_conditions = $request->chronic_conditions;
                $student->last_checkup = $request->checkup_date;
                $student->update();
            }
            event(new UpdateHealthRecord($request, $record));

            return redirect()->back()->with('success', __('The health record details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_health_record delete')) {
            $record = SchoolHealthRecord::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroyHealthRecord($record));
            $record->delete();
            return redirect()->back()->with('success', __('The health record has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
