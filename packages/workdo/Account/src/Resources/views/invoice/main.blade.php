@php
    $company_settings = getCompanyAllSetting();
    $repeaterItems = collect([null]);
    if ($acction == 'edit' && !empty($invoice) && !empty($invoice->items) && $invoice->items->count() > 0) {
        $repeaterItems = $invoice->items;
    }
@endphp

<h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Items') }}</h5>
<div class="card repeater"
    @if ($acction == 'edit') data-value="{{ base64_encode(json_encode($invoice->items)) }}" @endif>
    <div class="item-section p-3 pb-0">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-12 d-flex align-items-center justify-content-md-end px-4">
                <a href="#" data-repeater-create="" class="btn btn-primary mr-2" data-toggle="modal"
                    data-target="#add-bank">
                    <i class="ti ti-plus"></i> {{ __('Add Item') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-body table-border-style">
        <div class="table-responsive">
            <table class="table  mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
                <thead>
                    <tr>
                        <th>{{ __('Item Type') }}</th>
                        <th>{{ __('Items') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Price') }} </th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Tax') }} (%)</th>
                        <th class="text-end">{{ __('Amount') }} <br><small
                                class="text-danger font-weight-bold">{{ __('After discount & tax') }}</small></th>
                        <th></th>
                    </tr>
                </thead>

                @foreach ($repeaterItems as $rowItem)
                    <tbody class="ui-sortable" data-repeater-item>
                        <tr>
                            {{ Form::hidden('id', !empty($rowItem) ? $rowItem->id : null, ['class' => 'form-control id']) }}
                            <td class="form-group pt-0">
                                {{ Form::select('product_type', $product_type, !empty($rowItem) ? $rowItem->product_type : null, ['class' => 'form-control product_type ', 'required' => 'required', 'placeholder' => '--']) }}
                            </td>
                            <td width="25%" class="form-group pt-0 product_div">
                                <div class="input-group">
                                    <select name="item" class="form-control product_id item  js-searchBox"
                                        data-url="{{ route('invoice.product') }}" required>
                                        <option value="0">{{ '--' }}</option>
                                        @foreach ($product_services as $key => $product_service)
                                            <option value="{{ $key }}"
                                                {{ !empty($rowItem) && (string) $rowItem->product_id === (string) $key ? 'selected' : '' }}>
                                                {{ $product_service }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary quick-add-service-btn" type="button"
                                        data-title="{{ __('Create New Service') }}"
                                        data-url="{{ route('product-service.quick.create') }}" data-size="lg"
                                        data-ajax-popup="true">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                                @if (empty($product_services_count))
                                    <div class=" text-xs">{{ __('Please create Product first.') }}<a
                                            href="{{ route('product-service.index') }}"><b>{{ __('Add Product') }}</b></a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="form-group price-input input-group search-form mb-0" style="width: 160px">
                                    {{ Form::text('quantity', !empty($rowItem) ? $rowItem->quantity : '', ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required']) }}
                                    <span class="unit input-group-text bg-transparent"></span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group price-input input-group search-form mb-0" style="width: 160px">
                                    {{ Form::text('price', !empty($rowItem) ? $rowItem->price : '', ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price'), 'required' => 'required']) }}
                                    <span
                                        class="input-group-text bg-transparent">{{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group price-input input-group search-form mb-0" style="width: 160px">
                                    {{ Form::text('discount', !empty($rowItem) ? $rowItem->discount : '', ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount')]) }}
                                    <span
                                        class="input-group-text bg-transparent">{{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0">
                                    <div class="input-group colorpickerinput">
                                        <div class="taxes"></div>
                                        {{ Form::hidden('tax', !empty($rowItem) ? $rowItem->tax : '', ['class' => 'form-control tax text-dark']) }}
                                        {{ Form::hidden('itemTaxPrice', '', ['class' => 'form-control itemTaxPrice']) }}
                                        {{ Form::hidden('itemTaxRate', '', ['class' => 'form-control itemTaxRate']) }}
                                    </div>
                                </div>
                            </td>

                            <td class="text-end amount">{{ __('0.00') }}</td>
                            <td>
                                <a href="#" class="action-btn ms-2 float-end" data-repeater-delete>
                                    <div class="mx-3 btn btn-sm d-inline-flex align-items-center m-2 p-2 bg-danger">
                                        <i class="ti ti-trash text-white" data-bs-toggle="tooltip"
                                            data-bs-original-title="Delete"></i>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="form-group mb-0">
                                    {{ Form::textarea('description', !empty($rowItem) ? $rowItem->description : null, ['class' => 'form-control pro_description', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                </div>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                @endforeach
                <tfoot>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td><strong>{{ __('Sub Total') }}
                                ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                        </td>
                        <td class="text-end subTotal">{{ __('0.00') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td><strong>{{ __('Discount') }}
                                ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                        </td>
                        <td class="text-end totalDiscount">{{ __('0.00') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td><strong>{{ __('Tax') }}
                                ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                        </td>
                        <td class="text-end totalTax">{{ __('0.00') }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="blue-text"><strong>{{ __('Total Amount') }}
                                ({{ isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '' }})</strong>
                        </td>
                        <td class="text-end totalAmount blue-text">{{ __('0.00') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    var selector = "body";
    if ($(selector + " .repeater").length) {
        var $dragAndDrop = null;
        if ($.fn.sortable) {
            $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
        }
        var $repeater = $(selector + ' .repeater').repeater({
            initEmpty: false,
            defaultValues: {
                'status': 1
            },
            show: function() {
                $(this).slideDown();
                var file_uploads = $(this).find('input.multi');
                if (file_uploads.length) {
                    $(this).find('input.multi').MultiFile({
                        max: 3,
                        accept: 'png|jpg|jpeg',
                        max_size: 2048
                    });
                }
                // for item SearchBox ( this function is  custom Js )
                JsSearchBox();
            },
            hide: function(deleteElement) {
                var $row = $(this); // The current row
                var id = $row.find('.id').val(); // Get the item ID

                // Call the global delete function
                deleteInvoiceItem($row, id, function() {
                    // Call the global delete function
                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                    }
                    var totalItemPrice = 0;
                    var inputs_quantity = $(".quantity");
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(
                            inputs_quantity[j].value));
                    }
                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    var totalItemDiscountPrice = 0;
                    var itemDiscountPriceInput = $('.discount');
                    for (var k = 0; k < itemDiscountPriceInput.length; k++) {
                        if (itemDiscountPriceInput[k].value == '') {
                            itemDiscountPriceInput[k].value = parseFloat(0);
                        }
                        totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                    }
                    $('.subTotal').html(totalItemPrice.toFixed(2));
                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                    $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
                });
            },
            ready: function(setIndexes) {
                if ($dragAndDrop) {
                    $dragAndDrop.on('drop', setIndexes);
                }
            },
            isFirstItemUndeletable: true
        });
        var value = $(selector + " .repeater").attr('data-value');
        if ('{{ $acction }}' !== 'edit' && typeof value != 'undefined' && value.length != 0) {
            try {
                value = JSON.parse(atob(value));
                $repeater.setList(value);
            } catch (error) {
                console.error('Failed to parse invoice items for repeater:', error);
            }
        }
    }
</script>

@if ($acction == 'edit')
    <script>
        $(document).ready(function() {
            $("#customer").trigger('change');

            var value = $(selector + " .repeater").attr('data-value');
            var type = '{{ $type }}';
            if (typeof value != 'undefined' && value.length != 0) {
                try {
                    value = JSON.parse(atob(value));
                } catch (error) {
                    console.error('Failed to parse invoice items in edit mode:', error);
                    value = [];
                }

                // Remove delete button for first row
                $('.repeater [data-repeater-item]').first().find('[data-repeater-delete]').remove();

                // Fallback hydration: map invoice items to repeater rows by index.
                // This avoids blank edit rows when repeater setList cannot map complex row markup reliably.
                var createBtn = $('.repeater [data-repeater-create]').first();
                var itemRows = $('.repeater [data-repeater-item]');

                while (itemRows.length < value.length) {
                    createBtn.trigger('click');
                    itemRows = $('.repeater [data-repeater-item]');
                }

                for (var i = 0; i < value.length; i++) {
                    var row = $(itemRows[i]);
                    var rowData = value[i] || {};

                    row.find('.id').val(rowData.id || '');
                    row.find('.pro_description').val(rowData.description || '');
                    row.find('.quantity').val(rowData.quantity || '');
                    row.find('.price').val(rowData.price || '');
                    row.find('.discount').val(rowData.discount || 0);
                    row.find('.tax').val(rowData.tax || '');

                    var productTypeElement = row.find('.product_type');
                    if (rowData.product_type) {
                        productTypeElement.val(rowData.product_type);
                    }

                    if (type == 'product' || type == 'salesagent') {
                        ProductType(productTypeElement, rowData.product_id, 'edit');
                    } else {
                        row.find('.item').val(rowData.product_id || '0');
                    }
                }

                if (type == 'salesagent') {
                    $('.item-section').addClass('d-none');
                }
            }
            const elementsToRemove = document.querySelectorAll('.bs-pass-para.repeater-action-btn');
            if (elementsToRemove.length > 0) {
                elementsToRemove[0].remove();
            }
        });
    </script>
    <script>
        var invoice_id = '{{ $invoice->id }}';

        function changeItem(element) {

            var iteams_id = element.val();

            var url = element.data('url');
            var el = element;

            $.ajax({
                url: '{{ route('invoice.items') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'invoice_id': invoice_id,
                    'product_id': iteams_id,
                },

                cache: false,
                success: function(data) {
                    var item = JSON.parse(data);
                    var invoiceItems = item.items;

                    if (invoiceItems != null) {
                        var amount = (invoiceItems.price * invoiceItems.quantity);

                        $(el.closest('tr').find('.quantity')).val(invoiceItems
                            .quantity);
                        $(el.closest('tr').find('.price')).val(invoiceItems.price);
                        $(el.closest('tr').find('.discount')).val(invoiceItems
                            .discount);
                    } else {
                        $(el.closest('tr').find('.quantity')).val(1);
                        $(el.closest('tr').find('.price')).val(item.product.sale_price);
                        $(el.closest('tr').find('.discount')).val(0);
                    }


                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;
                    for (var i = 0; i < item.taxes.length; i++) {
                        taxes +=
                            '<span class="badge bg-primary p-2 px-3 me-1">' +
                            item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                            '</span>';
                        tax.push(item.taxes[i].id);
                        totalItemTaxRate += parseFloat(item.taxes[i].rate);
                    }

                    if (invoiceItems != null) {
                        var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (
                            invoiceItems.price * invoiceItems.quantity));
                    } else {
                        var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item
                            .product.sale_price * 1));
                    }

                    $(el.closest('tr').find('.itemTaxPrice')).val(itemTaxPrice.toFixed(
                        2));
                    $(el.closest('tr').find('.itemTaxRate')).val(totalItemTaxRate
                        .toFixed(2));
                    $(el.closest('tr').find('.taxes')).html(taxes);
                    $(el.closest('tr').find('.tax')).val(tax);
                    $(el.closest('tr').find('.unit')).html(item.unit);

                    $(".discount").trigger('change');
                }
            });
        }
    </script>
@endif
