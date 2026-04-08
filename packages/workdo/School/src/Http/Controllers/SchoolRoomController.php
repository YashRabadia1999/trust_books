<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\HostelRoomDataTable;
use Workdo\School\Entities\SchoolHostel;
use Workdo\School\Entities\SchoolRoom;
use Workdo\School\Events\CreateSchoolRoom;
use Workdo\School\Events\DestroySchoolRoom;
use Workdo\School\Events\UpdateSchoolRoom;

class SchoolRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HostelRoomDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_room manage')) {
            return $dataTable->render('school::room.index');
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
        if (Auth::user()->isAbleTo('school_room create')) {
            $hostels = SchoolHostel::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('hostel_name','id');
            return view('school::room.create' , compact('hostels'));
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
        if (Auth::user()->isAbleTo('school_room create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'hostel_id'   => 'required',
                    'room_number' => 'required',
                    'capacity'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $room               = new SchoolRoom();
            $room->hostel_id    = $request->hostel_id;
            $room->room_number  = $request->room_number;
            $room->capacity     = $request->capacity;
            $room->created_by   = creatorId();
            $room->workspace    = getActiveWorkSpace();
            $room->save();

            event(new CreateSchoolRoom($request, $room));

            return redirect()->back()->with('success', __('The room has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_room edit')) {
            $id       = Crypt::decrypt($id);
            $room     = SchoolRoom::find($id);
            $hostels  = SchoolHostel::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('hostel_name','id');

            return view('school::room.edit', compact('room','hostels'));
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
        if (Auth::user()->isAbleTo('school_room edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'hostel_id'   => 'required',
                    'room_number' => 'required',
                    'capacity'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $room               = SchoolRoom::find($id);
            $room->hostel_id    = $request->hostel_id;
            $room->room_number  = $request->room_number;
            $room->capacity     = $request->capacity;
            $room->update();
            event(new UpdateSchoolRoom($request, $room));

            return redirect()->back()->with('success', __('The room details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_room delete')) {
            $room = SchoolRoom::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolRoom($room));
            $room->delete();
            return redirect()->back()->with('success', __('The room has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
