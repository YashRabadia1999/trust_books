<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Workdo\School\Entities\Admission;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\Subject;
use Workdo\School\Entities\SchoolHomework;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\SchoolParent;


class SchoolController extends Controller
{
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        if (Auth::user()->isAbleTo('school_dashboard manage'))
        {
            if(Auth::user()->type == 'company'){
            $data['totalStudent'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type','student')->count();
            $data['totalParent']  = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type','parent')->count();
            $data['totalTeacher'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type','staff')->count();
            $data['totalClass']   = Classroom::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalSubject'] = Subject::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $data['totalAdmission'] = Admission::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
            $homeworks = SchoolHomework::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('school::dashboard.index',compact('data','homeworks'));

            }
            elseif (Auth::user()->type == 'student') {
                $student = SchoolStudent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id',Auth::user()->id)->first();
                if(!empty($student))
                {
                    $classroom = Classroom::where('id',$student->class_name)->first();
                    $parentIds = explode(',', $student->parent_id);
    
                    $data['totalStudent'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('id',Auth::user()->id)->count();
                    $data['totalParent']  = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->whereIn('id',$parentIds)->count();
                    $data['totalClass']   = Classroom::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('id',$student->class_name)->count();
                    $data['totalSubject'] = Subject::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('class_id',$classroom->id)->count();
                    $homeworks = SchoolHomework::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                }
                else{
                    $data['totalStudent'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('id',Auth::user()->id)->count();
                    $data['totalParent']  = 0;
                    $data['totalClass']   = 0;
                    $data['totalSubject'] = 0;
                    $homeworks = SchoolHomework::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                }
                return view('school::dashboard.index',compact('data','homeworks'));
            }
            elseif (Auth::user()->type == 'parent') {
                $parent = SchoolParent::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->where('user_id',Auth::user()->id)->first();
                if(!empty($parent))
                {
                    $std = SchoolStudent::whereRaw("FIND_IN_SET($parent->user_id, parent_id)")->first();
                    $classroom = Classroom::where('id',$std->class_name)->first();
                    $data['totalStudent'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('id',$std->user_id)->count();
                    $data['totalClass']   = Classroom::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('id',$std->class_name)->count();
                    $data['totalSubject'] = Subject::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('class_id',$classroom->id)->count();
                }
                else
                {
                    $data['totalStudent'] = 0;
                    $data['totalClass']   = 0;
                    $data['totalSubject'] = 0;
                }

                $data['totalParent']  = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('id',Auth::user()->id)->count();
                $homeworks = SchoolHomework::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
                return view('school::dashboard.index',compact('data','homeworks'));

            }
            elseif (Auth::user()->type == 'staff') {
                $user = User::where('workspace_id',getActiveWorkSpace())->where('id',Auth::user()->id)->first();

                $data['totalStudent'] = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type','student')->count();
                $data['totalParent']  = User::where('created_by', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type','parent')->count();
                $data['totalClass']   = Classroom::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->count();
                $data['totalSubject'] = Subject::where('created_by', creatorId())->where('workspace', '=', getActiveWorkSpace())->where('teacher',$user->id)->count();
                $homeworks = SchoolHomework::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();

                return view('school::dashboard.index',compact('data','homeworks'));
            }
        }
    }

    public function create()
    {
        return view('school::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('school::show');
    }

    public function edit($id)
    {
        return view('school::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
