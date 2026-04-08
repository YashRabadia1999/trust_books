<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\BusDataTable;
use Workdo\School\Entities\SchoolBus;
use Workdo\School\Events\CreateSchoolBus;
use Workdo\School\Events\DestroySchoolBus;
use Workdo\School\Events\UpdateSchoolBus;

class SchoolBusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BusDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_bus manage')) {
            return $dataTable->render('school::bus.index');
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
        if (Auth::user()->isAbleTo('school_bus create')) {
            return view('school::bus.create');
        } else {
            return redirect()->back()->with('e rror', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_bus create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'bus_number'   => 'required',
                    'driver_name'  => 'required',
                    'capacity'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $buses               = new SchoolBus();
            $buses->bus_number   = $request->bus_number;
            $buses->driver_name  = $request->driver_name;
            $buses->capacity     = $request->capacity;
            $buses->created_by   = creatorId();
            $buses->workspace    = getActiveWorkSpace();
            $buses->save();

            event(new CreateSchoolBus($request, $buses));

            return redirect()->back()->with('success', __('The school Bus has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_bus edit')) {
            $id       = Crypt::decrypt($id);
            $bus      = SchoolBus::find($id);

            return view('school::bus.edit' , compact('bus'));
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
        if (Auth::user()->isAbleTo('school_bus edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'bus_number'   => 'required',
                    'driver_name'  => 'required',
                    'capacity'     => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $bus               = SchoolBus::find($id);
            $bus->bus_number   = $request->bus_number;
            $bus->driver_name  = $request->driver_name;
            $bus->capacity     = $request->capacity;
            $bus->created_by   = creatorId();
            $bus->workspace    = getActiveWorkSpace();
            $bus->update();
            event(new UpdateSchoolBus($request, $bus));

            return redirect()->back()->with('success', __('The school bus details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_bus delete')) {
            $bus = SchoolBus::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolBus($bus));
            $bus->delete();
            return redirect()->back()->with('success', __('The school bus has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
