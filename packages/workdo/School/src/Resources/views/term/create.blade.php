{{ Form::open(['route' => 'school.term.store', 'method' => 'post', 'class'=>'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('academic_year_id', __('Academic Year'), ['class' => 'form-label']) }} <x-required></x-required>
                {!! Form::select('academic_year_id', $academicYears, null, [
                    'class'=>'form-control',
                    'placeholder'=>__('Select Academic Year'),
                    'required'=>'required'
                ]) !!}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('name', __('Term Name'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('name', null, [
                    'class'=>'form-control',
                    'placeholder'=>__('Enter Term Name'),
                    'required'=>'required'
                ]) }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::date('start_date', null, [
                    'class'=>'form-control',
                    'required'=>'required'
                ]) }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::date('end_date', null, [
                    'class'=>'form-control',
                    'required'=>'required'
                ]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save'), ['class'=>'btn btn-primary']) }}
</div>
{{ Form::close() }}
