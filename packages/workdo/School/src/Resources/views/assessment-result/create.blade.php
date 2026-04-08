{{ Form::open(['route' => 'school-assessment-result.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('assessment_id', __('Assessment'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('assessment_id', $assessments, null, ['class' => 'form-control', 'placeholder' => __('Select Assessment'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id', $students, null, ['class' => 'form-control', 'placeholder' => __('Select Student'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('marks_obtained', __('Marks'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('marks_obtained', null, ['class' => 'form-control', 'placeholder' => __('Enter Marks'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('grade', __('Grade'), ['class' => 'form-label']) }}
                {{ Form::text('grade', null, ['class' => 'form-control', 'placeholder' => __('Enter Grade')]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
