@extends('layouts.main')
@section('page-title')
    {{ __('Manage Timetable') }}
@endsection
@section('title')
    {{ __('Timetable') }}
@endsection
@section('page-breadcrumb')
    {{ __('Timetable') }}
@endsection
@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('school_timetable create')
            <a href="{{ route('timetable.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="text-start faq">
        @if ($timetables->count())
            <div class="accordion accordion-flush mb-4" id="faq-accordion">

                @foreach ($timetables as $index => $timetable)
                    <div class="accordion-item card mt-4">
                        <div class="accordion-header" id="heading-{{ $index }}">
                            <button class="accordion-button collapsed gap-3 p-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $index }}"
                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $index }}">

                                <div class="d-flex gap-2 justify-content-between w-100">
                                    <span class="d-flex align-items-center">
                                        <i class="ti ti-info-circle text-primary"></i>
                                        {{ __('Class : ') }}{{ $timetable->class_name }}
                                    </span>
                                    <div class="d-flex align-items-center">
                                        <div class="action-btn me-3">
                                            @if (Auth::user()->type == 'company' || Auth::user()->type == 'staff')
                                                <div class="action-btn">
                                                    <a href="{{ route('timetable.edit', \Crypt::encrypt($timetable->id)) }}"
                                                        class="btn bg-info btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="action-btn">
                                            @if (Auth::user()->type == 'company')
                                                {{ Form::open(['route' => ['timetable.destroy', \Crypt::encrypt($timetable->id)], 'class' => 'm-0']) }}
                                                @method('DELETE')
                                                <a href="#"
                                                    class="btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="{{ __('Delete') }}"
                                                    aria-label="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $timetable->id }}">
                                                    <i class="ti ti-trash text-white text-white"></i>
                                                </a>
                                                {{ Form::close() }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </button>
                        </div>
                        @php
                            $allTimeData = json_decode($timetable->all_time, true);
                        @endphp
                        <div id="collapse-{{ $index }}"
                            class="accordion-collapse collapse @if ($index == 0) show @endif"
                            aria-labelledby="heading-{{ $index }}" data-bs-parent="#faq-accordion">
                            <div class="accordion-body p-3">
                                <div class="table-responsive ">
                                    <table class="table table-bordered">
                                        @foreach ($allTimeData as $day => $entries)
                                            <tr>
                                                <th class="text-capitalize" width="100" style="min-width: 150px">
                                                    {{ $day }}
                                                </th>
                                                <td>
                                                    <p class="mb-0">
                                                        @php
                                                            $subjectsString = '';
                                                            $counter = 0;
                                                        @endphp
                                                        @foreach ($entries as $ent => $entry)
                                                            @php
                                                                $subjects_name = explode(
                                                                    ',',
                                                                    $timetable->subjects_name,
                                                                );
                                                                if ($entry['first_time'] && $entry['last_time']) {
                                                                    $subjectsString .= "  {$subjects_name[$counter]}({$entry['first_time']} - {$entry['last_time']}), ";
                                                                }
                                                                $counter++;
                                                            @endphp
                                                        @endforeach
                                                        {{ rtrim($subjectsString, ', ') }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @else
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0 text-center">{{ __('No Timetable found.') }}</h6>
                </div>
            </div>
        @endif
    </div>
@endsection
