{{ Form::open(['route' => 'driving-class.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}


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
                {{ Form::input('datetime-local', 'start_date_time', now()->format('Y-m-d\TH:i'), ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => __('Select Start Date & Time')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('end_date_time', __('End Date & Time'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::input('datetime-local', 'end_date_time', now()->format('Y-m-d\TH:i'), ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => __('Select End Date & Time')]) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('teacher', __('Vehicle Name'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::select('vehicle_id', $vehicle, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Vehicle')]) }}
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('teacher', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-icon-user">
                    {{ Form::select('teacher_id', $users, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Teacher')]) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('student_id[]', $student, null, ['class' => 'form-control student_data multi-select choices', 'data-toggle' => 'select2', 'multiple' => 'multiple', 'id' => 'student', 'data-placeholder' => 'Select Student', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('location', __('Location'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('location', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Location')]) }}
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
                </div>
                <div id="weeklyOptions" class="schedule-options">
                    <label>Choose Days:</label>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '1', null, ['class' => 'form-check-input', 'id' => 'check_Sunday']) !!}
                        <label class="form-check-label" for="check_Sunday">Sunday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '2', null, ['class' => 'form-check-input', 'id' => 'check_Monday']) !!}
                        <label class="form-check-label" for="check_Monday">Monday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '3', null, ['class' => 'form-check-input', 'id' => 'check_Tuesday']) !!}
                        <label class="form-check-label" for="check_Tuesday">Tuesday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '4', null, ['class' => 'form-check-input', 'id' => 'check_Wednesday']) !!}
                        <label class="form-check-label" for="check_Wednesday">Wednesday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '5', null, ['class' => 'form-check-input', 'id' => 'check_Thursday']) !!}
                        <label class="form-check-label" for="check_Thursday">Thursday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '6', null, ['class' => 'form-check-input', 'id' => 'check_Friday']) !!}
                        <label class="form-check-label" for="check_Friday">Friday</label>
                    </div>
                    <div class="form-check custom-checkbox custom-control custom-control-inline">
                        {!! Form::checkbox('weeklyDays[]', '7', null, ['class' => 'form-check-input', 'id' => 'check_Saturday']) !!}
                        <label class="form-check-label" for="check_Saturday">Saturday</label>
                    </div>
                </div>
                <div id="monthlyOptions" class="schedule-options">
                    {!! Form::label('monthlyDate', 'Select Date:', ['class' => 'form-label']) !!}
                    {!! Form::date('monthlyDate', null, [
                        'class' => 'form-control',
                        'placeholder' => __('Select date'),
                        'required' => 'required',
                    ]) !!}
                    <div id="additionalDates"></div>
                    <a href="javascript:void(0);" id="addDateLink">Add another date</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var scheduleSelect = $('#scheduleSelect');
        var dailyOptions = $('#dailyOptions');
        var weeklyOptions = $('#weeklyOptions');
        var monthlyOptions = $('#monthlyOptions');
        var monthlyDateInput = $('#monthlyDate');
        var additionalDates = $('#additionalDates');
        var addDateLink = $('#addDateLink');
        var dateCount = 0;

        hideAllOptions();

        scheduleSelect.change(function() {
            hideAllOptions();

            var selectedOption = scheduleSelect.val();
            if (selectedOption === 'daily') {
                dailyOptions.show();
            } else if (selectedOption === 'weekly') {
                weeklyOptions.show();
            } else if (selectedOption === 'monthly') {
                monthlyOptions.show();
                monthlyDateInput.prop('disabled', false);
            }
        });

        addDateLink.click(function() {
            dateCount++;

            var newDateInput = monthlyDateInput.clone();
            newDateInput.attr('id', 'additionalDate' + dateCount);
            newDateInput.attr('name', 'additionalDates[]');
            newDateInput.val('');
            newDateInput.prop('disabled', false);
            newDateInput.css('margin-top', '10px'); // Enable the input
            additionalDates.append(newDateInput);
        });

        function hideAllOptions() {
            dailyOptions.hide();
            weeklyOptions.hide();
            monthlyOptions.hide();
            additionalDates.empty();

            monthlyDateInput.prop('disabled', true);
        }
    });
</script>
