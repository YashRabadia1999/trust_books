{{ Form::model($contact, ['route' => ['petcare.contact.us.update', $contactId], 'method' => 'PUT','class'=>'needs-validation','novalidate']) }}
    <div class="modal-body">
        <div class="col-md-12 form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter name'), 'required' => 'required']) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::select('status', $status, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Status')]) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('subject', __('Subject'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('subject', null, ['class' => 'form-control', 'placeholder' => __('Enter Subject'), 'required' => 'required']) }}
        </div>
        <div class="col-md-12 form-group">
            {{ Form::label('message', __('Message'), ['class' => 'form-label']) }}
            {{ Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => __('Enter Message'),'rows'=>3]) }}
        </div>

    </div>
    <div class="modal-footer">
        {{ Form::button(__('Cancel'), ['type' => 'button', 'class' => 'btn btn-light', 'data-bs-dismiss' => 'modal'])}}
        {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}
