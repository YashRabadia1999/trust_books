<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\BookIssueDataTable;
use Workdo\School\Entities\BookIssue;
use Workdo\School\Entities\LibraryBook;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Events\CreateBookIssue;
use Workdo\School\Events\DestroyBookIssue;
use Workdo\School\Events\UpdateBookIssue;

class BookIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(BookIssueDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('library_books_issue manage')) {
            return $dataTable->render('school::bookissue.index');
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
        if (Auth::user()->isAbleTo('library_books_issue create')) {
            $students = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $books = LibraryBook::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())
                ->whereDoesntHave('issues', function ($query) {
                    $query->where('return_date', '>', now());
                })->pluck('title', 'id');
            return view('school::bookissue.create' , compact('students' , 'books'));
        } else {
            return redirect()->back()->with('e rror', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('library_books_issue create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'book_id'     => 'required',
                    'student_id'  => 'required',
                    'issue_date'  => 'required',
                    'return_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $issue                = new BookIssue();
            $issue->book_id       = $request->book_id;
            $issue->student_id    = $request->student_id;
            $issue->issue_date    = $request->issue_date;
            $issue->return_date   = $request->return_date;
            $issue->created_by    = creatorId();
            $issue->workspace     = getActiveWorkSpace();
            $issue->save();

            event(new CreateBookIssue($request, $issue));

            return redirect()->back()->with('success', __('The book issue has been created successfully.'));
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
        if (Auth::user()->isAbleTo('library_books_issue edit')) {
            $id        = Crypt::decrypt($id);
            $issue     = BookIssue::find($id);
            $students  = SchoolStudent::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('name','id');
            $books     = LibraryBook::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->pluck('title','id');

            return view('school::bookissue.edit', compact('issue','students','books'));
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
        if (Auth::user()->isAbleTo('library_books_issue edit')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'book_id'     => 'required',
                    'student_id'  => 'required',
                    'issue_date'  => 'required',
                    'return_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $issue                = BookIssue::find($id);
            $issue->book_id       = $request->book_id;
            $issue->student_id    = $request->student_id;
            $issue->issue_date    = $request->issue_date;
            $issue->return_date   = $request->return_date;
            $issue->created_by    = creatorId();
            $issue->workspace     = getActiveWorkSpace();
            $issue->update();
            event(new UpdateBookIssue($request, $issue));

            return redirect()->back()->with('success', __('The book issue details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('library_books_issue delete')) {
            $issue = BookIssue::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroyBookIssue($issue));
            $issue->delete();
            return redirect()->back()->with('success', __('The book issue has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
