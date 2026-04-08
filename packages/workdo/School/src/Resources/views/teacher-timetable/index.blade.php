@extends('layouts.main')
@section('page-title')
    {{ __('Manage Teacher Timetable') }}
@endsection
@section('title')
    {{ __('Teacher Timetable') }}
@endsection
@section('page-breadcrumb')
    {{ __('Teacher Timetable') }}
@endsection
@section('page-action')
<div class="d-flex">
    @stack('addButtonHook')
</div>
@endsection

@section('content')
<div class="text-start faq">
    @if ($teacherTimetables->count())
        <div class="accordion accordion-flush mb-4" id="faq-accordion">
            @foreach ($teacherTimetables as $index => $teacherTimetable)
                <div class="accordion-item card mt-4">
                    <div class="accordion-header" id="heading-{{ $index }}">
                        <button class="accordion-button collapsed p-3 gap-3" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $index }}"
                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                            aria-controls="collapse-{{ $index }}">
                            <span class="d-flex align-items-center">
                                <i class="ti ti-info-circle text-primary"></i>
                                {{__('Teacher : ')}}{{ $teacherTimetable->teacher_name }} {{''}} {{','}} {{''}}
                                {{__('Class : ')}}{{ $teacherTimetable->class_name }}
                            </span>
                        </button>
                    </div>
                    @php
                        $allTimeData = json_decode($teacherTimetable->all_time, true);
                    @endphp
                    <div id="collapse-{{ $index }}"
                        class="accordion-collapse collapse @if ($index == 0) show @endif"
                        aria-labelledby="heading-{{ $index }}" data-bs-parent="#faq-accordion">
                        <div class="accordion-body p-3">
                            <div class="table-responsive">
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
                                                        $subjects_name = explode(',',$teacherTimetable->subjects_name);
                                                        if($entry['first_time'] && $entry['last_time']){
                                                            $subjectsString .= "  [{$subjects_name[$counter]} ({$entry['first_time']} - {$entry['last_time']}) {$teacherTimetable->class_name}]  ,  ";
                                                        }
                                                        $counter ++;
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
                <h6 class="card-title mb-0 text-center">{{ __('No Teacher Timetable found.') }}</h6>
            </div>
        </div>
    @endif
</div>
@endsection
