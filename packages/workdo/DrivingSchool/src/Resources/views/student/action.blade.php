@permission('drivingstudent show')
    <div class="action-btn me-2">
        <a data-size="md" data-url="{{ route('driving-student.show', \Crypt::encrypt($student->id)) }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('View') }}" data-title="{{ __('Student Details') }}"
            class="mx-3 btn btn-sm  align-items-center bg-warning">
            <i class="ti ti-eye"></i>
        </a>
    </div>
@endpermission
@permission('drivingstudent edit')
    <div class="action-btn me-2">
        <a class="mx-3 btn btn-sm  align-items-center bg-info" data-url="{{ route('driving-student.edit', $student->id) }}"
            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
            data-title="{{ __('Edit Student') }}" data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@if (in_array($student->id, $student_id))
    @permission('drivingstudent delete')
        {{ Form::open(['route' => ['driving-student.destroy', $student->id], 'class' => 'action-btn']) }}
        @method('DELETE')
        <a class="mx-3 btn btn-sm  align-items-center show_confirm bg-danger" data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $student->id }}"><i class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    @endpermission
@endif
