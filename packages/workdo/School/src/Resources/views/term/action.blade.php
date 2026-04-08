@permission('school_term edit')
<div class="action-btn me-2">
    <a data-url="{{ route('school.term.edit', $row->id) }}"
       data-ajax-popup="true"
       data-size="lg"
       class="mx-3 btn bg-info btn-sm align-items-center text-white"
       data-bs-toggle="tooltip"
       title="{{ __('Edit Term') }}">
        <i class="ti ti-pencil"></i>
    </a>
</div>
@endpermission

@permission('school_term delete')
<div class="action-btn me-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['school.term.destroy', $row->id], 'class' => 'delete-form d-inline']) !!}
        <button type="button"
            class="mx-3 btn btn-sm bg-danger align-items-center text-white show_confirm"
            data-bs-toggle="tooltip"
            title="{{ __('Delete') }}">
            <i class="ti ti-trash"></i>
        </button>
    {!! Form::close() !!}
</div>
@endpermission
