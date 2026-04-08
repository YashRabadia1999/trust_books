<link href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

{!! Form::model($petAdoption, ['route' => ['pet.adoption.update', $AdoptionId],'method' => 'PUT','enctype' => 'multipart/form-data','class' => 'needs-validation','novalidate',]) !!}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('pet_name', __('Pet Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('pet_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Pet Name')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('species', __('Species'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('species', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter species')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('breed', __('Breed'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('breed', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Breed')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('adoption_amount', __('Adoption Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('adoption_amount', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'totalAmount', 'step' => '0.01', 'placeholder' => __('Enter Adoption Amount')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date_of_birth', __('Date of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('date_of_birth', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Date of Birth'),'max' => date('Y-m-d')]) }}
            </div>
        </div>  
        <div class="form-group col-md-6">
            {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="d-flex radio-check">
                <div class="form-check form-check-inline">
                    {{ Form::radio('gender', __('Male'), $petAdoption->gender == 'Male', ['class' => 'form-check-input', 'id' => 'p_a_male']) }}
                    <label class="form-check-label" for="p_a_male">{{ __('Male') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    {{ Form::radio('gender', __('Female'), $petAdoption->gender == 'Female', ['class' => 'form-check-input', 'id' => 'p_a_female']) }}
                    <label class="form-check-label" for="p_a_female">{{ __('Female') }}</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('availability', __('Availability Status'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::select('availability', $availability_status, null, ['class' => 'form-select','required','placeholder' => __('Please Select Availability'),]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('health_status', __('Health Status'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::select('health_status', $health_status, null, ['class' => 'form-select','required','placeholder' => __('Please Select Health Status'),]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('classification_tags', __('Classification Tags'), ['class' => 'form-label']) !!}<x-required></x-required>
                {!! Form::text('classification_tags', null, ['class' => 'form-control', 'id' => 'choices-text-remove-button','placeholder' => __('Enter Classification Tags'),'required' => 'required']) !!}
            </div>
        </div>
        <div class="form-group col-md-6 mb-0">
            {{ Form::label('pet_image', __('Image'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <label for="pet_image" class="form-label">
                    <input type="file" name="pet_image" id="pet_image" class="form-control me-3"
                        style="width: 365px;"   
                        onchange="document.getElementById('pet_image_preview').src = window.URL.createObjectURL(this.files[0])">
                </label>
                @if ($petAdoption->pet_image)
                    <img id="pet_image_preview" class="mt-2 mb-0" width="35%" src="{{ asset($petAdoption->pet_image) }}" />
                @else
                    <img id="pet_image_preview" class="mt-2 mb-0" width="35%" src="" />
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'rows' => 3]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{!! Form::close() !!}

<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/choices.min.js') }}"></script>
<script>
    var textRemove = new Choices(
        document.getElementById('choices-text-remove-button'), {
            delimiter: ',',
            editItems: true,
            removeItemButton: true,
        }
    );
</script>
