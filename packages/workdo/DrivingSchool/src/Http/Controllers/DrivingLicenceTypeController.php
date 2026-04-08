<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\DrivingSchool\Entities\DrivingLicenceType;
use Workdo\DrivingSchool\Events\CreateDrivingLicenceType;
use Workdo\DrivingSchool\Events\DestroyDrivingLicenceType;
use Workdo\DrivingSchool\Events\UpdateDrivingLicenceType;

class DrivingLicenceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('driving licencetype manage')) {
            $licence_types = DrivingLicenceType::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('driving-school::licence_type.index', compact('licence_types'));
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
        if (Auth::user()->isAbleTo('driving licencetype create')) {
            return view('driving-school::licence_type.create');
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
        if (Auth::user()->isAbleTo('driving licencetype create')) {

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

            $jobCategory             = new DrivingLicenceType();
            $jobCategory->name       = $request->name;
            $jobCategory->workspace  = getActiveWorkSpace();
            $jobCategory->created_by = creatorId();
            $jobCategory->save();

            event(new CreateDrivingLicenceType($request, $jobCategory));

            return redirect()->back()->with('success', __('The licence type has been created successfully.'));
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
        if (Auth::user()->isAbleTo('driving licencetype edit')) {
            $licence_type = DrivingLicenceType::find($id);
            return view('driving-school::licence_type.edit', compact('licence_type'));
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
        if (Auth::user()->isAbleTo('driving licencetype edit')) {

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

            $licence_type = DrivingLicenceType::find($id);
            $licence_type->name = $request->name;
            $licence_type->save();

            event(new UpdateDrivingLicenceType($request, $licence_type));

            return redirect()->back()->with('success', __('The licence type details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('driving licencetype delete')) {
            $licence_type = DrivingLicenceType::find($id);
            if ($licence_type->created_by == creatorId() && $licence_type->workspace == getActiveWorkSpace()) {

                event(new DestroyDrivingLicenceType($licence_type));
                $licence_type->delete();
                return redirect()->back()->with('success', __('The licence type has been deleted.'));

            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
