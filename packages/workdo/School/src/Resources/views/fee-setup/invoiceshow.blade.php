@extends('layouts.main')
@section('page-title', __('Invoice Details'))
@section('page-breadcrumb', __('Invoice #') . $invoice->invoice_id)

@section('page-action')
<div class="d-flex">
    <a href="{{ route('school-invoice.index') }}" class="btn btn-sm btn-primary me-2"> {{__('Back')}}</a>
    @if($invoice->status != 'Paid')
       <form method="POST" action="{{ route('school-fee-setup.send-notifications', encrypt($invoice->id)) }}" style="display: inline;">
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
    {{-- Left Column: Invoice & Student Summary --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-header"><h5>{{ __('Invoice Info') }}</h5></div>
            <div class="card-body">
                <p><strong>{{ __('Invoice #') }}:</strong> {{ $invoice->invoice_id }}</p>
                <p><strong>{{ __('Status') }}:</strong>
                    <span class="badge bg-{{ $invoice->status == 'Paid' ? 'success' : ($invoice->status == 'Sent' ? 'info' : 'warning') }}">
                        {{ $invoice->status }}
                    </span>
                </p>
                <p><strong>{{ __('Due Date') }}:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</p>
                <p><strong>{{ __('Amount') }}:</strong> ${{ number_format($invoice->amount, 2) }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h5>{{ __('Student Info') }}</h5></div>
            <div class="card-body text-center">
                @if($invoice->student['student_image'])
                    <img src="{{ asset($invoice->student['student_image']) }}" alt="Student Image" class="img-fluid rounded mb-2">
                @endif
                <p><strong>{{ $invoice->student['name'] }}</strong></p>
                <p>{{ $invoice->student['class_name'] }} - {{ $invoice->student['grade_name'] }}</p>
                <p>{{ ucfirst($invoice->student['student_gender']) }}</p>
            </div>
        </div>
    </div>

    {{-- Right Column: Invoice Items & Parent Info --}}
    <div class="col-md-9">
        {{-- Invoice Items --}}
        <div class="card">
            <div class="card-header"><h5>{{ __('Invoice Items') }}</h5></div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Product Name') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td>{{ $item['product_id'] ?: 'N/A' }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    <td>{{ $item['description'] ?: '-' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"><strong>{{ __('Total Amount') }}</strong></td>
                                <td colspan="2"><strong>${{ number_format($invoice->amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Parent Details --}}
        <div class="card mt-3">
            <div class="card-header"><h5>{{ __('Parent Details') }}</h5></div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th>{{ __("Father's Name") }}</th>
                            <td>{{ $invoice->student['father_name'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Father's Contact") }}</th>
                            <td>{{ $invoice->student['father_number'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Father's Email") }}</th>
                            <td>{{ $invoice->student['father_email'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Mother's Name") }}</th>
                            <td>{{ $invoice->student['mother_name'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Mother's Contact") }}</th>
                            <td>{{ $invoice->student['mother_number'] }}</td>
                        </tr>
                        <tr>
                            <th>{{ __("Mother's Email") }}</th>
                            <td>{{ $invoice->student['mother_email'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
