@permission('petcare_contacts edit')
    <div class="action-btn me-2">
        <a class="btn btn-sm btn-info" data-ajax-popup="true" data-size="md" data-title="{{ __('Edit Contact') }}"
            data-url="{{ route('petcare.contact.us.edit', \Illuminate\Support\Facades\Crypt::encrypt($contact->id)) }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('petcare_contacts delete')
    <div class="action-btn">
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['petcare.contact.us.destroy', \Illuminate\Support\Facades\Crypt::encrypt($contact->id)],
        ]) !!}
        <a href="#!" class="btn btn-sm align-items-center show_confirm  bg-danger" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
        {!! Form::close() !!}
    </div>
@endpermission
