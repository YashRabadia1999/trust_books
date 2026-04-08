{{ Form::model($class, ['route' => ['driving-class.update', $class->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}

<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('name', __('Class Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Class Name')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {!! Form::label('start_date_time', __('Start Date & Time'), ['class' => 'form-label']) !!}<x-required></x-required>
                {{ Form::input('datetime-local', 'start_date_time', isset($class->start_date_time) ? $class->start_date_time : now()->format('Y-m-d\TH:i'), ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => __('Select Start Date & Time')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('end_date_time', __('End Date & Time'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::input('datetime-local', 'end_date_time', isset($class->end_date_time) ? $class->end_date_time : now()->format('Y-m-d\TH:i'), ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => __('Select End Date & Time')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('teacher', __('Vehicle Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('vehicle_id', $vehicle, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Vehicle')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('teacher', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('teacher_id', $users, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Teacher')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id[]', $student, $student_id, ['class' => 'form-control student_data multi-select choices', 'data-toggle' => 'select2', 'multiple' => 'multiple', 'id' => 'student', 'data-placeholder' => 'Select Student', 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => __('Enter Location')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('fees', __('Fees'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('fees', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Fees')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {!! Form::label('schedule', __('Schedule'), ['class' => 'form-label']) !!}<x-required></x-required>

                {!! Form::select('schedule', ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'], null, [
                    'class' => 'form-control',
                    'placeholder' => __('Enter schedule'),
                    'required' => 'required',
                    'id' => 'scheduleSelect',
                ]) !!}
                <div id="dailyOptions" class="schedule-options">
                    <!-- Daily options go here -->
                    <!-- For simplicity, let's assume there's nothing specific for daily at the moment -->
                </div>

                <div id="weeklyOptions" class="schedule-options">
                    <!-- Weekly options go here -->
                    <label>Choose Days:</label>
                    @if ($weekly_days)
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '1', in_array('1', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Sunday">Sunday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '2', in_array('2', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Sunday">Monday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '3', in_array('3', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Tuesday">Tuesday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '4', in_array('4', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Wednesday">Wednesday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '5', in_array('5', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Thursday">Thursday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '6', in_array('6', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Friday">Friday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '7', in_array('7', $weekly_days), ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Saturday">Saturday</label>
                        </div>
                    @else
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '1', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Sunday">Sunday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '2', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Monday">Monday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '3', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Tuesday">Tuesday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '4', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Wednesday">Wednesday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '5', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Thursday">Thursday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '6', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Friday">Friday</label>
                        </div>
                        <div class="form-check custom-checkbox custom-control custom-control-inline">
                            {!! Form::checkbox('weeklyDays[]', '7', null, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="check_Saturday">Saturday</label>
                        </div>
                    @endif

                    <!-- Add the other days accordingly -->
                </div>
                <div id="monthlyOptions" class="schedule-options">
                    @if ($main_date)

                        {!! Form::label('monthlyDate', 'Select Date:', ['class' => 'form-label']) !!}
                        {!! Form::date('monthlyDate', $main_date, [
                            'class' => 'form-control',
                            'placeholder' => __('Select date'),
                        ]) !!}

                        @if ($additional_dates)
                            @foreach ($additional_dates as $index => $additionalDate)
                                {!! Form::date('additionalDates[]', $additionalDate, [
                                    'class' => 'form-control mt-3',
                                    'placeholder' => __('Select additional date'),
                                ]) !!}
                            @endforeach
                        @endif

                        <div id="additionalDates"></div>
                        <a href="javascript:void(0);" id="addDateLink">Add another date</a>
                    @else
                        {!! Form::label('monthlyDate', 'Select Date:', ['class' => 'form-label']) !!}
                        {!! Form::date('monthlyDate', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Select date'),
                        ]) !!}

                        @if ($additional_dates)
                            @foreach ($additional_dates as $index => $additionalDate)
                                {!! Form::date('additionalDates[]', $additionalDate, [
                                    'class' => 'form-control mt-3',
                                    'placeholder' => __('Select additional date'),
                                ]) !!}
                            @endforeach
                        @endif

                        <div id="additionalDates"></div>
                        <a href="javascript:void(0);" id="addDateLink">Add another date</a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
<script>
    var selectedSchedule = '{{ $class->schedule }}';
    var addDateLink = $('#addDateLink');
    var dateCount = 0;

    if (selectedSchedule == 'daily') {
        $('#weeklyOptions').hide();
        $('#monthlyOptions').hide();
        $('#monthlyDate').hide();
        $('#additionalDates').hide();
    } else if (selectedSchedule == 'weekly') {
        $('#weeklyOptions').show();
        $('#dailyOptions').hide();
        $('#monthlyOptions').hide();
        $('#monthlyDate').hide();
        $('#additionalDates').hide();
    } else {
        $('#weeklyOptions').hide();
        $('#dailyOptions').hide();
        $('#monthlyOptions').show();
        $('#monthlyDate').show();
        $('#additionalDates').show();
        addDateLink.click(function() {
            // Increment the dateCount for unique identifiers
            dateCount++;

            // Clone the original date input and append it with a new identifier
            var newDateInput = $('#monthlyDate').clone();
            newDateInput.attr('id', 'additionalDate' + dateCount);
            newDateInput.attr('name', 'additionalDates[]');
            newDateInput.val('');
            newDateInput.prop('disabled', false);
            newDateInput.css('margin-top', '10px'); // Enable the input
            $('#additionalDates').append(newDateInput);
        });
    }

    $('#scheduleSelect').on('change', function() {
        var value = $(this).val();
        if (value == 'daily') {
            $('#dailyOptions').show();
            $('#weeklyOptions').hide();
            $('#monthlyOptions').hide();
            $('#monthlyDate').hide();
            $('#additionalDates').hide();
        } else if (value == 'weekly') {

            $('#weeklyOptions').show();
            $('#dailyOptions').hide();
            $('#monthlyOptions').hide();
            $('#monthlyDate').hide();
            $('#additionalDates').hide();
        } else if (value == 'monthly') {
            $('#weeklyOptions').hide();
            $('#dailyOptions').hide();
            $('#monthlyOptions').show();
            $('#monthlyDate').show();
            $('#additionalDates').show();
            addDateLink.click(function() {
                // Increment the dateCount for unique identifiers
                dateCount++;

                // Clone the original date input and append it with a new identifier
                var newDateInput = $('#monthlyDate').clone();
                newDateInput.attr('id', 'additionalDate' + dateCount);
                newDateInput.attr('name', 'additionalDates[]');
                newDateInput.val('');
                newDateInput.prop('disabled', false);
                newDateInput.css('margin-top', '10px'); // Enable the input
                $('#additionalDates').append(newDateInput);
            });
        }
    });
</script>
