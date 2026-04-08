@extends('layouts.main')

@section('page-title')
    {{ __('USSD Dashboard') }}
@endsection

@section('page-breadcrumb')
    {{ __('USSD Statistics') }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .transaction-row {
        display: flex;
        align-items: center;        
        justify-content: space-between; 
        width: 100%;
    }
    .dash-white-box {
        background: #fff;
        border-color: blue;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        border-radius: 15px;
        padding: 20px;
    }
    .dash-box-title {
        font-weight: 500;
        color: #000;
        font-size: 16px;
    }
    .fw-700 {
        font-weight: 700 !important;
    }
    .mt-15 {
        margin-top: 15px !important;
    }
    .dash-xs-title {
        font-size: 10px;
        font-weight: 600;
    }
    .dash-price {
        font-size: 36px;
        font-weight: 600;
        color: #000;
    }
    .dash-price span {
        font-size: 14px;
        color: #ccc;
    }
    .v-top {
        vertical-align: 15px;
        padding-right: 3px;
    }
    .mt-20 {
        margin-top: 20px !important;
    }
    .mb-15 {
        margin-bottom: 15px !important;
    }
    .mt-30 {
        margin-top: 30px !important;
    }
    .mt-50 {
        margin-top: 50px !important;
    }
    .d-flex {
        display: flex !important;
    }
    .align-items-center {
        align-items: center;
    }
    .ml-auto {
        margin-left: auto !important;
    }
    .circle-box {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 12px;
        text-align: center;
        line-height: 25px;
        background: #e5e5e5;
        color: #fff;
        font-weight: 600;
        border: solid 5px #e5e5e5;
        box-shadow: 0 0 10px rgb(0 0 0 / 10%);
    }
    .small-text {
        font-size: 12px;
        color: #999;
    }
</style>
@endpush

@section('content')
{{-- @if(isset($data) && !empty($data))
    <div class="alert alert-info">
        <strong>API Debug Data:</strong>
        <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    </div>
@endif --}}
{{-- Start of Date Filter for Charts --}}
<div class="row mb-4">
    <div class="col-lg-6">
        <label for="dateFilter">{{ __('Select Date Range for Charts:') }}</label>
        <select id="dateFilter" class="form-control">
            <option value="today" selected>{{ __('Today') }}</option>
            <option value="last7days">{{ __('Last 7 Days') }}</option>
            <option value="last30days">{{ __('Last 30 Days') }}</option>
            <option value="thismonth">{{ __('This Month') }}</option>
            <option value="thisyear">{{ __('This Year') }}</option>
        </select>
    </div>
</div>

{{-- Start of the charts --}}
<div class="row mt-4 mb-4">
    {{-- Customers by Date --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Customers by Date') }}</h5>
            </div>
            <div class="card-body">
                <div style="width: 100%; height: 300px;">
                    <canvas id="customersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions by Date --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Transactions by Date') }}</h5>
            </div>
            <div class="card-body">
                <div style="width: 100%; height: 300px;">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Dashboard Content --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('USSD Dashboard') }}</h4>
            </div>
            <div class="card-body" style="background-color:#f6f6f6;">
                
                {{-- Summary Statistics --}}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Total Members') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="customer-total">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Active Contributors') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="active-contributors-total">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Revenue This Week') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="transaction-week">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Highest Collection Day') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="highest-day">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Highest Collection Month') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="highest-month">0</b>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Total Revenue') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="total-revenue">0 GHS</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Revenue Today') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="transaction-today">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Revenue This Month') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="transaction-month">0</b>
                                </div>
                            </div>
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Top 5 Contributors') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <div style="font-size:16px;" class="top-contributors">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Date Range Picker Filter --}}
                <div class="row mb-3 align-items-center mt-4">
                    <div class="col-md-4">
                        <label for="dateRangePicker" class="form-label">{{ __('Filter Date') }}</label>
                        <div id="dateRangePicker" class="form-control d-flex align-items-center" style="cursor: pointer;">
                            <i class="ti ti-calendar me-2"></i>
                            <span>Select Date Range</span>
                            <i class="ti ti-chevron-down ms-auto"></i>
                        </div>
                    </div>
                </div>

                {{-- Filtered Statistics --}}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Total Revenue') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="transaction-amount-balance">0</b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Customers') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="customer-count">0</b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Active Contributors') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="active-contributors">0</b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dash-white-box mb-15">
                                <div class="dash-box-title"><span class="fw-700">{{ __('Total Balance') }}</span></div>
                                <div class="dash-price">
                                    <span class="v-top"></span>
                                    <b style="font-size:28px;" class="transaction-amount">0</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>


            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Pass data from the backend (simulated with empty data for API integration)
    const customersData = @json($data['customers_data'] ?? []);
    const transactionsData = @json($data['transactions_data'] ?? []);
    const activeCustomersData = @json($data['active_customers_data'] ?? []);

    // Function to parse a date string into a JS Date object
    function parseDate(dateString) {
        const [year, month, day] = dateString.split('-');
        return new Date(year, month - 1, day);
    }

    // Function to get day name from date string
    function getDayName(dateString) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const date = parseDate(dateString);
        return days[date.getDay()];
    }

    // Function to get month name from date string
    function getMonthName(dateString) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const date = parseDate(dateString);
        return months[date.getMonth()];
    }

    // Function to group data by weeks
    function groupDataByWeeks(data, field = 'count') {
        const weeks = {
            'Week 1': {total: 0, count: 0},
            'Week 2': {total: 0, count: 0},
            'Week 3': {total: 0, count: 0},
            'Week 4': {total: 0, count: 0}
        };
        
        data.forEach((item, index) => {
            const weekNum = Math.floor(index / 7) + 1;
            if (weekNum <= 4) {
                weeks[`Week ${weekNum}`].total += (item.total || 0);
                weeks[`Week ${weekNum}`].count += (item.count || 0);
            }
        });
        
        return Object.keys(weeks).map(week => weeks[week][field]);
    }

    // Function to group data by months
    function groupDataByMonths(data, field = 'count') {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const monthsData = {};
        
        months.forEach(month => {
            monthsData[month] = 0;
        });
        
        data.forEach(item => {
            const month = getMonthName(item.date);
            monthsData[month] += (item[field] || 0);
        });
        
        return Object.values(monthsData);
    }

    // Function to filter data based on the selected date range
    function filterDataByDate(data, range) {
        const today = new Date();
        let startDate = new Date(today);
        let endDate = new Date(today);

        switch (range) {
            case 'today':
                startDate.setHours(0, 0, 0, 0);
                endDate.setHours(23, 59, 59, 999);
                break;
            case 'last7days':
                startDate.setDate(today.getDate() - 7);
                break;
            case 'last30days':
                startDate.setDate(today.getDate() - 30);
                break;
            case 'thismonth':
                startDate.setDate(1);
                break;
            case 'thisyear':
                startDate.setMonth(0, 1);
                break;
            default:
                return data;
        }

        return data.filter(item => {
            const itemDate = parseDate(item.date);
            return itemDate >= startDate && itemDate <= endDate;
        });
    }

    // Function to get chart labels based on range
    function getChartLabels(filteredData, range) {
        if (range === 'today') {
            return filteredData.length > 0 ? filteredData.map(d => d.date) : ['Today'];
        } else if (range === 'last7days') {
            return filteredData.map(d => getDayName(d.date));
        } else if (range === 'last30days' || range === 'thismonth') {
            return ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        } else if (range === 'thisyear') {
            return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        }
        return filteredData.map(d => d.date);
    }

    // Function to get chart data based on range
    function getChartData(filteredData, range, field = 'count') {
        if (range === 'today') {
            return filteredData.length > 0 ? filteredData.map(d => d[field]) : [0];
        } else if (range === 'last7days') {
            return filteredData.map(d => d[field]);
        } else if (range === 'last30days' || range === 'thismonth') {
            return groupDataByWeeks(filteredData, field);
        } else if (range === 'thisyear') {
            return groupDataByMonths(filteredData, field);
        }
        return filteredData.map(d => d[field]);
    }

    // Filter data for today by default
    const initialRange = 'today';
    const initialCustomersData = filterDataByDate(customersData, initialRange);
    const initialTransactionsData = filterDataByDate(transactionsData, initialRange);
    const initialActiveCustomersData = filterDataByDate(activeCustomersData, initialRange);

    // Initialize charts
    const ctx1 = document.getElementById('customersChart').getContext('2d');
    const customersChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: getChartLabels(initialCustomersData, initialRange),
            datasets: [
                {
                    label: 'New Customers',
                    data: getChartData(initialCustomersData, initialRange, 'count'),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Active Customers (Made Payment)',
                    data: getChartData(initialActiveCustomersData, initialRange, 'count'),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctx2 = document.getElementById('transactionsChart').getContext('2d');
    const transactionsChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: getChartLabels(initialTransactionsData, initialRange),
            datasets: [{
                label: 'Total Transactions',
                data: getChartData(initialTransactionsData, initialRange, 'total'),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Event listener for date filter change
    document.getElementById('dateFilter').addEventListener('change', () => {
        const selectedRange = document.getElementById('dateFilter').value;

        const filteredCustomersData = filterDataByDate(customersData, selectedRange);
        const filteredTransactionsData = filterDataByDate(transactionsData, selectedRange);
        const filteredActiveCustomersData = filterDataByDate(activeCustomersData, selectedRange);

        customersChart.data.labels = getChartLabels(filteredCustomersData, selectedRange);
        customersChart.data.datasets[0].data = getChartData(filteredCustomersData, selectedRange, 'count');
        customersChart.data.datasets[1].data = getChartData(filteredActiveCustomersData, selectedRange, 'count');
        customersChart.update();

        transactionsChart.data.labels = getChartLabels(filteredTransactionsData, selectedRange);
        transactionsChart.data.datasets[0].data = getChartData(filteredTransactionsData, selectedRange, 'total');
        transactionsChart.update();
    });
</script>

<script>
    let activeFilter = '';
    $(document).ready(function() {
        // Initialize Date Range Picker
        $('#dateRangePicker').daterangepicker({
            opens: 'right',
            startDate: moment(),
            endDate: moment(),  
            ranges: {
                'Today': [moment(), moment()],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            },
            locale: {
                format: 'MMMM D, YYYY'
            }
        }, function(start, end, label) {
            $('#dateRangePicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            let activeFilter;
            switch (label) {
                case 'Today':
                    activeFilter = 'today';
                    break;
                case 'Last 7 Days':
                    activeFilter = 'last_7_days';
                    break;
                case 'Last 30 Days':
                    activeFilter = 'last_30_days';
                    break;
                case 'This Month':
                    activeFilter = 'this_month';
                    break;
                case 'Last Month':
                    activeFilter = 'last_month';
                    break;
                default:
                    activeFilter = 'custom';
                    break;
            }

            fetchTransactionsCount(activeFilter, start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });
        
        $('#dateRangePicker span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        function formatChange(change) {
            if (change > 0) {
                return `<span style="color:green;">▲ ${change.toFixed(2)}%</span>`;
            } else if (change < 0) {
                return `<span style="color:red;">▼ ${Math.abs(change).toFixed(2)}%</span>`;
            } else {
                return `<span style="color:gray;">0%</span>`;
            }
        }

        function fetchTransactionsCount(filter='today', start = null, end = null){
            $.ajax({
                url: '{{ route("ussd.transactions") }}',
                type: 'GET',
                data: {
                    filter: filter,
                    start_date: start,
                    end_date: end
                },
                success: function(response) {
                    // Update all dashboard elements
                    $('.transaction-amount').html(response.total_amount.toFixed(2) + ' GHS');
                    $('.transaction-amount-final').html(response.total_amount.toFixed(2) + ' GHS');
                    $('.transaction-amount-balance').html(response.total_amount_balance.toFixed(2) + ' GHS');
                    $('.customer-count').html(response.customer_count + ' Count');
                    $('.customer-total').html(response.total_customers + ' Count');
                    $('.active-contributors-total').html(response.active_customers + ' Count');
                    $('.active-contributors').html(response.active_customers_count + ' Count');
                    $('.total-revenue').html(response.query_for_total_revenue.toFixed(2) + ' GHS');
                    $('.transaction-week').html(response.total_week.toFixed(2) + ' GHS ' + formatChange(response.week_change) + ' <span class="small-text">(last week)</span>');
                    $('.transaction-today').html(response.total_today.toFixed(2) + ' GHS ' + formatChange(response.today_change) + ' <span class="small-text">(yesterday)</span>');
                    $('.transaction-month').html(response.total_month.toFixed(2) + ' GHS ' + formatChange(response.month_change) + ' <span class="small-text">(last month)</span>');
                    $('.org_dues').html(response.orgDuesCalculated + ' GHS');
                    $('.service_charge').html(response.totalServiceCharge.toFixed(2) + ' GHS');
                    $('.members_welfare').html(response.membersWelfareCalculated.toFixed(2) + ' GHS');
                    
                    // Display Top 5 Contributors
                    if (response.top_contributors && response.top_contributors.length > 0) {
                        let topContributorsHtml = '<ul style="list-style:none; padding:0; margin:0;">';
                        response.top_contributors.forEach((contributor, index) => {
                            topContributorsHtml += `<li style="margin-bottom:8px;">
                                <strong>${index + 1}. ${contributor.name}</strong><br>
                                <span style="color:#666;">${parseFloat(contributor.total_amount).toFixed(2)} GHS</span>
                            </li>`;
                        });
                        topContributorsHtml += '</ul>';
                        $('.top-contributors').html(topContributorsHtml);
                    } else {
                        $('.top-contributors').html('No contributors found');
                    }
                    
                    // Highest Collection Stats
                    if (response.highest_collection_day) {
                        $('.highest-day').html(
                            response.highest_collection_day.day + 
                            ' (' + parseFloat(response.highest_collection_day.total_amount).toFixed(2) + ' GHS)'
                        );
                    }
                    
                    if (response.highest_collection_month) {
                        $('.highest-month').html(
                            response.highest_collection_month.month + 
                            ' (' + parseFloat(response.highest_collection_month.total_amount).toFixed(2) + ' GHS)'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("An error occurred: " + error);
                    toastrs('Error', 'Failed to fetch data', 'error');
                }
            });
        }

        // Load data with 'today' filter on page load
        fetchTransactionsCount('today');
    });
</script>
@endpush