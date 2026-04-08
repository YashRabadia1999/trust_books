@extends('layouts.main')

@section('page-title')
    {{ __('Excel SMS Upload') }}
@endsection

@section('page-breadcrumb')
    {{ __('Excel SMS Upload') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('excel-sms.download-sample') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-download"></i> {{ __('Download Sample Excel') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Upload Excel File for Bulk SMS') }}</h5>
                    <small class="text-muted">
                        {{ __('Upload an Excel file with contacts. The file should have: Name (Column 1), Phone Number (Column 2), Message (Column 3).') }}
                        <br>
                        {{ __('Note: If you select a custom message template, the message column in Excel will be ignored.') }}
                    </small>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'excel-sms.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::label('excel_file', __('Excel File'), ['class' => 'form-label']) }}
                                <div class="custom-file">
                                    <input type="file" class="form-control" name="excel_file" id="excel_file"
                                        accept=".xlsx,.xls,.csv" required>
                                </div>
                                <small class="text-muted">{{ __('Accepted formats: .xlsx, .xls, .csv') }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('message_type', __('Message Type'), ['class' => 'form-label']) }}
                                {{ Form::select(
                                    'message_type',
                                    [
                                        'excel' => 'Use Message from Excel File',
                                        'custom' => 'Use Custom Message Template',
                                    ],
                                    'excel',
                                    ['class' => 'form-control', 'id' => 'message_type', 'required' => true],
                                ) }}
                            </div>
                        </div>

                        <div class="col-md-6" id="custom_message_div" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('custom_message_id', __('Select Custom Message'), ['class' => 'form-label']) }}
                                <select name="custom_message_id" id="custom_message_id" class="form-control">
                                    <option value="">{{ __('-- Select Message Template --') }}</option>
                                    @foreach ($customMessages as $msg)
                                        <option value="{{ $msg->id }}" data-message="{{ $msg->message }}">
                                            {{ $msg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" id="message_preview_div" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('message_preview', __('Message Preview'), ['class' => 'form-label']) }}
                                <textarea id="message_preview" class="form-control" rows="3" readonly></textarea>
                                <small class="text-muted">
                                    <span id="char_count">0</span> {{ __('characters') }} |
                                    <span id="page_count">0</span> {{ __('page(s)') }}
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('sender_id', __('Sender ID'), ['class' => 'form-label']) }}
                                {{ Form::select('sender_id', array_combine($senderIds, $senderIds), null, [
                                    'class' => 'form-control',
                                    'required' => true,
                                    'placeholder' => __('Select Sender ID'),
                                ]) }}
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="ti ti-info-circle"></i> {{ __('Excel File Format:') }}
                                </h6>
                                <ul class="mb-0">
                                    <li>{{ __('Column 1: Recipient Name (optional)') }}</li>
                                    <li>{{ __('Column 2: Phone Number (required)') }}</li>
                                    <li>{{ __('Column 3: Message Content (required if not using custom message)') }}</li>
                                </ul>
                                <hr>
                                <p class="mb-0">
                                    <strong>{{ __('Example:') }}</strong><br>
                                    John Doe | 0501234567 | Hello John, this is your message.<br>
                                    Jane Smith | 0559876543 | Hi Jane, welcome to our service!
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('bulksms-send-sms.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send"></i> {{ __('Upload & Send SMS') }}
                            </button>
                        </div>
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle message type change
            $('#message_type').on('change', function() {
                const messageType = $(this).val();
                if (messageType === 'custom') {
                    $('#custom_message_div').show();
                    $('#custom_message_id').attr('required', true);
                } else {
                    $('#custom_message_div').hide();
                    $('#message_preview_div').hide();
                    $('#custom_message_id').attr('required', false);
                    $('#custom_message_id').val('');
                }
            });

            // Handle custom message selection
            $('#custom_message_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const message = selectedOption.data('message');

                if (message) {
                    $('#message_preview').val(message);
                    $('#message_preview_div').show();

                    // Calculate characters and pages
                    const charCount = message.length;
                    const pageCount = charCount <= 150 ? 1 : Math.ceil((charCount - 150) / 100) + 1;

                    $('#char_count').text(charCount);
                    $('#page_count').text(pageCount);
                } else {
                    $('#message_preview_div').hide();
                }
            });

            // File input validation
            $('#excel_file').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileSize = file.size / 1024 / 1024; // in MB
                    if (fileSize > 5) {
                        alert('File size should not exceed 5MB');
                        $(this).val('');
                    }
                }
            });
        });
    </script>
@endpush
