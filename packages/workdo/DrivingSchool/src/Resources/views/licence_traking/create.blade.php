{{ Form::open(['route' => 'licence_traking.store', 'method' => 'post', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('student_id', $student, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Student')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('licence_type_id', __('Licence Type'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('licence_type_id', $licence_types, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Licence Type')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('application_date', __('Application Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('application_date', old('application_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('test_date', __('Test Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('test_date', old('test_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('test_result', __('Test Result'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="d-flex radio-check mt-2">
                <div class="col-md-6 custom-control custom-radio custom-control-inline">
                    <input type="radio" id="pass" value="pass" name="test_result" class="form-check-input" checked="checked"style="margin-right: 5px;">
                    <label class="form-check-label" for="pass" style="margin-right: 30px;">{{ __('Pass') }}</label>
                </div>
                <div class="col-md-6 custom-control custom-radio ms-1 custom-control-inline">
                    <input type="radio" id="fail" value="fail" name="test_result" class="form-check-input" style="margin-right: 5px;">
                    <label class="form-check-label" for="fail">{{ __('Fail') }}</label>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('licence_number', __('Licence Number'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('licence_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Licence Number')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('licence_issue_date', __('Licence Issue Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('licence_issue_date', old('licence_issue_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('licence_expiry_date', __('Licence Expiry Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('licence_expiry_date', old('licence_expiry_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
