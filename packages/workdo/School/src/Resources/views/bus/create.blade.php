{{ Form::open(['route' => 'school-bus.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('driver_name', __('Driver Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('driver_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Driver Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('bus_number', __('Bus Number'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('bus_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Bus Number'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('capacity', __('Capacity'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('capacity', null, ['class' => 'form-control', 'placeholder' => __('Enter Capacity'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
