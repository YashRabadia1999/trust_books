@if (isset($homework->student_homework))
<a href="{{ get_file($homework->student_homework) }}" download=""
    class="btn btn-sm btn-primary text-white" data-bs-toggle="tooltip" target="_blank"
    title="{{ __('Download') }}"><span
        class="ti ti-download"></span></a>
@endif
