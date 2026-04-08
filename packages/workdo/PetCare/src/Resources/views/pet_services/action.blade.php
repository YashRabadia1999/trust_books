@permission('pet_services add features & process')
    <div class="action-btn me-2">
        <a class="btn btn-sm align-items-center bg-success" data-title="{{ __('Add Features & Process') }}"
            href="{{ route('show.features.process.page',\Illuminate\Support\Facades\Crypt::encrypt($pet_service->id)) }}" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Add Features & Process') }}">
            <i class="ti ti-square-plus text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_services edit')
    <div class="action-btn me-2">
        <a data-size="lg" data-url="{{ route('pet.services.edit', $pet_service->id) }}" data-ajax-popup="true"
            class="mx-3 btn btn-sm align-items-center bg-info  " data-bs-toggle="tooltip"
            data-title="{{ __('Edit Pet Service') }}" title="{{ __('Edit') }}"><i
                class="ti ti-pencil text-white"></i></a>
    </div>
@endpermission
@permission('pet_services delete')
    <div class="action-btn">
        {!! Form::open(['method' => 'DELETE', 'route' => ['pet.services.destroy', $pet_service->id]]) !!}
        <a href="#!" class="mx-3 btn btn-sm   align-items-center bg-danger show_confirm" data-bs-toggle="tooltip"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}" title={{ __('Delete') }}>
            <i class="ti ti-trash text-white"></i>
        </a>
        {!! Form::close() !!}
    </div>
@endpermission
