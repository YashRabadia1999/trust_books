
@permission('pettycash edit')
<div class="action-btn me-2">
    <a href="#" class="mx-3 bg-info btn btn-sm align-items-center" data-url="{{ route('petty-cash.edit',$patty_cash->id) }}" data-size="md" class="dropdown-item" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}" data-title="{{__('Edit Petty Cash')}}">
        <span class="text-white"  data-title="{{__('Edit Petty Cash')}}">
            <i class="ti ti-pencil"></i>
        </span>
    </a>
</div>
@endpermission

@permission('pettycash delete')
<div class="action-btn me-2">
    {{ Form::open(['route' => ['petty-cash.destroy', $patty_cash->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a href="#" class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm-yes="delete-form-{{ $patty_cash->id }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
        <i class="ti ti-trash text-white text-white"></i>
    </a>
    {{ Form::close() }}
</div>
@endpermission
