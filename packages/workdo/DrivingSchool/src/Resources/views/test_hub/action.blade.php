@permission('driving testhub show')
    <div class="action-btn me-2">
        <a data-size="md" data-url="{{ route('driving_test_hub.show', \Crypt::encrypt($test_hub->id)) }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('View') }}" data-title="{{ __('Test Hub Details') }}"
            class="mx-3 btn btn-sm align-items-center bg-warning">
            <i class="ti ti-eye"></i>
        </a>
    </div>
@endpermission

@permission('driving testhub edit')
    <div class="action-btn me-2">
        <a class="mx-3 btn btn-sm align-items-center bg-info" data-url="{{ route('driving_test_hub.edit', $test_hub['id']) }}"
            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit Test Hub') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission

@permission('driving testhub delete')
    {{ Form::open(['route' => ['driving_test_hub.destroy', $test_hub['id']], 'class' => 'action-btn']) }}
    @method('DELETE')
    <a class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm bg-danger" data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $test_hub['id'] }}"><i class="ti ti-trash text-white text-white"></i></a>
    {{ Form::close() }}
@endpermission
