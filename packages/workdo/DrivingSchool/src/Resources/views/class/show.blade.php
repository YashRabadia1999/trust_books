@extends('layouts.main')

@section('page-title')
    {{ __('Class Detail') }}
@endsection

@section('page-breadcrumb')
    {{ __($class->name) }}
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
            <div class="card mb-4">
                <div class="card-header p-3">
                    <h4 class="mb-0">{{ $class->name }}</h4>
                </div>
                <div class="card-body p-3">
                    <div class="row row-gap">
                        <div class="col-md-6">
                            <ul class="list-none p-0 mb-0">
                                <li class="mb-2">
                                    <h6 class="mb-1 d-block">{{ __('Duration Of Class') }}</h6>
                                    <p class="mb-0">{{ $class->start_date_time }} - {{ $class->end_date_time }}</p>
                                </li>
                                <li class="mb-2">
                                    <h6 class="mb-1 d-block">{{ __('Teacher') }}</h6>
                                    <p class="mb-0">{{ $class->teacherName->name }}</p>
                                </li>
                                <li>
                                    <h6 class="mb-1 d-block">{{ __('Location') }}</h6>
                                    <p class="mb-0">{{ $class->location }}</p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-none p-0 mb-0">
                                <li class="mb-2">
                                    <h6 class="mb-1 d-block">{{ __('Students') }}</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach (explode(',', $class->student_id) as $studentId)
                                            @php
                                                $student = \Workdo\DrivingSchool\Entities\DrivingStudent::find($studentId);
                                            @endphp
                                            @if ($student)
                                                <span class="badge p-2  bg-primary status">{{ $student->name }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <h6 class="mb-1 d-block">{{ __('Schedule') }}</h6>
                                    <p class="mb-0">{{ $class->schedule }}</p>
                                </li>
                                <li>
                                    <h6 class="mb-1 d-block">{{ __('Fees') }}</h6>
                                    <p class="mb-0">{{ $class->fees }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 overflow-hidden rounded" id="assets">
                    <thead>
                        <tr>
                            <th class="active text-white bg-primary">{{ __('Name') }}</th>
                            @foreach ($lessons as $lesson)
                                <th class="Id text-white bg-primary">
                                    @permission('drivinglesson show')
                                        {{ Workdo\DrivingSchool\Entities\DrivingLesson::lessonNumberFormat($lesson['id']) }}
                                    @else
                                        {{ Workdo\DrivingSchool\Entities\DrivingLesson::lessonNumberFormat($lesson['id']) }}
                                    @endpermission
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (Auth::user()->type === 'company' || Auth::user()->type === 'staff')
                            @foreach (explode(',', $class->student_id) as $studentId)
                                @php
                                    $student = \Workdo\DrivingSchool\Entities\DrivingStudent::find($studentId);
                                @endphp
                                @if ($student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @foreach ($lessons as $lesson)
                                            @php
                                                $present_students = explode(',', $lesson->present_student_id);
                                                $absent_students = explode(',', $lesson->absent_student_id);
                                                $student_attendance = in_array($studentId, $present_students)
                                                    ? 'P'
                                                    : (in_array($studentId, $absent_students)
                                                        ? 'A'
                                                        : '');
                                            @endphp
                                            <td>
                                                @if ($student_attendance === 'P')
                                                    <i class="badge bg-success p-2 rounded">{{ __('P') }}</i>
                                                @elseif ($student_attendance === 'A')
                                                    <i class="badge bg-danger p-2 rounded">{{ __('A') }}</i>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        @elseif (Auth::user()->type == 'driving student')
                            @foreach (explode(',', $class->student_id) as $studentId)
                                @php
                                    $student = \Workdo\DrivingSchool\Entities\DrivingStudent::find($studentId);
                                @endphp
                                @if ($student && $student->user_id == auth()->user()->id)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @foreach ($lessons as $lesson)
                                            @php
                                                $present_students = explode(',', $lesson->present_student_id);
                                                $absent_students = explode(',', $lesson->absent_student_id);
                                                $student_attendance = in_array($studentId, $present_students)
                                                    ? 'P'
                                                    : (in_array($studentId, $absent_students)
                                                        ? 'A'
                                                        : '');
                                            @endphp
                                            <td>
                                                @if ($student_attendance === 'P')
                                                    <i class="badge bg-success p-2 rounded">{{ __('P') }}</i>
                                                @elseif ($student_attendance === 'A')
                                                    <i class="badge bg-danger p-2 rounded">{{ __('A') }}</i>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
