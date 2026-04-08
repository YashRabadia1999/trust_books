{{-- repeter --}}
<script>
    var selector = "body";
    if ($(selector + " .repeater").length) {
        var $dragAndDrop = $("body .repeater tbody").sortable({
            handle: '.sort-handler'
        });
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
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                    $(this).remove();

                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));
                    $('.totalAmount').html(subTotal.toFixed(2));
                }
            },
            ready: function(setIndexes) {
                $dragAndDrop.on('drop', setIndexes);
            },
            isFirstItemUndeletable: true
        });
        var value = $(selector + " .repeater").attr('data-value');
        if (typeof value != 'undefined' && value.length != 0) {
            value = JSON.parse(value);
            $repeater.setList(value);
        }
    }
</script>

@if ($action == 'edit')
    <script>
        $(document).ready(function() {
            var selector = "body";

            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    changeItem(tr.find('.Class'));
                }
            }
        });
    </script>

    <script>
        function changeItem(element) {
            var class_id = element.val();

            var url = element.data('url');
            var invoice_id = $('#driving_invoice').val();
            var el = element;


            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'class_id': class_id,
                    'invoice_id': invoice_id
                },
                cache: false,
                success: function(data) {
                    var price = data.quantity * data.fees;
                    $(el.parent().parent().find('.total_price')).html(price);


                    var totalPrice = 0;
                    var priceInput = $('.total_price');

                    for (var i = 0; i < priceInput.length; i++) {
                        totalPrice += parseFloat($(priceInput[i]).html());
                    }

                    $('.totalAmount').html(totalPrice.toFixed(2));

                },
            });
        }
        $(document).on('click', '[data-repeater-create]', function() {
            $('.item :selected').each(function() {
                var id = $(this).val();
                $(".item option[value=" + id + "]").addClass("d-none");
            });
        })
    </script>
@endif

{{-- class pr thi data --}}
<script>
    $(document).on('change', '.Class', function() {
        var product_type = $(this).val();
        var fees = $(this).val();
        var el = $(this);

        $.ajax({
            url: '{{ route('student.get.item') }}',
            type: 'post',
            data: {
                "product_type": product_type,
                "fees": fees,
                "_token": "{{ csrf_token() }}",
            },

            success: function(data) {

                $(el.parent().parent().find('.quantity')).val(data.quantity);
                $(el.parent().parent().find('.fees')).val(data.fees);
                var price = data.quantity * data.fees;

                $(el.parent().parent().find('.total_price')).html(price);


                var totalPrice = 0;
                var priceInput = $('.total_price');

                for (var i = 0; i < priceInput.length; i++) {
                    totalPrice += parseFloat($(priceInput[i]).html());
                }

                $('.totalAmount').html(totalPrice.toFixed(2));
            },
            error: function(xhr, status, error) {
            }
        });
    });
</script>

<div class="card-body mt-3">
    <div class="table-responsive">
        <table class="table mb-0 table-custom-style" data-repeater-list="items" id="sortable-table">
            <thead>
                <tr>
                    <th>{{ __('Class') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('fees') }}</th>
                    <th>{{ __('Amount')}}</th>
                    <th class="text-end">{{ __('Action') }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody class="ui-sortable">
                <tr id="itemContainer" data-repeater-item>
                    {{ Form::hidden('id', null, ['class' => 'form-control id']) }}
                    <td>
                        <select class="form-control class-select Class" name="driving_class_id"
                            data-url="{{ route('student.item') }}"required style="width: 200px">
                            <option value="">{{ __('Select Class') }}</option>
                            @foreach ($student_class as $id => $name)
                                <option value="{{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div style="width: 160px">
                            {{ Form::number('quantity', '', ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required', 'readonly' => 'readonly']) }}
                        </div>
                    </td>
                    <td>
                        <div class="price-input" style="width: 160px">
                            {{ Form::number('fees', '', ['class' => 'form-control fees', 'id' => 'fees', 'required' => 'required', 'placeholder' => __('fees'), 'required' => 'required', 'readonly' => 'readonly']) }}
                        </div>
                    </td>
                    <td class="text-right total_price mb-4" name="total_price">{{ __('0.00') }}</td>
                    <td class="text-end">
                        <div class="action-btn">
                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm bg-danger" data-repeater-delete data-toggle="tooltip" title="{{__('Delete')}}">
                                <i class="ti ti-trash text-white text-white"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="blue-text"><strong>{{ __('Total Amount') }}
                        </strong></td>
                    <td class="text-right totalAmount blue-text">{{ __(0.0) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
