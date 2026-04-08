@permission('group_contact show')
<div class="action-btn me-2">
        <a href="{{ route('bulksms-group.show', \Crypt::encrypt($bulksmsGroup->id)) }}" 
            class="btn btn-sm align-items-center bg-warning" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-original-title="{{ __('Detail') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission

@permission('group_contact edit')
    <div class="action-btn me-2">
        <a class="btn btn-sm  align-items-center bg-info"
            data-url="{{ route('bulksms-group.edit', $bulksmsGroup->id) }}"
            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
            title="" data-title="{{ __('Edit Group') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission

@permission('group_contact delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['bulksms-group.destroy', $bulksmsGroup->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a class="btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $bulksmsGroup->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
