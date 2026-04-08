@extends('layouts.main')
@section('page-title')
    {{ __('Lesson Detail') }}
@endsection
@section('page-breadcrumb')
    {{ $lesson->name }}
@endsection
@push('css')
    <style>
        .cus-card {
            min-height: 204px;
        }

        .status {
            min-width: 50px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h4 class="mb-0">{{ $lesson->name }}</h4>
                    <div class="all-button-box">
                        <select class="form-control status_change pe-5 " name="status"
                            data-url="{{ route('driving-lesson.status.change', $lesson->id) }}">
                            @foreach ($status as $k => $val)
                                <option value="{{ $k }}"
                                    {{ $lesson->status == $k ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body p-3">
                    <ul class="d-flex flex-wrap justify-content-between gap-2 list-none p-0 mb-0">
                        <li>
                            <span class="mb-1 d-block">{{ __('Lesson Period') }}</span>
                            <h6 class="mb-0">{{ $lesson->start_date_time }} - {{ $lesson->end_date_time }}
                            </h6>
                        </li>
                        <li>
                            <span class="mb-1 d-block">{{ __('Teacher') }}</span>
                            <h6 class="mb-0">{{ $class->teacherName->name }}</h6>
                        </li>
                        <li>
                            <span class="mb-1 d-block">{{ __('Students') }}</span>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach (explode(',', $class->student_id) as $studentId)
                                @php
                                    $student = \Workdo\DrivingSchool\Entities\DrivingStudent::find($studentId);
                                @endphp

                                @if ($student)
                                    <span
                                        class="badge p-2 bg-primary status">{{ $student->name }}</span>
                                @endif
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple overflow-hidden rounded" id="assets">
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white"> {{ __('Student Name') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Present') }}</th>
                                    <th class="bg-primary text-white"> {{ __('Absent') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (auth()->user()->type === 'company' || auth()->user()->type === 'staff' || auth()->user()->type === 'driving student')
                                    @foreach (explode(',', $class->student_id) as $studentId)
                                        @php
                                            $student = \Workdo\DrivingSchool\Entities\DrivingStudent::find($studentId);
                                        @endphp
                                        <tr class="font-style">
                                            <td>
                                                @if ($student)
                                                    <span
                                                        style="font-weight: bold; font-size:12px">{{ $student->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-primary btn-sm present-btn "@if (in_array($student->id, $present)) disabled @endif
                                                    data-lesson-id="{{ $lesson->id }}"
                                                    data-student-id="{{ $student->id }}" data-bs-toggle="tooltip"
                                                    title="{{ __('Present') }}">
                                                    <i class="ti ti-check text-white"
                                                        style="font-weight: bold; font-size:12px"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-danger btn-sm absent-btn"@if (in_array($student->id, $absent)) disabled @endif
                                                    data-lesson-id="{{ $lesson->id }}"
                                                    data-student-id="{{ $student->id }}" data-bs-toggle="tooltip"
                                                    title="{{ __('Absent') }}">
                                                    <i class="ti ti-x text-white text-white"
                                                        style="font-weight: bold; font-size:12px"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.present-btn').on('click', function() {
                handleAttendanceClick($(this).data('lesson-id'), $(this).data('student-id'), 'present', $(
                    this));
            });

            $('.absent-btn').on('click', function() {
                handleAttendanceClick($(this).data('lesson-id'), $(this).data('student-id'), 'absent', $(
                    this));
            });

            function handleAttendanceClick(lessonId, studentId, status, button) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('driving-store.attendance') }}',
                    data: {
                        lesson_id: lessonId,
                        student_id: studentId,
                        status: status
                    },
                    success: function(data) {
                        updateUI(button, status);
                    },
                    error: function(error) {
                        console.error('Error storing attendance', error);
                    }
                });
            }

            function updateUI(button, status) {
                var row = button.closest('tr');
                var otherButton = (status === 'present') ? row.find('.absent-btn') : row.find('.present-btn');

                button.prop('disabled', true);

                otherButton.prop('disabled', false);

                row.removeClass('present-row absent-row');

                if (status === 'present') {
                    row.addClass('present-row');
                } else {
                    row.addClass('absent-row');
                }
            }
        });
    </script>
    <script>
        $(document).on('change', '.status_change', function() {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function(data) {
                    location.reload();
                },
            });
        });

        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
@endpush
