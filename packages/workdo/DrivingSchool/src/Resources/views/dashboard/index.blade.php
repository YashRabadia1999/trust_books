@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Driving School') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/DrivingSchool/src/Resources/assets/css/main.css') }}">
@endpush

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (Auth::user()->type == 'company' || Auth::user()->type == 'driving student' || Auth::user()->type == 'staff')
    <div class="row row-gap mb-4 ">
        <div class="col-xl-6 col-12">
            <div class="dashboard-card">
                <img src="{{ asset('assets/images/layer.png')}}" class="dashboard-card-layer" alt="layer">
                <div class="card-inner">
                    <div class="card-content">
                        <h2>{{Auth::user()->ActiveWorkspaceName()}}</h2>
                        <p>{{ __('Manage driving school operations with lesson scheduling, student progress tracking.') }}</p>
                    </div>
                    <div class="card-icon  d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="114" height="114" viewBox="0 0 114 114" fill="none">
                        <path opacity="0.6" d="M44.0257 44.0883C42.982 44.5535 41.8687 44.7881 40.7176 44.7881C39.5666 44.7881 38.4533 44.5535 37.4076 44.0843L16.2891 34.7002V44.7881C16.2891 46.454 17.3029 47.949 18.8476 48.5692L39.2048 56.7121C39.6918 56.9069 40.2047 57.0024 40.7176 57.0024C41.2305 57.0024 41.7434 56.9069 42.2305 56.7121L62.5877 48.5692C64.1323 47.949 65.1462 46.454 65.1462 44.7881V34.7014L44.0257 44.0883Z" fill="#18BF6B"/>
                        <path opacity="0.6" d="M79.0112 16.6376L42.3683 0.351876C41.3147 -0.117292 40.1139 -0.117292 39.0603 0.351876L2.41741 16.6376C0.948277 17.2936 0 18.7488 0 20.3591C0 21.9694 0.948277 23.4246 2.41741 24.0807L39.0603 40.3664C39.5871 40.601 40.1517 40.7163 40.7143 40.7163C41.2769 40.7163 41.8415 40.601 42.3683 40.3664L73.2857 26.6253V48.8591C73.2857 51.1096 75.1087 52.9306 77.3571 52.9306C79.6056 52.9306 81.4286 51.1096 81.4286 48.8591V20.3591C81.4286 18.7488 80.4803 17.2936 79.0112 16.6376Z" fill="#18BF6B"/>
                        <path d="M26.4676 114.002C32.0891 114.002 36.6462 109.445 36.6462 103.823C36.6462 98.2016 32.0891 93.6445 26.4676 93.6445C20.8462 93.6445 16.2891 98.2016 16.2891 103.823C16.2891 109.445 20.8462 114.002 26.4676 114.002Z" fill="#18BF6B"/>
                        <path d="M113.571 73.2881H30.942C30.857 73.3684 19.8164 75.0693 19.8164 75.0693C8.52058 76.6816 0 86.5043 0 97.9174V101.788C0 104.037 1.82101 105.86 4.07143 105.86H12.2143C12.2143 105.511 12.2883 105.183 12.3172 104.841C12.2926 104.499 12.2143 104.172 12.2143 103.824C12.2143 95.9672 18.6077 89.5738 26.4643 89.5738C34.3209 89.5738 40.7143 95.9672 40.7143 103.824C40.7143 104.521 40.6057 105.189 40.5085 105.86H73.4915C73.3943 105.189 73.2857 104.521 73.2857 103.824C73.2857 95.9672 79.6791 89.5738 87.5357 89.5738C95.3923 89.5738 101.786 95.0024 101.786 105.86C101.786 106.178 111.217 101.579 111.217 101.579C112.879 101.025 114 99.4701 114 97.7167V77.3595C114 76.7273 113.853 73.8547 113.571 73.2881Z" fill="#18BF6B"/>
                        <path d="M87.5379 114.002C93.1594 114.002 97.7165 109.445 97.7165 103.823C97.7165 98.2016 93.1594 93.6445 87.5379 93.6445C81.9165 93.6445 77.3594 98.2016 77.3594 103.823C77.3594 109.445 81.9165 114.002 87.5379 114.002Z" fill="#18BF6B"/>
                        <path d="M73.2846 57.002H71.889C58.6554 57.002 46.0974 61.3132 35.7578 69.2162H73.2846V57.002Z" fill="#18BF6B"/>
                        <path d="M77.3594 69.2162H111.091C104.88 60.6972 94.7248 57.002 83.5818 57.002H77.3594V69.2162Z" fill="#18BF6B"/>
                    </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="row dashboard-wrp">
                @if (Auth::user()->type == 'company')
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="fas fa-users text-danger"></i>
                                    </div>
                                    <a href="{{ route('driving-student.index') }}">
                                        <h3 class="mt-3 mb-0 text-danger">{{ __('Students') }}</h3>
                                    </a>
                                </div>
                                <h3 class="mb-0">{{ $totalStudent }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    @php
                                        $role = App\Models\Role::where('name','staff')->where('created_by',creatorId())->first();
                                    @endphp
                                    <a href="{{ route('users.index' , ['role' => $role->id]) }}"><h3 class="mt-3 mb-0">{{ __('Teachers
                                        ') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ $totalTeacher }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <a href="{{ route('driving-class.index') }}"><h3 class="mt-3 mb-0">{{ __('Class') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ $totalClass }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="dashboard-project-card">
                            <div class="card-inner  d-flex justify-content-between">
                                <div class="card-content">
                                    <div class="theme-avtar bg-white">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <a href="{{ route('driving-vehicle.index') }}"><h3 class="mt-3 mb-0">{{ __('Vehicle') }}</h3></a>
                                </div>
                                <h3 class="mb-0">{{ $totalVehicle }}</h3>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->type == 'driving student' || Auth::user()->type == 'staff')
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-users text-danger"></i>
                                </div>
                                <a href="{{ route('driving-student.index') }}">
                                    <h3 class="mt-3 mb-0 text-danger">{{ __('Students') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $totalStudent }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <a href="{{ route('driving-class.index') }}"><h3 class="mt-3 mb-0">{{ __('Class') }}</h3></a>
                            </div>
                            <h3 class="mb-0">{{ $totalClass }}</h3>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xxl-7">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Calendar') }}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-5">
        <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                class=""></i></a>
                    </div>
                    <h5>{{ __('Lesson Status') }}</h5>
                </div>
                <div class="card-body p-sm-5 p-3">
                    <div class="row align-items-center">
                        <div class="col-sm-6 col-12">
                            <div id="lessons-chart"></div>
                        </div>
                        <div class="col-6">
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-info"></i>
                                    <span class="ms-2 text-sm">{{ __('Draft') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-warning"></i>
                                    <span class="ms-2 text-sm">{{ __('Start') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                                    <span class="ms-2 text-sm">{{ __('Complete') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-danger"></i>
                                    <span class="ms-2 text-sm">{{ __('Cancel') }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="row text-center">
                            @foreach ($drivingStatusData as $status => $percentage)
                                <div class="col-3">
                                    <i class="fas fa-chart"
                                        style="color: {{ $colors[array_search($status, $statusNames)] }}"></i>
                                    <h6 class="font-weight-bold">
                                        <span>{{ round($percentage, 2) }}%</span>
                                    </h6>
                                    <p class="text-muted">{{ $status }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Current Month Class') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="event-cards list-group list-group-flush w-100">
                        <div class="row align-items-center justify-content-between">
                            <div class=" align-items-center">
                                @forelse ($current_month_class as $item)
                                    <li class="list-group-item card mb-3" style="border-radius: 0px !important;">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-auto mb-3 mb-sm-0">
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="m-0">
                                                            {{ $item->name ?? 'Deleted User' }}
                                                            <small class="text-muted text-xs"></small>
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ date('Y M d', strtotime($item->start_date_time)) }}-
                                                            {{ date('Y M d', strtotime($item->end_date_time)) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item card mb-3" style="border-radius: 0px !important;">
                                        <div class="row align-items-center justify-content-between">
                                            <div class="col-auto mb-3 mb-sm-0">
                                                <div class="d-flex align-items-center">
                                                    {{ __('No Class Found.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforelse
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('packages/workdo/DrivingSchool/src/Resources/assets/js/main.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/DrivingSchool/src/Resources/assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    {{-- calender --}}
    <script>
        (function() {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: "{{ __('Today') }}",
                    timeGridDay: "{{ __('Day') }}",
                    timeGridWeek: "{{ __('Week') }}",
                    dayGridMonth: "{{ __('Month') }}"
                },
                themeSystem: 'bootstrap',
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                firstDay: {{ company_setting('calendar_start_day') ?? 0 }},
                events: {!! json_encode($events) !!}
            });
            calendar.render();
        })();
    </script>

    <script>
        (function() {
            var options = {
                chart: {
                    height: 170,
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
                series: {!! json_encode(array_values($drivingStatusData)) !!},
                labels: {!! json_encode(array_keys($drivingStatusData)) !!},
                colors: {!! json_encode(array_values($colors)) !!},
                legend: {
                    show: false
                }
            };
            var chart = new ApexCharts(document.querySelector("#lessons-chart"), options);
            chart.render();
        })();
    </script>
@endpush
