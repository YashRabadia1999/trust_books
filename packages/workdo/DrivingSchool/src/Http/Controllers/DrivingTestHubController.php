<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\DrivingSchool\Entities\DrivingTestHub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Workdo\DrivingSchool\Entities\DrivingTestType;
use Workdo\DrivingSchool\DataTables\DrivingTestHubDatatable;
use Workdo\DrivingSchool\Events\CreateDrivingTestHub;
use Workdo\DrivingSchool\Events\DestroyDrivingTestHub;
use Workdo\DrivingSchool\Events\UpdateDrivingTestHub;

class DrivingTestHubController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingTestHubDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('driving testhub manage')) {

            return $dataTable->render('driving-school::test_hub.index');
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
        if (Auth::user()->isAbleTo('driving testhub create')) {
            $student            = DrivingStudent::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            $users              = User::where('created_by', creatorId())->where('type', 'staff')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $test_types         = DrivingTestType::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('driving-school::test_hub.create', compact('student', 'users', 'test_types'));
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
        if (Auth::user()->isAbleTo('driving testhub create')) {
            $validator = \Validator::make($request->all(), [
                'teacher_id' => 'required',
                'student_id' => 'required',
                'test_type_id' => 'required',
                'test_date' => 'required',
                'test_score' => 'required',
                'test_result' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->route('test_hub.index')->with('error', $validator->errors()->first());
            }

            $test_hub = new DrivingTestHub;
            $test_hub->student_id   = $request->student_id;
            $test_hub->teacher_id   = $request->teacher_id;
            $test_hub->test_type_id = $request->test_type_id;
            $test_hub->test_date    = $request->test_date;
            $test_hub->test_score   = $request->test_score;
            $test_hub->test_result  = $request->test_result;
            $test_hub->remarks      = $request->remarks;
            $test_hub->workspace    = getActiveWorkSpace();
            $test_hub->created_by   = Auth::user()->id;
            $test_hub->save();
            event(new CreateDrivingTestHub($request, $test_hub));

            return redirect()->back()->with('success', __('The test hub has been created successfully'));
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
        if (Auth::user()->isAbleTo('driving testhub show')) {
            try {
                $id         = Crypt::decrypt($id);
                $test_hub   = DrivingTestHub::where('id', $id)->where('workspace', getActiveWorkSpace())->firstOrFail();
                $student    = DrivingStudent::select('name')->where('id',$test_hub->student_id)->where('workspace', '=', getActiveWorkSpace())->first();
                $users      = User::select('name')->where('id',$test_hub->teacher_id)->where('type', 'staff')->where('workspace_id', getActiveWorkSpace())->first();
                $test_types = DrivingTestType::select('name')->where('id',$test_hub->test_type_id)->where('workspace', '=', getActiveWorkSpace())->first();

                return view('driving-school::test_hub.show', compact('test_hub', 'student', 'users', 'test_types'));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Test hub not found.'));
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
        if (Auth::user()->isAbleTo('driving testhub edit')) {
            $test_hub   = DrivingTestHub::find($id);
            $student    = DrivingStudent::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            $users      = User::where('created_by', creatorId())->where('type', 'staff')->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $test_types = DrivingTestType::where('created_by', '=', creatorId())->where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('driving-school::test_hub.edit', compact('test_hub','student', 'users', 'test_types'));
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
        if (Auth::user()->isAbleTo('driving testhub edit')) {

            $validator = \Validator::make($request->all(), [
                'teacher_id' => 'required',
                'student_id' => 'required',
                'test_type_id' => 'required',
                'test_date' => 'required',
                'test_score' => 'required',
                'test_result' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $test_hub               = DrivingTestHub::find($id);
            $test_hub->student_id   = $request->student_id;
            $test_hub->teacher_id   = $request->teacher_id;
            $test_hub->test_type_id = $request->test_type_id;
            $test_hub->test_date    = $request->test_date;
            $test_hub->test_score   = $request->test_score;
            $test_hub->test_result  = $request->test_result;
            $test_hub->remarks      = $request->remarks;
            $test_hub->save();

            event(new UpdateDrivingTestHub($request, $test_hub));

            return redirect()->back()->with('success', __('The test hub details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('driving testhub delete')) {
            $test_hub = DrivingTestHub::find($id);
            if ($test_hub->created_by == creatorId() && $test_hub->workspace == getActiveWorkSpace()) {

                event(new DestroyDrivingTestHub($test_hub));
                $test_hub->delete();
                return redirect()->back()->with('success', __('The test hub has been deleted.'));

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
