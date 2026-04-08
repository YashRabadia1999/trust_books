<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Branch;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Department;
use Workdo\School\Entities\Designation;
use Workdo\School\Entities\Employee;

class SchoolBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('school_branch manage')) {
            $branches = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('school::branch.index', compact('branches'));
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
        if (Auth::user()->isAbleTo('school_branch create')) {

        return view('school::branch.create');
        }
        else{
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
        if (Auth::user()->isAbleTo('school_branch create')) {
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

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->workspace  = getActiveWorkSpace();
            $branch->created_by = creatorId();
            $branch->save();

            return redirect()->route('schoolbranches.index')->with('success', __('The branch  has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_branch edit')) {
            $branch = Branch::find($id);
                return view('school::branch.edit',compact('branch'));
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
        if (Auth::user()->isAbleTo('school_branch edit')) {
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
                $branch = Branch::find($id);

                $branch->name = $request->name;
                $branch->save();

                return redirect()->route('schoolbranches.index')->with('success', __('The branch details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_branch delete')) {
            $branch = Branch::find($id);

                $employee     = Employee::where('branch_id', $branch->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($employee) == 0) {
                    Department::where('branch_id', $branch->id)->delete();
                    Designation::where('branch_id', $branch->id)->delete();


                    $branch->delete();
                } else {
                    return redirect()->route('schoolbranches.index')->with('error', __('This branch has employees. Please remove the employee from this branch.'));
                }

                return redirect()->route('schoolbranches.index')->with('success', __('The branch has been deleted.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BranchNameEdit()
    {
        if (Auth::user()->isAbleTo('school_branch name edit')) {
            return view('school::branch.branchnameedit');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function saveBranchName(Request $request)
    {
        if (Auth::user()->isAbleTo('school_branch name edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'hrm_branch_name' => 'required',
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
                return redirect()->route('schoolbranches.index')->with('success', __('The branch name are updated successfully.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
