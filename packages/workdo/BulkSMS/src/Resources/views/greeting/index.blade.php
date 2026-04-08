@extends('layouts.main')

@section('page-title')
    {{ __('Send Greeting SMS') }}
@endsection

@section('page-breadcrumb')
    {{ __('Bulk SMS') }},
    {{ __('Greeting SMS') }}
@endsection

@section('page-action')
    <div class="d-flex">
        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('View SMS History') }}">
            <i class="ti ti-history"></i>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'bulksms.greeting.send', 'method' => 'POST', 'id' => 'greeting-sms-form']) }}

                    <div class="row">
                        <!-- Greeting Type Selection -->
                        <div class="col-md-12 mb-3">
                            <h5>{{ __('Select Greeting Type') }}</h5>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="greeting_type" id="type_seasonal"
                                    value="seasonal" checked>
                                <label class="btn btn-outline-primary" for="type_seasonal">
                                    <i class="ti ti-snowflake me-2"></i>{{ __('Seasonal Greetings') }}
                                </label>

                                <input type="radio" class="btn-check" name="greeting_type" id="type_general"
                                    value="general">
                                <label class="btn btn-outline-primary" for="type_general">
                                    <i class="ti ti-mail me-2"></i>{{ __('General Messages') }}
                                </label>

                                <input type="radio" class="btn-check" name="greeting_type" id="type_custom"
                                    value="custom">
                                <label class="btn btn-outline-primary" for="type_custom">
                                    <i class="ti ti-edit me-2"></i>{{ __('Custom Message') }}
                                </label>
                            </div>
                        </div>

                        <!-- Template Selection -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Select Template (Optional)') }}</label>
                            <select class="form-control" id="template-select">
                                <option value="">{{ __('-- Select a template --') }}</option>
                            </select>
                            <small class="text-muted">
                                {{ __('Choose a predefined template or write your own message below') }}
                            </small>
                        </div>

                        <!-- Message Textarea -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                            <textarea name="message" id="sms-message" class="form-control" rows="6" required
                                placeholder="{{ __('Type your greeting message here...') }}">{{ old('message') }}</textarea>

                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">
                                    {{ __('Available variables: {name}, {first_name}, {email}, {company}') }}
                                </small>
                                <small class="text-muted">
                                    <span id="char-count">0</span> {{ __('characters') }} |
                                    <span id="sms-count">0</span> {{ __('SMS credits needed per user') }}
                                </small>
                            </div>
                        </div>

                        <!-- User Selection -->
                        <div class="col-md-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">{{ __('Select Recipients') }} <span
                                        class="text-danger">*</span></label>
                                <div>
                                    <a href="#" id="select-all" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-checkbox me-1"></i>{{ __('Select All') }}
                                    </a>
                                    <a href="#" id="deselect-all" class="btn btn-sm btn-outline-secondary ms-1">
                                        <i class="ti ti-square me-1"></i>{{ __('Deselect All') }}
                                    </a>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                    @if ($users->isEmpty())
                                        <div class="text-center text-muted py-4">
                                            <i class="ti ti-users-off" style="font-size: 48px;"></i>
                                            <p class="mt-2">{{ __('No users found with mobile numbers.') }}</p>
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach ($users as $user)
                                                <div class="col-md-6 col-lg-4 mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input user-checkbox" type="checkbox"
                                                            name="user_ids[]" value="{{ $user->id }}"
                                                            id="user_{{ $user->id }}">
                                                        <label class="form-check-label" for="user_{{ $user->id }}"
                                                            style="cursor: pointer;">
                                                            <div class="fw-bold">{{ $user->name }}</div>
                                                            <small
                                                                class="text-muted d-block">{{ $user->mobile_no }}</small>
                                                            <small class="text-muted">
                                                                <span
                                                                    class="badge bg-info">{{ ucfirst($user->type) }}</span>
                                                                {{-- @if ($user->is_disable)
                                                                    <span
                                                                        class="badge bg-warning">{{ __('Disabled') }}</span>
                                                                @endif --}}
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        <span id="selected-count">0</span> {{ __('users selected') }} |
                                        <span id="total-credits">0</span> {{ __('total SMS credits will be used') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="col-md-12 mb-3">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ __('Message Preview') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div id="message-preview" class="text-muted fst-italic">
                                        {{ __('Your message preview will appear here...') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-danger">
                                        <i class="ti ti-alert-circle me-1"></i>
                                        {{ __('SMS credits will be deducted from your account balance.') }}
                                    </small>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary" id="send-btn" disabled>
                                        <i class="ti ti-send me-2"></i>{{ __('Send SMS') }}
                                    </button>
                                </div>
                            </div>
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
            let templates = {};

            // Load templates when greeting type changes
            $('input[name="greeting_type"]').on('change', function() {
                const type = $(this).val();
                loadTemplates(type);
            });

            // Load initial templates
            loadTemplates('seasonal');

            // Template selection
            $('#template-select').on('change', function() {
                const templateName = $(this).val();
                if (templateName && templates[templateName]) {
                    $('#sms-message').val(templates[templateName]);
                    updateCharCount();
                    updatePreview();
                }
            });

            // Message input handlers
            $('#sms-message').on('input', function() {
                updateCharCount();
                updatePreview();
            });

            // User selection handlers
            $('.user-checkbox').on('change', function() {
                updateSelection();
            });

            $('#select-all').on('click', function(e) {
                e.preventDefault();
                $('.user-checkbox:not(:disabled)').prop('checked', true);
                updateSelection();
            });

            $('#deselect-all').on('click', function(e) {
                e.preventDefault();
                $('.user-checkbox').prop('checked', false);
                updateSelection();
            });

            // Form submission
            $('#greeting-sms-form').on('submit', function(e) {
                const selectedCount = $('.user-checkbox:checked').length;
                const message = $('#sms-message').val();

                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('{{ __('Please select at least one user.') }}');
                    return false;
                }

                if (!message || message.trim().length < 10) {
                    e.preventDefault();
                    alert('{{ __('Message must be at least 10 characters long.') }}');
                    return false;
                }

                const totalCredits = $('#total-credits').text();
                if (!confirm(
                        `{{ __('You are about to send SMS to') }} ${selectedCount} {{ __('users. Total credits:') }} ${totalCredits}. {{ __('Continue?') }}`
                    )) {
                    e.preventDefault();
                    return false;
                }
            });

            // Functions
            function loadTemplates(type) {
                $.ajax({
                    url: '{{ route('bulksms.greeting.templates') }}',
                    type: 'GET',
                    data: {
                        type: type
                    },
                    success: function(response) {
                        if (response.success) {
                            const select = $('#template-select');
                            select.empty().append(
                                '<option value="">{{ __('-- Select a template --') }}</option>');

                            templates = {};
                            response.templates.forEach(function(template) {
                                templates[template.name] = template.message;
                                select.append(
                                    `<option value="${template.name}">${template.name}</option>`
                                );
                            });
                        }
                    }
                });
            }

            function updateCharCount() {
                const message = $('#sms-message').val();
                const length = message.length;
                $('#char-count').text(length);

                // Calculate SMS credits needed (150 chars = 1 credit, then 100 chars per additional)
                let credits = 0;
                if (length > 0) {
                    if (length <= 150) {
                        credits = 1;
                    } else {
                        credits = 1 + Math.ceil((length - 150) / 100);
                    }
                }
                $('#sms-count').text(credits);

                updateSelection();
            }

            function updateSelection() {
                const selectedCount = $('.user-checkbox:checked').length;
                const creditsPerUser = parseInt($('#sms-count').text()) || 0;
                const totalCredits = selectedCount * creditsPerUser;

                $('#selected-count').text(selectedCount);
                $('#total-credits').text(totalCredits);

                // Enable/disable send button
                const message = $('#sms-message').val().trim();
                $('#send-btn').prop('disabled', selectedCount === 0 || message.length < 10);
            }

            function updatePreview() {
                let message = $('#sms-message').val();

                if (message) {
                    // Replace variables with sample data
                    message = message
                        .replace(/{name}/g, 'John Doe')
                        .replace(/{first_name}/g, 'John')
                        .replace(/{email}/g, 'john.doe@example.com')
                        .replace(/{company}/g, '{{ getCompanyAllSetting()['company_name'] ?? 'Your Company' }}');

                    $('#message-preview').removeClass('text-muted fst-italic').text(message);
                } else {
                    $('#message-preview').addClass('text-muted fst-italic').text(
                        '{{ __('Your message preview will appear here...') }}');
                }
            }
        });
    </script>
@endpush
