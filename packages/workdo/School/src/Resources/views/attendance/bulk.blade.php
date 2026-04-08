@extends('layouts.main')
@section('page-title')
    {{ __('Manage Bulk Student Attendance') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bulk Attendance') }}
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                {{ Form::open(['route' => ['student.bulkattendance'], 'method' => 'get', 'id' => 'bulkattendance_filter']) }}
                    <div class="row gy-3 align-items-end justify-content-lg-end">
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="btn-box form-group mb-0">
                                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                {!! Form::date('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), [
                                    'class' => 'form-control',
                                    'placeholder' => 'Select Date',
                                    'max' => date('Y-m-d'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="btn-box form-group mb-0">
                                {{ Form::label('grade', __('Grade'), ['class' => 'form-label']) }}
                                {{ Form::select('grade', $grade, isset($_GET['grade']) ? $_GET['grade'] : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="btn-box form-group mb-0">
                                {{ Form::label('classRoom', __('Class'), ['class' => 'form-label']) }}
                                {{ Form::select('classRoom', $classroom, isset($_GET['classRoom']) ? $_GET['classRoom'] : '', ['class' => 'form-control select']) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4 mb-2">
                            <div class="row">
                                <div class="col-auto">
                                    <a class="btn btn-sm btn-primary me-1" 
                                        onclick="document.getElementById('bulkattendance_filter').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                        id="applyfilter" data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="#!" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"
                                        title="{{ __('Reset') }}" id="clearfilter"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{ Form::open(['route' => ['student.bulkattendance.store'], 'method' => 'post']) }}
                <div class="table-responsive">
                    <table class="table" id="">
                        <thead>
                            <tr>
                                <th>{{ __('Student') }}</th>
                                <th>
                                    <div class="form-group my-auto">
                                        <div class="custom-control ">
                                            <input class="form-check-input" type="checkbox" name="present_all"
                                                id="present_all" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label ms-1" for="present_all">
                                                {{ __('Attendance') }}</label>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                @php
                                    $attendance = Workdo\School\Entities\SchoolAttendance::present_status(
                                        $student->id,
                                        isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'),
                                    );
                                @endphp
                                <tr>
                                    {{-- <td class="Id"> --}}
                                    <input type="hidden" value="{{ $student->id }}" name="student_id[]">
                                    {{-- </td> --}}
                                    <td>{{ $student->name }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="form-group mb-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="form-check-input present" type="checkbox"
                                                            name="present-{{ $student->id }}"
                                                            id="present{{ $student->id }}"
                                                            {{ !empty($attendance) && $attendance->status == 'Present' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="present{{ $student->id }}"></label>
                                                    </div>
                                                </div>
                                                <div class="present_check_in {{ empty($attendance) ? 'd-none' : '' }} ">
                                                    <div class="d-flex align-items-center gap-3">
                                                    <label class="control-label">{{ __('In') }}</label>
                                                    <div class="time-box">
                                                        <input type="time" class="form-control timepicker"
                                                            name="in-{{ $student->id }}"
                                                            value="{{ !empty($attendance) && $attendance->clock_in != '00:00:00' ? $attendance->clock_in : $company_settings['company_start_time'] }}">
                                                    </div>

                                                    <label for="inputValue"
                                                        class="control-label">{{ __('Out') }}</label>
                                                    <div class="time-box">
                                                        <input type="time" class="form-control timepicker"
                                                            name="out-{{ $student->id }}"
                                                            value="{{ !empty($attendance) && $attendance->clock_out != '00:00:00' ? $attendance->clock_out : $company_settings['company_end_time'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </td>

                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="attendance-btn float-end pt-4">
                    <input type="hidden" value="{{ isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') }}"
                        name="date">
                    <input type="hidden" value="{{ isset($_GET['grade']) ? $_GET['grade'] : '' }}" name="grade">
                    <input type="hidden" value="{{ isset($_GET['classRoom']) ? $_GET['classRoom'] : '' }}"
                        name="classRoom">
                    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('.daterangepicker').length > 0) {
                $('.daterangepicker').daterangepicker({
                    format: 'yyyy-mm-dd',
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });
            }
        });
    </script>
    <script>
        $('#present_all').click(function(event) {
            if (this.checked) {
                $('.present').each(function() {
                    this.checked = true;
                });

                $('.present_check_in').removeClass('d-none');
                $('.present_check_in').addClass('d-block');

            } else {
                $('.present').each(function() {
                    this.checked = false;
                });
                $('.present_check_in').removeClass('d-block');
                $('.present_check_in').addClass('d-none');
            }
        });
        $('.present').click(function(event) {
            var div = $(this).parent().parent().parent().parent().find('.present_check_in');

            if (this.checked) {
                div.removeClass('d-none');
                div.addClass('d-block');

            } else {
                div.removeClass('d-block');
                div.addClass('d-none');
            }
        });
    </script>
    <script type="text/javascript">
        $(document).on('change', '#grade', function() {
            var grade_id = $(this).val();
            getDepartment(grade_id);
        });

        function getDepartment(grade_id) {
            var data = {
                "grade_id": grade_id,
                "_token": "{{ csrf_token() }}",
            }
            $.ajax({
                url: '{{ route('student.getclassRoom') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#classRoom').empty();
                    $('#classRoom').append('<option value="" disabled>{{ __('All') }}</option>');

                    $.each(data, function(key, value) {
                        $('#classRoom').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#classRoom').val('');
                }
            });
        }
    </script>
@endpush
