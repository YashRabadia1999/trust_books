<?php

namespace Workdo\School\Http\Controllers;

use App\Http\Controllers\Controller;
use Workdo\School\Entities\ExamEntry;
use Workdo\School\Entities\Exam;
use Workdo\School\Entities\SchoolStudent;
use Workdo\School\Entities\AcademicYear;
use Workdo\School\Entities\Term;
use Illuminate\Http\Request;
use Workdo\School\Entities\Classroom;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
class ExamEntryController extends Controller
{
    // List all exam entries
    public function index(Request $request)
    {
       if ($request->ajax()) {
        $exams = Exam::with(['academicYear', 'term', 'classroom'])->latest();
        // dd($exams->get()->toArray());

        return DataTables::of($exams)
            ->addIndexColumn()
            ->addColumn('exam_name', fn($row) => $row->exam_name)
            ->addColumn('academic_year', fn($row) => $row->academicYear->name ?? '')
            ->addColumn('term', fn($row) => $row->term->name ?? '')
            ->addColumn('classroom', fn($row) => $row->classroom->class_name ?? '') // Added classroom
            ->addColumn('action', function($row){
            $editUrl = route('school.exam.edit', $row->id);
            $deleteUrl = route('school.exam.destroy', $row->id);
            return '
                    <a href="'.$editUrl.'" class="btn btn-sm btn-info">Edit</a>
                    <form action="'.$deleteUrl.'" method="POST" style="display:inline;">
                        '.csrf_field().method_field("DELETE").'
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                ';
            })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('school::exam.index');
    }

    // Show form to create a new exam entry
    public function create()
    {
        $exams = Exam::all();
        $students = SchoolStudent::all();
        $academicYears = AcademicYear::pluck('name', 'id');
        $terms = Term::pluck('name', 'id');
        $classrooms = Classroom::pluck('class_name', 'id');

        return view('school::exam.create', compact('exams', 'students', 'academicYears', 'terms', 'classrooms'));
    }


    // Store new exam entry
public function store(Request $request)
{

    $request->validate([
        'name' => 'required|string|max:255',
        'academic_year_id' => 'required|exists:academic_years,id',
        'term_id' => 'required|exists:terms,id',
        'students_json' => 'required|json',
    ]);

    $payload = json_decode($request->students_json, true);
    // Assuming you want to store the user_id of the first student in the exam table
    $firstStudent = \Workdo\School\Entities\SchoolStudent::find($payload['students'][0]['id'] ?? null);
    $studentUserId = $firstStudent ? $firstStudent->user_id : null;
    // echo "<PRE>"; print_r($firstStudent); exit;
   
    $exam = Exam::create([
        'name' => $request->name,
        'academic_year_id' => $request->academic_year_id,
        'term_id' => $request->term_id,
        'classroom_id' => $request->classroom_id,
        'exam_name' => $request->name,
        'created_by' => auth()->id(),
        'user_id' =>$firstStudent->user_id,
      
    ]);

    
    $payload = json_decode($request->students_json, true);

    foreach ($payload['students'] as $studentData) {
        ExamEntry::create([
            'exam_id' => $exam->id,
            'student_id' => $studentData['id'],
            'academic_year_id' => $payload['academic_year_id'],
            'term_id' => $payload['term_id'],
            'marks_obtained' => $studentData['exam_marks'],
            'assignment_marks' => $studentData['assignment_marks'],
            'total_marks' => $studentData['total_marks'],
            'user_id' =>$firstStudent->user_id,
        ]);

    }

    return redirect()->route('school.exam.index')->with('success', __('Exam and marks saved successfully.'));
}




    // Show form to edit an existing exam entry
public function edit($id)
{
    $exam = Exam::with('entries.student')->findOrFail($id);

    $academicYears = AcademicYear::pluck('name', 'id');
    $terms         = Term::pluck('name', 'id');
    $classrooms    = Classroom::pluck('class_name', 'id');
    $students      = SchoolStudent::all();

    // Build JSON array of existing student marks
    $studentsJson = $exam->entries->map(function($entry) {
        $examMarks       = (float) ($entry->marks_obtained ?? 0);
        $assignmentMarks = (float) ($entry->assignment_marks ?? 0);
   
        return [
            'id'               => $entry->student_id,
            'exam_marks'       => number_format($examMarks, 2, '.', ''), 
            'assignment_marks' => number_format($assignmentMarks, 2, '.', ''), 
            'total_marks'      => number_format(($examMarks + $assignmentMarks), 2, '.', ''),
        ];
    })->values();

    // Optional debug (outside map):
    // dd($studentsJson->toArray());

    return view('school::exam.edit', compact(
        'exam',
        'academicYears',
        'terms',
        'classrooms',
        'students',
        'studentsJson'
    ));
}


    // Update exam entry
    public function update(Request $request, $id)
    {
        $examEntry = ExamEntry::findOrFail($id);

        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:students,id',
            'marks' => 'required|numeric|min:0',
        ]);

        $examEntry->update($request->only('exam_id', 'student_id', 'marks'));

        return redirect()->route('school.exam.index')
            ->with('success', 'Exam entry updated successfully.');
    }

    // Delete exam entry
    public function destroy($id)
   {
    $exam = Exam::with('entries')->findOrFail($id);

    // Delete all related exam entries first
    $exam->entries()->delete();

    // Then delete the exam
    $exam->delete();

    return redirect()->route('school.exam.index')
        ->with('success', 'Exam and related entries deleted successfully.');
}
public function show($id)
{
    $examEntry = ExamEntry::with(['exam', 'student'])->findOrFail($id);
    return view('school.exam_entries.show', compact('examEntry'));
}

    // Bulk upload form
    public function bulkUploadForm()
    {
        $exams = Exam::all();
        return view('school::exam.upload', compact('exams'));
    }

    // Bulk upload processing


public function bulkUploadStore(Request $request)
{
    // Step 1️⃣ Validate request
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv',
    ], [
        'file.required' => 'Please upload a valid Excel or CSV file.',
        'file.mimes' => 'File must be in .xlsx or .csv format.',
    ]);

    // Step 2️⃣ Read Excel contents
    $rows = Excel::toArray([], $request->file('file'))[0];
    // print_r($rows); exit;
    if (count($rows) < 2) {
        return back()->with('error', 'The uploaded file is empty or missing data.');
    }

    // Step 3️⃣ Extract header and data rows
    $dataRows = array_slice($rows, 1);
    $firstRow = $dataRows[0];

    $name = $firstRow[0] ?? null;
    $academicYearId = $firstRow[1] ?? null;
    $termId = $firstRow[2] ?? null;
    $classroomId = $firstRow[3] ?? null;

    // Step 4️⃣ Convert student data
    $students = [];
    foreach ($dataRows as $row) {
        $students[] = [
            'id' => $row[4],
            'exam_marks' => (float) $row[5],
            'assignment_marks' => (float) $row[6],
            'total_marks' => (float) $row[5] + (float) $row[6],
            'user_id' => $row[7]
        ];
    }
    // Step 5️⃣ Wrap in DB transaction
    DB::beginTransaction();

    try {
        // Create exam record
        $exam = Exam::create([
            'exam_name' => $name,
            'name' => $name,
            'academic_year_id' => $academicYearId,
            'term_id' => $termId,
            'classroom_id' => $classroomId,
            'created_by' => auth()->id(),
             'user_id' => $row[7],
        ]);

        // Step 6️⃣ Insert each student entry
        foreach ($students as $student) {
            ExamEntry::create([
                'exam_id' => $exam->id,
                'student_id' => $student['id'],
                'academic_year_id' => $academicYearId,
                'term_id' => $termId,
                'marks_obtained' => $student['exam_marks'],
                'assignment_marks' => $student['assignment_marks'],
                'total_marks' => $student['total_marks'],
                'created_by' => auth()->id(),
                 'user_id' => $row[7],
            ]);
        }

        DB::commit();

        return redirect()->route('school.exam.index')
            ->with('success', 'Exam and student entries uploaded successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error importing data: ' . $e->getMessage());
    }
}


    // Download bulk sample
    public function downloadSample()
    {
        $filePath = public_path('samples/exam_bulk_sample_data.xlsx');
        return response()->download($filePath);
    }
}
