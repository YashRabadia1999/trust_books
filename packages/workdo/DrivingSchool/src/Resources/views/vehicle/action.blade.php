@permission('drivingvehicle edit')
    <div class="action-btn me-2">
        <a class="mx-3 btn btn-sm  align-items-center bg-info" data-url="{{ route('driving-vehicle.edit', $vehicle->id) }}"
            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title="" data-title="{{ __('Edit vehicle') }}"
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('drivingvehicle delete')
    {{ Form::open(['route' => ['driving-vehicle.destroy', $vehicle->id], 'class' => 'action-btn']) }}
    @method('DELETE')
    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger" data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $vehicle->id }}"><i class="ti ti-trash text-white text-white"></i></a>
    {{ Form::close() }}
@endpermission
