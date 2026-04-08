@if ($pet_adoption_request->request_status !== 'completed' && $pet_adoption_request->getAdoptionRequestDueAmount() != 0) 
    @permission('adoption_request_payments create')
    <div class="action-btn  me-2">
        <a class="mx-3 btn bg-primary btn-sm  align-items-center" data-url="{{ route('pet.adoption.request.payments.create', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption_request->id)) }}"
            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
            data-title="{{ __('Create Adoption Payment') }}" data-bs-original-title="{{ __('Add Payment') }}">
            <i class="ti ti-caret-right text-white"></i>
        </a>
    </div>
    @endpermission
@endif
@permission('pet_adoption_request show')
    <div class="action-btn  me-2">
        <a href="{{ route('pet.adoption.request.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption_request->id)) }}"
            class="mx-3 btn btn-sm bg-warning align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('adoption_request_payments show')
    <div class="action-btn  me-2">
        <a href="{{ route('pet.adoption.request.payments.show', \Illuminate\Support\Facades\Crypt::encrypt($pet_adoption_request->id)) }}"
            class="mx-3 btn btn-sm bg-secondary align-items-center" data-bs-toggle="tooltip" title=""
            data-bs-original-title="{{ __('Payment Summary') }}">
            <i class="ti ti-history text-white"></i>
        </a>
    </div>
@endpermission