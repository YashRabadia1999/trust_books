
<span>
@if (!empty($parent['parent_id']))
        @permission('school_parent show')
            <div class="action-btn me-2">
                <a href="{{ route('school-parent.show', $parent['id']) }}" 
                   class="mx-3 btn bg-warning btn-sm align-items-center text-white"
                   data-bs-toggle="tooltip" title="{{ __('View') }}">
                    <i class="ti ti-eye"></i>
                </a>
            </div>
        @endpermission
    @endif
    @permission('school_parent edit')
        <div class="action-btn me-2">
            <a href="{{ route('school-parent.edit', \Crypt::encrypt($parent->id)) }}"
                class="mx-3 btn bg-info btn-sm  align-items-center"
                data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Edit') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>
    @endpermission
    @if (!empty($parent['parent_id']))
        @permission('school_parent delete')
            <div class="action-btn  me-2">
                {{ Form::open(['route' => ['school-parent.destroy', $parent['user_id']], 'class' => 'm-0']) }}
                @method('DELETE')
                <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
                    data-bs-toggle="tooltip" title=""
                    data-bs-original-title="Delete" aria-label="Delete"
                    data-confirm="{{ __('Are You Sure?') }}"
                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                    data-confirm-yes="delete-form-{{ $parent['id'] }}"><i
                        class="ti ti-trash text-white text-white"></i></a>
                {{ Form::close() }}
            </div>
        @endpermission
    @endif
</span>
