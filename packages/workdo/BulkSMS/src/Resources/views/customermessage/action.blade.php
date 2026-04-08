@permission('bulksms_contact manage')
    <a href="#" data-url="{{ route('customer-messages.show', $message->id) }}" data-size="md" data-ajax-popup="true"
        data-title="{{ __('View Message') }}" data-bs-toggle="tooltip" title="{{ __('View') }}"
        class="btn btn-sm btn-warning btn-icon">
        <i class="ti ti-eye"></i>
    </a>
@endpermission

@permission('bulksms_contact edit')
    <a href="#" data-url="{{ route('customer-messages.edit', $message->id) }}" data-size="lg" data-ajax-popup="true"
        data-title="{{ __('Edit Message Template') }}" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
        class="btn btn-sm btn-info btn-icon">
        <i class="ti ti-pencil"></i>
    </a>
@endpermission

<div class="btn-group" role="group">
    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="ti ti-send"></i> {{ __('Send') }}
    </button>
    <ul class="dropdown-menu">
        @permission('bulksms_contact create')
            <li>
                <a class="dropdown-item" href="#"
                    data-url="{{ route('customer-messages.send-single', $message->id) }}" data-ajax-popup="true"
                    data-size="lg" data-title="{{ __('Send as Single SMS') }}">
                    <i class="ti ti-user"></i> {{ __('Send as Single SMS') }}
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#" data-url="{{ route('customer-messages.send-bulk', $message->id) }}"
                    data-ajax-popup="true" data-size="lg" data-title="{{ __('Send as Bulk SMS') }}">
                    <i class="ti ti-users"></i> {{ __('Send as Bulk SMS') }}
                </a>
            </li>
        @endpermission
    </ul>
</div>

@permission('bulksms_contact delete')
    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['customer-messages.destroy', $message->id],
        'class' => 'd-inline',
    ]) !!}
    <a href="#" class="btn btn-sm btn-danger btn-icon show_confirm" data-bs-toggle="tooltip"
        title="{{ __('Delete') }}">
        <i class="ti ti-trash"></i>
    </a>
    {!! Form::close() !!}
@endpermission
