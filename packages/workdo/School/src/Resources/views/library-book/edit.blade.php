{{ Form::model($book, ['route' => ['library-books.update', $book->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Select Title'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('author', __('Author'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('author', null, ['class' => 'form-control', 'placeholder' => __('Enter Author Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('category', null, ['class' => 'form-control', 'placeholder' => __('Enter Category'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('availability', __('Availability'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('availability', $availability, null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
