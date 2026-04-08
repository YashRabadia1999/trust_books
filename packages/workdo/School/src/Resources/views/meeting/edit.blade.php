{{ Form::model($meeting, ['route' => ['school-meeting.update', $meeting->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('parent_id', __('Parent'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('parent_id', $parents, null, ['class' => 'form-control', 'placeholder' => __('Select Parent'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('teacher_id', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('teacher_id', $employees, null, ['class' => 'form-control', 'placeholder' => __('Select Teacher'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('meeting_date', __('Meeting Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('meeting_date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('agenda', __('Agenda'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('agenda', null, ['class' => 'form-control', 'placeholder' => __('Enter Agenda'), 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}
