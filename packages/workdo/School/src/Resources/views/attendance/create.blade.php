{{ Form::open(['url' => 'school-attendance', 'method' => 'post' , 'class' => 'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id', $student, null, ['class' => 'form-control ', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('clock_in', __('Clock In'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::time('clock_in', null, ['class' => 'form-control timepicker', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('clock_out', __('Clock Out'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::time('clock_out', null, ['class' => 'form-control timepicker', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
