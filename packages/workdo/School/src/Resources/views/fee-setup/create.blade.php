@extends('layouts.main')
@section('page-title')
    {{__('Create Fee Setup')}}
@endsection
@section('page-breadcrumb')
    {{__('Fee Setup')}},
    {{__('Create')}}
@endsection
@section('page-action')
    <div class="d-flex">
        <a href="{{ route('school-fee-setup.index') }}" class="btn btn-sm btn-primary me-2"> {{__('Back')}}</a>
    </div>
@endsection
@section('content')
{{ Form::open(['route' => 'school-fee-setup.store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Fee Setup Details')}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('name', __('Fee Setup Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter fee setup name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('academic_year_id', __('Academic Year'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::select('academic_year_id', $academicYears, null, ['class' => 'form-control', 'placeholder' => __('Select Academic Year'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('term_id', __('Term'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::select('term_id', $terms, null, ['class' => 'form-control', 'placeholder' => __('Select Term'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('class_id', __('Class'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::select('class_id', $classes, null, ['class' => 'form-control', 'placeholder' => __('Select Class'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::date('due_date', null, ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('discount_amount', __('Discount Amount'), ['class' => 'form-label']) }}
                            {{ Form::number('discount_amount', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Select Services/Items')}}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 col-12">
                        <input type="text" id="searchService" class="form-control" placeholder="Search services...">
                    </div>
                    <div class="col-sm-8 col-12 text-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllServices">
                            <label class="form-check-label" for="selectAllServices">
                                {{ __('Select All') }}
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="services-container" style="max-height: 400px; overflow-y: auto;">
                    @if(!empty($services) && count($services) > 0)
                        <div class="row" id="servicesList">
                            @foreach($services as $service)
                                <div class="col-md-6 mb-3 service-item" data-name="{{ strtolower($service->name) }}">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <div class="form-check">
                                                <input class="form-check-input service-checkbox" type="checkbox" 
                                                       name="services[]" 
                                                       value="{{ $service->id }}" 
                                                       id="service_{{ $service->id }}"
                                                       data-price="{{ $service->sale_price }}">
                                                <label class="form-check-label" for="service_{{ $service->id }}">
                                                    <strong>{{ $service->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $service->description ?? 'No description' }}</small>
                                                    <br>
                                                    <span class="text-primary">${{ number_format($service->sale_price, 2) }}</span>
                                                </label>
                                            </div>
                                            <div class="service-inputs mt-2" style="display: none;">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="form-label small">{{ __('Quantity') }}</label>
                                                        <input type="number" 
                                                               name="quantities[{{ $service->id }}]" 
                                                               class="form-control form-control-sm quantity-input" 
                                                               min="1" 
                                                               value="1">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">{{ __('Price') }}</label>
                                                        <input type="number" 
                                                               name="prices[{{ $service->id }}]" 
                                                               class="form-control form-control-sm price-input" 
                                                               step="0.01" 
                                                               value="{{ $service->sale_price }}">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="descriptions[{{ $service->id }}]" value="{{ $service->description ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <p>{{ __('No services found. Please add services first.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Options')}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 col-12">
                        <div class="form-check">
                            {{ Form::checkbox('auto_invoice', '1', true, ['class' => 'form-check-input']) }}
                            {{ Form::label('auto_invoice', __('Auto Generate Invoices'), ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="form-check">
                            {{ Form::checkbox('send_email', '1', true, ['class' => 'form-check-input']) }}
                            {{ Form::label('send_email', __('Send Email Notifications'), ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="form-check">
                            {{ Form::checkbox('send_sms', '1', false, ['class' => 'form-check-input']) }}
                            {{ Form::label('send_sms', __('Send SMS Notifications'), ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter description (optional)')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Summary')}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="alert alert-info">
                            <strong>{{ __('Total Selected Services') }}: </strong>
                            <span id="selectedCount">0</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="alert alert-success">
                            <strong>{{ __('Estimated Total Amount') }}: </strong>
                            <span id="totalAmount">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{ route('school-fee-setup.index') }}';" class="btn btn-light me-2">
    {{ Form::submit(__('Create Fee Setup'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Service selection functionality
    $('.service-checkbox').change(function() {
        const serviceId = $(this).val();
        const inputsDiv = $(this).closest('.card-body').find('.service-inputs');
        
        if ($(this).is(':checked')) {
            inputsDiv.show();
            updateHiddenInputs();
        } else {
            inputsDiv.hide();
            updateHiddenInputs();
        }
    });

    // Quantity and price change handlers
    $('.quantity-input, .price-input').on('input', function() {
        updateHiddenInputs();
    });

    // Search functionality
    $('#searchService').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.service-item').filter(function() {
            $(this).toggle($(this).data('name').indexOf(value) > -1);
        });
    });

    // Select all functionality
    $('#selectAllServices').change(function() {
        $('.service-checkbox').prop('checked', $(this).is(':checked')).trigger('change');
    });

    function updateHiddenInputs() {
    // Remove existing items inputs
    $('input[name^="items"]').remove();

    let idx = 0;
    $('.service-checkbox:checked').each(function() {
        const serviceId = $(this).val();
        const quantity = $(this).closest('.card-body').find(`input[name="quantities[${serviceId}]"]`).val();
        const price = $(this).closest('.card-body').find(`input[name="prices[${serviceId}]"]`).val();
        const description = $(this).closest('.card-body').find(`input[name="descriptions[${serviceId}]"]`).val();

        if (quantity && price) {
            $('<input>').attr({
                type: 'hidden',
                name: `items[${idx}][product_id]`,
                value: serviceId
            }).appendTo('form');

            $('<input>').attr({
                type: 'hidden',
                name: `items[${idx}][quantity]`,
                value: quantity
            }).appendTo('form');

            $('<input>').attr({
                type: 'hidden',
                name: `items[${idx}][price]`,
                value: price
            }).appendTo('form');

            $('<input>').attr({
                type: 'hidden',
                name: `items[${idx}][description]`,
                value: description
            }).appendTo('form');

            idx++;
        }
    });

    updateSummary();
}

    function updateSummary() {
        var selectedCount = $('.service-checkbox:checked').length;
        var totalAmount = 0;
        
        $('.service-checkbox:checked').each(function() {
            var quantity = parseFloat($(this).closest('.card-body').find('.quantity-input').val()) || 0;
            var price = parseFloat($(this).closest('.card-body').find('.price-input').val()) || 0;
            totalAmount += quantity * price;
        });
        
        var discount = parseFloat($('input[name="discount_amount"]').val()) || 0;
        var finalAmount = totalAmount - discount;
        
        $('#selectedCount').text(selectedCount);
        $('#totalAmount').text('$' + finalAmount.toFixed(2));
    }

    // Discount amount handler
    $('input[name="discount_amount"]').on('input', updateSummary);

    // Form validation
    $('form').submit(function(e) {
        if ($('.service-checkbox:checked').length === 0) {
            e.preventDefault();
            alert('{{ __("Please select at least one service.") }}');
            return false;
        }
    });
});
</script>
@endpush
