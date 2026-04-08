@extends('layouts.main')
@section('page-title')
    {{ __('Manage Receivable Reports') }}
@endsection
@section('page-breadcrumb')
    {{__('Receivable Reports')}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/DoubleEntry/src/Resources/assets/css/app.css') }}" id="main-style-link">
@endpush
@push('scripts')
    <script src="{{ asset('packages/workdo/DoubleEntry/src/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();
        function saveAsPDF() {
            var id1 = $('.nav-item .active').attr('href');
            if(id1 == '#customer_balance') {
                var printContents = document.getElementById('customer_balance').innerHTML;
            }
            else if(id1 == '#receivable_summary') {
                var printContents = document.getElementById('receivable_summary').innerHTML;
            }
            else if(id1 == '#receivable_details') {
                var printContents = document.getElementById('receivable_details').innerHTML;
            }             
            else if(id1 == '#aging_summary') {
                var printContents = document.getElementById('aging_summary').innerHTML;
            }             
            else if(id1 == '#aging_details') {
                var printContents = document.getElementById('aging_details').innerHTML;
            }             
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

    <script>
        $(document).ready(function() {
            $("#filter").click(function() {
                $("#show_filter").toggle();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            callback();

            function callback() {
                var start_date = $(".startDate").val();
                var end_date = $(".endDate").val();
                var customer = $(".getCustomer").val();

                $('.start_date').val(start_date);
                $('.end_date').val(end_date);
                $('.customer').val(customer);

            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var id1 = $('.nav-item .active').attr('href');
            $('.report').val(id1);

            $("ul.nav-pills > li > a").click(function() {
                var report = $(this).attr('href');
                $('.report').val(report);
            });
        });
    </script>
@endpush

@section('page-action')
    <div class="d-flex">
    <a href="#" onclick="saveAsPDF()" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip"
           title="{{ __('Print') }}"
           data-original-title="{{ __('Print') }}"><i class="ti ti-printer"></i></a>

        <button id="filter" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Filter') }}"><i class="ti ti-filter"></i></button>
    </div>
@endsection

@section('content')
    <div class="mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div id="multiCollapseExample1">
                    <div class="card" id="show_filter" style="display:none;">
                        <div class="card-body">
                            {{ Form::open(['route' => ['report.receivables'], 'method' => 'GET', 'id' => 'report_bill_summary']) }}
                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-10">
                                <div class="row row-gap-4 justify-content-xl-end">
                                       <div class="col-xl-3 col-md-4 col-sm-6 col-12">
                                            <div class="btn-box form-group mb-0">
                                                {{ Form::label('customer', __('Customer'),['class'=>'form-label'])}}
                                                {{ Form::select('customer',$customers,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select getCustomer','placeholder' => 'Select Customer')) }}

                                            </div>
                                        </div>
                                       <div class="col-xl-3 col-md-4 col-sm-6 col-12">
                                            <div class="btn-box form-group mb-0">
                                                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                                {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'startDate form-control']) }}
                                            </div>
                                        </div>

                                       <div class="col-xl-3 col-md-4 col-sm-6 col-12">
                                            <div class="btn-box form-group mb-0">
                                                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                                {{ Form::date('end_date', $filter['endDateRange'], ['class' => 'endDate form-control']) }}
                                            </div>
                                        </div>
                                        <input type="hidden" name="report" class="report">
                                    </div>
                                </div>
                                <div class="col-auto mt-4">
                                    <div class="row">
                                        <div class="col-auto">
                                            <a href="#" class="btn btn-sm btn-primary me-1"
                                               onclick="document.getElementById('report_bill_summary').submit(); return false;"
                                               data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                               data-original-title="{{ __('apply') }}">
                                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                            </a>

                                            <a href="{{ route('report.receivables') }}" class="btn btn-sm btn-danger "
                                               data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                               data-original-title="{{ __('Reset') }}">
                                                <span class="btn-inner--icon"><i
                                                        class="ti ti-trash-off text-white-off"></i></span>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-gap justify-content-between align-items-center mb-3">
        <div class="col-md-6">
            <ul class="nav nav-pills nav-fill cust-nav information-tab invoice-tab" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="receivable-tab1" data-bs-toggle="pill" data-bs-target="#customer_balance"
                        type="button">{{ __('Customer Balance') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="receivable-tab2" data-bs-toggle="pill"
                        data-bs-target="#receivable_summary" type="button">{{ __('Receivable Summary') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="receivable-tab3" data-bs-toggle="pill"
                        data-bs-target="#receivable_details" type="button">{{ __('Receivable Details') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="receivable-tab4" data-bs-toggle="pill"
                        data-bs-target="#aging_summary" type="button">{{ __('Aging Summary') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="receivable-tab5" data-bs-toggle="pill"
                        data-bs-target="#aging_details" type="button">{{ __('Aging Details') }}</button>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12" id="invoice-container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTabContent2">
                                <div class="tab-pane fade fade show active" id="customer_balance" role="tabpanel" aria-labelledby="receivable-tab1">
                                <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th width="33%"> {{ __('Customer Name') }}</th>
                                            <th width="33%"> {{ __('Invoice Balance') }}</th>
                                            <th width="33%"> {{ __('Available Credits') }}</th>
                                            <th class="text-end"> {{ __('Balance') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $mergedArray = [];

                                            foreach ($receivableCustomers as $item) {
                                                $name = $item['name'];

                                                if (!isset($mergedArray[$name])) {
                                                    $mergedArray[$name] = [
                                                        'name' => $name,
                                                        'price' => 0.0,
                                                        'pay_price' => 0.0,
                                                        'total_tax' => 0.0,
                                                        'credit_price' => 0.0,
                                                    ];
                                                }

                                                $mergedArray[$name]['price'] += floatval($item['price']);
                                                if ($item['pay_price'] !== null) {
                                                    $mergedArray[$name]['pay_price'] += floatval($item['pay_price']);
                                                }
                                                $mergedArray[$name]['total_tax'] += floatval($item['total_tax']);
                                                $mergedArray[$name]['credit_price'] += floatval($item['credit_price']);
                                            }
                                            $resultArray = array_values($mergedArray);
                                            $total = 0;
                                        @endphp
                                        @forelse ($resultArray as $receivableCustomer)
                                            <tr>
                                                @php
                                                    $customerBalance = $receivableCustomer['price'] + $receivableCustomer['total_tax'] - $receivableCustomer['pay_price'];
                                                    $balance = $customerBalance - $receivableCustomer['credit_price'];
                                                    $total += $balance;
                                                @endphp
                                                <td> {{ $receivableCustomer['name'] }}</td>
                                                <td> {{ currency_format_with_sym($customerBalance) }} </td>
                                                <td> {{ !empty($receivableCustomer['credit_price']) ? currency_format_with_sym($receivableCustomer['credit_price']) : currency_format_with_sym(0) }}</td>
                                                <td class="text-end"> {{ currency_format_with_sym($balance) }} </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">{{ __('No Data Found.!') }}</td>
                                            </tr>

                                        @endforelse
                                        @if ($receivableCustomers != [])
                                            <tr>
                                                <th>{{ __('Total') }}</th>
                                                <td></td>
                                                <td></td>
                                                <th class="text-end">{{ currency_format_with_sym($total) }}</th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade fade show" id="receivable_summary" role="tabpanel"
                                     aria-labelledby="receivable-tab2">
                                     <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Customer Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Transaction') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Transaction Type') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $total = 0;
                                            $totalAmount = 0;

                                            function compare($a, $b)
                                            {
                                                return strtotime($b['issue_date']) - strtotime($a['issue_date']);
                                            }
                                            usort($receivableSummaries, 'compare');
                                        @endphp
                                        @forelse ($receivableSummaries as $receivableSummary)
                                            <tr>
                                                @php
                                                    if ($receivableSummary['invoice']) {
                                                        $receivableBalance = $receivableSummary['price'] + $receivableSummary['total_tax'];
                                                    } else {
                                                        $receivableBalance = -$receivableSummary['price'];
                                                    }
                                                    $pay_price = $receivableSummary['pay_price'] != null ? $receivableSummary['pay_price'] : 0;
                                                    $balance = $receivableBalance - $pay_price;
                                                    $total += $balance;
                                                    $totalAmount += $receivableBalance;
                                                @endphp
                                                <td> {!! $receivableSummary['name'] ? $receivableSummary['name'] : '<span class="p-2 px-3">-</span>' !!}</td>
                                                <td> {{ $receivableSummary['issue_date'] }}</td>
                                                @if ($receivableSummary['invoice'])
                                                    <td> {{ \App\Models\Invoice::invoiceNumberFormat($receivableSummary['invoice']) }}
                                                @else
                                                    <td>{{ __('Credit Note') }}</td>
                                                    @endif
                                                    </td>
                                                    <td>
                                                        @if ($receivableSummary['status'] == 0)
                                                            <span
                                                                class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableSummary['status']]) }}</span>
                                                        @elseif($receivableSummary['status'] == 1)
                                                            <span
                                                                class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableSummary['status']]) }}</span>
                                                        @elseif($receivableSummary['status'] == 2)
                                                            <span
                                                                class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableSummary['status']]) }}</span>
                                                        @elseif($receivableSummary['status'] == 3)
                                                            <span
                                                                class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableSummary['status']]) }}</span>
                                                        @elseif($receivableSummary['status'] == 4)
                                                            <span
                                                                class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableSummary['status']]) }}</span>
                                                        @else
                                                            <span class="p-2 px-3">-</span>
                                                        @endif
                                                    </td>
                                                    @if ($receivableSummary['invoice'])
                                                        <td> {{ __('Invoice') }}
                                                    @else
                                                        <td>{{ __('Credit Note') }}</td>
                                                    @endif
                                                    <td> {{ currency_format_with_sym($receivableBalance) }} </td>
                                                    <td> {{ currency_format_with_sym($balance) }} </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">{{ __('No Data Found.!') }}</td>
                                            </tr>

                                        @endforelse
                                        @if ($receivableSummaries != [])
                                            <tr>
                                                <th>{{ __('Total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($totalAmount) }}</th>
                                                <th>{{ currency_format_with_sym($total) }}</th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade fade show" id="receivable_details" role="tabpanel"
                                     aria-labelledby="receivable-tab3">
                                     <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Customer Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Transaction') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Transaction Type') }}</th>
                                            <th>{{ __('Item Name') }}</th>
                                            <th>{{ __('Quantity Ordered') }}</th>
                                            <th>{{ __('Item Price') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $total = 0;
                                            $totalQuantity = 0;

                                            function compares($a, $b)
                                            {
                                                return strtotime($b['issue_date']) - strtotime($a['issue_date']);
                                            }
                                            usort($receivableDetails, 'compares');
                                        @endphp
                                        @forelse ($receivableDetails as $receivableDetail)
                                            <tr>
                                                @php
                                                    if ($receivableDetail['invoice']) {
                                                        $receivableBalance = $receivableDetail['price'];
                                                    } else {
                                                        $receivableBalance = - $receivableDetail['price'];
                                                    }
                                                    if ($receivableDetail['invoice']) {
                                                        $quantity = $receivableDetail['quantity'];
                                                    } else {
                                                        $quantity = 0;
                                                    }

                                                    if ($receivableDetail['invoice']) {
                                                        $itemTotal = $receivableBalance * $receivableDetail['quantity'];
                                                    } else {
                                                        $itemTotal = - $receivableDetail['price'];
                                                    }

                                                    $total += $itemTotal;
                                                    $totalQuantity += $quantity;
                                                @endphp
                                                <td> {!! $receivableDetail['name'] ? $receivableDetail['name'] : '<span class="p-2 px-3">-</span>' !!}</td>
                                                <td> {{ $receivableDetail['issue_date'] }}</td>
                                                @if ( $receivableDetail['invoice'])
                                                    <td> {{\App\Models\Invoice::invoiceNumberFormat($receivableDetail['invoice']) }}
                                                    </td>
                                                @else
                                                    <td>{{ __('Credit Note') }}</td>
                                                    @endif
                                                    </td>
                                                    <td>
                                                        @if ($receivableDetail['status'] == 0)
                                                            <span
                                                                class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableDetail['status']]) }}</span>
                                                        @elseif($receivableDetail['status'] == 1)
                                                            <span
                                                                class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableDetail['status']]) }}</span>
                                                        @elseif($receivableDetail['status'] == 2)
                                                            <span
                                                                class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableDetail['status']]) }}</span>
                                                        @elseif($receivableDetail['status'] == 3)
                                                            <span
                                                                class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableDetail['status']]) }}</span>
                                                        @elseif($receivableDetail['status'] == 4)
                                                            <span
                                                                class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$receivableDetail['status']]) }}</span>
                                                        @else
                                                            <span class="p-2 px-3">-</span>
                                                        @endif
                                                    </td>
                                                    @if ($receivableDetail['invoice'])
                                                        <td> {{ __('Invoice') }}</td>
                                                    @else
                                                        <td>{{ __('Credit Note') }}</td>
                                                    @endif
                                                    <td>{{ $receivableDetail['product_name'] }}</td>
                                                    <td> {{ $quantity }}</td>
                                                    <td>{{ currency_format_with_sym($receivableBalance) }}</td>
                                                    <td>{{ currency_format_with_sym($itemTotal) }}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">{{ __('No Data Found.!') }}</td>
                                            </tr>
                                        @endforelse
                                        @if ($receivableSummaries != [])
                                            <tr>
                                                <th>{{ __('Total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ $totalQuantity }}</th>
                                                <th>-</th>
                                                <th>{{ currency_format_with_sym($total) }}</th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade fade show" id="aging_summary" role="tabpanel" aria-labelledby="receivable-tab4">
                                <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Customer Name') }}</th>
                                            <th>{{ __('Current') }}</th>
                                            <th>{{ __('1-15 DAYS') }}</th>
                                            <th>{{ __('16-30 DAYS') }}</th>
                                            <th>{{ __('31-45 DAYS') }}</th>
                                            <th>{{ __('> 45 DAYS') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $currentTotal = 0;
                                            $days15 = 0;
                                            $days30 = 0;
                                            $days45 = 0;
                                            $daysMore45 = 0;
                                            $total = 0;

                                        @endphp
                                        @forelse ($agingSummaries as $key => $agingSummary)
                                            <tr>
                                                <td> {{ $key }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['current']) }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['1_15_days']) }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['16_30_days']) }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['31_45_days']) }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['greater_than_45_days']) }}</td>
                                                <td>{{ currency_format_with_sym($agingSummary['total_due']) }}</td>
                                            </tr>

                                            @php
                                                $currentTotal += $agingSummary['current'];
                                                $days15 += $agingSummary['1_15_days'];
                                                $days30 += $agingSummary['16_30_days'];
                                                $days45 += $agingSummary['31_45_days'];
                                                $daysMore45 += $agingSummary['greater_than_45_days'];
                                                $total += $agingSummary['total_due'];

                                            @endphp
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">{{ __('No Data Found.!') }}</td>
                                            </tr>
                                        @endforelse
                                        @if ($agingSummaries != [])
                                            <tr>
                                                <th>{{ __('Total') }}</th>
                                                <th>{{ currency_format_with_sym($currentTotal) }}</th>
                                                <th>{{ currency_format_with_sym($days15) }}</th>
                                                <th>{{ currency_format_with_sym($days30) }}</th>
                                                <th>{{ currency_format_with_sym($days45) }}</th>
                                                <th>{{ currency_format_with_sym($daysMore45) }}</th>
                                                <th>{{ currency_format_with_sym($total) }}</th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade fade show" id="aging_details" role="tabpanel"
                                     aria-labelledby="receivable-tab5">
                                     <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Transaction') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Customer Name') }}</th>
                                            <th>{{ __('Age') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Balance Due') }}</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @php
                                            $currentTotal = 0;
                                            $currentDue = 0;
                                            $days15Total = 0;
                                            $days15Due = 0;

                                            $days30Total = 0;
                                            $days30Due = 0;

                                            $days45Total = 0;
                                            $days45Due = 0;

                                            $daysMore45Total = 0;
                                            $daysMore45Due = 0;

                                            $total = 0;
                                        @endphp

                                        @if ($moreThan45 != [])
                                            <tr>
                                                <th>{{ __(' > 45 Days') }}</th>
                                            </tr>
                                        @endif
                                        @foreach ($moreThan45 as $value)
                                            @php
                                                $daysMore45Total += $value['total_price'];
                                                $daysMore45Due += $value['balance_due'];
                                            @endphp
                                            <tr>
                                                <td>{{ $value['due_date'] }}</td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($value['invoice_id']) }}
                                                </td>
                                                <td>{{ __('Invoice') }}</td>
                                                <td>
                                                    @if ($value['status'] == 0)
                                                        <span
                                                            class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$value['status']]) }}</span>
                                                    @elseif($value['status'] == 1)
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$value['status']]) }}</span>
                                                    @elseif($value['status'] == 2)
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$value['status']]) }}</span>
                                                    @elseif($value['status'] == 3)
                                                        <span
                                                            class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$value['status']]) }}</span>
                                                    @elseif($value['status'] == 4)
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$value['status']]) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value['name'] }}</td>
                                                <td> {{ $value['age'] . __(' Days') }} </td>
                                                <td>{{ currency_format_with_sym($value['total_price']) }}</td>
                                                <td>{{ currency_format_with_sym($value['balance_due']) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($moreThan45 != [])
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($daysMore45Total) }}</th>
                                                <th>{{ currency_format_with_sym($daysMore45Due) }}</th>
                                            </tr>
                                        @endif

                                        @if ($days31to45 != [])
                                            <tr>
                                                <th>{{ __(' 31 to 45 Days') }}</th>
                                            </tr>
                                        @endif
                                        @foreach ($days31to45 as $day31to45)
                                            @php
                                                $days45Total += $day31to45['total_price'];
                                                $days45Due += $day31to45['balance_due'];
                                            @endphp
                                            <tr>
                                                <td>{{ $day31to45['due_date'] }}</td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($day31to45['invoice_id']) }}
                                                </td>
                                                <td>{{ __('Invoice') }}</td>
                                                <td>
                                                    @if ($day31to45['status'] == 0)
                                                        <span
                                                            class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day31to45['status']]) }}</span>
                                                    @elseif($day31to45['status'] == 1)
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day31to45['status']]) }}</span>
                                                    @elseif($day31to45['status'] == 2)
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day31to45['status']]) }}</span>
                                                    @elseif($day31to45['status'] == 3)
                                                        <span
                                                            class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day31to45['status']]) }}</span>
                                                    @elseif($day31to45['status'] == 4)
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day31to45['status']]) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $day31to45['name'] }}</td>
                                                <td> {{ $day31to45['age'] . __(' Days') }} </td>
                                                <td>{{ currency_format_with_sym($day31to45['total_price']) }}</td>
                                                <td>{{ currency_format_with_sym($day31to45['balance_due']) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($days31to45 != [])
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($days45Total) }}</th>
                                                <th>{{ currency_format_with_sym($days45Due) }}</th>
                                            </tr>
                                        @endif

                                        @if ($days16to30 != [])
                                            <tr>
                                                <th>{{ __(' 16 to 30 Days') }}</th>
                                            </tr>
                                        @endif
                                        @foreach ($days16to30 as $day16to30)
                                            @php
                                                $days30Total += $day16to30['total_price'];
                                                $days30Due += $day16to30['balance_due'];
                                            @endphp
                                            <tr>
                                                <td>{{ $day16to30['due_date'] }}</td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($day16to30['invoice_id']) }}
                                                </td>
                                                <td>{{ __('Invoice') }}</td>
                                                <td>
                                                    @if ($day16to30['status'] == 0)
                                                        <span
                                                            class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day16to30['status']]) }}</span>
                                                    @elseif($day16to30['status'] == 1)
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day16to30['status']]) }}</span>
                                                    @elseif($day16to30['status'] == 2)
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day16to30['status']]) }}</span>
                                                    @elseif($day16to30['status'] == 3)
                                                        <span
                                                            class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day16to30['status']]) }}</span>
                                                    @elseif($day16to30['status'] == 4)
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day16to30['status']]) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $day16to30['name'] }}</td>
                                                <td> {{ $day16to30['age'] . __(' Days') }} </td>
                                                <td>{{ currency_format_with_sym($day16to30['total_price']) }}</td>
                                                <td>{{ currency_format_with_sym($day16to30['balance_due']) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($days16to30 != [])
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($days30Total) }}</th>
                                                <th>{{ currency_format_with_sym($days30Due) }}</th>
                                            </tr>
                                        @endif

                                        @if ($days1to15 != [])
                                            <tr>
                                                <th>{{ __(' 1 to 15 Days') }}</th>
                                            </tr>
                                        @endif
                                        @foreach ($days1to15 as $day1to15)
                                            @php
                                                $days15Total += $day1to15['total_price'];
                                                $days15Due += $day1to15['balance_due'];
                                            @endphp
                                            <tr>
                                                <td>{{ $day1to15['due_date'] }}</td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($day1to15['invoice_id']) }}
                                                </td>
                                                <td>{{ __('Invoice') }}</td>
                                                <td>
                                                    @if ($day1to15['status'] == 0)
                                                        <span
                                                            class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day1to15['status']]) }}</span>
                                                    @elseif($day1to15['status'] == 1)
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day1to15['status']]) }}</span>
                                                    @elseif($day1to15['status'] == 2)
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day1to15['status']]) }}</span>
                                                    @elseif($day1to15['status'] == 3)
                                                        <span
                                                            class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day1to15['status']]) }}</span>
                                                    @elseif($day1to15['status'] == 4)
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$day1to15['status']]) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $day1to15['name'] }}</td>
                                                <td> {{ $day1to15['age'] . __(' Days') }} </td>
                                                <td>{{ currency_format_with_sym($day1to15['total_price']) }}</td>
                                                <td>{{ currency_format_with_sym($day1to15['balance_due']) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($days1to15 != [])
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($days15Total) }}</th>
                                                <th>{{ currency_format_with_sym($days15Due) }}</th>
                                            </tr>
                                        @endif

                                        @if ($currents != [])
                                            <tr>
                                                <th>{{ __('Current') }}</th>
                                            </tr>
                                        @endif
                                        @foreach ($currents as $current)
                                            @php
                                                $currentTotal += $current['total_price'];
                                                $currentDue += $current['balance_due'];
                                            @endphp
                                            <tr>
                                                <td>{{ $current['due_date'] }}</td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($current['invoice_id']) }}
                                                </td>
                                                <td>{{ __('Invoice') }}</td>
                                                <td>
                                                    @if ($current['status'] == 0)
                                                        <span
                                                            class="status_badge badge bg-secondary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$current['status']]) }}</span>
                                                    @elseif($current['status'] == 1)
                                                        <span
                                                            class="status_badge badge bg-warning p-2 px-3">{{ __(\App\Models\Invoice::$statues[$current['status']]) }}</span>
                                                    @elseif($current['status'] == 2)
                                                        <span
                                                            class="status_badge badge bg-danger p-2 px-3">{{ __(\App\Models\Invoice::$statues[$current['status']]) }}</span>
                                                    @elseif($current['status'] == 3)
                                                        <span
                                                            class="status_badge badge bg-info p-2 px-3">{{ __(\App\Models\Invoice::$statues[$current['status']]) }}</span>
                                                    @elseif($current['status'] == 4)
                                                        <span
                                                            class="status_badge badge bg-primary p-2 px-3">{{ __(\App\Models\Invoice::$statues[$current['status']]) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $current['name'] }}</td>
                                                <td> - </td>
                                                <td>{{ currency_format_with_sym($current['total_price']) }}</td>
                                                <td>{{ currency_format_with_sym($current['balance_due']) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($currents != [])
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($currentTotal) }}</th>
                                                <th>{{ currency_format_with_sym($currentDue) }}</th>
                                            </tr>
                                        @endif

                                        @if ($currents != [] || $days1to15 != [] || $days16to30 != [] || $days31to45 != [] || $moreThan45 != [])
                                            <tr>
                                                <th>{{ __('Total') }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ currency_format_with_sym($currentTotal + $days15Total + $days30Total + $days45Total + $daysMore45Total) }}
                                                </th>
                                                <th>{{ currency_format_with_sym($currentDue + $days15Due + $days30Due + $days45Due + $daysMore45Due) }}
                                                </th>
                                            </tr>
                                        @endif
                                        </tbody>
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
@endsection
