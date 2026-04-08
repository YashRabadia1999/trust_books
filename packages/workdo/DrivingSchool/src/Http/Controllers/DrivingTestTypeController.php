<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Entities\DrivingTestType;
use Workdo\DrivingSchool\Events\CreateDrivingTestType;
use Workdo\DrivingSchool\Events\DestroyDrivingTestType;
use Workdo\DrivingSchool\Events\UpdateDrivingTestType;

class DrivingTestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('driving testtype manage')) {
            $test_types = DrivingTestType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('driving-school::test_type.index', compact('test_types'));
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
        if (Auth::user()->isAbleTo('driving testtype create')) {
            return view('driving-school::test_type.create');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('driving testtype create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $jobCategory             = new DrivingTestType();
            $jobCategory->name       = $request->name;
            $jobCategory->workspace  = getActiveWorkSpace();
            $jobCategory->created_by = creatorId();
            $jobCategory->save();

            event(new CreateDrivingTestType($request, $jobCategory));

            return redirect()->back()->with('success', __('The test type has been created successfully.'));
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
        return view('driving-school::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('driving testtype edit')) {
            $test_type = DrivingTestType::find($id);
            return view('driving-school::test_type.edit', compact('test_type'));
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
    public function update($id, Request $request)
    {
        if (Auth::user()->isAbleTo('driving testtype edit')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $test_type = DrivingTestType::find($id);
            $test_type->name = $request->name;
            $test_type->save();

            event(new UpdateDrivingTestType($request, $test_type));

            return redirect()->back()->with('success', __('The test type details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('driving testtype delete')) {
            $test_type = DrivingTestType::find($id);
            if ($test_type->created_by == creatorId() && $test_type->workspace == getActiveWorkSpace()) {

                event(new DestroyDrivingTestType($test_type));
                $test_type->delete();
                return redirect()->back()->with('success', __('The test type has been deleted.'));

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
