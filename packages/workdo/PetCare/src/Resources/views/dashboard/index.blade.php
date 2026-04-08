@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('PetCare') }}
@endsection

@section('content')
    <div class="row row-gap mb-4 ">
        <div class="col-xl-6 col-12">
            <div class="dashboard-card">
                <img src="{{ asset('assets/images/layer.png') }}" class="dashboard-card-layer" alt="layer">
                <div class="card-inner">
                    <div class="card-content">
                        <h2>{{ Auth::user()->ActiveWorkspaceName() }}</h2>
                        <p>{{ __('Effortlessly manage services, packages, adoptions, and appointments with a streamlined pet care solution.') }}
                        </p>
                        @php
                            $user = Auth::user();
                        @endphp

                        <div class="btn-wrp d-flex gap-3">
                            @if (\Auth::user()->type == 'company')
                                <a href="javascript:" class="btn btn-primary d-flex align-items-center gap-1 cp_link"
                                    tabindex="0" data-link="{{ route('petcare.frontend', [$workspace->slug]) }}"
                                    data-bs-whatever="{{ __('Booking Link') }}" data-bs-toggle="tooltip"
                                    data-bs-original-title="{{ __('Create Booking') }}">
                                    <i class="ti ti-link text-white"></i>
                                    <span> {{ __('PetCare Booking Link') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-icon  d-flex align-items-center justify-content-center">
                        <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 256 253"
                            enable-background="new 0 0 256 253" xml:space="preserve">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M126.544,211.321c2.151,3.716,0.978,8.605-2.738,10.854c-3.814,2.249-8.605,0.978-10.854-2.738l-14.277-24.055v20.242 c0,4.4-3.52,7.921-7.921,7.921c-4.4,0-7.921-3.52-7.921-7.921v-51.827l-10.756-6.161c-2.249-1.271-3.031-4.107-1.76-6.258 c1.271-2.249,4.107-3.031,6.258-1.76l6.845,3.911h53l25.033,12.223v19.851c0,1.76-0.293,3.325-2.64,3.325v26.598 c0,4.4-3.52,7.921-7.921,7.921c-4.4,0-7.921-3.52-7.921-7.921v-26.598h-29.825L126.544,211.321z M138.865,152.063l22.491,10.659 v-8.116h18.286l6.649-14.472l-47.426-22.589V152.063z M2,69c0,13.678,9.625,25.302,22,29.576V233H2v18h252v-18h-22V98.554 c12.89-3.945,21.699-15.396,22-29.554v-8H2V69z M65.29,68.346c0,6.477,6.755,31.47,31.727,31.47 c21.689,0,31.202-19.615,31.202-31.47c0,11.052,7.41,31.447,31.464,31.447c21.733,0,31.363-20.999,31.363-31.447 c0,14.425,9.726,26.416,22.954,30.154V233H42V98.594C55.402,94.966,65.29,82.895,65.29,68.346z M222.832,22H223V2H34v20L2,54h252 L222.832,22z">
                                </path>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="row dashboard-wrp">
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-paw text-danger"></i>
                                </div>
                                <a href="{{ route('pet.services.index') }}">
                                    <h3 class="mt-3 mb-0 text-danger">{{ __('Total Service') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $data['totalServices'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <a href="{{ route('pet.grooming.packages.index') }}">
                                    <h3 class="mt-3 mb-0">{{ __('Total Package') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $data['totalPackages'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-calendar-time"></i>
                                </div>
                                <a href="{{ route('pet.appointments.index') }}">
                                    <h3 class="mt-3 mb-0">{{ __('Total Pet Appointment') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $data['totalAppointments'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <a href="{{ route('pet.adoption.index') }}">
                                    <h3 class="mt-3 mb-0">{{ __('Total Pet Adoption') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $data['totalAdoptions'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Appointments by Status') }}</h5>
                </div>
                <div class="card-body">
                    <div id="appointment-status-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                class=""></i></a>
                    </div>
                    <h5>{{ __('Adoption Request by Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Chart -->
                        <div class="col-sm-8">
                            <div id="adoption-request-chart"></div>
                        </div>

                        <!-- Label indicators -->
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <span class="d-flex align-items-center">
                                        <i class="f-10 lh-1 fas fa-circle" style="color:#ffa21d"></i>
                                        <span class="ms-2 text-sm">{{ __('Pending') }}</span>
                                    </span>
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="d-flex align-items-center">
                                        <i class="f-10 lh-1 fas fa-circle" style="color:#6fd943"></i>
                                        <span class="ms-2 text-sm">{{ __('Approved') }}</span>
                                    </span>
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="d-flex align-items-center">
                                        <i class="f-10 lh-1 fas fa-circle" style="color:#FF3A6E"></i>
                                        <span class="ms-2 text-sm">{{ __('Rejected') }}</span>
                                    </span>
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="d-flex align-items-center">
                                        <i class="f-10 lh-1 fas fa-circle" style="color:#007bff"></i>
                                        <span class="ms-2 text-sm">{{ __('Completed') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Text Percentages -->
                        <div class="row text-center mt-4">
                            @foreach ($adoptionStatusesPercentage as $index => $value)
                                <div class="col-3">
                                    <i class="" style="color: {{ $colors[$index] }}"></i>
                                    <h6 class="font-weight-bold">
                                        <span>{{ $value }}%</span>
                                    </h6>
                                    <p class="text-muted">{{ __($adoptionStatusesLabel[$index]) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Appointment & Adoption Payment') }}</h5>
                    <p class="mb-0 text-muted">{{ __('Last 15 Days') }}</p>
                </div>
                <div class="card-body">
                    <div id="appointment-adoption-payment-chart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/apexcharts.min.js') }}"></script>
    {{-- Copy link --}}
    <script>
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('Success', '{{ __('Link Copy on Clipboard') }}', 'success');
        });
    </script>

    {{-- Appointments by Status chart --}}
    <script>
        var options = {
            series: {!! $jsonStatusCounts !!},
            chart: {
                height: 250,
                type: 'pie'
            },
            labels: {!! $jsonStatusLabels !!},
            colors: ['#f39c12', '#28a745', '#dc3545', '#007bff', '#6c757d'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 280
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#appointment-status-chart"), options);
        chart.render();
    </script>

    {{-- Adoption Request by Status chart --}}
    <script>
        (function() {
            var options = {
                chart: {
                    height: 185,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($adoptionStatusesPercentage) !!},
                labels: {!! json_encode($adoptionStatusesLabel) !!},
                colors: {!! json_encode($colors) !!},
                legend: {
                    show: false
                },
            };

            var chart = new ApexCharts(document.querySelector("#adoption-request-chart"), options);
            chart.render();
        })();
    </script>

    {{-- Appointment & Adoption Payment chart --}}
    <script>
        (function() {
            var options = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                        name: "Appointment Payment",
                        data: {!! json_encode($chartData['appointment']) !!}
                    },
                    {
                        name: "Adoption Payment",
                        data: {!! json_encode($chartData['adoption']) !!}
                    }
                ],
                xaxis: {
                    categories: {!! json_encode($chartData['date']) !!},
                    title: {
                        text: "{{ __('Days') }}"
                    },
                },
                colors: ['#453b85', '#FF3A6E'],
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                yaxis: {
                    tickAmount: 8,
                    title: {
                        text: "{{ __('Amount') }}"
                    },
                }
            };

            var chart = new ApexCharts(document.querySelector("#appointment-adoption-payment-chart"), options);
            chart.render();
        })();
    </script>
@endpush
