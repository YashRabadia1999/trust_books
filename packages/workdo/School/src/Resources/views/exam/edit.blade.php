@extends('layouts.main')
@section('page-title', __('Edit Exam'))
@section('title', __('Exam'))
@section('page-breadcrumb', __('Exams'))

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('Edit Exam') }}</h5>
            </div>
            <div class="card-body">
                {{ Form::model($exam, ['route' => ['school.exam.update', $exam->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
                <div class="row">

                    {{-- Exam Name --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ Form::label('name', __('Exam Name'), ['class' => 'form-label']) }} <x-required></x-required>
                            {{ Form::text('name', $exam->exam_name, ['class'=>'form-control','placeholder'=>__('Enter Exam Name'),'required']) }}
                        </div>
                    </div>

                    {{-- Academic Year --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ Form::label('academic_year_id', __('Academic Year'), ['class' => 'form-label']) }} <x-required></x-required>
                            {!! Form::select('academic_year_id', $academicYears, $exam->academic_year_id, ['class'=>'form-control','placeholder'=>__('Select Academic Year'),'required']) !!}
                        </div>
                    </div>

                    {{-- Term --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ Form::label('term_id', __('Term'), ['class' => 'form-label']) }} <x-required></x-required>
                            {!! Form::select('term_id', $terms, $exam->term_id, ['class'=>'form-control','placeholder'=>__('Select Term'),'required']) !!}
                        </div>
                    </div>

                    {{-- Classroom --}}
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            {{ Form::label('classroom_id', __('Classroom'), ['class' => 'form-label']) }} <x-required></x-required>
                            {!! Form::select('classroom_id', $classrooms, $exam->classroom_id, ['class'=>'form-control','placeholder'=>__('Select Classroom'),'required']) !!}
                        </div>
                    </div>

                    {{-- Hidden JSON for students --}}
                    <input type="hidden" name="students_json" id="students_json" />
                    <input type="hidden" id="existing_students_json" data-json='@json($studentsJson)'>

                    {{-- Student marks table --}}
                    <div class="col-12 mb-3" id="student-section" style="display:none;">
                        <label class="form-label fw-semibold">{{ __('Enter Exam Marks') }}</label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="students-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Student') }}</th>
                                        <th>{{ __('Student ID') }}</th>
                                        <th>{{ __('Exam Marks') }}</th>
                                        <th>{{ __('Assignment Marks') }}</th>
                                        <th>{{ __('Total Marks') }}</th>
                                        <th class="text-center">+</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Template row (hidden) --}}
                                    <tr class="student-row-template" style="display:none;">
                                        <td>
                                            <select class="form-control student-select">
                                                <option value="">{{ __('Select Student') }}</option>
                                                @foreach($students as $student)
                                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="student-id-text"></td>
                                        <td><input type="number" class="form-control student-marks" step="1" min="0"></td>
                                        <td><input type="number" class="form-control assignment-marks" step="1" min="0"></td>
                                        <td><input type="number" class="form-control total-marks" readonly></td>
                                        <td class="text-center align-middle">
                                            <a href="#" class="text-success add-row">+</a>
                                            <a href="#" class="text-danger ms-2 remove-row" style="display:none;">&minus;</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('school.exam.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                    {{ Form::submit(__('Update'), ['class'=>'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){

    // Show/hide student section
    function bothSelected() {
        return $('select[name="academic_year_id"]').val() && $('select[name="term_id"]').val();
    }
    function toggleStudentSection() {
        if(bothSelected()) $('#student-section').slideDown(150);
        else $('#student-section').slideUp(150);
    }

    // Calculate total marks
    function calculateTotal(tr){
        var exam = parseFloat(tr.find('.student-marks').val()) || 0;
        var assignment = parseFloat(tr.find('.assignment-marks').val()) || 0;
        tr.find('.total-marks').val((exam + assignment).toFixed(2));
    }

    // On input changes
    $(document).on('input', '.student-marks, .assignment-marks', function(){
        calculateTotal($(this).closest('tr'));
    });

    $(document).on('change', '.student-select', function(){
        var studentId = $(this).val();
        $(this).closest('tr').find('.student-id-text').text(studentId);
        calculateTotal($(this).closest('tr'));
    });

    // Prepare JSON before submit
    function buildStudentsJson(){
        var students = [];
        $('#students-table tbody tr').not('.student-row-template').each(function(){
            var studentId = $(this).find('.student-select').val();
            if(!studentId) return;
            var exam = parseFloat($(this).find('.student-marks').val()) || 0;
            var assignment = parseFloat($(this).find('.assignment-marks').val()) || 0;
            students.push({
                id: studentId,
                exam_marks: exam.toFixed(2),
                assignment_marks: assignment.toFixed(2),
                total_marks: (exam + assignment).toFixed(2)
            });
        });

        $('#students_json').val(JSON.stringify({
            academic_year_id: Number($('select[name="academic_year_id"]').val()),
            term_id: Number($('select[name="term_id"]').val()),
            students: students
        }));
    }
    $('form').on('submit', function(){ buildStudentsJson(); });

    // Add/Remove row functions
    function addRow(afterTr){
        var template = $('.student-row-template').clone().removeClass('student-row-template').show();
        template.find('select').val('');
        template.find('input').val('');
        template.find('.student-id-text').text('');
        if(afterTr) template.insertAfter(afterTr);
        else $('#students-table tbody').append(template);
        updateRemoveButtons();
    }
    function updateRemoveButtons(){
        var rows = $('#students-table tbody tr').not('.student-row-template');
        rows.find('.remove-row').show();
        if(rows.length === 1) rows.find('.remove-row').hide();
    }
    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        addRow($(this).closest('tr'));
    });
    $(document).on('click', '.remove-row', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        updateRemoveButtons();
    });

    // Prefill students
    function prefillFromExisting(){
        var students = $('#existing_students_json').data('json');
        console.log(students);
        if(!Array.isArray(students)) return;

        var tbody = $('#students-table tbody');
        tbody.find('tr').not('.student-row-template').remove();

        students.forEach(function(s){
            var row = $('.student-row-template').clone().removeClass('student-row-template').show();
            row.find('.student-select').val(s.id);
            row.find('.student-id-text').text(s.id);
            row.find('.student-marks').val(s.exam_marks);
            row.find('.assignment-marks').val(s.assignment_marks);
            calculateTotal(row);
            tbody.append(row);
        });

        updateRemoveButtons();
    }

    // Init
    toggleStudentSection();
    prefillFromExisting();
    $(document).on('change', 'select[name="academic_year_id"], select[name="term_id"]', toggleStudentSection);

});
</script>
@endpush
