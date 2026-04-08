{{ Form::open(['route' => 'petty-cash.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('added_amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('added_amount', null, ['class' => 'form-control', 'step' => '0.01', 'required' => true, 'placeholder' => __('Enter amount to add')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('date', now()->toDateString(), ['class' => 'form-control', 'required' => true]) }}
            </div>
        </div>

        <div class="col-md-12">
                <div class="form-group">
                {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
                {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any remarks...')]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}
