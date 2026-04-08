{{ Form::open(['url' => 'student_convert/' . $admission->id, 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('class_name', __('Class'), ['class' => 'form-label']) }}
                {!! Form::select('class_name', $classRoom, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Class'),
                ]) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('roll_no', __('Student Number'), ['class' => 'form-label']) }}
                {{ Form::number('roll_no', null, ['class' => 'form-control', 'placeholder' => __('Enter Student Number'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-0">
                {{ Form::label('grade_name', __('Grade'), ['class' => 'form-label']) }}
                {!! Form::select('grade_name', $grade, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Grade'),
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light"
        data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Convert'),array('class'=>'btn  btn-primary '))}}{{Form::close()}}
</div>
{{ Form::close() }}
