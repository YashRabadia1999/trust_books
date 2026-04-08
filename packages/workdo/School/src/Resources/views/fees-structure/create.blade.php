{{ Form::open(['route' => 'school-fee-structure.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('class_id', __('Class'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('class_id', $classRooms, null, ['class' => 'form-control', 'placeholder' => __('Enter ClassRoom'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('fee_type', __('Fee Type'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('fee_type', null, ['class' => 'form-control', 'placeholder' => __('Enter Fee Type'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
