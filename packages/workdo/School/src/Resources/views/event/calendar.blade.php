@extends('layouts.main')
@section('page-title')
    {{ __('Manage Events') }}
@endsection
@section('title')
    {{ __('Events') }}
@endsection
@section('page-breadcrumb')
    {{ __('Events') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/School/src/Resources/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/School/src/Resources/assets/css/custom.css') }}">
@endpush

@section('page-action')
    @permission('school_event create')
        <div>
            <a href="{{ route('school-event.index') }}" data-bs-toggle="tooltip" title="{{ __('List View') }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-list"></i>
            </a>
            <a data-size="" data-url="{{ route('school-event.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create Event') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100 mb-0">
                <div class="card-header">
                    <h5>{{ __('Calendar') }}</h5>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 mb-0">
                <div class="card-header">
                    <h5>{{ __('Upcoming Events') }}</h4>
                </div>
                <div class="card-body">                   
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        <li class="list-group-item card mb-3 rounded-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class=" align-items-center">
                                    @forelse ($current_month_event as $event)
                                        <div class="card mb-3 border shadow-none">
                                            <div class="px-3">
                                                <div class="row align-items-center">
                                                    <div class="col ml-n2">
                                                        <h5 class="text-sm mb-0 fc-event-title-container pointer">
                                                            <a data-size="md"
                                                                data-url="{{ route('school-event.edit', $event->id) }}"
                                                                data-ajax-popup="true" data-title="{{ __('Edit Event') }}"
                                                                class="fc-event-title text-primary">
                                                                {{ $event->event_name }}
                                                            </a>
                                                        </h5>
                                                        <p class="card-text small text-dark mt-0">
                                                            {{ __('Event Date : ') }}
                                                            {{ company_date_formate($event->event_date) }}
                                                        </p>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center">
                                            <h6 class="mb-0">{{ __('There is no event in this month') }}</h6>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('packages/workdo/School/src/Resources/assets/js/main.min.js') }}"></script>
    <script type="text/javascript">
        (function() {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
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
                events: {!! $arrEvents !!},


            });

            calendar.render();
        })();
    </script>

    <script>
        $(document).on('click', '.fc-daygrid-event', function(e) {
            e.preventDefault();
            var event = $(this);
            var title = $(this).find('.fc-event-title').html();
            var size = 'md';
            var url = $(this).attr('href');
            $("#commonModal .modal-title ").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $('#commonModal .body').html(data);
                    $("#commonModal").modal('show');
                    if ($(".flatpickr-input").length) {
                        $(".flatpickr-input").flatpickr({
                            enableTime: false,
                            dateFormat: "Y-m-d",
                        });
                    }

                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', data.error, 'error')
                }
            });
        });
    </script>
@endpush
