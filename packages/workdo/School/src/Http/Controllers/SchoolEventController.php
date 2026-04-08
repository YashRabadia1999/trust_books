<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\EventDataTable;
use Workdo\School\Entities\SchoolEvent;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Events\CreateSchoolEvent;
use Workdo\School\Events\DestroySchoolEvent;
use Workdo\School\Events\UpdateSchoolEvent;

class SchoolEventController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(EventDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_event manage')) {
            return $dataTable->render('school::event.index');
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
        if (Auth::user()->isAbleTo('school_event create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            return view('school::event.create' , compact('students'));
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
        if (Auth::user()->isAbleTo('school_event create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'  => 'required',
                    'event_name'  => 'required',
                    'event_date'  => 'required',
                    'location'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $event               = new SchoolEvent();
            $event->student_id   = $request->student_id;
            $event->event_name   = $request->event_name;
            $event->event_date   = $request->event_date;
            $event->location     = $request->location;
            $event->description  = $request->description;
            $event->created_by   = creatorId();
            $event->workspace    = getActiveWorkSpace();
            $event->save();

            event(new CreateSchoolEvent($request, $event));

            return redirect()->back()->with('success', __('The event has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_event edit')) {
            $event    = SchoolEvent::find($id);
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            return view('school::event.edit', compact('event','students'));
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
        if (Auth::user()->isAbleTo('school_event edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'student_id'  => 'required',
                    'event_name'  => 'required',
                    'event_date'  => 'required',
                    'location'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $event               = SchoolEvent::find($id);
            $event->student_id   = $request->student_id;
            $event->event_name   = $request->event_name;
            $event->event_date   = $request->event_date;
            $event->location     = $request->location;
            $event->description  = $request->description;
            $event->update();
            event(new UpdateSchoolEvent($request, $event));

            return redirect()->back()->with('success', __('The event details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_event delete')) {
            $event = SchoolEvent::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolEvent($event));
            $event->delete();
            return redirect()->back()->with('success', __('The event has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function calendarView()
    {
        if (Auth::user()->isAbleTo('school_event manage')) {

            $events    = SchoolEvent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            $today_date = date('m');
            $current_month_event = SchoolEvent::select('id', 'event_date', 'event_name', 'created_at')->where('workspace', getActiveWorkSpace())->whereNotNull(['event_date'])->whereMonth('event_date', $today_date)->get();
            $arrEvents = [];
            foreach ($events as $event) {

                $arr['id']        = $event['id'];
                $arr['title']     = $event['event_name'];
                $arr['start']     = $event['event_date'];
                $arr['end']       = date('Y-m-d', strtotime($event['event_date'] . ' +1 day'));
                $arr['className'] = 'event-info';
                $arr['url']       = route('school-event.edit', Crypt::encrypt($event['id']));

                $arrEvents[] = $arr;
            }
            $arrEvents =  json_encode($arrEvents);

            return view('school::event.calendar' , compact('current_month_event' , 'arrEvents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        if(isset($id)){
            $event    = SchoolEvent::find($id);
            return view('school::event.description', compact('event'));
        }
        else {
            return redirect()->back()->with('error', __('Something want wrong.'));
        }
    }
}
