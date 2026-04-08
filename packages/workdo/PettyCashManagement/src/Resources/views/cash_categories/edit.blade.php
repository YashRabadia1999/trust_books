{{ Form::model($categorie, ['route' => ['cash_categories.update', $categorie->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Categorie Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', $categorie->name, ['class' => 'form-control', 'required' => true]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
    </div>
{{ Form::close() }}
