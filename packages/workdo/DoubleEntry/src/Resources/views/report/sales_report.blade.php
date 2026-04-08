@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Report') }}
@endsection
@section('page-breadcrumb')
    {{__('Sales Report')}}
@endsection

@push('scripts')
    <script src="{{ asset('packages/workdo/DoubleEntry/src/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();
        function saveAsPDF() {
            var id1 = $('.nav-item .active').attr('href');
            if(id1 == '#item') {
                var printContents = document.getElementById('item').innerHTML;
            }
            else {
                var printContents = document.getElementById('customer').innerHTML;
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
        $(document).ready(function () {
            callback();
            function callback() {
                var start_date = $(".startDate").val();
                var end_date = $(".endDate").val();

                $('.start_date').val(start_date);
                $('.end_date').val(end_date);
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
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/DoubleEntry/src/Resources/assets/css/app.css') }}" id="main-style-link">
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
                            {{ Form::open(['route' => ['report.sales'], 'method' => 'GET', 'id' => 'report_bill_summary']) }}
                            <div class="row align-items-center justify-content-end">
                                <div class="col-xl-10">
                                <div class="row row-gap-4 justify-content-xl-end">
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
                                        <input type="hidden" name="view" value="horizontal">
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

                                            <a href="{{ route('report.sales') }}" class="btn btn-sm btn-danger "
                                                data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                                data-original-title="{{ __('Reset') }}">
                                                <span class="btn-inner--icon"><i
                                                        class="ti ti-trash-off text-white-off "></i></span>
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
                    <button class="nav-link active" id="profile-tab3" data-bs-toggle="pill" data-bs-target="#item"
                        type="button">{{ __('Sales by Item') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab4" data-bs-toggle="pill"
                        data-bs-target="#customer" type="button">{{ __('Sales by Customer') }}</button>
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
                                <div class="tab-pane fade fade show active" id="item" role="tabpanel" aria-labelledby="profile-tab3">
                                <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th width="33%"> {{__('Invoice Item')}}</th>
                                            <th width="33%"> {{__('Quantity Sold')}}</th>
                                            <th width="33%"> {{__('Amount')}}</th>
                                            <th class="text-end"> {{__('Average Price')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($invoiceItems as $invoiceItem)
                                                <tr>
                                                    <td>{{ $invoiceItem['name']}}</td>
                                                    <td>{{ $invoiceItem['quantity']}}</td>
                                                    <td>{{ currency_format_with_sym($invoiceItem['price']) }}</td>
                                                    <td>{{ currency_format_with_sym($invoiceItem['avg_price']) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('No Data Found.!') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade fade" id="customer" role="tabpanel" aria-labelledby="profile-tab3">
                                <div class="table-responsive">
                                    <table class="table table-flush" id="report-dataTable">
                                        <thead>
                                        <tr>
                                            <th width="33%"> {{__('Customer Name')}}</th>
                                            <th width="33%"> {{__('Invoice Count')}}</th>
                                            <th width="33%"> {{__('Sales')}}</th>
                                            <th class="text-end"> {{__('Sales With Tax')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($invoiceCustomers as $invoiceCustomer)
                                                <tr>
                                                    <td>{{ $invoiceCustomer['name'] }}</td>
                                                    <td>{{ $invoiceCustomer['invoice_count']}}</td>
                                                    <td>{{ currency_format_with_sym($invoiceCustomer['price']) }}</td>
                                                    <td>{{ currency_format_with_sym($invoiceCustomer['price'] + $invoiceCustomer['total_tax']) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('No Data Found.!') }}</td>
                                                </tr>
                                            @endforelse
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
