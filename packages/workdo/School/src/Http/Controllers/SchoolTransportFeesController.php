<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\TransportFeesDataTable;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolTransportFee;
use Workdo\School\Entities\SchoolTransportRoute;
use Workdo\School\Events\CreateSchoolTransportFees;
use Workdo\School\Events\DestroySchoolTransportFees;
use Workdo\School\Events\UpdateSchoolTransportFees;

class SchoolTransportFeesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(TransportFeesDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_transport_fees manage')) {
            return $dataTable->render('school::transport-fee.index');
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
        if (Auth::user()->isAbleTo('school_transport_fees create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $routes   = SchoolTransportRoute::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('route_name','id');
            $status   = SchoolTransportFee::$status;

            return view('school::transport-fee.create' , compact('students' , 'routes' , 'status'));
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
        if (Auth::user()->isAbleTo('school_transport_fees create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'  => 'required',
                    'route_id'    => 'required',
                    'amount'      => 'required|numeric',
                    'status'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $fees               = new SchoolTransportFee();
            $fees->student_id   = $request->student_id;
            $fees->route_id     = $request->route_id;
            $fees->amount       = $request->amount;
            $fees->status       = $request->status;
            $fees->created_by   = creatorId();
            $fees->workspace    = getActiveWorkSpace();
            $fees->save();

            event(new CreateSchoolTransportFees($request, $fees));

            return redirect()->back()->with('success', __('The transport fee has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_transport_fees edit')) {
            $id       = Crypt::decrypt($id);
            $fee      = SchoolTransportFee::find($id);
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $routes   = SchoolTransportRoute::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('route_name','id');
            $status   = SchoolTransportFee::$status;

            return view('school::transport-fee.edit', compact('fee','students','routes','status'));
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
        if (Auth::user()->isAbleTo('school_transport_fees edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'student_id'  => 'required',
                    'route_id'    => 'required',
                    'amount'      => 'required|numeric',
                    'status'      => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $fees               = SchoolTransportFee::find($id);
            $fees->student_id   = $request->student_id;
            $fees->route_id     = $request->route_id;
            $fees->amount       = $request->amount;
            $fees->status       = $request->status;
            $fees->save();

            event(new UpdateSchoolTransportFees($request, $fees));

            return redirect()->back()->with('success', __('The transport fee details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_transport_fees delete')) {
            $fee = SchoolTransportFee::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolTransportFees($fee));
            $fee->delete();
            return redirect()->back()->with('success', __('The transport fee has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
