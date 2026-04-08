{!! Form::open(['route' => ['pet.adoption.request.store'], 'method' => 'POST', 'class' => 'needs-validation', 'novalidate']) !!}
<div class="modal-body">
    <div class="row">
        <input type="hidden" name="addoptionId" value="{{ $addoptionId }}">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('adopter_name', __("Adopter's Name"), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('adopter_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter email')]) }}
            </div>
        </div>
        <x-mobile divClass="col-md-6" name="contact_number" label="{{ __('Contact Number') }}" placeholder="{{ __('Enter Contact Number') }}" required></x-mobile>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('adoption_amount', __('Pet Adoption Amount'), ['class' => 'form-label']) }}
                {{ Form::number('adoption_amount',isset($petAdoption->adoption_amount) ? $petAdoption->adoption_amount : null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter amount'),'readonly','disabled']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('address', __('Residential Address'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('address', null, ['class' => 'form-control','rows' => 3,'required' => 'required','placeholder' => __('Enter address')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('reason_for_adoption', __('Reason for Adoption'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('reason_for_adoption', null, ['class' => 'form-control', 'rows' => 3,  'required' => 'required', 'placeholder' => __('Why do you want to adopt?')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{!! Form::close() !!}