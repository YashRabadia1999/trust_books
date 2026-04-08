@if ($admission->converted_student_id == 0)
<div class="action-btn me-2">
    <a class="mx-3 btn bg-success btn-sm align-items-center text-white"
        data-url="{{ route('admission.convert', $admission->id) }}"
        data-ajax-popup="true" data-size="md"
        data-bs-toggle="tooltip" title=""
        data-title="{{ __('Convert to Student') }}"
        data-bs-original-title="{{ __('Convert to Student') }}">
        <i class="ti ti-exchange"></i>

    </a>
</div>
@endif
@permission('school_admission edit')
<div class="action-btn me-2">
    <a href="{{ route('admission.edit', \Crypt::encrypt($admission->id)) }}"
        class="mx-3 btn bg-info btn-sm  align-items-center"
        data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission
@permission('school_admission delete')
<div class="action-btn me-2">
    {{ Form::open(['route' => ['admission.destroy', $admission->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a href="#"
        class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
        data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete" aria-label="Delete"
        data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $admission->id }}">
        <i class="ti ti-trash text-white text-white"></i>
    </a>
    {{ Form::close() }}
</div>
@endpermission
