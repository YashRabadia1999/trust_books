{{ Form::open(['route' => ['pet.appointments.status.update', $petAppointmentId], 'method' => 'POST', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('appointment_status', __('Appointment Status'), ['class' => 'form-label']) }}<x-required />
                {{ Form::select('appointment_status', $petAppointmentStatus,$petAppointment->appointment_status ?? null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Appointment Status'), 'id' => 'appointment_status_select']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

