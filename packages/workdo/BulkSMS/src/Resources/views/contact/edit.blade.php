{{ Form::model($bulksmsContact, ['route' => ['bulksms-contacts.update', $bulksmsContact->id], 'method' => 'put', 'class'=>'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required']) }}
            </div>
        </div> 
        
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required']) }}
            </div>
        </div>
        
        <div class="col-6">
            <x-mobile value="{{ !empty($bulksmsContact->mobile_no) ? $bulksmsContact->mobile_no : null }}"></x-mobile>
        </div>
        
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('city', __('City'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City'), 'required']) }}
            </div>
        </div>    
        
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('state', __('State'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State'), 'required']) }}
            </div>
        </div>        
        
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('zip', __('Zip Code'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required']) }}
            </div>
        </div>                  
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
