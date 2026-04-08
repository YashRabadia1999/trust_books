{{ Form::open(['route' => ['pet.adoption.request.status.update', $adoptionRequestId], 'method' => 'POST', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('adoption_request_status', __('Adoption Request Status'), ['class' => 'form-label']) }}<x-required />
                {{ Form::select('adoption_request_status', $petAdoptionRequestStatus,$petAdoptionRequest->request_status ?? null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Appointment Status'), 'id' => 'appointment_status_select']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}