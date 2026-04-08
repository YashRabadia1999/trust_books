<?php

namespace Workdo\School\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Workdo\School\Entities\Classroom;
use Workdo\School\Entities\Subject;
use Workdo\School\Entities\AssignmentEntry; 

class AssignmentEntryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assignments = AssignmentEntry::query()
                ->join('classrooms', 'assignment_entries.class_id', '=', 'classrooms.id')
                ->join('subjects', 'assignment_entries.subject_id', '=', 'subjects.id')
                ->select([
                    'assignment_entries.id',
                    'classrooms.class_name as class_name',
                    'subjects.subject_name as subject_name',
                    'assignment_entries.students',
                    'assignment_entries.created_at',
                ])
                ->orderBy('assignment_entries.created_at', 'desc');
    
            return datatables()->of($assignments)
                ->addColumn('students_count', function ($row) {
                    // Handle both array (cast) and JSON string
                    $students = is_array($row->students) ? $row->students : json_decode($row->students, true);
                    return is_array($students) ? count($students) : 0;
                })
                ->addColumn('action', function ($row) {
                    return view('school::assignment.action', compact('row'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('school::assignment.index');
    }
    
    public function create()
    {
        $classes = Classroom::orderBy('class_name')->pluck('class_name', 'id');
        $subjects = Subject::orderBy('subject_name')->pluck('subject_name', 'id');
        return view('school::assignment.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classrooms,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'students_json' => ['required', 'string'],
        ]);

        $payload = json_decode($validated['students_json'], true);
        if (!is_array($payload) || !isset($payload['students']) || !is_array($payload['students'])) {
            return back()->withErrors(['students_json' => __('Invalid students JSON. Expected key: students')])->withInput();
        }

        $normalizedStudents = array_values(array_map(function ($item) {
            return [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? null,
                'marks' => isset($item['marks']) ? (float) $item['marks'] : null,
            ];
        }, $payload['students']));

        AssignmentEntry::create([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            // store as array; model cast will JSON-encode correctly (no extra escaping)
            'students' => $normalizedStudents,
        ]);

        return redirect()->route('school.assignment.index')->with('success', __('Assignment saved successfully.'));
    }

    public function edit($id)
    {
        $assignment = AssignmentEntry::findOrFail($id);
        $classes = Classroom::orderBy('class_name')->pluck('class_name', 'id');
        $subjects = Subject::orderBy('subject_name')->pluck('subject_name', 'id');
        $studentsJson = json_encode($assignment->students);
        return view('school::assignment.edit', compact('assignment', 'classes', 'subjects', 'studentsJson'));
    }

    public function update(Request $request, $id)
    {
        $assignment = AssignmentEntry::findOrFail($id);

        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classrooms,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'students_json' => ['required', 'string'],
        ]);

        $payload = json_decode($validated['students_json'], true);
        if (!is_array($payload) || !isset($payload['students']) || !is_array($payload['students'])) {
            return back()->withErrors(['students_json' => __('Invalid students JSON. Expected key: students')])->withInput();
        }

        $normalizedStudents = array_values(array_map(function ($item) {
            return [
                'id' => $item['id'] ?? null,
                'name' => $item['name'] ?? null,
                'marks' => isset($item['marks']) ? (float) $item['marks'] : null,
            ];
        }, $payload['students']));

        $assignment->update([
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
            // store as array; model cast will JSON-encode correctly (no extra escaping)
            'students' => $normalizedStudents,
        ]);

        return redirect()->route('school.assignment.index')->with('success', __('Assignment updated successfully.'));
    }

    public function destroy($id)
    {
        $assignment = AssignmentEntry::findOrFail($id);
        $assignment->delete();
        return redirect()->route('school.assignment.index')->with('success', __('Assignment deleted successfully.'));
    }
}
