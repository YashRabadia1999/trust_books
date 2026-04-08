{{ Form::open(['route' => ['driving-invoice.pay.form', $invoice], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount').' ' .' (' . (!empty(company_setting('defult_currancy_symbol')) ? company_setting('defult_currancy_symbol') : '$') . ')'  , ['class' => 'form-label']) }}
                {{ Form::text('amount', $dueAmount, ['class' => 'form-control', 'required' => 'required' , 'placeholder' => __('Enter Amount')]) }}
                <input type="hidden" name="dueAmount" value="{{$dueAmount}}">
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}"
        onclick="location.href = '{{ route('drivinginvoice.index') }}';" class="btn btn-light ">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}