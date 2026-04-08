@permission('pet_vaccines edit')
    <div class="action-btn me-2">
        <a data-size="lg" data-url="{{ route('pet.vaccines.edit', $pet_vaccine->id) }}"
            data-ajax-popup="true" class="mx-3 btn btn-sm align-items-center bg-info  "
            data-bs-toggle="tooltip" data-title="{{ __('Edit Pet Vaccine') }}" title="{{ __('Edit') }}"><i
                class="ti ti-pencil text-white"></i></a>
    </div>
@endpermission
@permission('pet_vaccines delete')
    <div class="action-btn">
        {!! Form::open(['method' => 'DELETE', 'route' => ['pet.vaccines.destroy', $pet_vaccine->id]]) !!}
        <a href="#!" class="mx-3 btn btn-sm   align-items-center bg-danger show_confirm" data-bs-toggle="tooltip"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}" title={{ __('Delete') }}>
            <i class="ti ti-trash text-white"></i>
        </a>
        {!! Form::close() !!}
    </div>
@endpermission
