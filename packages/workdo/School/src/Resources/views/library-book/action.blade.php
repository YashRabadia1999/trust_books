@permission('library_books edit')
<div class="action-btn me-2">
    <a class="mx-3 btn bg-info btn-sm  align-items-center"
        data-url="{{ route('library-books.edit', \Crypt::encrypt($book->id)) }}"
        data-ajax-popup="true" data-size="" data-bs-toggle="tooltip"
        title="" data-title="{{ __('Edit Book') }}"
        data-bs-original-title="{{ __('Edit') }}">
        <i class="ti ti-pencil text-white"></i>
    </a>
</div>
@endpermission

@permission('library_books delete')
<div class="action-btn  me-2">
    {{ Form::open(['route' => ['library-books.destroy', $book->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
        data-bs-toggle="tooltip" title=""
        data-bs-original-title="Delete" aria-label="Delete"
        data-confirm="{{ __('Are You Sure?') }}"
        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
        data-confirm-yes="delete-form-{{ $book->id }}"><i
            class="ti ti-trash text-white text-white"></i></a>
    {{ Form::close() }}
</div>
@endpermission
