<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\SchoolStudent;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\SchoolHomework;
use Workdo\School\Entities\SchoolParent;
use Workdo\School\Entities\Subject;
use Workdo\School\Events\CreateSchoolHomework;
use Workdo\School\Events\DestorySchoolHomework;
use Workdo\School\Events\UpdateSchoolHomework;
use Workdo\School\DataTables\HomeWorkDataTable;
use Workdo\School\DataTables\ViewHomeWorkDataTable;

class SchoolHomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HomeWorkDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_homework manage')) {

            return $dataTable->render('school::homework.index');
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
        if (Auth::user()->isAbleTo('school_homework create')) {
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            if (module_is_active('CustomField')) {
                $customFields =  \Workdo\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module', 'Home Work')->get();
            } else {
                $customFields = null;
            }
            return view('school::homework.create', compact('classRoom', 'customFields'));
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('school_homework create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'classroom' => 'required',
                    'subject' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $homework = SchoolHomework::create([
                'title'           => $request->title,
                'classroom'       => $request->classroom,
                'subject'         => $request->subject,
                'submission_date' => $request->submission_date,
                'content'         => $request->content,
                'workspace'       => getActiveWorkSpace(),
                'created_by'      => creatorId()
            ]);
            if ($request->hasFile('homework')) {
                $fileName = time() . "_" . $request->homework->getClientOriginalName();
                $path = upload_file($request, 'homework', $fileName, 'Homework');
                $homework->homework = empty($path) ? null : ($path['url'] ?? null);
            }
            $homework->save();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($homework, $request->customField);
            }
            event(new CreateSchoolHomework($request, $homework));

            return redirect()->back()->with('success', 'The homework has been created successfully.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
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
        if (Auth::user()->isAbleTo('school_homework edit')) {

            $ids = decrypt($id);
            $homework  = SchoolHomework::find($ids);
            $subjects = Subject::pluck('subject_name', 'id');
            $selectedSubject = $homework->subject;
            $classRoom = Classroom::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('class_name', 'id');
            if (module_is_active('CustomField')) {
                $homework->customField = \Workdo\CustomField\Entities\CustomField::getData($homework, 'School', 'Home Work');
                $customFields             = \Workdo\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'School')->where('sub_module', 'Home Work')->get();
            } else {
                $customFields = null;
            }

            return view('school::homework.edit', compact('homework', 'classRoom', 'subjects', 'selectedSubject', 'customFields'));
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
        if (Auth::user()->isAbleTo('school_homework edit')) {
            $homework  = SchoolHomework::find($id);
            $homework->title           = $request->title;
            $homework->classroom       = $request->classroom;
            $homework->subject         = $request->subject;
            $homework->submission_date = $request->submission_date;
            $homework->content         = $request->content;
            if ($request->hasFile('homework')) {
                $fileName = time() . "_" . $request->homework->getClientOriginalName();
                $path = upload_file($request, 'homework', $fileName, 'Homework');
                $homework->homework = empty($path) ? null : ($path['url'] ?? null);
            }
            $homework->update();
            if (module_is_active('CustomField')) {
                \Workdo\CustomField\Entities\CustomField::saveData($homework, $request->customField);
            }
            event(new UpdateSchoolHomework($request, $homework));

            return redirect()->back()->with('success', 'The homework details are updated successfully.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('school_homework delete')) {
            $homework  = SchoolHomework::find($id);
            if(module_is_active('CustomField'))
            {
                $customFields = \Workdo\CustomField\Entities\CustomField::where('module','School')->where('sub_module','Home Work')->get();
                foreach($customFields as $customField)
                {
                    $value = \Workdo\CustomField\Entities\CustomFieldValue::where('record_id', '=', $homework->id)->where('field_id',$customField->id)->first();
                    if(!empty($value)){
                        $value->delete();
                    }
                }
            }
            event(new DestorySchoolHomework($homework));
            $homework->delete();

            return redirect()->back()->with('success', 'The homework has been deleted.');
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function getschoolsubject(Request $request)
    {
        $subject = Subject::where('class_id', $request->classroom_id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('subject_name', 'id');
        return response()->json($subject);
    }

    public function gethomework($id)
    {
        $ids = decrypt($id);
        $homework = SchoolHomework::where('id', $ids)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
        return view('school::homework.homework', compact('homework'));
    }

    public function getstdhomework(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'student_homework' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $homework = SchoolHomework::where('id', $id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
        if ($request->hasFile('student_homework')) {
            $fileName = time() . "_" . $request->student_homework->getClientOriginalName();
            $path = upload_file($request, 'student_homework', $fileName, 'student_homework');
            $homework->student_homework = empty($path) ? null : ($path['url'] ?? null);
        }
        $homework->save();
        return redirect()->back()->with('success', 'Homework submitted successfully.');
    }

    public function viewhomework(ViewHomeWorkDataTable $dataTable , Request $request)
    {
        $subjectNames = Subject::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('subject_name', 'id');

        return $dataTable->render('school::homework.viewhomework', compact('subjectNames'));
    }

    public function content($id)
    {
        if (\Auth::user()->isAbleTo('school_homework manage')) {
            $homework = SchoolHomework::find($id);
            return view('school::homework.content', compact('homework'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
}
