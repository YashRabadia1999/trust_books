{{ Form::open(['route' => 'school-transport-routes.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('route_name', __('Route Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('route_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Route'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('start_location', __('Start Location'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('start_location', null, ['class' => 'form-control', 'placeholder' => __('Enter Start Location'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('end_location', __('End Location'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('end_location', null, ['class' => 'form-control', 'placeholder' => __('Enter End Location'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('bus_id', __('Bus'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('bus_id', $buses, null, ['class' => 'form-control', 'placeholder' => __('Select Bus'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
