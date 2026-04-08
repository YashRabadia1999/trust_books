@permission('drivinginvoice show')
    <div class="action-btn me-2">
        <a href="{{ route('drivinginvoice.show', \Crypt::encrypt($invoice->id)) }}" class="mx-3 btn btn-sm align-items-center bg-warning" 
            data-bs-toggle="tooltip" title="{{ __('View') }}">
            <i class="ti ti-eye  text-white"></i>
        </a>
    </div>
@endpermission
@permission('drivinginvoice edit')
    <div class="action-btn me-2">
        <a href="{{ route('drivinginvoice.edit', \Crypt::encrypt($invoice->id)) }}"
            class="mx-3 btn btn-sm  align-items-center bg-info" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('drivinginvoice delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['drivinginvoice.destroy', $invoice->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger" data-bs-toggle="tooltip"
            title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $invoice->id }}">
            <i class="ti ti-trash text-white text-white"></i>
        </a>
        {{ Form::close() }}
    </div>
@endpermission
