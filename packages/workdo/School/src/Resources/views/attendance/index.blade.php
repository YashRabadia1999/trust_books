@extends('layouts.main')
@section('page-title')
    {{ __('Manage Student Attendance') }}
@endsection
@section('title')
    {{ __('Attendance') }}
@endsection
@section('page-breadcrumb')
    {{ __('Attendance') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush


@section('page-action')
    <div>
        @permission('school_attendance create')
            <a data-size="" data-url="{{ route('school-attendance.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create Student Attendance') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row gy-3">
                                <div class="col-xl-3 col-lg-3 col-sm-6 col-sm-12 col-12">
                                   <div class="form-group mb-0">
                                        <label class="form-label">{{ __('Type') }}</label> <br>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="monthly" value="monthly" name="type"
                                                class="form-check-input pointer"
                                                {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                            <label class="form-check-label pointer" for="monthly">{{ __('Monthly') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="daily" value="daily" name="type"
                                                class="form-check-input pointer"
                                                {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : '' }}>
                                            <label class="form-check-label pointer" for="daily">{{ __('Daily') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-sm-6 col-sm-12 col-12 month">
                                    <div class="btn-box form-group mb-0">
                                        {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                        {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-sm-6 col-sm-12 col-12 date">
                                    <div class="btn-box form-group mb-0">
                                        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                        {!! Form::date('date', isset($_GET['date']) ? $_GET['date'] : null, [
                                            'class' => 'form-control ',
                                            'placeholder' => 'Select Date',
                                        ]) !!}
                                    </div>
                                </div>
                                @if (in_array(Auth::user()->type, Auth::user()->not_emp_type))
                                    <div class="col-xl-3 col-lg-3 col-sm-6 col-sm-12 col-12">
                                        <div class="btn-box form-group mb-0">
                                            {{ Form::label('grade', __('Grade'), ['class' => 'form-label']) }}
                                            {{ Form::select('grade', $grade, isset($_GET['grade']) ? $_GET['grade'] : '', ['class' => 'form-control' , 'placeholder' => __('Select Grade')]) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-sm-6 col-sm-12 col-12">
                                        <div class="btn-box form-group mb-0">
                                            {{ Form::label('classRoom', __('Class'), ['class' => 'form-label']) }}
                                            {{ Form::select('classRoom', $classRoom, isset($_GET['classRoom']) ? $_GET['classRoom'] : '', ['class' => 'form-control select' , 'placeholder'=>__('Select Class')]) }}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">
                                    <a  class="btn btn-sm btn-primary me-1"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}" id="applyfilter"
                                        data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="#!" class="btn btn-sm btn-danger "
                                        data-bs-toggle="tooltip" title="{{ __('Reset') }}" id="clearfilter"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });
        $('input[name="type"]:radio:checked').trigger('change');
    </script>
     <script type="text/javascript">
        $(document).on('change', '#grade', function() {
                var grade_id = $(this).val();
                getClass(grade_id);
            });

            function getClass(grade_id)
            {
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
