<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\Branch;
use Workdo\School\Entities\Department;
use Workdo\School\Entities\Designation;
use Workdo\School\Entities\DocumentType;
use Workdo\School\Entities\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Workdo\School\Events\CreateSchoolEmployee;
use Workdo\School\Events\DestorySchoolEmployee;
use Workdo\School\Events\UpdateSchoolEmployee;
use Workdo\School\DataTables\TeacherDataTable;

class SchoolEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(TeacherDataTable $dataTable)
    {
//   $query = $dataTable->query(new \App\Models\User());

//     // See the SQL
//     dd($query->toSql(), $query->getBindings());

//     // Or see the actual rows
//     dd($query->get());
        if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
            
            return $dataTable->render('school::employee.index');
        } elseif (Auth::user()->isAbleTo('school_employee manage')) {
            
            return $dataTable->render('school::employee.index');
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
        if (Auth::user()->isAbleTo('school_employee create')) {

           
            $role             = Role::where('created_by', creatorId())->whereNotIn('name', Auth::user()->not_emp_type)->get()->pluck('name', 'id');
            $branches         = Branch::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments      = Department::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations     = Designation::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $employees        = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
            $employeesId      = Employee::employeeIdFormat($this->employeeNumber());
            return view('school::employee.create',compact('employees','employeesId', 'departments', 'designations', 'branches','role'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    function employeeNumber()
    {
        // Fix: Find the highest employee_id globally, ordering numerically not alphabetically
        // This ensures we get the correct next employee number
        $latest = Employee::orderByRaw('CAST(employee_id AS UNSIGNED) DESC')->first();
        
        if (!$latest) {
            return 1;
        }
        // Fix: Convert employee_id to integer before adding 1
        return (int)$latest->employee_id + 1;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $canUse =  PlanCheck('User', Auth::user()->id);
        if ($canUse == false) {
            return redirect()->back()->with('error', 'You have maxed out the total number of Employee allowed on your current plan');
        }
        $roles            = Role::where('created_by', creatorId())->where('id', $request->role)->first();
        if (Auth::user()->isAbleTo('school_employee create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'dob' => 'before:' . date('Y-m-d'),
                    'gender' => 'required',
                    'address' => 'required',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'designation_id' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            if ($request->input('phone')) {
                $validator = \Validator::make(
                     $request->all(), ['phone' => 'required|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails()){
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            if (isset($request->user_id)) {
                $user = User::where('id', $request->user_id)->first();
            } else {
                // Fix: Handle cases where getActiveWorkSpace() or creatorId() might return empty values
                $workspace = getActiveWorkSpace() ?: Auth::user()->workspace_id;
                $createdBy = creatorId() ?: Auth::user()->id;
                
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => $workspace,
                        'active_workspace' => $workspace,
                        'created_by' => $createdBy,
                    ]
                );
                $user->save();

                $user->addRole($roles);
            }
            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name) {
                $user->name = $request->name;
                $user->save();
            }
            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'dob' => $request['dob'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'email' => $user->email,
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
                    'company_doj' => $request['company_doj'],
                    'documents' => $document_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'workspace' => $user->workspace_id,
                    'created_by' => $user->created_by,
                ]
            );
            $employee->save();
            event(new CreateSchoolEmployee($request,$employee));

            return redirect()->route('schoolemployee.index')->with('success', __('The teacher has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    // public function show($id)
    // {
    //     try {
    //         $id = Crypt::decrypt($id);
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', __('Teacher Not Found.'));
    //     }
    
        // if (
        //     !in_array(Auth::user()->type, Auth::user()->not_emp_type) ||
        //     Auth::user()->isAbleTo('school_employee show') ||
        //     Auth::user()->isAbleTo('school_employee edit') ||
        //     Auth::user()->isAbleTo('school_employee manage')
        // ) {
        //     $employee = Employee::where('id', $id)
        //         ->where('workspace', getActiveWorkSpace())
        //         ->first();
    
        //     if (!$employee) {
        //         return redirect()->back()->with('error', __('Teacher Not Found.'));
        //     }
    
        //     // Relations safe check
        //     $classes = method_exists($employee, 'classes') ? $employee->classes : collect();
        //     $subjects = method_exists($employee, 'subjects') ? $employee->subjects : collect();
    
        //     return view('school::employee.show', compact('employee', 'classes', 'subjects'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    // }
    public function show($user_id)
    {
        if (Auth::user()->isAbleTo('school_employee show')) {
            
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            
            // Fix: Find employee by user_id instead of employee id
            $employee = Employee::where('user_id', $user_id)->first();
            // Additional workspace check if needed
            if ($employee && getActiveWorkSpace() && $employee->workspace != getActiveWorkSpace()) {
                $employee = null;
            }
            
            // If no employee record found, try to create one from user data
            if (!$employee) {
                $user = User::where('id', $user_id)->first();
                if ($user) {
                    // Create a basic employee record
                    $employee = Employee::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'workspace' => $user->workspace_id,
                        'created_by' => $user->created_by,
                    ]);
                }
            }
            
            $user = '';
            if($employee != null)
            {            
                $user = User::where('id', $employee->user_id)->first();
            }
            $role = Role::where('created_by', creatorId())->whereNotIn('name', Auth::user()->not_emp_type)->get()->pluck('name', 'id');

            return view('school::employee.show', compact('employee', 'user', 'branches', 'departments', 'designations','role'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($user_id)
    {
        if (Auth::user()->isAbleTo('school_employee edit')) {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            
            // Fix: Find employee by user_id instead of employee id
            $employee = Employee::where('user_id', $user_id)->first();
            // Additional workspace check if needed
            if ($employee && getActiveWorkSpace() && $employee->workspace != getActiveWorkSpace()) {
                $employee = null;
            }
            $user = '';
            
            if($employee != null)
            {            
                $user = User::where('id', $employee->user_id)->where('workspace_id', getActiveWorkSpace())->first();
            }
            $role = Role::where('created_by', creatorId())->whereNotIn('name', Auth::user()->not_emp_type)->get()->pluck('name', 'id');
            
            return view('school::employee.edit', compact('employee', 'user', 'branches', 'departments', 'designations','role'));
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
        if (Auth::user()->isAbleTo('school_employee edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'dob' => 'required',
                    'gender' => 'required',
                    'address' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if ($request->input('phone')) {
                $validator = \Validator::make(
                     $request->all(), ['phone' => 'required|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails()){
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $user = User::where('id', $request->user_id)->first();

            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name) {
                $user->name = $request->name;
                $user->save();
            }

            // Fix: Find employee by user_id instead of employee id
            $employee = Employee::where('user_id', $id)->first();
            // Additional workspace check if needed
            if (!$employee || (getActiveWorkSpace() && $employee->workspace != getActiveWorkSpace())) {
                return redirect()->back()->with('error', __('Employee not found.'));
            }

            $input    = $request->all();
            $employee->fill($input)->save();
            event(new UpdateSchoolEmployee($request,$employee));

            return redirect()->route('schoolemployee.index')->with('success', 'The teacher details are updated successfully.');
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
        if (Auth::user()->isAbleTo('school_employee delete')) {
            $employee     = Employee::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
            if (!empty($employee)) {
                event(new DestorySchoolEmployee($employee));
                $employee->delete();
            } else {
                return redirect()->back()->with('error', __('Teacher already delete.'));
            }

            return redirect()->back()->with('success', 'The teacher has been deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getDepartment(Request $request)
    {
        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('branch_id', $request->branch_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($departments);
    }

    public function getdDesignation(Request $request)
    {
        if ($request->department_id == 0) {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        } else {
            $designations = Designation::where('department_id', $request->department_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($designations);
    }
}
