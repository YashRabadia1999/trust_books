{{ Form::open(['route' => 'school-health-record.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id', $students, null, ['class' => 'form-control', 'placeholder' => __('Select Student'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('checkup_date', __('Checkup Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('checkup_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('doctor_name', __('Doctor Name'), ['class' => 'form-label']) }}
                {{ Form::text('doctor_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Doctor Name (Optional)')]) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('diagnosis', __('Diagnosis'), ['class' => 'form-label']) }}
                {{ Form::text('diagnosis', null, ['class' => 'form-control', 'placeholder' => __('Enter Diagnosis')]) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('treatment', __('Treatment'), ['class' => 'form-label']) }}
                {{ Form::text('treatment', null, ['class' => 'form-control', 'placeholder' => __('Enter Treatment')]) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('vaccination_status', __('Vaccination Status'), ['class' => 'form-label']) }}
                {{ Form::select('vaccination_status', $status, null, ['class' => 'form-control', 'placeholder' => __('Select Vaccination Status (Optional)')]) }}
            </div>
        </div>
        <div class="col-sm-12 col-12">
            <div class="form-group">
                {{ Form::label('allergies', __('Allergies'), ['class' => 'form-label']) }}
                {{ Form::textarea('allergies', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any known allergies (Optional)')]) }}
            </div>
        </div>
        <div class="col-sm-12 col-12">
            <div class="form-group">
                {{ Form::label('chronic_conditions', __('Chronic Conditions'), ['class' => 'form-label']) }}
                {{ Form::textarea('chronic_conditions', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any chronic health conditions (Optional)')]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
