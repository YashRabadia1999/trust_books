{{ Form::model($room, ['route' => ['school-room.update', $room->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('hostel_id', __('Hostel'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('hostel_id', $hostels, null, ['class' => 'form-control', 'placeholder' => __('Select Hostel'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('room_number', __('Room Number'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('room_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Room Number'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('capacity', __('Capacity'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('capacity', null, ['class' => 'form-control', 'placeholder' => __('Enter Capacity'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
