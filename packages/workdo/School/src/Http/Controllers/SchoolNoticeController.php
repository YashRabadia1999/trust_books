<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\NoticeBoardDataTable;
use Workdo\School\Entities\Employee;
use Workdo\School\Entities\SchoolNotice;
use Workdo\School\Events\CreateSchoolNotice;
use Workdo\School\Events\DestroySchoolNotice;
use Workdo\School\Events\UpdateSchoolNotice;

class SchoolNoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(NoticeBoardDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('school_notice manage')) {
            return $dataTable->render('school::notice.index');
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
        if (Auth::user()->isAbleTo('school_notice create')) {
            $employes   = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $audiences   = SchoolNotice::$audiences;
            return view('school::notice.create' , compact('employes' , 'audiences'));
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
        if (Auth::user()->isAbleTo('school_notice create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'           => 'required',
                    'posted_by'       => 'required',
                    'date_posted'     => 'required',
                    'target_audience' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $notice                   = new SchoolNotice();
            $notice->title            = $request->title;
            $notice->posted_by        = $request->posted_by;
            $notice->date_posted      = $request->date_posted;
            $notice->target_audience  = $request->target_audience;
            $notice->description      = $request->description;
            $notice->created_by       = creatorId();
            $notice->workspace        = getActiveWorkSpace();
            $notice->save();

            event(new CreateSchoolNotice($request, $notice));

            return redirect()->back()->with('success', __('The notice has been created successfully.'));
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
        if (Auth::user()->isAbleTo('school_notice manage')) {
            $SchoolNotice = SchoolNotice::find($id);
            return view('school::notice.description' ,compact('SchoolNotice'));
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
        if (Auth::user()->isAbleTo('school_notice edit')) {
            $id          = Crypt::decrypt($id);
            $notice      = SchoolNotice::find($id);
            $employes    = Employee::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $audiences   = SchoolNotice::$audiences;

            return view('school::notice.edit', compact('notice','employes','audiences'));
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
        if (Auth::user()->isAbleTo('school_notice edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title'           => 'required',
                    'posted_by'       => 'required',
                    'date_posted'     => 'required',
                    'target_audience' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $notice                   = SchoolNotice::find($id);
            $notice->title            = $request->title;
            $notice->posted_by        = $request->posted_by;
            $notice->date_posted      = $request->date_posted;
            $notice->target_audience  = $request->target_audience;
            $notice->description      = $request->description;
            $notice->update();
            event(new UpdateSchoolNotice($request, $notice));

            return redirect()->back()->with('success', __('The notice details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('school_notice delete')) {
            $notice = SchoolNotice::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolNotice($notice));
            $notice->delete();
            return redirect()->back()->with('success', __('The notice has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
