<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Workdo\School\DataTables\LibraryBookDataTable;
use Workdo\School\Entities\LibraryBook;
use Workdo\School\Events\CreateSchoolLibraryBook;
use Workdo\School\Events\DestroySchoolLibraryBook;
use Workdo\School\Events\UpdateSchoolLibraryBook;

class LibraryBookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LibraryBookDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('library_books manage')) {
            return $dataTable->render('school::library-book.index');
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
        if (Auth::user()->isAbleTo('library_books create')) {
            $availability = LibraryBook::$availability;
            return view('school::library-book.create', compact('availability'));
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
        if (Auth::user()->isAbleTo('library_books create')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'        => 'required',
                    'author'       => 'required',
                    'category'     => 'required',
                    'availability' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $book               = new LibraryBook();
            $book->title        = $request->title;
            $book->author       = $request->author;
            $book->category     = $request->category;
            $book->availability = $request->availability;
            $book->created_by   = creatorId();
            $book->workspace    = getActiveWorkSpace();
            $book->save();

            event(new CreateSchoolLibraryBook($request, $book));

            return redirect()->back()->with('success', __('The library book has been created successfully.'));
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
        if (Auth::user()->isAbleTo('library_books edit')) {
            $id           = Crypt::decrypt($id);
            $book         = LibraryBook::find($id);
            $availability = LibraryBook::$availability;

            return view('school::library-book.edit', compact('book','availability'));
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
        if (Auth::user()->isAbleTo('library_books edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title'        => 'required',
                    'author'       => 'required',
                    'category'     => 'required',
                    'availability' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $book               = LibraryBook::find($id);
            $book->title        = $request->title;
            $book->author       = $request->author;
            $book->category     = $request->category;
            $book->availability = $request->availability;
            $book->created_by   = creatorId();
            $book->workspace    = getActiveWorkSpace();
            $book->update();
            event(new UpdateSchoolLibraryBook($request, $book));

            return redirect()->back()->with('success', __('The library book details are updated successfully.'));
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
        if (Auth::user()->isAbleTo('library_books delete')) {
            $book = LibraryBook::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            event(new DestroySchoolLibraryBook($book));
            $book->delete();
            return redirect()->back()->with('success', __('The library book has been deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
