{{ Form::open(['url' => 'classroom', 'method' => 'post', 'enctype' => 'multipart/form-data' , 'class' => 'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        {{-- <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('grade_name', __('Grade'), ['class' => 'form-label']) }}
                {!! Form::select('grade_name', $grade, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Grade'),
                ]) !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('class_name', __('Class Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('class_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Class Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('class_capacity', __('Class Capacity'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('class_capacity', null, ['class' => 'form-control', 'placeholder' => __('Enter Class Capacity'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}

