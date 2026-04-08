@permission('school_employee show')
<div class="action-btn me-2">
<?//php dd($employee)?>
    <a href="{{ route('schoolemployee.show', $employee->user_id) }}"
        class="mx-3 btn bg-warning btn-sm align-items-center text-white"
        data-bs-toggle="tooltip" title="{{ __('View') }}">
        <i class="ti ti-eye"></i>
    </a>
</div>
@endpermission
@permission('school_employee edit')
<div class="action-btn  me-2">
    <a href="{{ route('schoolemployee.edit', $employee->user_id)}}"
        class="mx-3 btn bg-info btn-sm  align-items-center"
        data-bs-toggle="tooltip" title=""
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission
@if (!empty($employee->employee_id))
@permission('school_employee delete')
    <div class="action-btn  me-2">
        {{ Form::open(['route' => ['schoolemployee.destroy', $employee->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $employee->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>

        {{ Form::close() }}
    </div>
@endif
@endpermission
