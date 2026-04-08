@permission('expense delete')
<div class="action-btn me-2">
    {{ Form::open(['route' => ['patty_cash_expense.destroy', $expense->id], 'class' => 'm-0']) }}
    @method('DELETE')
    <a href="#" class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm-yes="delete-form-{{ $expense->id }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
        <i class="ti ti-trash text-white text-white"></i>
    </a>
    {{ Form::close() }}
</div>
@endpermission
