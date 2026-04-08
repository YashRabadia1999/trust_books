{{ Form::model($academicYear, array(
    'route' => array('school.academic-year.update', $academicYear->id),
    'method' => 'PUT',
    'enctype' => 'multipart/form-data',
    'class' => 'needs-validation',
    'novalidate'
)) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', isset($academicYear->name) ? $academicYear->name : '', [
                    'class' => 'form-control',
                    'placeholder' => __('Enter Academic Year Name'),
                    'required' => 'required'
                ]) }}
            </div>
        </div>

        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('start_date', isset($academicYear->start_date) ? $academicYear->start_date : '', [
                    'class' => 'form-control',
                    'required' => 'required'
                ]) }}
            </div>
        </div>

        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('end_date', isset($academicYear->end_date) ? $academicYear->end_date : '', [
                    'class' => 'form-control',
                    'required' => 'required'
                ]) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), array('class' => 'btn btn-primary')) }}
</div>
{{ Form::close() }}
