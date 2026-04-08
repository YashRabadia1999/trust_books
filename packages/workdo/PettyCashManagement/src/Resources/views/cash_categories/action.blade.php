@permission('categories edit')
<div class="action-btn me-2">
    <a href="#" class="mx-3 bg-info btn btn-sm align-items-center" data-url="{{ route('cash_categories.edit',$PettyCashCategorie->id) }}" data-size="md" class="dropdown-item" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}" data-title="{{__('Edit Categories')}}">
        <span class="text-white"  data-title="{{__('Edit Categories')}}">
            <i class="ti ti-pencil"></i>
        </span>
    </a>
</div>
@endpermission


@permission('categories delete')
<div class="action-btn me-2">
    {{ Form::open(['route' => ['cash_categories.destroy', $PettyCashCategorie->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a href="#" class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm-yes="delete-form-{{ $PettyCashCategorie->id }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
        <i class="ti ti-trash text-white text-white"></i>
    </a>
    {{ Form::close() }}
</div>
@endpermission
