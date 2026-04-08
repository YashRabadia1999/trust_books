{{ Form::open(['route' => 'school-notice.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Title'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('posted_by', __('Posted By'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('posted_by', $employes, null, ['class' => 'form-control', 'placeholder' => __('Select Teacher'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('date_posted', __('Post Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('date_posted', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('target_audience', __('Target Audience'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('target_audience', $audiences, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Audience')]) }}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => 3]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
