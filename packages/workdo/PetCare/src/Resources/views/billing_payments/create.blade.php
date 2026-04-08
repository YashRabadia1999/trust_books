{{ Form::open(array('route' => ['petcare.billing.payments.store'],'enctype' => 'multipart/form-data','method' => 'POST' , 'class'=>'needs-validation','novalidate')) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden" name="appointmentId" value="{{ $appointmentId }}">
        <div class="form-group col-md-6">
            {{ Form::label('payer_name', __('Payer Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('payer_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Payer Name')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::number('amount',$getDueAmount, array('class' => 'form-control','required'=>'required','min'=>'0','step'=>'0.01','placeholder' => __('Enter Amount'),'max' => $getDueAmount)) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('payment_method', __('Payment Method'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::select('payment_method', $payment_method, null, ['class' => 'form-control ','required'=>'required','placeholder' => __('Select Payment Method')]) }}
        </div>       
        <div class="form-group col-md-6">
            {{ Form::label('payment_date', __('Payment Date'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::date('payment_date',date('Y-m-d'), ['class' => 'form-control ','required'=>'required','placeholder' => __('Select Date'),'max' => date('Y-m-d')]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::text('reference',null, array('class' => 'form-control','placeholder' => __('Enter Reference'),'required'=>'required')) }}
        </div>
        <div class="form-group col-md-6 mb-0">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <label for="add_receipt" class="form-label">
                    <input type="file" name="add_receipt" id="add_receipt" class="form-control me-3" style="width: 365px;" onchange="document.getElementById('add_receipt_preview').src = window.URL.createObjectURL(this.files[0])">
                </label>
                <p class="text-danger d-none" id="validation">{{ __('This field is required.') }}</p>
                <img id="add_receipt_preview" class="mt-2 mb-0" width="35%" src="" />
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::textarea('description',null, array('class' => 'form-control','placeholder' => __('Enter Description'),'rows'=>3)) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>

{{ Form::close() }}


