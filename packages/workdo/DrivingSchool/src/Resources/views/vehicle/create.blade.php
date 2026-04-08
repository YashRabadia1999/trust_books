{{ Form::open(['route' => 'driving-vehicle.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('name', __('Vehicle Name'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Vehicle Name')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('teacher', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::select('teacher_id', $users, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Teacher')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('chassis_number ', __('Chassis Number'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('chassis_number', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Vehicle Chassis No')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('odometer', __('Odometer'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::number('odometer', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Odometer')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('model_year', __('Model Year'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::selectYear('model_year', date('Y'), 0, date('Y'), ['class' => 'form-control month-btn', 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('engine_transmission', __('Engine Transmission'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('engine_transmission', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Engine Transmission')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::textarea('location', null, ['class' => 'form-control', 'placeholder' => __('Enter Location'), 'rows' => 3, 'required' => 'required']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
