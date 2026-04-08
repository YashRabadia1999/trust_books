<?php
namespace Workdo\School\Http\Controllers;
use App\Http\Controllers\Controller;
use Workdo\School\Entities\ExamEntry;
use Workdo\School\Entities\Exam;
use Workdo\School\Entities\SchoolStudent as Student;
use Workdo\School\Entities\AssignmentEntry;
use Workdo\School\Entities\ExamSetting;
use Illuminate\Http\Request;
use Workdo\School\Entities\AcademicYear;
use Workdo\School\Entities\Term;
use Workdo\School\Entities\Classroom;
use Illuminate\Support\Facades\Auth;
use Workdo\School\Entities\SchoolStudent;
use App\Models\User;


class ExamReportController extends Controller
{
  public function index(Request $request)
{
    $year  = $request->academic_year_id;
    $term  = $request->term_id;
    $class = $request->classroom_id;

    $students = Student::when($class, fn($q) => $q->where('class_name', $class))->get();
    $settings = ExamSetting::first();

    $report = [];

    foreach ($students as $s) {
        // ✅ Fetch all exam_entries rows for this student
        $examEntries = ExamEntry::where('student_id', $s->id)
            ->when($term, fn($q) => $q->where('term_id', $term))
            ->when($year, fn($q) => $q->where('academic_year_id', $year))
            ->when($class, fn($q) => $q->whereHas('exam', fn($q) => $q->where('classroom_id', $class)))
            ->get();

        // ✅ Sum marks directly from exam_entries table
        $examMarks = $examEntries->sum('marks_obtained');
        $assignmentMarks = $examEntries->sum('assignment_marks');

        // ✅ Weighted total (based on exam_settings percentages)
        // $total = ($assignmentMarks * $settings->assignment_percentage / 100)
        //        + ($examMarks * $settings->exam_percentage / 100);
        $total = $assignmentMarks + $examMarks;
        $report[] = [
            'name'        => $s->name,
            'id'          => $s->id,
            'assignment'  => $assignmentMarks,
            'exam'        => $examMarks,
            'total'       => $total,
        ];
    }

    // ✅ Ranking logic
    usort($report, fn($a, $b) => $b['total'] <=> $a['total']);
    foreach ($report as $i => $r) {
        $report[$i]['position'] = $i + 1;
    }

    // ✅ If AJAX request, load partial table
    if ($request->ajax()) {
        return view('school::exam.report-table', compact('report'))->render();
    }

    // ✅ Filter dropdowns
    $years = AcademicYear::all();
    $terms = Term::all();
    $classes = Classroom::all();

    return view('school::exam.report', compact('report', 'years', 'terms', 'classes'));
}



public function show($student_id, $tab = 'details')
{
    if (!Auth::user()->isAbleTo('school_student show')) {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    $student = SchoolStudent::with(['fees', 'healthRecords'])
                ->where('id', $student_id)
                ->where('workspace', getActiveWorkSpace())
                ->first();

    // If student not found, set $student to null
    if (!$student) {
        $student = null;
        $assignments = collect(); // empty collection
        $exams = collect();       // empty collection
    } else {
        // Fetch assignments
        $assignments = AssignmentEntry::whereJsonContains('students', ['id' => $student->id])->get();

        // Fetch exam entries along with exam details (like exam_name)
        $exams = ExamEntry::with(['exam','academicYear']) // make sure relations exist
                    ->where('student_id', $student->id)
                    ->latest()
                    ->get();
    }
    // echo '<pre>'; print_r($exams); exit;
    return view('school::student.show', compact('student','assignments','exams','tab'));
}


}
