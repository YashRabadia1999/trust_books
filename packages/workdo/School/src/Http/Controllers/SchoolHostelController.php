<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\HostelDataTable;
use Workdo\School\Entities\SchoolHostel;
use Workdo\School\Events\CreateSchoolHostel;
use Workdo\School\Events\DestroySchoolHostel;
use Workdo\School\Events\UpdateSchoolHostel;

class SchoolHostelController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HostelDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_hostel manage')) {
            return $dataTable->render('school::hostel.index');
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
        if (Auth::user()->isAbleTo('school_hostel create')) {
            return view('school::hostel.create');
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
        if (Auth::user()->isAbleTo('school_hostel create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'hostel_name' => 'required',
                    'location'    => 'required',
                    'capacity'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $hostel                = new SchoolHostel();
            $hostel->hostel_name   = $request->hostel_name;
            $hostel->location      = $request->location;
            $hostel->capacity      = $request->capacity;
            $hostel->created_by    = creatorId();
            $hostel->workspace     = getActiveWorkSpace();
            $hostel->save();

            event(new CreateSchoolHostel($request, $hostel));

            return redirect()->back()->with('success', __('The hostel has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_hostel edit')) {
            $id       = Crypt::decrypt($id);
            $hostel   = SchoolHostel::find($id);

            return view('school::hostel.edit', compact('hostel'));
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
        if (Auth::user()->isAbleTo('school_hostel edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'hostel_name' => 'required',
                    'location'    => 'required',
                    'capacity'    => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $hostel                = SchoolHostel::find($id);
            $hostel->hostel_name   = $request->hostel_name;
            $hostel->location      = $request->location;
            $hostel->capacity      = $request->capacity;
            $hostel->update();
            event(new UpdateSchoolHostel($request, $hostel));

            return redirect()->back()->with('success', __('The hostel details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_hostel delete')) {
            $hostel = SchoolHostel::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolHostel($hostel));
            $hostel->delete();
            return redirect()->back()->with('success', __('The hostel has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
