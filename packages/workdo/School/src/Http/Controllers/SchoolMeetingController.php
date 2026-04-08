<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\MeetingDataTable;
use Workdo\School\Entities\Employee;
use Workdo\School\Entities\SchoolMeeting;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Events\CreateSchoolMeeting;
use Workdo\School\Events\DestroySchoolMeeting;
use Workdo\School\Events\UpdateSchoolMeeting;

class SchoolMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(MeetingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_meeting manage')) {
            return $dataTable->render('school::meeting.index');
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
        if (Auth::user()->isAbleTo('school_meeting create')) {
            $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $parents   = SchoolParent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');

            return view('school::meeting.create' , compact('employees' , 'parents'));
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
        if (Auth::user()->isAbleTo('school_meeting create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'parent_id'    => 'required',
                    'teacher_id'   => 'required',
                    'meeting_date' => 'required',
                    'agenda'       => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $meeting               = new SchoolMeeting();
            $meeting->parent_id    = $request->parent_id;
            $meeting->teacher_id   = $request->teacher_id;
            $meeting->meeting_date = $request->meeting_date;
            $meeting->agenda       = $request->agenda;
            $meeting->created_by   = creatorId();
            $meeting->workspace    = getActiveWorkSpace();
            $meeting->save();

            event(new CreateSchoolMeeting($request, $meeting));

            return redirect()->back()->with('success', __('The meeting has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_meeting edit')) {
            $id        = Crypt::decrypt($id);
            $meeting   = SchoolMeeting::find($id);
            $employees = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $parents   = SchoolParent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            return view('school::meeting.edit', compact('meeting','employees','parents'));
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
        if (Auth::user()->isAbleTo('school_meeting edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'parent_id'    => 'required',
                    'teacher_id'   => 'required',
                    'meeting_date' => 'required',
                    'agenda'       => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $meeting               = SchoolMeeting::find($id);
            $meeting->parent_id    = $request->parent_id;
            $meeting->teacher_id   = $request->teacher_id;
            $meeting->meeting_date = $request->meeting_date;
            $meeting->agenda       = $request->agenda;
            $meeting->update();
            event(new UpdateSchoolMeeting($request, $meeting));

            return redirect()->back()->with('success', __('The meeting details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_meeting delete')) {
            $meeting = SchoolMeeting::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolMeeting($meeting));
            $meeting->delete();
            return redirect()->back()->with('success', __('The meeting has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
