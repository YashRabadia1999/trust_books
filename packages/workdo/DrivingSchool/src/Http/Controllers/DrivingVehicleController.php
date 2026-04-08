<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\DrivingSchool\Entities\DrivingVehicle;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\DataTables\DrivingVehicleDatatable;
use Workdo\DrivingSchool\Events\CreateDrivingVehicle;
use Workdo\DrivingSchool\Events\UpdateDrivingVehicle;
use Workdo\DrivingSchool\Events\DestoryDrivingVehicle;

class DrivingVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingVehicleDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('drivingvehicle manage')) {
            return $dataTable->render('driving-school::vehicle.index');
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
        if (Auth::user()->isAbleTo('drivingvehicle create')) {

            $users = User::where('created_by', creatorId())->where('type', 'staff')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('driving-school::vehicle.create', compact('users'));
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
        if (Auth::user()->isAbleTo('drivingvehicle create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name'   => 'required',
                    'teacher_id' => 'required',
                    'location'  => 'required',
                    'chassis_number' => 'required',
                    'odometer'   => 'required',
                    'model_year'   => 'required',
                    'engine_transmission' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $Vehicle                       = new DrivingVehicle();
            $Vehicle->name                 = $request->name;
            $Vehicle->teacher_id           = $request->teacher_id;
            $Vehicle->location             = $request->location;
            $Vehicle->chassis_number       = $request->chassis_number;
            $Vehicle->odometer             = $request->odometer;
            $Vehicle->model_year           = $request->model_year;
            $Vehicle->engine_transmission  = $request->engine_transmission;
            $Vehicle->workspace            = getActiveWorkSpace();
            $Vehicle->created_by           = creatorId();
            $Vehicle->save();

            event(new CreateDrivingVehicle($request, $Vehicle));

            return redirect()->route('driving-vehicle.index')->with('success', __('The vehicle has been created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('driving-school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('drivingvehicle edit')) {

            $vehicle = DrivingVehicle::find($id);
            $users = User::where('created_by', creatorId())->where('type', 'staff')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::vehicle.edit', compact('vehicle', 'users'));
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
        if (Auth::user()->isAbleTo('drivingvehicle edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name'   => 'required',
                    'teacher_id' => 'required',
                    'location'  => 'required',
                    'chassis_number' => 'required',
                    'odometer'   => 'required',
                    'model_year'   => 'required',
                    'engine_transmission' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $Vehicle                       = DrivingVehicle::find($id);
            $Vehicle->name                 = $request->name;
            $Vehicle->teacher_id           = $request->teacher_id;
            $Vehicle->location             = $request->location;
            $Vehicle->chassis_number       = $request->chassis_number;
            $Vehicle->odometer             = $request->odometer;
            $Vehicle->model_year           = $request->model_year;
            $Vehicle->engine_transmission  = $request->engine_transmission;
            $Vehicle->workspace            = getActiveWorkSpace();
            $Vehicle->created_by           = creatorId();
            $Vehicle->save();

            event(new UpdateDrivingVehicle($request, $Vehicle));

            return redirect()->route('driving-vehicle.index')->with('success', __('The vehicle details are updated successfully'));
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
        $vehicle = DrivingVehicle::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

        if (Auth::user()->isAbleTo('drivingvehicle delete')) {
            if (!empty($vehicle->workspace)) {
                if ($vehicle->workspace == getActiveWorkSpace()) {

                    event(new DestoryDrivingVehicle($vehicle));

                    $vehicle->delete();

                    return redirect()->route('driving-vehicle.index')->with('success', __('The student has been deleted'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
