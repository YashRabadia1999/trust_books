{{ Form::open(['route' => 'cash_categories.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Categorie Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => true]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}
