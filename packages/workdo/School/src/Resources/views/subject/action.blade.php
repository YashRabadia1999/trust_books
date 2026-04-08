@permission('school_subject edit')
<div class="action-btn  me-2">
    <a data-url="{{ route('subject.edit', $subject->id) }}"
        class="mx-3 btn bg-info btn-sm d align-items-center text-white "
        data-bs-toggle="tooltip" data-ajax-popup="true"
        data-title="{{ __('Edit Subject') }}"title="{{ __('Edit') }}"><i
            class="ti ti-pencil"></i></a>
</div>
@endpermission
@permission('school_subject delete')
<div class="action-btn  me-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['subject.destroy', $subject->id]]) !!}
    <a href="#!"
        class="mx-3 btn btn-sm bg-danger align-items-center text-white show_confirm"
        data-bs-toggle="tooltip" title='Delete'>
        <i class="ti ti-trash"></i>
    </a>
    {!! Form::close() !!}
</div>
@endpermission
