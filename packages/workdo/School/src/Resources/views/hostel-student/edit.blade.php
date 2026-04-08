{{ Form::model($student, ['route' => ['hostel-student.update', $student->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id', $students, null, ['class' => 'form-control', 'placeholder' => __('Select Student'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('hostel_id', __('Hostel'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('hostel_id', $hostels, null, ['class' => 'form-control', 'placeholder' => __('Select Hostel'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('room_id', __('Room'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('room_id', $rooms, null, ['class' => 'form-control', 'placeholder' => __('Select Room'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
