<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\FeeStructureDataTable;
use Workdo\School\Entities\SchoolFeesStructure;
use Workdo\School\Entities\Classroom;
use Workdo\School\Events\CreateSchoolFeesStructure;
use Workdo\School\Events\DestroySchoolFeesStructure;
use Workdo\School\Events\UpdateSchoolFeesStructure;

class SchoolFeesStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(FeeStructureDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_fees_structure manage')) {
            return $dataTable->render('school::fees-structure.index');
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
        if (Auth::user()->isAbleTo('school_fees_structure create')) {
            $classRooms = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('class_name','id');
            return view('school::fees-structure.create' , compact('classRooms'));
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
        if (Auth::user()->isAbleTo('school_fees_structure create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'class_id' => 'required',
                    'fee_type' => 'required',
                    'amount' => 'required|numeric',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $feeStructure             = new SchoolFeesStructure();
            $feeStructure->class_id   = $request->class_id;
            $feeStructure->fee_type   = $request->fee_type;
            $feeStructure->amount     = $request->amount;
            $feeStructure->due_date   = $request->due_date;
            $feeStructure->created_by = creatorId();
            $feeStructure->workspace  = getActiveWorkSpace();
            $feeStructure->save();

            event(new CreateSchoolFeesStructure($request, $feeStructure));

            return redirect()->back()->with('success', __('The fees structure has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_fees_structure edit')) {
            $id = Crypt::decrypt($id);

            $fees = SchoolFeesStructure::find($id);
            $classRooms = Classroom::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('class_name','id');
            return view('school::fees-structure.edit', compact('fees','classRooms'));
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
        if (Auth::user()->isAbleTo('school_fees_structure edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'class_id' => 'required',
                    'fee_type' => 'required',
                    'amount' => 'required|numeric',
                    'due_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $feeStructure             = SchoolFeesStructure::find($id);
            $feeStructure->class_id   = $request->class_id;
            $feeStructure->fee_type   = $request->fee_type;
            $feeStructure->amount     = $request->amount;
            $feeStructure->due_date   = $request->due_date;
            $feeStructure->created_by = creatorId();
            $feeStructure->workspace  = getActiveWorkSpace();
            $feeStructure->update();
            event(new UpdateSchoolFeesStructure($request, $feeStructure));

            return redirect()->back()->with('success', __('The fees structure details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_fees_structure delete')) {
            $feesStructure = SchoolFeesStructure::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolFeesStructure($feesStructure));
            $feesStructure->delete();
            return redirect()->back()->with('success', __('The fees structure has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}

