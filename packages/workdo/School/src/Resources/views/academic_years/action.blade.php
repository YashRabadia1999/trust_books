@permission('school_academic_year edit')
<div class="action-btn me-2">
    <a data-url="{{ route('school.academic-year.edit', $row->id) }}"
       class="mx-3 btn bg-info btn-sm align-items-center text-white"
       data-bs-toggle="tooltip" data-ajax-popup="true" data-size="lg"
       data-title="{{ __('Edit Academic Year') }}" title="{{ __('Edit') }}">
       <i class="ti ti-pencil"></i>
    </a>
</div>
@endpermission

@permission('school_academic_year delete')
<div class="action-btn me-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['school.academic-year.destroy', $row->id]]) !!}
    <a href="#!" class="mx-3 btn btn-sm bg-danger align-items-center text-white show_confirm"
       data-bs-toggle="tooltip" title='Delete'>
       <i class="ti ti-trash"></i>
    </a>
    {!! Form::close() !!}
</div>
@endpermission
