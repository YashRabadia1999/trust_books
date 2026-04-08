@extends('layouts.main')
@section('page-title', __('Edit Assignment'))
@section('title', __('Assignment'))
@section('page-breadcrumb', __('Assignments'))

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">{{ __('Edit Assignment') }}</h5>
            </div>
            <div class="card-body">
                {{ Form::model($assignment, ['route' => ['school.assignment.update', $assignment->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
                <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('class_id', __('Class'), ['class' => 'form-label']) }} <x-required></x-required>
                {!! Form::select('class_id', $classes, $assignment->class_id, [
                    'class'=>'form-control',
                    'placeholder'=>__('Select Class'),
                    'required'=>'required'
                ]) !!}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('subject_id', __('Subject'), ['class' => 'form-label']) }} <x-required></x-required>
                {!! Form::select('subject_id', $subjects, $assignment->subject_id, [
                    'class'=>'form-control',
                    'placeholder'=>__('Select Subject'),
                    'required'=>'required'
                ]) !!}
            </div>
        </div>

        <input type="hidden" name="students_json" id="students_json" />

        <div class="col-12 mb-3" id="student-section" style="display:none;">
            <label class="form-label">{{ __('Enter Student Details') }}</label>
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="students-table">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Student Name') }}</th>
                            <th style="width:220px;">{{ __('Marks') }}</th>
                            <th style="width:220px;">{{ __('ID') }}</th>
                            <th style="width:60px;" class="text-center">+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control student-name" placeholder="{{ __('Enter Name') }}"></td>
                            <td><input type="number" class="form-control student-marks" placeholder="{{ __('Enter Marks') }}" step="1" min="0"></td>
                            <td><input type="text" class="form-control student-id" placeholder="{{ __('Enter ID') }}"></td>
                            <td class="text-center align-middle">
                                <a href="#" class="text-success add-row" title="{{ __('Add Row') }}">+</a>
                                <a href="#" class="text-danger ms-2 remove-row" title="{{ __('Remove Row') }}" style="display:none;">&minus;</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
                </div>
                <input type="hidden" id="existing_students_json" value='{{ $studentsJson }}'>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('school.assignment.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
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
(function(){
    function bothSelected(){
        return $('select[name="class_id"]').val() && $('select[name="subject_id"]').val();
    }

    function toggleStudentSection(){
        if(bothSelected()){
            $('#student-section').slideDown(150);
        } else {
            $('#student-section').slideUp(150);
        }
    }

    function buildStudentsJson(){
        var students = [];
        $('#students-table tbody tr').each(function(){
            var name = $(this).find('.student-name').val().trim();
            var marksStr = $(this).find('.student-marks').val();
            var id = $(this).find('.student-id').val().trim();
            var hasAny = name !== '' || id !== '' || marksStr !== '';
            if(hasAny){
                var marks = marksStr === '' ? null : Number(marksStr);
                students.push({ id: id || null, name: name || null, marks: marks });
            }
        });
        var payload = {
            class_id: Number($('select[name="class_id"]').val()),
            subject_id: Number($('select[name="subject_id"]').val()),
            students: students
        };
        $('#students_json').val(JSON.stringify(payload));
    }

    function addRow(afterTr){
        var row = $('<tr>\
            <td><input type="text" class="form-control student-name" placeholder="{{ __('EnterName') }}"></td>\
            <td><input type="number" class="form-control student-marks" placeholder="{{ __('Enter Marks') }}" step="1" min="0"></td>\
            <td><input type="text" class="form-control student-id" placeholder="{{ __('Enter ID') }}"></td>\
            <td class="text-center align-middle">\
                <a href="#" class="text-success add-row" title="{{ __('Add Row') }}">+</a>\
                <a href="#" class="text-danger ms-2 remove-row" title="{{ __('Remove Row') }}">&minus;</a>\
            </td>\
        </tr>');
        if(afterTr){ row.insertAfter(afterTr); } else { $('#students-table tbody').append(row); }
        updateRemoveButtons();
    }

    function updateRemoveButtons(){
        var rows = $('#students-table tbody tr');
        rows.find('.remove-row').show();
        if(rows.length === 1){
            rows.find('.remove-row').hide();
        }
    }

    function prefillFromExisting(){
        var existingJson = $('#existing_students_json').val();
        if(!existingJson){ return; }
        try{
            var students = JSON.parse(existingJson);
            var tbody = $('#students-table tbody');
            tbody.empty();
            if(Array.isArray(students) && students.length){
                students.forEach(function(s){
                    var row = $('<tr>\
                        <td><input type="text" class="form-control student-name" placeholder="{{ __('Enter Name') }}"></td>\
                        <td><input type="number" class="form-control student-marks" placeholder="{{ __('Enter Marks') }}" step="1" min="0"></td>\
                        <td><input type="text" class="form-control student-id" placeholder="{{ __('Enter ID') }}"></td>\
                        <td class="text-center align-middle">\
                            <a href="#" class="text-success add-row" title="{{ __('Add Row') }}">+</a>\
                            <a href="#" class="text-danger ms-2 remove-row" title="{{ __('Remove Row') }}">&minus;</a>\
                        </td>\
                    </tr>');
                    row.find('.student-name').val(s.name || '');
                    row.find('.student-marks').val(s.marks != null ? s.marks : '');
                    row.find('.student-id').val(s.id || '');
                    tbody.append(row);
                });
            } else {
                addRow();
            }
        }catch(e){
            addRow();
        }
        updateRemoveButtons();
    }

    $(document).on('change', 'select[name="class_id"], select[name="subject_id"]', toggleStudentSection);
    $(document).on('click', '.add-row', function(e){ e.preventDefault(); addRow($(this).closest('tr')); });
    $(document).on('click', '.remove-row', function(e){ e.preventDefault(); $(this).closest('tr').remove(); updateRemoveButtons(); });

    $('form').on('submit', function(){ buildStudentsJson(); });

    // Initialize
    updateRemoveButtons();
    toggleStudentSection();
    prefillFromExisting();
})();
</script>
@endpush
