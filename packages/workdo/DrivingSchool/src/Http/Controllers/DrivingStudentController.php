<?php

namespace Workdo\DrivingSchool\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\DrivingSchool\Entities\DrivingStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Workdo\DrivingSchool\Events\CreateDrivingStudent;
use Workdo\DrivingSchool\Events\UpdateDrivingStudent;
use Workdo\DrivingSchool\Events\DestoryDrivingStudent;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;
use Workdo\DrivingSchool\DataTables\DrivingStudentDatatable;

class DrivingStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DrivingStudentDatatable $dataTable)
    {
        if (Auth::user()->isAbleTo('drivingstudent manage')){
            return $dataTable->render('driving-school::student.index');
        }
        else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('driving-school::student.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('drivingstudent create')) {
            $canUse =  PlanCheck('User', Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', 'You have maxed out the total number of customer allowed on your current plan');
            }
            $rules = [
                'name' => 'required',
                'dob' => 'required',
                'address' => 'required',
                'language' => 'required',
                'gender' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pin_code' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if (empty($request->user_id)) {
                $rules = [
                    'email' => [
                        'required',
                        Rule::unique('users')->where(function ($query) {
                            return $query->where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace());
                        })
                    ],
                    'password' => 'required',

                ];
                $validator = Validator::make($request->all(), $rules);
            }
            if($request->input('mobile_no')){
                $validator = Validator::make(
                    $request->all(), ['mobile_no' => 'nullable|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails())
                {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('driving-student.index')->with('error', $messages->first());
            }
            $roles  =Role::where('name', 'driving student')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('student Role Not found !'));
            }
            if (!empty($request->user_id)) {
                $user = User::find($request->user_id);

                if (empty($user)) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if ($user->name != $request->name) {
                    $user->name = $request->name;
                    $user->save();
                }
                if ($user->mobile_no != $request->mobile_no) {
                    $user->mobile_no = $request->mobile_no;
                    $user->save();
                }
            } else {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'mobile_no' => $request['mobile_no'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'active_workspace' => getActiveWorkSpace(),     'workspace_id' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ]
                );
                $user->save();
                $user->addRole($roles);
            }

            $student                        = new DrivingStudent();
            $student->user_id               = $user->id;
            $student->name                  = !empty($request->name) ? $request->name : null;
            $student->email                 = !empty($user->email) ? $user->email : null;
            $student->password              = !empty($request->password) ? $request->password : null;
            $student->gender                = !empty($request->gender) ? $request->gender : null;
            $student->dob                   = !empty($request->dob) ? $request->dob : null;
            $student->mobile_no             = !empty($request->mobile_no) ? $request->mobile_no : null;
            $student->address               = !empty($request->address) ? $request->address : null;
            $student->city                  = !empty($request->city) ? $request->city : null;
            $student->state                 = !empty($request->state) ? $request->state : null;
            $student->country               = !empty($request->country) ? $request->country : null;
            $student->pin_code              = !empty($request->pin_code) ? $request->pin_code : null;
            $student->language              = !empty($request->language) ? $request->language : '';
            $student->workspace             = getActiveWorkSpace();
            $student->created_by            = Auth::user()->id;
            $student->save();

            event(new CreateDrivingStudent($request, $student));
            return redirect()->back()->with('success', __('The student has been created successfully'));
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
        if (Auth::user()->isAbleTo('drivingstudent show')) {
            $ids       = Crypt::decrypt($id);
            $student = DrivingStudent::where('user_id', $ids)->where('workspace', getActiveWorkSpace())->first();
            return view('driving-school::student.show', compact('student'));
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
        $user         = User::where('id', $id)->where('workspace_id', getActiveWorkSpace())->first();
        $student     = DrivingStudent::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();

        return view('driving-school::student.edit', compact('user', 'student'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('drivingstudent edit')) {
            $rules = [
                'name' => 'required',
                'dob' => 'required',
                'gender' => 'required',
                'address' => 'required',
                'language' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pin_code' => 'required',
            ];
            if($request->input('mobile_no')){
                $validator = Validator::make(
                    $request->all(), ['mobile_no' => 'nullable|regex:/^\+\d{1,3}\d{9,13}$/',]
                );
                if($validator->fails())
                {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $user = User::where('id', $request->user_id)->first();
            if (empty($user)) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if ($user->name != $request->name) {
                $user->name = $request->name;
                $user->save();
            }
            if ($user->email != $request->email) {
                $user->email = $request->email;
                $user->save();
            }
            if ($user->mobile_no != $request->mobile_no) {
                $user->mobile_no = $request->mobile_no;
                $user->save();
            }
            $student                    = DrivingStudent::find($id);
            $student->name              = $request->name;
            $student->email             = $request->email;
            $student->dob               = $request->dob;
            $student->mobile_no         = $request->mobile_no;
            $student->language          = $request->language;
            $student->gender            = $request->gender;
            $student->address           = $request->address;
            $student->city              = $request->city;
            $student->state             = $request->state;
            $student->country           = $request->country;
            $student->pin_code          = $request->pin_code;
            $student->save();

            event(new UpdateDrivingStudent($request, $student));
            return redirect()->back()->with('success', __('The student details are updated successfully'));
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
        $student = DrivingStudent::where('user_id', $id)->where('workspace',getActiveWorkSpace())->first();

        if (Auth::user()->isAbleTo('drivingstudent delete')) {
            if (!empty($student->workspace)) {
                if ($student->workspace == getActiveWorkSpace()) {

                    event(new DestoryDrivingStudent($student));
                    $student->delete();
                    return redirect()->route('driving-student.index')->with('success', __('The student has been deleted'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
