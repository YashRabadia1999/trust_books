@if ($pet_adoption->availability !== 'adopted')
    @permission('pet_adoption_request create')
        <div class="action-btn  me-2">
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Pet Adoption request') }}"
                data-url="{{ route('pet.adoption.request.create',\Illuminate\Support\Facades\Crypt::encrypt($pet_adoption->id)) }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Adoption Request Form') }}">
                <i class="ti ti-file-invoice text-white"></i>
            </a>
        </div>
    @endpermission
@endif
@permission('pet_adoption show')
    <div class="action-btn  me-2">
        <a class="btn btn-sm btn-warning" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Pet Adoption Details') }}"
            data-url="{{ route('pet.adoption.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption->id)) }} }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_adoption edit')
    <div class="action-btn me-2">
        <a class="btn btn-sm btn-info" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Pet Adoption') }}"
            data-url="{{ route('pet.adoption.edit', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption->id)) }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_adoption delete')
    <div class="action-btn">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['pet.adoption.destroy', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption->id)],
        ]) !!}
        <a href="#!" class="btn btn-sm align-items-center show_confirm  bg-danger" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
        {!! Form::close() !!}
    </div>
@endpermission
