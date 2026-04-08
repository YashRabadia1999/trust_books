@extends('layouts.main')
@section('page-title')
    {{ __('Create Timetable') }}
@endsection
@section('title')
    {{ __('Create Timetable') }}
@endsection
@section('page-breadcrumb')
    {{ __('Timetable') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="needs-validation needs-validation" action="{{ route('timetable.store') }}" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12 form-group">
                                {{ Form::label('time_range', __('Time Range'), ['class' => 'form-label']) }}<x-required></x-required>
                                <div class="input-group">
                                    <input class="form-control" required="required" name="start_time" type="time"
                                        id="start_time">
                                    <span class="input-group-text">to</span>
                                    <input class="form-control" required="required" name="end_time" type="time"
                                        id="end_time">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">{{ __('Class') }}</label><x-required></x-required>
                                {{ Form::select('class_id', $classRoom, null, ['id' => 'class', 'class' => 'form-control class_id', 'placeholder' => 'Select Class' ,'required'=>'required']) }}
                            </div>
                            <div class="table-responsive">
                                <table class="table modal-table" id="subject-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Subject') }}</th>
                                            @foreach ($week_days as $name)
                                                <th>
                                                    <span class="email-address">{{ $name }}</span>
                                                </th>
                                            @endforeach
                                            <th scope="col">{{ __('Total Hours') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody class="subject-table-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer text-end mt-4">
                            <input type="submit" value="{{ __('Create') }}" class="btn btn-primary d-none"  id="saveChangesButton">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#class').on('change', function() {
            var selectedClass = $(this).val();
            var startTime = $('#start_time').val();
            var endTime = $('#end_time').val();
            getSubjects(selectedClass,startTime, endTime);
        });
    });

    function getSubjects(subject_id, startTime, endTime) {
        $.ajax({
            url: '{{ route('getsubject') }}',
            type: 'POST',
            data: {
                "subject_id": subject_id,
                "start_time": startTime,
                "end_time": endTime,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                var tableBody = $('.subject-table-body');
                tableBody.empty();

                tableBody.append(data.html);
                if(data.subjects > 0)
                {
                    $('#saveChangesButton').removeClass('d-none');
                }
            }
        });
    }

</script>

<script>
    function parseTime(time) {
        var parts = time.split(':');
        return {
            hours: parseInt(parts[0]),
            minutes: parseInt(parts[1])
        };
    }

    $(document).on('change', '.time-input', function() {
        var td = $(this).closest('td');
        var data_td = td.attr('data-td');

        var tr = td.closest('tr') ;
        var data_tr = tr.attr('data-tr');

        var totalHours = 0;


        tr.find('td').each(function () {
            var firstTime = $(this).find('input[name^="first_time[' + data_tr + ']"]').val();
            var lastTime = $(this).find('input[name^="last_time[' + data_tr + ']"]').val();

            if (firstTime && lastTime) {
                var startTime = parseTime(firstTime, 'HH:mm');
                var endTime = parseTime(lastTime, 'HH:mm');
                var minutesDiff = (endTime.hours - startTime.hours) * 60 + (endTime.minutes - startTime.minutes);
                var hoursDiff = minutesDiff / 60;


                totalHours += hoursDiff;
            }
        });

        var td_total =  tr.find('td:last').find('input').val(totalHours.toFixed(2));

        var totalHoursTd = 0;

        $("[data-td="+data_td+"]").each(function ()
        {
            var firstTime = $(this).find('input[name^="first_time"]').val();
            var lastTime = $(this).find('input[name^="last_time"]').val();

            if (firstTime && lastTime) {
                var startTime = parseTime(firstTime, 'HH:mm');
                var endTime = parseTime(lastTime, 'HH:mm');
                var minutesDiff = (endTime.hours - startTime.hours) * 60 + (endTime.minutes - startTime.minutes);
                var hoursDiff = minutesDiff / 60;


                totalHoursTd += hoursDiff;
            }
        });
        $("[data-td-count="+data_td+"]").find('span').text(totalHoursTd.toFixed(2))
    });
</script>

@endpush
