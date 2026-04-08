@extends('layouts.main')
@section('page-title')
    {{ __('Invoice Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice') }},
    {{ __('Detail') }}
@endsection
@section('page-action')
    <div>
        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
            @if ($dueAmount > 0)
                @permission('drivinginvoice payment')
                    <div class="all-button-box">
                        <a href="#" data-url="{{ route('driving-invoice.pay', $drivinginvoice->id) }}" data-ajax-popup="true"
                            data-toggle="tooltip" title="{{ __('Add Payment') }}" data-title="{{ __('Invoice Payment') }}" class="me-2 btn btn-sm btn-primary btn-icon-only width-auto"
                            data-original-title="{{ __('Invoice Payment') }}"><i
                                class="ti ti-report-money"></i></a>
                    </div>
                @endpermission
            @endif
            <div class="all-button-box">
                <a href="{{ route('driving.invoice.pdf', Crypt::encrypt($drivinginvoice->id)) }}" target="_blank"
                    class="btn btn-sm btn-primary btn-icon-only width-auto me-2" data-toggle="tooltip" title="{{ __('Download') }}" >
                    <i class="ti ti-download"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="invoice" role="tabpanel" aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row row-gap invoice-title border-1 border-bottom  pb-3 mb-3">
                                        <div class="col-sm-4  col-12">
                                            <h2 class="h3 mb-0">{{ __('Invoice Detail') }}</h2>
                                        </div>
                                        <div class="col-sm-8  col-12">
                                            <div class="d-flex invoice-wrp flex-wrap align-items-center gap-md-2 gap-1 justify-content-end">
                                                <div class="d-flex invoice-date flex-wrap align-items-center justify-content-end gap-md-3 gap-1">
                                                    <p class="mb-0"><strong>{{ __('Issue Date') }} :</strong>
                                                        {{ company_date_formate($drivinginvoice->issue_date) }}</p>
                                                    <p class="mb-0"><strong>{{ __('Due Date') }} :</strong>
                                                        {{ company_date_formate($drivinginvoice->due_date) }}</p>
                                                </div>
                                                <h3 class="invoice-number mb-0">
                                                    {{ \Workdo\DrivingSchool\Entities\DrivingInvoice::invoiceNumberFormat($drivinginvoice->invoice_id) }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-sm-4 p-3 invoice-billed">
                                        <div class="row row-gap">
                                            <div class="col-sm-6">
                                                <strong class="h5 d-block mb-1">{{ __('Student') }} :</strong>
                                                <address class="mb-0">{{ !empty($students->name) ? $students->name : '' }}<br>
                                                    {{ !empty($students->address) ? $students->address : '' }}
                                                    {{ !empty($students->city) ? $students->city . ' ,' : '' }}<br>
                                                    {{ !empty($students->state) ? $students->state . ' ,' : '' }}
                                                    {{ !empty($students->country) ? $students->country : '' }}<br>
                                                    {{ !empty($students->pin_code) ? $students->pin_code : '' }}
                                                    {{ !empty($students->mobile_no) ? $students->mobile_no : '' }}
                                                </address>
                                            </div>
                                            <div class="col-lg-2 col-sm-6">
                                                @if (!empty($students->name) && !empty($students->address))
                                                    <strong>{{ __('Status') }} :</strong>
                                                    @if ($drivinginvoice->status == 0)
                                                        <span class="badge fix_badge f-12 p-2 d-inline-block bg-info">
                                                            {{ __(\Workdo\DrivingSchool\Entities\DrivingInvoice::$statues[$drivinginvoice->status]) }}
                                                        </span>
                                                    @elseif($drivinginvoice->status == 1)
                                                        <span class="badge fix_badge f-12 p-2 d-inline-block bg-primary">
                                                            {{ __(\Workdo\DrivingSchool\Entities\DrivingInvoice::$statues[$drivinginvoice->status]) }}
                                                        </span>
                                                    @elseif($drivinginvoice->status == 2)
                                                        <span class="badge fix_badge f-12 p-2 d-inline-block bg-danger">
                                                            {{ __(\Workdo\DrivingSchool\Entities\DrivingInvoice::$statues[$drivinginvoice->status]) }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="float-lg-end qr-code">
                                                    {!! DNS2D::getBarcodeHTML(
                                                        route('drivinginvoice.show', \Illuminate\Support\Facades\Crypt::encrypt($drivinginvoice->id)),
                                                        'QRCODE',
                                                        2,
                                                        2,
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-summary mt-3">
                                        <div class="invoice-title border-1 border-bottom mb-3 pb-2">
                                            <h3 class="h4 mb-0">{{ __('Item Summary') }}</h3>
                                            <small>{{ __('All items here cannot be deleted.') }}</small>
                                        </div>
                                        <div class="table-responsive mt-2">
                                            <table class="table mb-0 table-striped">
                                                <thead>
                                                    <tr>
                                                        <th data-width="40" class="bg-primary text-white text-uppercase">#</th>
                                                        <th class="bg-primary text-white text-uppercase">{{ __('Class') }}</th>
                                                        <th class="bg-primary text-white text-uppercase">{{ __('Qunatity') }}</th>
                                                        <th class="bg-primary text-white text-uppercase">
                                                            {{ __('Fees') }}<br>
                                                        </th>
                                                        <th class="bg-primary text-white text-uppercase">
                                                            {{ __('Amount') }}<br>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $colspan = 3;
                                                    $i = 1;
                                                @endphp

                                                @foreach ($items as $key => $iteam)
                                                    @php
                                                        $invoiceclass = $iteam->driving_class_id;
                                                        $data = Workdo\DrivingSchool\Entities\DrivingClass::where(
                                                            'id',
                                                            $invoiceclass,
                                                        )->get();
                                                    @endphp

                                                    @foreach ($data as $key => $datas)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $datas->name }}</td>
                                                            <td>{{ $iteam->quantity }}</td>
                                                            <td>{{ $datas->fees }}</td>
                                                            <td>{{ $iteam->quantity * $datas->fees }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td width="20%"><b>{{ __('Total Amount') }}</b></td>
                                                        <td width="20%">
                                                            {{ currency_format_with_sym($drivinginvoice->getTotal()) }}
                                                        </td>
                                                    </tr>
                                                    @if (!empty($amount))
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-right"><b>{{ __('Paid') }}</b></td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($amount) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td class="text-right"><b>{{ __('Due') }}</b></td>
                                                        <td class="text-right">
                                                            {{ currency_format_with_sym($dueAmount) }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on("click", '.status_change', function() {
            var value = $(this).data('id');
            var id = {{ $drivinginvoice->id }};


            $.ajax({
                url: "{{ route('driving.invoice.changestatus') }}",
                method: "POST",
                data: {
                    id: id,
                    value: value,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.status == 'success') {
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
