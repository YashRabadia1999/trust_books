{{ Form::model($pettycash, array('route' => array('petty-cash.update', $pettycash->id), 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate' => true)) }}
    <div class="modal-body">
        <!-- Added Amount -->
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('added_amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('added_amount', old('added_amount', $pettycash->added_amount), [
                    'class' => 'form-control',
                    'step' => '0.01',
                    'min' => '0',
                    'required' => true,
                    'placeholder' => __('Enter amount to add')
                ]) }}
                @if ($errors->has('added_amount'))
                    <small class="text-danger">{{ $errors->first('added_amount') }}</small>
                @endif
            </div>
        </div>

        <!-- Date -->
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::date('date', old('date', $pettycash->date ?? now()->toDateString()), [
                    'class' => 'form-control',
                    'required' => true
                ]) }}
                @if ($errors->has('date'))
                    <small class="text-danger">{{ $errors->first('date') }}</small>
                @endif
            </div>
        </div>

        <!-- Remarks -->
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
                {{ Form::textarea('remarks', old('remarks', $pettycash->remarks), [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => __('Enter any remarks...')
                ]) }}
                @if ($errors->has('remarks'))
                    <small class="text-danger">{{ $errors->first('remarks') }}</small>
                @endif
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
    </div>
{{ Form::close() }}
