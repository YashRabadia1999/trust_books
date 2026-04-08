@if (isset($student->schoolStudent->student_id) && !empty($student->schoolStudent->student_id))
@permission('school_student show')

<div class="action-btn  me-2">
    <!-- <a href="#" data-size="md"
        data-url="{{ route('school-student.show', $student->id) }}"
        data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Show Student') }}"
        title="{{ __('View') }}"class="mx-3 btn bg-warning btn-sm  align-items-center text-white  ">
        <i class="ti ti-eye"></i>
    </a> -->
    <a href="{{ route('school-student.show', $student->id) }}" class="mx-3 btn bg-warning btn-sm align-items-center text-white" 
        data-bs-toggle="tooltip" title="{{ __('View') }}">
           <i class="ti ti-eye"></i>
    </a>
</div>
@endpermission
@endif

@permission('school_student edit')
<div class="action-btn  me-2">
    <a href="{{ route('school-student.edit', \Crypt::encrypt($student->id)) }}"
        class="mx-3 btn bg-info btn-sm  align-items-center"
        data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission
@if (isset($student->schoolStudent->student_id) && !empty($student->schoolStudent->student_id))
@permission('school_student delete')
    <div class="action-btn  me-2">
        {{ Form::open(['route' => ['school-student.destroy', $student->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete" aria-label="Delete"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $student->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
@endif
