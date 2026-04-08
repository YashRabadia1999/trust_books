{{ Form::open(['route' => 'school-event.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
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
                {{ Form::label('event_name', __('Event Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('event_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('event_date', __('Event Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('event_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Date'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('location', __('Loaction'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('Enter Location'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Description')]) }}
            </div>
        </div>
        @stack('calendar')
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
