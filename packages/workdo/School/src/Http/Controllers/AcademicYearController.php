<?php

// namespace Modules\School\Http\Controllers;
namespace Workdo\School\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workdo\School\Entities\AcademicYear;
use Yajra\DataTables\DataTables;
class AcademicYearController extends Controller
{


public function index(Request $request)
{
    if ($request->ajax()) {
        $data = AcademicYear::orderBy('start_date', 'desc')->get();

        return datatables()->of($data)
            ->addColumn('action', function($row){
                return view('school::academic_years.action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('school::academic_years.index'); 
}




   public function create()
{
    return view('school::academic_years.create');
}

public function edit(AcademicYear $academicYear)
{
    return view('school::academic_years.edit', compact('academicYear'));
}


  public function store(Request $request)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);
    $request['workspace'] = getActiveWorkSpace();
    $request['created_by'] = auth()->id();
    AcademicYear::create($request->all());

    return redirect()->route('school.academic-year.index')
                     ->with('success', 'Academic Year created successfully.');
}

public function update(Request $request, AcademicYear $academicYear)
{
    $request->validate([
        'name'       => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    $academicYear->update($request->all());

    return redirect()->route('school.academic-year.index')
                     ->with('success', 'Academic Year updated successfully.');
}

public function destroy(AcademicYear $academicYear)
{
    $academicYear->delete();

    return redirect()->route('school.academic-year.index')
                     ->with('success', 'Academic Year deleted successfully.');
}

}
