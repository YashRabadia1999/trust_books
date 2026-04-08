{{ Form::model($alumini, ['route' => ['school-alumini.update', $alumini->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id', $students, null, ['class' => 'form-control', 'id' => 'student_id', 'placeholder' => __('Select Student'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('batch_year', __('Batch Year'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('batch_year', null, ['class' => 'form-control', 'placeholder' => __('Enter Batch Year'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('current_position', __('Current Position'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('current_position', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Current Position')]) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <x-Mobile name="contact" label="{{ __('Contact') }}" required></x-Mobile>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
