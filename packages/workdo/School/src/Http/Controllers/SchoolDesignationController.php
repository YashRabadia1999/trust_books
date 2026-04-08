<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Department;
use Workdo\School\Entities\Designation;
use Workdo\School\Entities\Employee;

class SchoolDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('school_designation manage')) {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with(['branch', 'department'])->get();

            return view('school::designation.index', compact('designations'));
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
        if (Auth::user()->isAbleTo('school_designation create')) {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('school::designation.create', compact('departments'));
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
        if (Auth::user()->isAbleTo('school_designation create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'department_id' => 'required',
                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            try {
                $branch = Department::where('id', $request->department_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first()->branch->id;
            } catch (Exception $e) {
                $branch = null;
            }
            $designation                = new Designation();
            $designation->branch_id     = $branch;
            $designation->department_id = $request->department_id;
            $designation->name          = $request->name;
            $designation->workspace  = getActiveWorkSpace();
            $designation->created_by    = creatorId();
            $designation->save();

            return redirect()->route('schooldesignation.index')->with('success', __('The designation has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_designation edit')) {
            $designation = Designation::find($id);
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('school::designation.edit', compact('designation', 'departments'));
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
        if (Auth::user()->isAbleTo('school_designation edit')) {
            $designation = Designation::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'department_id' => 'required',
                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            try {
                $branch = Department::where('id', $request->department_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->first()->branch->id;
            } catch (Exception $e) {
                $branch = null;
            }
            $designation->branch_id     = $branch;
            $designation->department_id = $request->department_id;
            $designation->name          = $request->name;
            $designation->save();


            return redirect()->route('schooldesignation.index')->with('success', __('The designation details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_designation delete')) {
            $designation = Designation::find($id);

                $employee = Employee::where('designation_id', $designation->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($employee) == 0) {

                    $designation->delete();
                } else {
                    return redirect()->route('schooldesignation.index')->with('error', __('This designation has employees. Please remove the employee from this designation.'));
                }
                return redirect()->route('schooldesignation.index')->with('success', __('The designation has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function DesignationNameEdit()
    {
        if (Auth::user()->isAbleTo('school_designation name edit')) {
            return view('school::designation.designationnameedit');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function saveDesignationName(Request $request)
    {
        if (Auth::user()->isAbleTo('school_designation name edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'hrm_designation_name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            } else {
                $post = $request->all();
                unset($post['_token']);

                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ];
                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
                // Settings Cache forget
                comapnySettingCacheForget();
                return redirect()->route('schooldesignation.index')->with('success', __('The designation name are updated successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
