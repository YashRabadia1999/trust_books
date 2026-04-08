<?php

namespace Workdo\School\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\Term;
use Workdo\School\Entities\AcademicYear;
use Yajra\DataTables\DataTables;

class TermController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $terms = Term::query()
            ->join('academic_years', 'terms.academic_year_id', '=', 'academic_years.id')
            ->select([
                'terms.id',
                'terms.name',
                'terms.start_date',
                'terms.end_date',
                'academic_years.name as academic_year',
            ])
            ->orderBy('terms.start_date', 'desc');

        return datatables()->of($terms)
            ->addColumn('action', function ($row) {
                return view('school::term.action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('school::term.index');
}



    public function create()
    {
        $academicYears = AcademicYear::pluck('name', 'id');
        return view('school::term.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $requestData = $request->all();
        $requestData['workspace'] = getActiveWorkSpace();
         $requestData['created_by'] = auth()->id();
         Term::create($requestData);

        return redirect()->route('school.term.index')->with('success', 'Term created successfully.');
    }

    public function edit(Term $term)
    {
        $academicYears = AcademicYear::pluck('name', 'id');
        return view('school::term.edit', compact('term', 'academicYears'));
    }

    public function update(Request $request, Term $term)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $term->update($request->all());

        return redirect()->route('school.term.index')->with('success', 'Term updated successfully.');
    }

    public function destroy(Term $term)
    {
        $term->delete();
        return redirect()->route('school.term.index')->with('success', 'Term deleted successfully.');
    }
}
