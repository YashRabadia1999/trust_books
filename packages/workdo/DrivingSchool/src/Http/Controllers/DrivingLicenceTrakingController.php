<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\DataTables\DrivingLicenceTrakingDataTable;
use Workdo\DrivingSchool\Entities\DrivingLicenceTraking;
use Workdo\DrivingSchool\Entities\DrivingLicenceType;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Events\CreateDrivingLicenceTraking;
use Workdo\DrivingSchool\Events\DestroyDrivingLicenceTraking;
use Workdo\DrivingSchool\Events\UpdateDrivingLicenceTraking;

class DrivingLicenceTrakingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingLicenceTrakingDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('licence traking manage')) {

            return $dataTable->render('driving-school::licence_traking.index');
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
        if (Auth::user()->isAbleTo('licence traking create')) {
            $student            = DrivingStudent::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            $licence_types      = DrivingLicenceType::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::licence_traking.create', compact('student','licence_types'));
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
        if (Auth::user()->isAbleTo('licence traking create')) {
            $validator = \Validator::make($request->all(), [
                'student_id' => 'required',
                'licence_type_id' => 'required',
                'application_date' => 'required',
                'test_date' => 'required',
                'test_result' => 'required',
                'licence_issue_date' => 'required',
                'licence_number' => 'required',
                'licence_expiry_date' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->route('licence_traking.index')->with('error', $validator->errors()->first());
            }

            $licence_traking                      = new DrivingLicenceTraking;
            $licence_traking->student_id          = $request->student_id;
            $licence_traking->licence_type_id     = $request->licence_type_id;
            $licence_traking->application_date    = $request->application_date;
            $licence_traking->test_date           = $request->test_date;
            $licence_traking->test_result         = $request->test_result;
            $licence_traking->licence_issue_date  = $request->licence_issue_date;
            $licence_traking->licence_number      = $request->licence_number;
            $licence_traking->licence_expiry_date = $request->licence_expiry_date;
            $licence_traking->workspace           = getActiveWorkSpace();
            $licence_traking->created_by          = Auth::user()->id;
            $licence_traking->save();

            event(new CreateDrivingLicenceTraking($request, $licence_traking));

            return redirect()->back()->with('success', __('The licence tracking has been created successfully'));
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
        if (Auth::user()->isAbleTo('licence traking show')) {
            try {
                $id              = Crypt::decrypt($id);
                $licence_traking = DrivingLicenceTraking::where('id', $id)->where('workspace', getActiveWorkSpace())->firstOrFail();
                $student         = DrivingStudent::select('name')->where('id',$licence_traking->student_id)->where('workspace', '=', getActiveWorkSpace())->first();
                $licence_types   = DrivingLicenceType::select('name')->where('id',$licence_traking->licence_type_id)->where('workspace', '=', getActiveWorkSpace())->first();

                return view('driving-school::licence_traking.show', compact('licence_traking', 'student', 'licence_types'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Licence tracking not found.'));
            }
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
        if (Auth::user()->isAbleTo('licence traking edit')) {

            $traking       = DrivingLicenceTraking::find($id);
            $student       = DrivingStudent::where('id',$traking->student_id)->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $licence_types = DrivingLicenceType::where('id',$traking->licence_type_id)->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('driving-school::licence_traking.edit', compact('traking','student', 'licence_types'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if (Auth::user()->isAbleTo('licence traking edit')) {

            $validator = \Validator::make($request->all(), [
                'student_id' => 'required',
                'licence_type_id' => 'required',
                'application_date' => 'required',
                'test_date' => 'required',
                'test_result' => 'required',
                'licence_issue_date' => 'required',
                'licence_number' => 'required',
                'licence_expiry_date' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->route('licence_traking.index')->with('error', $validator->errors()->first());
            }

            $licence_traking                      = DrivingLicenceTraking::find($id);
            $licence_traking->student_id          = $request->student_id;
            $licence_traking->licence_type_id     = $request->licence_type_id;
            $licence_traking->application_date    = $request->application_date;
            $licence_traking->test_date           = $request->test_date;
            $licence_traking->test_result         = $request->test_result;
            $licence_traking->licence_issue_date  = $request->licence_issue_date;
            $licence_traking->licence_number      = $request->licence_number;
            $licence_traking->licence_expiry_date = $request->licence_expiry_date;
            $licence_traking->save();

            event(new UpdateDrivingLicenceTraking($request, $licence_traking));

            return redirect()->back()->with('success', __('The licence tracking details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('licence traking delete')) {

            $traking = DrivingLicenceTraking::find($id);
            if ($traking->created_by == creatorId() && $traking->workspace == getActiveWorkSpace()) {

                event(new DestroyDrivingLicenceTraking($traking));
                $traking->delete();
                return redirect()->back()->with('success', __('The licence tracking has been deleted.'));

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
