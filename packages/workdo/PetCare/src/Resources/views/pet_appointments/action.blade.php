@permission('pet_appointments status update')
    <div class="action-btn  me-2">
        <a data-size="md"
            data-url="{{ route('pet.appointments.status.edit', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            data-ajax-popup="true" class="mx-3 btn btn-sm align-items-center bg-success  " data-bs-toggle="tooltip"
            data-title="{{ __('Update Appointment Status') }}" title="{{ __('Update Appointment Status') }}">
            <i class="ti ti-activity text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_appointments show')
    <div class="action-btn  me-2">
        <a href="{{ route('pet.appointments.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            class="mx-3 btn btn-sm bg-warning align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_appointments edit')
    <div class="action-btn me-2">
        <a class="btn btn-sm align-items-center bg-info"
            href="{{ route('pet.appointments.edit', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            data-title="{{ __('Edit Appointment') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
            data-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('pet_appointments delete')
    <div class="action-btn">
        {!! Form::open(['method' => 'DELETE', 'route' => ['pet.appointments.destroy', $pet_appointment->id]]) !!}
        <a href="#!" class="btn btn-sm align-items-center show_confirm  bg-danger" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
        {!! Form::close() !!}
    </div>
@endpermission
