@permission('school_classroom edit')
<div class="action-btn  me-2">
    <a data-url="{{ route('classroom.edit', $classroom->id) }}"
        class="mx-3 btn bg-info btn-sm  align-items-center text-white "
        data-bs-toggle="tooltip" data-ajax-popup="true" data-size="lg"
        data-title="{{ __('Edit Class') }}"title="{{ __('Edit') }}"><i
            class="ti ti-pencil"></i></a>
</div>
@endpermission
@permission('school_classroom delete')
<div class="action-btn me-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['classroom.destroy', $classroom->id]]) !!}
    <a href="#!"
        class="mx-3 btn btn-sm bg-danger align-items-center text-white show_confirm"
        data-bs-toggle="tooltip" title='Delete'>
        <i class="ti ti-trash"></i>
    </a>
    {!! Form::close() !!}
</div>
@endpermission
