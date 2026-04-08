@if ($pet_appointment->appointment_status !== 'completed' && $pet_appointment->getDueAmount() != 0) 
    @permission('billing_payments create')
    <div class="action-btn  me-2">
        <a class="mx-3 btn bg-primary btn-sm  align-items-center" data-url="{{ route('petcare.billing.payments.create', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
            data-title="{{ __('Create Payment') }}" data-bs-original-title="{{ __('Add Payment') }}">
            <i class="ti ti-caret-right text-white"></i>
        </a>
    </div>
    @endpermission
@endif
@permission('pet_appointments show')
    <div class="action-btn  me-2">
        <a href="{{ route('pet.appointments.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            class="mx-3 btn btn-sm bg-warning align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('billing_payments show')
    <div class="action-btn  me-2">
        <a href="{{ route('petcare.billing.payments.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_appointment->id)) }}"
            class="mx-3 btn btn-sm bg-secondary align-items-center" data-bs-toggle="tooltip" title=""
            data-bs-original-title="{{ __('Payment Summary') }}">
            <i class="ti ti-history text-white"></i>
        </a>
    </div>
@endpermission