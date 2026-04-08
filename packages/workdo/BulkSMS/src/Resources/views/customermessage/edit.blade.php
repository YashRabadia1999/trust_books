{{ Form::model($customerMessage, ['route' => ['customer-messages.update', $customerMessage->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 mb-3">
            {{ Form::label('name', __('Template Name / Title'), ['class' => 'form-label']) }}
            <x-required />
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter template name'), 'required']) }}
        </div>

        <div class="col-12 mb-3">
            {{ Form::label('message', __('Message Content'), ['class' => 'form-label']) }}
            <x-required />
            {{ Form::textarea('message', null, ['class' => 'form-control', 'rows' => 5, 'id' => 'message_content', 'placeholder' => __('Enter your message content'), 'required']) }}
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">
                    <i class="ti ti-message"></i> <span id="char_number">0</span> {{ __('characters') }}
                </small>
                <small class="badge bg-primary">
                    <i class="ti ti-file"></i> <span id="page_number">0</span> {{ __('page(s)') }}
                </small>
            </div>
            <small class="text-muted d-block mt-1">
                <i class="ti ti-info-circle"></i>
                {{ __('First 150 characters = 1 page, then +1 page per 100 characters') }}
            </small>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update Template'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        function updateCharCount() {
            var len = $('#message_content').val().length;
            var pages = 0;

            if (len > 0) {
                if (len <= 150) {
                    pages = 1;
                } else {
                    pages = 1 + Math.ceil((len - 150) / 100);
                }
            }

            $('#char_number').text(len);
            $('#page_number').text(pages);
        }

        $('#message_content').on('input', updateCharCount);
        updateCharCount();
    });
</script>
