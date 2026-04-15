<form id="quick-add-service-form" action="{{ route('product-service.quick.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-pills nav-fill cust-nav information-tab mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab-btn" data-bs-toggle="pill"
                            data-bs-target="#details-tab-modal" type="button">{{ __('Details') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pricing-tab-btn" data-bs-toggle="pill" data-bs-target="#pricing-tab-modal"
                            type="button">{{ __('Pricing') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab-btn" data-bs-toggle="pill" data-bs-target="#media-tab-modal"
                            type="button">{{ __('Media') }}</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <!-- Details Tab -->
            <div class="tab-pane fade show active" id="details-tab-modal" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('sku', __('SKU'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="input-group">
                                {{ Form::text('sku', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter SKU')]) }}
                                <button class="btn btn-outline-primary" type="button" onclick="generateQuickSKU()">{{ __('Generate') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('tax_id', __('Tax'), ['class' => 'form-label']) }}
                        {{ Form::select('tax_id[]', $tax, null, ['class' => 'form-control choices', 'id' => 'tax_id', 'searchEnabled' => 'true', 'multiple']) }}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}<x-required></x-required>
                        {{ Form::select('category_id', $category, null, ['class' => 'form-control', 'required' => 'required']) }}
                    </div>
                    <div class="form-group col-md-12">
                        {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '2', 'placeholder' => __('Enter Description')]) !!}
                    </div>
                    <input type="hidden" name="type" value="service">
                </div>
                <div class="row text-end">
                    <div class="col-12">
                        <button class="btn btn-primary" onClick="changeModalTab('#pricing-tab-btn')" type="button">{{ __('Next') }} <i class="ti ti-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Pricing Tab -->
            <div class="tab-pane fade" id="pricing-tab-modal" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('sale_price', __('Sale Price'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::number('sale_price', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => __('Enter Sale Price')]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('purchase_price', __('Purchase Price'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::number('purchase_price', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => __('Enter Purchase Price')]) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
                        {{ Form::select('unit_id', $unit, null, ['class' => 'form-control']) }}
                    </div>

                    @if(module_is_active('Account'))
                        @include('account::setting.add_column_table', ['productService' => [], 'incomeChartAccounts' => $incomeChartAccounts, 'expenseChartAccounts' => $expenseChartAccounts])
                    @endif
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-outline-secondary" onClick="changeModalTab('#details-tab-btn')" type="button"><i class="ti ti-chevron-left"></i> {{ __('Previous') }}</button>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-primary" onClick="changeModalTab('#media-tab-btn')" type="button">{{ __('Next') }} <i class="ti ti-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- Media Tab -->
            <div class="tab-pane fade" id="media-tab-modal" role="tabpanel">
                <div class="col-12 form-group">
                    {{ Form::label('image', __('Image'), ['class' => 'col-form-label']) }}
                    <div class="choose-file form-group">
                        <label for="file" class="form-label d-block">
                            <input type="file" class="form-control file" name="image" id="file"
                                onchange="document.getElementById('quick-blah').src = window.URL.createObjectURL(this.files[0])">
                            <hr>
                            <img id="quick-blah" src="{{ asset('packages/workdo/ProductService/src/Resources/assets/image/img01.jpg') }}" alt="your image" width="100" height="100" />
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-outline-secondary" onClick="changeModalTab('#pricing-tab-btn')" type="button"><i class="ti ti-chevron-left"></i> {{ __('Previous') }}</button>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-primary" id="submit-quick-add-service">{{ __('Create') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    function changeModalTab(targetBtnId) {
        $(targetBtnId).trigger('click');
    }

    function generateQuickSKU() {
        var sku = 'SKU-' + Math.random().toString(24).substr(2, 7);
        $('input[name=sku]').val(sku.toUpperCase());
    }
</script>
