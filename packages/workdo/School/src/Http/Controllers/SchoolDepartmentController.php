<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Department;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Branch;
use Workdo\School\Entities\Designation;
use Workdo\School\Entities\Employee;

use Rawilk\Settings\Support\Context;

class SchoolDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('school_department manage')) {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('school::department.index', compact('departments'));
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
        if (Auth::user()->isAbleTo('school_department create')) {
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('school::department.create', compact('branch'));
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
        if (Auth::user()->isAbleTo('school_department create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch_id' => 'required',
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $department             = new Department();
            $department->branch_id  = $request->branch_id;
            $department->name       = $request->name;
            $department->workspace  = getActiveWorkSpace();
            $department->created_by = \Auth::user()->id;
            $department->save();

            return redirect()->route('schooldepartment.index')->with('success', __('The department has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_department edit')) {

            $department = Department::find($id);
            $branch = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('school::department.edit', compact('department', 'branch'));
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
        if(Auth::user()->isAbleTo('school_department edit'))
        {
            $department = Department::find($id);

                $validator = \Validator::make(
                    $request->all(), [
                                       'branch_id' => 'required',
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                // update Designation branch id
                Designation::where('department_id',$department->id)->where('created_by', '=', creatorId())->where('workspace',getActiveWorkSpace())->update(['branch_id' => $request->branch_id]);

                $department->branch_id = $request->branch_id;
                $department->name      = $request->name;
                $department->save();

                return redirect()->route('schooldepartment.index')->with('success', __('The department details are updated successfully.'));
        }
        else
        {
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
        if(Auth::user()->isAbleTo('school_department delete'))
        {
            $department = Department::find($id);

                $employee     = Employee::where('department_id',$department->id)->where('workspace',getActiveWorkSpace())->get();
                if(count($employee) == 0)
                {
                    Designation::where('department_id',$department->id)->delete();
                    $department->delete();
                }
                else
                {
                    return redirect()->route('schooldepartment.index')->with('error', __('This department has employees. Please remove the employee from this department.'));
                }
                return redirect()->route('schooldepartment.index')->with('success', __('The department has been deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function DepartmentsNameEdit()
    {
        if(Auth::user()->isAbleTo('school_department name edit'))
        {
            return view('school::department.departmentnameedit');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    public function saveDepartmentName(Request $request)
    {
        if (Auth::user()->isAbleTo('school_department name edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'hrm_department_name' => 'required',
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
                return redirect()->route('schooldepartment.index')->with('success', __('The department name are updated successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
