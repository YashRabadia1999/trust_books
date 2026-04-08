<?php

namespace Workdo\School\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Google\Service\Classroom\Student;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Classroom;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Workdo\School\Entities\Admission;
use Workdo\School\Entities\SchoolGrade;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Events\CreateSchoolStudent;
use Workdo\School\Events\DestorySchoolStudent;
use Workdo\School\Events\UpdateSchoolStudent;
use Workdo\School\DataTables\StudentDataTable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Workdo\School\Entities\ExamEntry;
use Workdo\School\Entities\AssignmentEntry;

class SchoolStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(StudentDataTable $dataTable)
    {

        if (Auth::user()->isAbleTo('school_student manage')) {

            return $dataTable->render('school::student.index');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('school_student create')) {
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
            $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');

            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module', 'Student')->get();
            } else {
                $customFields = null;
            }
            return view('school::student.create', compact('classRoom', 'customFields', 'grade', 'client'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
    function studentNumber()
    {
        $latest = SchoolStudent::where('workspace', getActiveWorkSpace())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->student_id + 1;
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_student create')) {
            $canUse =  PlanCheck('User', \Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', 'You have maxed out the total number of customer allowed on your current plan');
            }
            $rules = [];
            $validator = \Validator::make($request->all(), $rules);
            if (empty($request->user_id)) {
                $rules = [
                    // 'email' => [
                    //     'required',
                    // ],
                    'password' => 'required',
                ];
                $validator = \Validator::make($request->all(), $rules);
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('school-student.index')->with('error', $messages->first());
            }
            $validationRules = [];

            if ($request->input('contact')) {
                $validationRules['contact'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if ($request->input('father_number')) {
                $validationRules['father_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if ($request->input('mother_number')) {
                $validationRules['mother_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if (!empty($validationRules)) {
                $validator = \Validator::make($request->all(), $validationRules);

                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $roles = Role::where('name', 'student')->where('guard_name', 'web')->where('created_by', creatorId())->first();
            if (empty($roles)) {
                return redirect()->back()->with('error', __('Student Role Not found !'));
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
                if ($user->mobile_no != $request->contact) {
                    $user->mobile_no = $request->contact;
                    $user->save();
                }
            } else {
                $user = User::create(
                    [
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'mobile_no' => $request['contact'],
                        'password' => Hash::make($request['password']),
                        'email_verified_at' => date('Y-m-d h:i:s'),
                        'type' => $roles->name,
                        'lang' => 'en',
                        'workspace_id' => getActiveWorkSpace(),
                        'active_workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ]
                );
                $user->save();
                $user->addRole($roles);
            }
            $student                    = new SchoolStudent();
            $student->user_id           = $user->id;
            $student->student_id        = $this->studentNumber();
            $student->client            = !empty($request->client) ? $request->client : null;
            $student->class_name        = !empty($request->class_name) ? $request->class_name : null;
            $student->grade_name        = !empty($request->grade_name) ? $request->grade_name : null;
            $student->roll_number       = !empty($request->roll_number) ? $request->roll_number : null;
            $student->name              = !empty($user->name) ? $user->name : null;
            $student->student_gender    = !empty($request->student_gender) ? $request->student_gender : null;
            $student->std_date_of_birth = !empty($request->std_date_of_birth) ? $request->std_date_of_birth : null;
            $student->std_address       = !empty($request->std_address) ? $request->std_address : null;
            $student->std_state         = !empty($request->std_state) ? $request->std_state : null;
            $student->std_city          = !empty($request->std_city) ? $request->std_city : null;
            $student->std_zip_code      = !empty($request->std_zip_code) ? $request->std_zip_code : null;
            $student->contact           = !empty($request->contact) ? $request->contact : null;
            $student->email             = !empty($request->email) ? $request->email : null;
            $student->password          = !empty($request->password) ? $request->password : null;
            if ($request->hasFile('student_image')) {
                $fileName = time() . "_" . $request->student_image->getClientOriginalName();
                $path = upload_file($request, 'student_image', $fileName, 'Student');
                $student->student_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->father_name       = $request->father_name;
            $student->father_number     = $request->father_number;
            $student->father_occupation = $request->father_occupation;
            $student->father_email      = $request->father_email;
            $student->father_address    = $request->father_address;
            if ($request->hasFile('father_image')) {
                $fileName = time() . "_" . $request->father_image->getClientOriginalName();
                $path = upload_file($request, 'father_image', $fileName, 'Student');
                $student->father_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->mother_name       = $request->mother_name;
            $student->mother_number     = $request->mother_number;
            $student->mother_occupation = $request->mother_occupation;
            $student->mother_email      = $request->mother_email;
            $student->mother_address    = $request->mother_address;
            if ($request->hasFile('mother_image')) {
                $fileName = time() . "_" . $request->mother_image->getClientOriginalName();
                $path = upload_file($request, 'mother_image', $fileName, 'Student');
                $student->mother_image = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('attachments')) {
                $fileName = time() . "_" . $request->attachments->getClientOriginalName();
                $path = upload_file($request, 'attachments', $fileName, 'Student');
                $student->attachments = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->blood_group        = $request->blood_group;
            $student->allergies          = $request->allergies;
            $student->chronic_conditions = $request->chronic_conditions;
            $student->emergency_contact  = $request->emergency_contact;
            $student->workspace     = getActiveWorkSpace();
            $student->created_by    = \Auth::user()->id;
            $student->save();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($student, $request->customField);
            }
            event(new CreateSchoolStudent($request, $student));

            return redirect()->route('school-student.index')->with('success', __('The student has been created successfully.'));
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
    if (Auth::user()->isAbleTo('school_student show')) {
        $user = User::where('id', $id)
            ->where('workspace_id', getActiveWorkSpace())
            ->first();

        $student = SchoolStudent::with([
            'fees', 
            'fees.payments', // <-- If payments relation exists
            'healthRecords'
        ])
        ->where('user_id', $user->id)
        ->where('workspace', getActiveWorkSpace())
        ->first();

        if (!$student) {
            return redirect()->back()->with('error', __('Student not found.'));
        }

        // Custom fields
        if (module_is_active('CustomField')) {
            $student->customField = \Workdo\CustomField\Entities\CustomField::getData($student, 'School', 'Student');
            $customFields = \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())
                ->where('module', 'School')
                ->where('sub_module', 'Student')
                ->get();
        } else {
            $customFields = null;
        }

        // Assignments and exams
        $assignments = AssignmentEntry::whereJsonContains('students', ['id' => $student->id])->get();
        $exams = ExamEntry::with(['exam', 'academicYear'])
            ->where('user_id', $student->user_id)
            ->latest()
            ->get();

        // --- Compute due amounts for invoices (fees)
        foreach ($student->fees as $fee) {
            $fee->paid_amount = $fee->payments ? $fee->payments->sum('amount') : 0;
            $fee->due_amount = max($fee->amount - $fee->paid_amount, 0);
            if ($fee->due_amount <= 0) {
                $fee->status = 'Paid';
            } elseif ($fee->paid_amount > 0) {
                $fee->status = 'Partially Paid';
            } else {
                $fee->status = 'Unpaid';
            }
        }
        
        return view('school::student.show', compact('student', 'customFields', 'assignments', 'exams'));
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
        if (Auth::user()->isAbleTo('school_student edit')) {
            $ids = decrypt($id);
            $user      = User::where('id', $ids)->where('workspace_id', getActiveWorkSpace())->first();
            $student   = SchoolStudent::where('user_id', $ids)->where('workspace', getActiveWorkSpace())->first();
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            $grade = SchoolGrade::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('grade_name', 'id');
            $client = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'client')->get()->pluck('name', 'id');

            if(!empty($student)){
                if(module_is_active('CustomField')){
                    $student->customField = \Workdo\CustomField\Entities\CustomField::getData($student, 'School','Student');
                    $customFields         = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module','Student')->get();
                }else{
                    $customFields = null;
                }
                return view('school::student.edit', compact('classRoom', 'student', 'user', 'grade', 'client','customFields'));
            }
            return view('school::student.edit', compact('classRoom', 'student', 'user', 'grade', 'client'));

        } else {
            return redirect()->back()->with('error', 'permission Denied');
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
        if (Auth::user()->isAbleTo('school_student edit')) {

            $validationRules = [];

            if ($request->input('contact')) {
                $validationRules['contact'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if ($request->input('father_number')) {
                $validationRules['father_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if ($request->input('mother_number')) {
                $validationRules['mother_number'] = 'required|regex:/^\+\d{1,3}\d{9,13}$/';
            }

            if (!empty($validationRules)) {
                $validator = \Validator::make($request->all(), $validationRules);

                if ($validator->fails()) {
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
            if ($user->mobile_no != $request->contact) {
                $user->mobile_no = $request->contact;
                $user->save();
            }
            $student = SchoolStudent::find($id);
            $student->class_name        = !empty($request->class_name) ? $request->class_name : null;
            $student->client            = !empty($request->client) ? $request->client : null;
            $student->grade_name        = !empty($request->grade_name) ? $request->grade_name : null;
            $student->roll_number       = !empty($request->roll_number) ? $request->roll_number : null;
            $student->name              = !empty($user->name) ? $user->name : null;
            $student->student_gender    = !empty($request->student_gender) ? $request->student_gender : null;
            $student->std_date_of_birth = !empty($request->std_date_of_birth) ? $request->std_date_of_birth : null;
            $student->std_address       = !empty($request->std_address) ? $request->std_address : null;
            $student->std_state         = !empty($request->std_state) ? $request->std_state : null;
            $student->std_city          = !empty($request->std_city) ? $request->std_city : null;
            $student->std_zip_code      = !empty($request->std_zip_code) ? $request->std_zip_code : null;
            $student->contact           = !empty($request->contact) ? $request->contact : null;
            $student->email             = !empty($request->email) ? $request->email : null;
            if ($request->hasFile('student_image')) {
                $fileName = time() . "_" . $request->student_image->getClientOriginalName();
                $path = upload_file($request, 'student_image', $fileName, 'Student');
                $student->student_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->father_name       = $request->father_name;
            $student->father_number     = $request->father_number;
            $student->father_occupation = $request->father_occupation;
            $student->father_email      = $request->father_email;
            $student->father_address    = $request->father_address;
            if ($request->hasFile('father_image')) {
                $fileName = time() . "_" . $request->father_image->getClientOriginalName();
                $path = upload_file($request, 'father_image', $fileName, 'Student');
                $student->father_image = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->mother_name       = $request->mother_name;
            $student->mother_number     = $request->mother_number;
            $student->mother_occupation = $request->mother_occupation;
            $student->mother_email      = $request->mother_email;
            $student->mother_address    = $request->mother_address;
            if ($request->hasFile('mother_image')) {
                $fileName = time() . "_" . $request->mother_image->getClientOriginalName();
                $path = upload_file($request, 'mother_image', $fileName, 'Student');
                $student->mother_image = empty($path) ? null : ($path['url'] ?? null);
            }
            if ($request->hasFile('attachments')) {
                $fileName = time() . "_" . $request->attachments->getClientOriginalName();
                $path = upload_file($request, 'attachments', $fileName, 'Student');
                $student->attachments = empty($path) ? null : ($path['url'] ?? null);
            }
            $student->blood_group        = $request->blood_group;
            $student->allergies          = $request->allergies;
            $student->chronic_conditions = $request->chronic_conditions;
            $student->emergency_contact  = $request->emergency_contact;

            $student->update();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($student, $request->customField);
            }
            event(new UpdateSchoolStudent($request, $student));

            return redirect()->route('school-student.index')->with('success', __('The student details are updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bulkUploadForm()
    {
        if (!Auth::user()->isAbleTo('school_student bulk upload')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return view('school::student.bulk_upload');
    }

    public function bulkUploadStore(Request $request)
    {
        if (!Auth::user()->isAbleTo('school_student bulk upload')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ]);
    
        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->with('error', __('Could not read uploaded file.'));
        }
    
        // Example expected header:
        // name,email,contact,class_name,grade_name,student_gender,std_date_of_birth,std_address,std_state,std_city,std_zip_code,father_name,father_number,father_occupation,father_email,mother_name,mother_number,mother_occupation,mother_email
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return back()->with('error', __('CSV appears to be empty.'));
        }
    
        $header = array_map(function ($h, $i) {
            $h = trim((string)$h);
            if ($i === 0) {
                $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
            }
            return $h;
        }, $header, array_keys($header));
    
        $createdCount = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $nonEmpty = array_filter($row, fn($v) => $v !== null && $v !== '');
            if (count($nonEmpty) === 0) { continue; }
    
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            } elseif (count($row) > count($header)) {
                $row = array_slice($row, 0, count($header));
            }
    
            $data = @array_combine($header, $row);
            if (!$data) { continue; }
    
            // Create or find user
            $user = User::firstOrCreate(
                ['email' => $data['email'] ?? (uniqid('student_').'@example.com')],
                [
                    'name' => $data['name'] ?? 'Student',
                    'mobile_no' => $data['contact'] ?? null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'type' => 'student',
                    'lang' => 'en',
                    'workspace_id' => getActiveWorkSpace(),
                    'active_workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ]
            );
    
            
            $classId = null;
            if (!empty($data['class_name'])) {
                $classId = is_numeric($data['class_name'])
                    ? (int) $data['class_name']
                    : Classroom::where('workspace', getActiveWorkSpace())
                        ->where('created_by', creatorId())
                        ->where('class_name', $data['class_name'])
                        ->value('id');
            }
    
            $gradeId = null;
            if (!empty($data['grade_name'])) {
                $gradeId = is_numeric($data['grade_name'])
                    ? (int) $data['grade_name']
                    : SchoolGrade::where('workspace', getActiveWorkSpace())
                        ->where('created_by', creatorId())
                        ->where('grade_name', $data['grade_name'])
                        ->value('id');
            }
    
            // Create student record
            $student = new SchoolStudent();
            $student->user_id = $user->id;
            $student->student_id = $this->studentNumber();
            $student->class_name = $classId;
            $student->grade_name = $gradeId;
            $student->name = $user->name;
            $student->workspace = getActiveWorkSpace();
            $student->created_by = creatorId();
    
            // Optional fields from CSV
            $student->student_gender = $data['student_gender'] ?? null;
            $student->std_date_of_birth = $data['std_date_of_birth'] ?? null;
            $student->std_address = $data['std_address'] ?? null;
            $student->std_state = $data['std_state'] ?? null;
            $student->std_city = $data['std_city'] ?? null;
            $student->std_zip_code = $data['std_zip_code'] ?? null;
    
            $student->father_name = $data['father_name'] ?? null;
            $student->father_number = $data['father_number'] ?? null;
            $student->father_occupation = $data['father_occupation'] ?? null;
            $student->father_email = $data['father_email'] ?? null;
    
            $student->mother_name = $data['mother_name'] ?? null;
            $student->mother_number = $data['mother_number'] ?? null;
            $student->mother_occupation = $data['mother_occupation'] ?? null;
            $student->mother_email = $data['mother_email'] ?? null;
            $student->roll_number = $data['roll_number'] ?? null;
            $student->save();
    
            $createdCount++;
        }
        fclose($handle);
    
        return redirect()->route('school-student.index')
            ->with('success', __("Bulk upload completed: :count students", ['count' => $createdCount]));
    }

    public function downloadSample()
    {
        if (!Auth::user()->isAbleTo('school_student bulk upload')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $absolutePath = '/home/business.atdamss.com/students_extended_bulk_upload_with_roll.csv';

        if (!is_file($absolutePath)) {
            return redirect()->back()->with('error', __('Sample file not found at :path', ['path' => $absolutePath]));
        }

        return response()->download(
            $absolutePath,
            'students_extended_bulk_upload_with_roll.csv',
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $student = SchoolStudent::where('user_id', $id)->where('workspace', getActiveWorkSpace())->first();
        if (Auth::user()->isAbleTo('school_student delete')) {
            if (module_is_active('CustomField')) {
                $customFields = \Workdo\CustomField\Entities\CustomField::where('module', 'School')->where('sub_module', 'Student')->get();
                foreach ($customFields as $customField) {
                    $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $student->id)->where('field_id', $customField->id)->first();
                    if (!empty($value)) {
                        $value->delete();
                    }
                }
            }
            event(new DestorySchoolStudent($student));
            $student->delete();

            return redirect()->route('school-student.index')->with('success', __('The student has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
