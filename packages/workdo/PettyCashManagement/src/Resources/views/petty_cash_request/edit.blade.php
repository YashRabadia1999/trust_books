{{ Form::model($pettyCashRequest, ['route' => ['petty-cash-request.update', $pettyCashRequest->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate' => true]) }}
    <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group">
                @if(in_array(Auth::user()->type, Auth::user()->not_emp_type))
                    {{-- Admin/Manager View --}}
                    {{ Form::label('user_id', __('Select Employee'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::select('user_id', $user, $pettyCashRequest->user_id, ['class' => 'form-control', 'placeholder' => __('Select employee'), 'required' => true]) }}
                @else
                    {{-- Employee View --}}
                    {{ Form::label('user_name', __('Employee Name'), ['class' => 'form-label']) }}
                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                    {{ Form::hidden('user_id', $user->id) }}
                @endif
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('category_id', $categories, $pettyCashRequest->categorie_id, ['class' => 'form-control', 'placeholder' => __('Select category'), 'required' => true]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('requested_amount', __('Requested Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('requested_amount', $pettyCashRequest->requested_amount, ['class' => 'form-control', 'step' => '0.01', 'required' => true, 'placeholder' => __('Enter requested amount')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
                {{ Form::textarea('remarks', $pettyCashRequest->remarks, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Any additional remarks')]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Update Request'), ['class' => 'btn btn-primary']) }}
    </div>
{{ Form::close() }}

