{{ Form::model($test_type, ['route' => ['driving_test_type.update', $test_type->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::text('name', !empty($test_type->name) ? $test_type->name : null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
