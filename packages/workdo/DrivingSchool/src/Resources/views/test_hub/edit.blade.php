{{ Form::model($test_hub, ['route' => ['driving_test_hub.update', $test_hub->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('student_id', $student, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Student')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('teacher_id', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('teacher_id', $users, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Teacher')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('test_type_id', __('Test Type'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('test_type_id', $test_types, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Test Type')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('test_date', __('Test Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('test_date', old('test_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
                'min' => date('Y-m-d'),
            ]) !!}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('test_score', __('Test Score'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('test_score', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Test Score')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('test_result', __('Test Result'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="d-flex radio-check mt-2">
                <div class="col-md-6 custom-control custom-radio custom-control-inline">
                    <input type="radio" id="pass" value="pass" name="test_result" class="form-check-input" {{ $test_hub['test_result'] == 'pass' ? 'checked' : '' }} style="margin-right: 5px;">
                    <label class="form-check-label" for="pass" style="margin-right: 30px;">{{ __('Pass') }}</label>
                </div>
                <div class="col-md-6 custom-control custom-radio ms-1 custom-control-inline">
                    <input type="radio" id="fail" value="fail" name="test_result" class="form-check-input" {{ $test_hub['test_result'] == 'fail' ? 'checked' : '' }} style="margin-right: 5px;">
                    <label class="form-check-label" for="fail">{{ __('Fail') }}</label>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('remarks', __('Remarks'), ['class' => ' form-label']) }}
            {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Remarks')]) !!}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
