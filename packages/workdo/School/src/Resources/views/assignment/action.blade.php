@permission('school_assignment edit')
<div class="action-btn me-2">
    <a href="{{ route('school.assignment.edit', $row->id) }}"
       class="mx-3 btn bg-info btn-sm align-items-center text-white"
       data-bs-toggle="tooltip"
       title="{{ __('Edit Assignment') }}">
        <i class="ti ti-pencil"></i>
    </a>
    </div>
@endpermission

@permission('school_assignment delete')
<div class="action-btn me-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['school.assignment.destroy', $row->id], 'class' => 'delete-form d-inline']) !!}
        <button type="button"
            class="mx-3 btn btn-sm bg-danger align-items-center text-white show_confirm"
            data-bs-toggle="tooltip"
            title="{{ __('Delete') }}">
            <i class="ti ti-trash"></i>
        </button>
    {!! Form::close() !!}
</div>
@endpermission
