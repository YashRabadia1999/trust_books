@permission('drivinglesson show')
    <div class="action-btn me-2">
        <a href="{{ route('lesson.show', \Crypt::encrypt($lesson['id'])) }}" class="mx-3 btn btn-sm align-items-center bg-warning"
            data-bs-toggle="tooltip" title="{{ __('View') }}">
            <i class="ti ti-eye text-white text-white"></i>
        </a>
    </div>
@endpermission
