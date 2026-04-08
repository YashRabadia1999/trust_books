@forelse ($subjects as $key => $subject)
    <tr data-tr="{{ $key }}">
        <td>
            <span>{{ $subject->subject_name }}</span>
            <input type="hidden" name="subject[]" value="{{ $subject->id }}">
        </td>
        @foreach ($week_days as $k => $day)
            <td data-td="{{ $loop->index }}">
                <div class="input-group">
                    <input class="form-control time-input mb-2 rounded-1" name="first_time[{{ $key }}][{{ $day }}]" type="time"
                        style="width: 100px;">
                    <input class="form-control time-input rounded-1" name="last_time[{{ $key }}][{{ $day }}]" type="time"
                        style="width: 100px;">
                </div>
            </td>
        @endforeach
        <td>
            <input type="number" name="total_hours[{{ $subject->id }}]"
                class="form-control total-hours-{{ $subject->id }}" readonly
                style="background-color: #e9ecef">
        </td>
    </tr>
<tr>
    <td>{{ __('Total Time: ') }}</td>
    @foreach ($totalTimePerDays as $day => $totalTimePerDay)
        <td  data-td-count="{{ $loop->index }}" ><span id="{{ $day }}">0</span> / {{$totalTimePerDay}}</td>
    @endforeach
</tr>

@empty
    <tr>
        <td colspan="10" class="text-center">
            <div class="text-center">
                <i class="fas fa-folder-open text-primary fs-40"></i>
                <h5 class="text-center">{{ __('No subject found in this class') }}</h5>
            </div>
        </td>
    </tr>
@endforelse
