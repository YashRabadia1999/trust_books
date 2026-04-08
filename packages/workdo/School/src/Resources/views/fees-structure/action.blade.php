@permission('school_fees_structure edit')
<div class="action-btn me-2">
    <a class="mx-3 btn bg-info btn-sm  align-items-center"
        data-url="{{ route('school-fee-structure.edit', \Crypt::encrypt($fees->id)) }}"
        data-ajax-popup="true" data-size="" data-bs-toggle="tooltip"
        title="" data-title="{{ __('Edit Fee Structure') }}"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission

@permission('school_fees_structure delete')
<div class="action-btn  me-2">
    {{ Form::open(['route' => ['school-fee-structure.destroy', $fees->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a class="mx-3 btn bg-danger btn-sm  align-items-center bs-pass-para show_confirm"
        data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete" aria-label="Delete"
        data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $fees->id }}"><i
            class="ti ti-trash text-white text-white"></i></a>
    {{ Form::close() }}
</div>
@endpermission
