@permission('bulksms_send manage')
<div class="action-btn me-2">
        <a href="{{ route('bulksms-send-sms.show', \Crypt::encrypt($bulksmsSend->id)) }}" 
            class="btn btn-sm align-items-center bg-warning" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-original-title="{{ __('Detail') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('bulksms_send delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['bulksms-send-sms.destroy', $bulksmsSend->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a class="btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $bulksmsSend->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
