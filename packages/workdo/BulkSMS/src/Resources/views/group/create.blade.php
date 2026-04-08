{{ Form::open(['route' => 'bulksms-group.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('name', __('Group Name'), ['class' => 'form-label']) }} <x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Group Name'), 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                {{ Form::label('mobile_no', __('Select Contacts'), ['class' => 'form-label']) }}
                <x-required></x-required>
                <select name="mobile_no[]" id="group-contacts-select" class="form-control multi-select" multiple required>
                    @if ($contacts->count() > 0)
                        <optgroup label="{{ __('BulkSMS Contacts') }}">
                            @foreach ($contacts as $contact)
                                <option value="{{ $contact->mobile_no }}">{{ $contact->name }}
                                    ({{ $contact->mobile_no }})</option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($users->count() > 0)
                        <optgroup label="{{ __('Users') }}">
                            @foreach ($users as $user)
                                <option value="{{ $user->mobile_no }}">{{ $user->name }} ({{ $user->mobile_no }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($customers->count() > 0)
                        <optgroup label="{{ __('Customers') }}">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->mobile_no }}">{{ $customer->name }}
                                    ({{ $customer->mobile_no }})</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <small
                    class="text-muted">{{ __('Select contacts from BulkSMS Contacts, Users, or Customers') }}</small>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn btn-primary ']) }}
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        if ($('#group-contacts-select').length) {
            $('#group-contacts-select').select2({
                dropdownParent: $('#group-contacts-select').closest('.modal-body'),
                width: '100%',
                placeholder: '{{ __('Select contacts') }}'
            });
        }
    });
</script>
