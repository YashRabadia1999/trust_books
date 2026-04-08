{!! Form::model($reimbursement, ['route' => ['reimbursement.update', $reimbursement->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) !!}

<div class="modal-body">
    <div class="col-md-12">
        <div class="form-group">
            @if(in_array(Auth::user()->type, Auth::user()->not_emp_type))
                {{-- Admin/Manager View --}}
                {{ Form::label('user_id', __('Select Employee'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('user_id', $user, $reimbursement->user_id, ['class' => 'form-control', 'placeholder' => __('Select employee'), 'required' => true]) }}
            @else
                {{-- Employee View --}}
                {{ Form::label('user_name', __('Employee Name'), ['class' => 'form-label']) }}
                <input type="text" class="form-control" value="{{ $reimbursement->user->name }}" readonly>
                {{ Form::hidden('user_id', $reimbursement->user_id) }}
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::select('category_id', $categories, $reimbursement->category_id, ['class' => 'form-control', 'placeholder' => __('Select category'), 'required' => true]) }}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('amount', __('Requested Amount'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('amount', $reimbursement->amount, ['class' => 'form-control', 'step' => '0.01', 'required' => true, 'placeholder' => __('Enter requested amount')]) }}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
            {{ Form::textarea('remarks', $reimbursement->remarks, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Any additional remarks')]) }}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{-- Display Existing Receipt --}}
            @if($reimbursement->receipt_path)
                <div class="mb-2">
                    <label for="existing_receipt" class="form-label">{{__('Existing Receipt:')}}</label>
                    <br>
                    <img src="{{ asset($reimbursement->receipt_path) }}" alt="Receipt" class="img-thumbnail" style="max-width: 200px;">
                </div>
            @endif

            {{-- Upload New Receipt --}}
            {!! Form::label('receipt', __('Upload New Receipt'), ['class' => 'form-label']) !!}
            {!! Form::file('receipt', ['class' => 'form-control' . ($errors->has('receipt') ? ' is-invalid' : '')]) !!}
            @error('receipt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {!! Form::submit(__('Update Request'), ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
