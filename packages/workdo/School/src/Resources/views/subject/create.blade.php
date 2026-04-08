{{ Form::open(['url' => 'subject', 'method' => 'post', 'enctype' => 'multipart/form-data' , 'class' => 'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('subject_code', __('Subject Code'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('subject_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Subject Code'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('subject_name', __('Subject Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('subject_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Subject Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('grade_name', __('Grade'), ['class' => 'form-label']) }}
                {!! Form::select('grade_name', $grade, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Grade'),
                ]) !!}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('class', __('Class'), ['class' => 'form-label']) }}<x-required></x-required>
                {!! Form::select('class', $classRoom, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Class'),
                ]) !!}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group mb-0">
                {{ Form::label('teacher', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
                {!! Form::select('teacher', $user, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Teacher'),
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light"
        data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary '))}}
</div>
{{ Form::close() }}
