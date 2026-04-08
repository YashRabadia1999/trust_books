<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Admission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\User;
use Google\Service\Classroom\Student;
use Illuminate\Support\Facades\Crypt;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;
use App\Models\Role;
use Workdo\School\Entities\SchoolGrade;
use Workdo\School\Events\AdmissionConvert;
use Workdo\School\Events\CreateAdmission;
use Workdo\School\Events\DestoryAdmission;
use Workdo\School\Events\UpdateAdmission;
use Workdo\School\DataTables\AdmissionDataTable;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(AdmissionDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_admission manage')) {
            return $dataTable->render('school::admission.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_admission create')) {
            $admission_number = Admission::admissionNumberFormat($this->admissionNumber());
            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module', 'Addmission')->get();
            } else {
                $customFields = null;
            }
            return view('school::admission.create', compact('admission_number', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    function admissionNumber()
    {
        $latest = company_setting('admission_starting_number');
        if ($latest == null) {
            return 1;
        } else {
            return $latest;
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_admission create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'student_name' => 'required',
                    'email' => 'required',
                    'student_image' => 'required',
                    'date_of_birth' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $validationRules = [];

        if ($request->input('phone')) {
            $validationRules['phone'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if ($request->input('father_number')) {
            $validationRules['father_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if ($request->input('mother_number')) {
            $validationRules['mother_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if ($request->input('guardian_number')) {
            $validationRules['guardian_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if (!empty($validationRules)) {
            $validator = \Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
        }

            $admission = new Admission();
            $admission->admission_id      = $this->admissionNumber();
            $admission->date              = $request->date;
            $admission->student_name      = $request->student_name;
            $admission->date_of_birth     = $request->date_of_birth;
            $admission->gender            = $request->gender;
            $admission->blood_group       = $request->blood_group;
            $admission->address           = $request->address;
            $admission->state             = $request->state;
            $admission->city              = $request->city;
            $admission->zip_code          = $request->zip_code;
            $admission->phone             = $request->phone;
            $admission->email             = $request->email;
            $admission->password          = $request->password;
            $admission->previous_school   = isset($request->previous_school) ? $request->previous_school : '';
            if ($request->hasFile('student_image')) {
                $fileName = time() . "_" . $request->student_image->getClientOriginalName();
                $path = upload_file($request, 'student_image', $fileName, 'Student');
                $admission->student_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->medical_history   = $request->medical_history;
            $admission->father_name       = $request->father_name;
            $admission->father_number     = $request->father_number;
            $admission->father_occupation = $request->father_occupation;
            $admission->father_email      = $request->father_email;
            $admission->father_password   = $request->father_password;
            $admission->education_level   = $request->education_level;
            $admission->father_address    = $request->father_address;
            if ($request->hasFile('father_image')) {
                $fileName = time() . "_" . $request->father_image->getClientOriginalName();
                $path = upload_file($request, 'father_image', $fileName, 'Student');
                $admission->father_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->mother_name       = $request->mother_name;
            $admission->mother_number     = $request->mother_number;
            $admission->mother_occupation = $request->mother_occupation;
            $admission->mother_email      = $request->mother_email;
            $admission->mother_password          = $request->mother_password;
            $admission->mother_address    = $request->mother_address;
            if ($request->hasFile('mother_image')) {
                $fileName = time() . "_" . $request->mother_image->getClientOriginalName();
                $path = upload_file($request, 'mother_image', $fileName, 'Student');
                $admission->mother_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $guardian = $request->guardian;
            if ($guardian == 'father') {
                if ($request->hasFile('guardian_father_image')) {
                    $guardian_image = time() . "_" . $request->guardian_father_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_father_image', $fileName, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            } elseif ($guardian == 'mother') {
                if ($request->hasFile('guardian_mother_image')) {
                    $guardian_image = time() . "_" . $request->guardian_mother_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_mother_image', $fileName, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            } else {
                if ($request->hasFile('guardian_other_image')) {
                    $guardian_image = time() . "_" . $request->guardian_other_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_other_image', $fileName, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            }

            $jsonData = [
                'guardian'            => $request->guardian,
                'guardian_name'       => $request->guardian_name,
                'guardian_realtion'   => $request->guardian_realtion,
                'guardian_number'     => $request->guardian_number,
                'guardian_occupation' => $request->guardian_occupation,
                'guardian_email'      => $request->guardian_email,
                'guardian_address'    => $request->guardian_address,
                'guardian_' . $guardian . '_image'      => $admission->guardian
            ];
            $data = json_encode($jsonData);

            $admission->guardian    = $data;
            // if ($request->hasFile('leaving_certificate')) {
            //     $fileName = time() . "_" . $request->leaving_certificate->getClientOriginalName();
            //     $path = upload_file($request, 'leaving_certificate', $fileName, 'Student');
            //     $admission->leaving_certificate = empty($path) ? null : ($path['url'] ?? null);
            // }
            // if ($request->hasFile('marksheet')) {
            //     $fileName = time() . "_" . $request->marksheet->getClientOriginalName();
            //     $path = upload_file($request, 'marksheet', $fileName, 'Student');
            //     $admission->marksheet = empty($path) ? null : ($path['url'] ?? null);
            // }
            if ($request->hasFile('gov_issued_id')) {
                $fileName = time() . "_" . $request->gov_issued_id->getClientOriginalName();
                $path = upload_file($request, 'gov_issued_id', $fileName, 'Student');
                $admission->gov_issued_id = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('previous_school_certificate')) {
                $fileName = time() . "_" . $request->previous_school_certificate->getClientOriginalName();
                $path = upload_file($request, 'previous_school_certificate', $fileName, 'Student');
                $admission->previous_school_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('birth_certificate')) {
                $fileName = time() . "_" . $request->birth_certificate->getClientOriginalName();
                $path = upload_file($request, 'birth_certificate', $fileName, 'Student');
                $admission->birth_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('address_proof')) {
                $fileName = time() . "_" . $request->address_proof->getClientOriginalName();
                $path = upload_file($request, 'address_proof', $fileName, 'Student');
                $admission->address_proof = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('bonafide_certificate')) {
                $fileName = time() . "_" . $request->bonafide_certificate->getClientOriginalName();
                $path = upload_file($request, 'bonafide_certificate', $fileName, 'Student');
                $admission->bonafide_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->created_by        = creatorId();
            $admission->workspace         = getActiveWorkSpace();
            $admission->save();
            Admission::starting_number($admission->admission_id + 1);
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($admission, $request->customField);
            }
            event(new CreateAdmission($request, $admission));

            return redirect()->route('admission.index', $admission->id)->with('success', __('The admission has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_admission edit')) {
            try{
                $id = Crypt::decrypt($id);
            }catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Admission Not Found.'));
            }
            $admission = Admission::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            if (isset($admission['guardian'])) {
                $guardianData = json_decode($admission['guardian'], true);
            }
            $admission_number = Admission::admissionNumberFormat($admission->admission_id);
            if (module_is_active('CustomField')) {
                $admission->customField = \Workdo\CustomField\Entities\CustomField::getData($admission, 'School', 'Addmission');
                $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module', 'Addmission')->get();
            } else {
                $customFields = null;
            }

            return view('school::admission.edit', compact('admission', 'admission_number', 'guardianData', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
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
        if (Auth::user()->isAbleTo('school_admission edit')) {

        $validationRules = [];

        if ($request->input('phone')) {
            $validationRules['phone'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if ($request->input('father_number')) {
            $validationRules['father_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        if ($request->input('mother_number')) {
            $validationRules['mother_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        }

        // if ($request->input('guardian_number')) {
        //     $validationRules['guardian_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
        // }

        if (!empty($validationRules)) {
            $validator = \Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
        }

            $admission = Admission::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $admission->admission_id      = $this->admissionNumber();
            $admission->date              = $request->date;
            $admission->student_name      = $request->student_name;
            $admission->date_of_birth     = $request->date_of_birth;
            $admission->gender            = $request->gender;
            $admission->blood_group       = $request->blood_group;
            $admission->address           = $request->address;
            $admission->state             = $request->state;
            $admission->city              = $request->city;
            $admission->zip_code          = $request->zip_code;
            $admission->phone             = $request->phone;
            $admission->email             = $request->email;
            $admission->previous_school   = isset($request->previous_school) ? $request->previous_school : '';
            if ($request->hasFile('student_image')) {
                $fileName = time() . "_" . $request->student_image->getClientOriginalName();
                $path = upload_file($request, 'student_image', $fileName, 'Student');
                $admission->student_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->medical_history   = $request->medical_history;
            $admission->father_name       = $request->father_name;
            $admission->father_number     = $request->father_number;
            $admission->father_occupation = $request->father_occupation;
            $admission->father_email      = $request->father_email;
            $admission->father_address    = $request->father_address;
            if ($request->hasFile('father_image')) {
                $fileName = time() . "_" . $request->father_image->getClientOriginalName();
                $path = upload_file($request, 'father_image', $fileName, 'Student');
                $admission->father_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->mother_name       = $request->mother_name;
            $admission->mother_number     = $request->mother_number;
            $admission->mother_occupation = $request->mother_occupation;
            $admission->mother_email      = $request->mother_email;
            $admission->mother_address    = $request->mother_address;
            if ($request->hasFile('mother_image')) {
                $fileName = time() . "_" . $request->mother_image->getClientOriginalName();
                $path = upload_file($request, 'mother_image', $fileName, 'Student');
                $admission->mother_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $guardian = $request->guardian;

            if ($guardian == 'father') {
                if ($request->hasFile('guardian_father_image')) {
                    $guardian_image = time() . "_" . $request->guardian_father_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_father_image', $guardian_image, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            } elseif ($guardian == 'mother') {
                if ($request->hasFile('guardian_mother_image')) {
                    $guardian_image = time() . "_" . $request->guardian_mother_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_mother_image', $guardian_image, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            } else {
                if ($request->hasFile('guardian_other_image')) {
                    $guardian_image = time() . "_" . $request->guardian_other_image->getClientOriginalName();
                    $path = upload_file($request, 'guardian_other_image', $guardian_image, 'Student');
                    $admission->guardian = empty($path) ? null : ($path['url']);
                }
            }

            $jsonData = [
                'guardian'            => $request->guardian,
                'guardian_name'       => $request->guardian_name,
                'guardian_realtion'   => $request->guardian_realtion,
                'guardian_number'     => $request->guardian_number,
                'guardian_occupation' => $request->guardian_occupation,
                'guardian_email'      => $request->guardian_email,
                'guardian_address'    => $request->guardian_address,
                'guardian_' . $guardian . '_image'      => $admission->guardian
            ];
            $data = json_encode($jsonData);

            $admission->guardian    = $data;
            // if ($request->hasFile('leaving_certificate')) {
            //     $fileName = time() . "_" . $request->leaving_certificate->getClientOriginalName();
            //     $path = upload_file($request, 'leaving_certificate', $fileName, 'Student');
            //     $admission->leaving_certificate = empty($path) ? null : ($path['url'] ?? null);
            // }
            if ($request->hasFile('gov_issued_id')) {
                $fileName = time() . "_" . $request->gov_issued_id->getClientOriginalName();
                $path = upload_file($request, 'gov_issued_id', $fileName, 'Student');
                $admission->gov_issued_id = empty($path) ? null : ($path['url'] ?? null);
            }
            // if ($request->hasFile('marksheet')) {
            //     $fileName = time() . "_" . $request->marksheet->getClientOriginalName();
            //     $path = upload_file($request, 'marksheet', $fileName, 'Student');
            //     $admission->marksheet = empty($path) ? null : ($path['url'] ?? null);
            // }
            if ($request->hasFile('previous_school_certificate')) {
                $fileName = time() . "_" . $request->previous_school_certificate->getClientOriginalName();
                $path = upload_file($request, 'previous_school_certificate', $fileName, 'Student');
                $admission->previous_school_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('birth_certificate')) {
                $fileName = time() . "_" . $request->birth_certificate->getClientOriginalName();
                $path = upload_file($request, 'birth_certificate', $fileName, 'Student');
                $admission->birth_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('address_proof')) {
                $fileName = time() . "_" . $request->address_proof->getClientOriginalName();
                $path = upload_file($request, 'address_proof', $fileName, 'Student');
                $admission->address_proof = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('bonafide_certificate')) {
                $fileName = time() . "_" . $request->bonafide_certificate->getClientOriginalName();
                $path = upload_file($request, 'bonafide_certificate', $fileName, 'Student');
                $admission->bonafide_certificate = empty($path) ? null : ($path['url'] ?? null);
            }
            $admission->created_by        = creatorId();
            $admission->workspace         = getActiveWorkSpace();
            $admission->update();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($admission, $request->customField);
            }
            event(new UpdateAdmission($request, $admission));

            return redirect()->route('admission.index', $admission->id)->with('success', __('The admission details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('school_admission delete')) {
            $admission = Admission::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            if (module_is_active('CustomField')) {
                $customFields = \Workdo\CustomField\Entities\CustomField::where('module', 'School')->where('sub_module', 'Addmission')->get();
                foreach ($customFields as $customField) {
                    $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $admission->id)->where('field_id', $customField->id)->first();
                    if (!empty($value)) {
                        $value->delete();
                    }
                }
            }
            event(new DestoryAdmission($admission));
            $admission->delete();

            return redirect()->back()->with('success', __('The admission has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function setting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'admission_prefix' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        } else {
            $school = [];
            $school['admission_prefix'] =  $request->admission_prefix;
            foreach ($school as $key => $value) {
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
            return redirect()->back()->with('success', __('School Setting save successfully'));
        }
    }

    // public function pdf($id){
    //     $admissionId = Crypt::decrypt($id);
    //     $admission   = Admission::where('id', $admissionId)->first();


    // }

    public function admissionconvert($id)
    {

        $admission = Admission::find($id);

        $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
        $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');

        return view('school::student.convert', compact('admission', 'classRoom', 'grade'));
    }

    public function convert(Request $request, $id)
    {
        $admission = Admission::find($id);

        $father_user = new User();
        $father_user['name']         = $admission->father_name;
        $father_user['email']        = $admission->father_email;
        $father_user['mobile_no']    = $admission->father_number;
        $father_user['password']     = $admission->father_password;
        $father_user['type']         = 'parent';
        $father_user['active_workspace']   = getActiveWorkSpace();
        $father_user['workspace_id'] = $admission->workspace;
        $father_user['created_by']   = $admission->created_by;
        $father_user->save();
        $role_r = Role::where('name', 'parent')->first();
        $father_user->addRole($role_r);

        $father  = new SchoolParent();
        $father['parent_id'] = $this->parentNumber();
        $father['user_id'] = $father_user->id;
        $father['name'] = isset($admission->father_name) ? $admission->father_name : '';
        $father['gender'] = 'father';
        $father['relation'] = 'father';
        $father['address'] = $admission->father_address;
        $father['contact'] = $admission->father_number;
        $father['email'] = $admission->father_email;
        $father['password'] = $admission->father_password;
        $father['workspace'] = getActiveWorkSpace();
        $father['created_by'] = $admission->created_by;
        $father->save();

        $mother_user = new User();
        $mother_user['name']         = $admission->mother_name;
        $mother_user['email']        = $admission->mother_email;
        $mother_user['mobile_no']    = $admission->mother_number;
        $mother_user['password']     = $admission->father_password;
        $mother_user['type']         = 'parent';
        $mother_user['active_workspace']   = getActiveWorkSpace();
        $mother_user['workspace_id'] = $admission->workspace;
        $mother_user['created_by']   = $admission->created_by;
        $mother_user->save();
        $role_r = Role::where('name', 'parent')->first();
        $mother_user->addRole($role_r);

        $mother  = new SchoolParent();
        $mother['parent_id'] = $this->parentNumber();
        $mother['user_id'] = $mother_user->id;
        $mother['name'] = isset($admission->mother_name) ? $admission->mother_name : '';
        $mother['gender'] = 'mother';
        $mother['relation'] = 'mother';
        $mother['address'] = $admission->mother_address;
        $mother['contact'] = $admission->mother_number;
        $mother['email'] = $admission->mother_email;
        $mother['password'] = $admission->mother_password;
        $mother['workspace'] = getActiveWorkSpace();
        $mother['created_by'] = $admission->created_by;
        $mother->save();

        $user = new User();
        $user['name']         = $admission->student_name;
        $user['email']        = $admission->email;
        $user['mobile_no']    = $admission->phone;
        $user['password']     = $admission->password;
        $user['type']         = 'student';
        $user['active_workspace']   = getActiveWorkSpace();
        $user['workspace_id'] = $admission->workspace;
        $user['created_by']   = $admission->created_by;
        $user->save();

        $role_r = Role::where('name', 'student')->first();
        $user->addRole($role_r);

        $student                      = new SchoolStudent();
        $student['student_id']        = $this->studentNumber();
        $student['user_id']           = $user->id;
        $student['parent_id']         = $father_user->id . ',' . $mother_user->id;
        $student['class_name']        = $request->class_name;
        $student['roll_number']       = $request->roll_no;
        $student['grade_name']        = $request->grade_name;
        $student['name']              = $admission->student_name;
        $student['student_gender']    = $admission->gender;
        $student['std_date_of_birth'] = $admission->date_of_birth;
        $student['std_address']       = $admission->address;
        $student['std_state']         = $admission->state;
        $student['std_city']          = $admission->city;
        $student['std_zip_code']      = $admission->zip_code;
        $student['contact']           = $admission->phone;
        $student['email']             = $admission->email;
        $student['password']          = '';
        $student['student_image']     = $admission->student_image;
        $student['workspace']         = getActiveWorkSpace();
        $student['created_by']        = $admission->created_by;
        $student->save();
        if (!empty($student)) {
            $admission->converted_student_id = $user->id;
            $admission->save();
        }
        event(new AdmissionConvert($admission, $student));

        return redirect()->route('school-student.index')->with('success', __('Admission to Student Successfully Converted.'));
    }
    function studentNumber()
    {
        $latest = SchoolStudent::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->student_id + 1;
    }
    function parentNumber()
    {
        $latest = SchoolParent::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->parent_id + 1;
    }
}
