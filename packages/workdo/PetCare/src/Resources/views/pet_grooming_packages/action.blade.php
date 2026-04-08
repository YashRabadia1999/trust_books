@permission('pet_grooming_packages show')
    <div class="action-btn me-2">
        <a data-size="lg" data-url="{{ route('pet.grooming.packages.show', $pet_grooming_package->id) }}"
            data-ajax-popup="true" class="mx-3 btn btn-sm bg-warning align-items-center"
            data-bs-toggle="tooltip" data-title="{{ __('Pet Grooming Package Details') }}" title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_grooming_packages edit')
    <div class="action-btn me-2">
        <a data-size="lg" data-url="{{ route('pet.grooming.packages.edit', $pet_grooming_package->id) }}"
            data-ajax-popup="true" class="mx-3 btn btn-sm align-items-center bg-info  "
            data-bs-toggle="tooltip" data-title="{{ __('Edit Pet Grooming Package') }}" title="{{ __('Edit') }}"><i
                class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_grooming_packages delete')
    <div class="action-btn">
        {!! Form::open(['method' => 'DELETE', 'route' => ['pet.grooming.packages.destroy', $pet_grooming_package->id]]) !!}
        <a href="#!" class="mx-3 btn btn-sm   align-items-center bg-danger show_confirm" data-bs-toggle="tooltip"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}" title={{ __('Delete') }}>
            <i class="ti ti-trash text-white"></i>
        </a>
        {!! Form::close() !!}
    </div>
@endpermission
