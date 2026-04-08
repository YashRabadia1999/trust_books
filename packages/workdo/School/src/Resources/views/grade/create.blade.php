{{ Form::open(['url' => 'school-grade', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="form-group">
                {{ Form::label('grade_name', __('Grade Name'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('grade_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Grade Name'), 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('min_marks', __('Minimum Marks'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::number('min_marks', null, ['class' => 'form-control', 'placeholder' => __('Enter Minimum Marks'), 'required' => 'required', 'min'=>0]) }}
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('max_marks', __('Maximum Marks'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::number('max_marks', null, ['class' => 'form-control', 'placeholder' => __('Enter Maximum Marks'), 'required' => 'required', 'max'=>100]) }}
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-group">
                {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
                {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows'=>2, 'placeholder' => __('Enter Remarks')]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
