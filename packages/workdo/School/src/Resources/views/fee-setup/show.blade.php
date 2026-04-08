@extends('layouts.main')
@section('page-title')
    {{__('Fee Setup Details')}}
@endsection
@section('page-breadcrumb')
    {{__('Fee Setup')}},
    {{ $feeSetup->name }}
@endsection
@section('page-action')
    <div class="d-flex">
        <a href="{{ route('school-fee-setup.index') }}" class="btn btn-sm btn-primary me-2"> {{__('Back')}}</a>
        @if($feeSetup->status == 'Active')
            <form method="POST" action="{{ route('school-fee-setup.generate-invoices', encrypt($feeSetup->id)) }}" style="display: inline;" class="me-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Generate invoices for all students?')">
                    <i class="ti ti-generic"></i> {{ __('Generate Invoices') }}
                </button>
            </form>
            <form method="POST" action="{{ route('school-fee-setup.send-notifications', encrypt($feeSetup->id)) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">
                    <i class="ti ti-send"></i> {{ __('Send Notifications') }}
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Fee Setup Info') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ __('Name') }}:</strong> {{ $feeSetup->name }}</p>
                <p><strong>{{ __('Academic Year') }}:</strong> {{ $feeSetup->academicYear->name ?? '-' }}</p>
                <p><strong>{{ __('Term') }}:</strong> {{ $feeSetup->term->name ?? '-' }}</p>
                <p><strong>{{ __('Class') }}:</strong> {{ $feeSetup->classroom->class_name ?? '-' }}</p>
                <p><strong>{{ __('Total Amount') }}:</strong> ${{ number_format($feeSetup->total_amount, 2) }}</p>
                <p><strong>{{ __('Discount') }}:</strong> ${{ number_format($feeSetup->discount_amount, 2) }}</p>
                <p><strong>{{ __('Status') }}:</strong> 
                    <span class="badge bg-{{ $feeSetup->status == 'Active' ? 'success' : 'warning' }}">
                        {{ $feeSetup->status }}
                    </span>
                </p>
                <p><strong>{{ __('Due Date') }}:</strong> {{ $feeSetup->due_date }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ __('Options') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" {{ $feeSetup->auto_invoice ? 'checked' : '' }} disabled>
                    <label class="form-check-label">{{ __('Auto Generate Invoices') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" {{ $feeSetup->send_email ? 'checked' : '' }} disabled>
                    <label class="form-check-label">{{ __('Send Email Notifications') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" {{ $feeSetup->send_sms ? 'checked' : '' }} disabled>
                    <label class="form-check-label">{{ __('Send SMS Notifications') }}</label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Selected Services') }}</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($feeSetup->items))
                                @foreach(json_decode($feeSetup->items, true) as $item)
                                    @php
                                        $service = \Workdo\ProductService\Entities\ProductService::find($item['product_id']);
                                    @endphp
                                    <tr>
                                        <td>{{ $service->name ?? 'Unknown Service' }}</td>
                                        <td>{{ $item['description'] ?? '-' }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>${{ number_format($item['price'], 2) }}</td>
                                        <td>${{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($feeSetup->description)
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ __('Description') }}</h5>
            </div>
            <div class="card-body">
                <p>{{ $feeSetup->description }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Generated Invoices') }} ({{ $feeSetup->generatedInvoices->count() }} students)</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Student') }}</th>
                                <th>{{ __('Invoice Number') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Email Sent') }}</th>
                                <th>{{ __('SMS Sent') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($feeSetup->generatedInvoices->count() > 0)
                                @foreach($feeSetup->generatedInvoices as $generatedInvoice)
                                    <tr>
                                        <td>{{ $generatedInvoice->student->name ?? '-' }}</td>
                                        <td>{{ $generatedInvoice->invoice->invoice_id ?? '-' }}</td>
                                        <td>${{ number_format($generatedInvoice->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $generatedInvoice->status == 'Paid' ? 'success' : ($generatedInvoice->status == 'Sent' ? 'info' : 'warning') }}">
                                                {{ $generatedInvoice->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $generatedInvoice->email_sent ? 'success' : 'secondary' }}">
                                                {{ $generatedInvoice->email_sent ? __('Yes') : __('No') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $generatedInvoice->sms_sent ? 'success' : 'secondary' }}">
                                                {{ $generatedInvoice->sms_sent ? __('Yes') : __('No') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($generatedInvoice->invoice)
                                                <a href="{{ route('school-invoice.show', encrypt($generatedInvoice->invoice->id)) }}" class="btn btn-sm btn-info">
                                                    <i class="ti ti-eye"></i> {{ __('View Invoice') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <p>{{ __('No invoices generated yet.') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.pc-dt-simple').DataTable({
            "pageLength": 10,
            "order": [[0, "desc"]],
        });
    });
</script>
@endpush
