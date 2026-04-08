<link href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />

{{ Form::model($petGroomingPackage, ['route' => ['pet.grooming.packages.update', $petGroomingPackage->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12" id="icons">
            {{ Form::label('package_icon', __('Package Icon'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-group col-md-12">
                <input type="text" id="icon-search" class="form-control mb-4" placeholder="{{ __('search . .') }}">
            </div>
            <div class="form-group col-md-12">
                <div class="i-main" id="icon-wrapper" style="max-height: 100px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 10px;">
                </div>
            </div>
            <div class="form-group col-md-12">
                <input type="text" id="icon-input" name="package_icon" class="form-control"
                    placeholder="{{ __('Selected Icon') }}" readonly value="{{ old('package_icon', $petGroomingPackage->package_icon) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('package_name', __('Package Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('package_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Package Name')]) }}
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                {{ Form::label('total_package_amount', __('Package Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('total_package_amount', $petGroomingPackage->total_package_amount, ['class' => 'form-control', 'required' => 'required', 'id' => 'totalAmount', 'step' => '0.01', 'placeholder' => __('Enter Package Amount')]) }}
            </div>
        </div>
    </div>

    {{-- Services Section --}}
    <div class="mb-4 border p-3 rounded">
        <div class="form-group d-flex justify-content-between align-items-center mb-2">
            {{ Form::label('services', 'Services', ['class' => 'form-label']) }}
            <a href="javascript:void(0);" class="btn btn-sm btn-primary add_button" data-type="service">
                <i class="ti ti-plus"></i> {{ __('Add Service') }}
            </a>
        </div>
        <div class="field_wrapper" data-type="service">
            @if ($packageServices->count())
                @foreach ($packageServices as $i => $service)
                    <div class="row mb-2 add-fild">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            {{ Form::select('services[]', $allServices, $service->id, ['class' => 'form-control service-select', 'placeholder' => __('Select Service')]) }}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            {{ Form::number('service_prices[]', $service->pivot->service_price ?? 0.0, ['class' => 'form-control service-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0);" id="delete-pet-service"
                                class="remove_button btn btn-danger btn-sm bs-pass-para" data-toggle="tooltip"
                                title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"> <i
                                    class="ti ti-trash text-white"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row mb-2 add-fild">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        {{ Form::select('services[]', $allServices, null, ['class' => 'form-control service-select', 'placeholder' => __('Select Service')]) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        {{ Form::number('service_prices[]', 0.0, ['class' => 'form-control service-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" id="delete-pet-service"
                            class="remove_button btn btn-danger btn-sm bs-pass-para" data-toggle="tooltip"
                            title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"> <i
                                class="ti ti-trash text-white"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Vaccines Section --}}
    <div class="mb-4 border p-3 rounded">
        <div class="form-group d-flex justify-content-between align-items-center mb-2">
            {{ Form::label('vaccines', __('Vaccines'), ['class' => 'form-label']) }}
            <a href="#" class="btn btn-sm btn-primary add_button" data-type="vaccine">
                <i class="ti ti-plus"></i> {{ __('Add Vaccine') }}
            </a>
        </div>
        <div class="field_wrapper" data-type="vaccine">
            @if ($packageVaccines->count())
                @foreach ($packageVaccines as $i => $vaccine)
                    <div class="row mb-2 add-fild">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            {{ Form::select('vaccines[]', $allVaccines, $vaccine->id, ['class' => 'form-control vaccine-select', 'placeholder' => __('Select Vaccine')]) }}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            {{ Form::number('vaccine_prices[]', $vaccine->pivot->vaccine_price ?? 0.0, ['class' => 'form-control vaccine-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0);" id="delete-pet-vaccine"
                                class="remove_button btn btn-danger btn-sm bs-pass-para" data-toggle="tooltip"
                                title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"> <i
                                    class="ti ti-trash text-white"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row mb-2 add-fild">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        {{ Form::select('vaccines[]', $allVaccines, null, ['class' => 'form-control vaccine-select', 'placeholder' => __('Select Vaccine')]) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        {{ Form::number('vaccine_prices[]', 0.0, ['class' => 'form-control vaccine-price', 'step' => '0.01', 'placeholder' => __('Price')]) }}
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:void(0);" id="delete-pet-vaccine"
                            class="remove_button btn btn-danger btn-sm bs-pass-para" data-toggle="tooltip"
                            title="{{ __('Delete') }}" data-original-title="{{ __('Delete') }}"> <i
                                class="ti ti-trash text-white"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="form-group">
            {{ Form::label('package_features', __('Package Features'), ['class' => 'form-label']) }}
            {{ Form::text('package_features', null, ['class' => 'form-control', 'id' => 'choices-text-remove-button','placeholder' => __('Enter Features Tags'),'required' => 'required']) }}
        </div>
    </div> 

    {{-- Description --}}
    <div class="mb-3">
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'rows' => 3]) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
</div>

{{ Form::close() }}

<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/repeater.js') }}"></script>
<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('packages/workdo/PetCare/src/Resources/assets/js/choices.min.js') }}"></script>
<script>
    $(document).ready(function() {
        const maxField = 100;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add new Service or Vaccine
        $('.add_button').click(function() {
            const type = $(this).data('type');
            const wrapper = $('.field_wrapper[data-type="' + type + '"]');
            const lastField = wrapper.find('.add-fild').last();
            const fieldHTML = lastField.clone();

            // Reset select and input values
            fieldHTML.find('select').val('');
            fieldHTML.find('input').val('0.00');

            if (wrapper.find('.add-fild').length < maxField) {
                wrapper.append(fieldHTML);
            }
        });

        // Remove Service or Vaccine
        $(document).on('click', '.remove_button', function() {
            const wrapper = $(this).closest('.field_wrapper');
            if (wrapper.find('.add-fild').length <= 1) {
                alert("At least one card must remain. Deletion not allowed.");
                return;
            }
            $(this).closest('.add-fild').remove();
            calculateTotalAmount();
        });

        // Change Service - Fetch Price
        $(document).on('change', '.service-select', function() {
            const select = $(this);
            const serviceId = select.val();
            const priceInput = select.closest('.add-fild').find('.service-price');

            if (serviceId) {
                $.post("{{ route('get.pet.service.price') }}", {
                        serviceId: serviceId
                    },
                    function(res) {
                        priceInput.val(parseFloat(res.servicePrice).toFixed(2));
                        calculateTotalAmount();
                    }).fail(function() {
                    console.error('Failed to fetch service price');
                });
            } else {
                priceInput.val('0.00');
                calculateTotalAmount();
            }
        });

        // Change Vaccine - Fetch Price
        $(document).on('change', '.vaccine-select', function() {
            const select = $(this);
            const vaccineId = select.val();
            const priceInput = select.closest('.add-fild').find('.vaccine-price');

            if (vaccineId) {
                $.post("{{ route('get.pet.vaccine.price') }}", {
                    vaccineId: vaccineId
                }, function(res) {
                    priceInput.val(parseFloat(res.vaccinePrice).toFixed(2));
                    calculateTotalAmount();
                }).fail(function() {
                    console.error('Failed to fetch vaccine price');
                });
            } else {
                priceInput.val('0.00');
                calculateTotalAmount();
            }
        });

        // Manual price input
        $(document).on('input', '.service-price, .vaccine-price', function() {
            calculateTotalAmount();
        });

        // Calculate Total
        function calculateTotalAmount() {
            let total = 0;

            $('.service-price').each(function() {
                const val = parseFloat($(this).val());
                if (!isNaN(val)) total += val;
            });

            $('.vaccine-price').each(function() {
                const val = parseFloat($(this).val());
                if (!isNaN(val)) total += val;
            });

            $('#totalAmount').val(total.toFixed(2));
        }
    });
</script>

{{-- Service Icon Js --}}
<script type="text/javascript">
    var iconlist = [
                        'fa-shower', 'fa-cut', 'fa-bath', 'fa-paw', 'fa-dog', 'fa-cat', 'fa-bone',
                        'fa-heartbeat', 'fa-hand-holding-heart', 'fa-clipboard-check', 'fa-brush',
                        'fa-spray-can', 'fa-water', 'fa-tint', 'fa-spa', 'fa-leaf', 'fa-star',
                        'fa-plus', 'fa-dollar-sign', 'fa-tooth', 'fa-heart', 'fa-user-nurse',
                        'fa-venus-mars', 'fa-gift', 'fa-bolt', 'fa-feather', 'fa-wind', 'fa-compress-arrows-alt',
                        'fa-magic', 'fa-smile', 'fa-sun', 'fa-moon', 'fa-cloud-sun','fa-crown'
                    ];

    var iconWrapper = document.getElementById('icon-wrapper');
    var iconSearch = document.getElementById('icon-search');
    var iconInput = document.getElementById('icon-input');

    function getPrefix(iconClass) {        
        return 'fas';
    }

    function filterIcons(searchText) {
        iconWrapper.innerHTML = '';
        iconlist.forEach(function(iconClass) {
            if (iconClass.toLowerCase().includes(searchText)) {
                const prefix = getPrefix(iconClass);
                const iconDiv = document.createElement('div');
                const iconElement = document.createElement('i');

                iconElement.classList.add(prefix, iconClass);
                iconElement.style.fontSize = '24px';

                iconDiv.classList.add('i-block');
                iconDiv.style.cursor = 'pointer';
                iconDiv.style.padding = '6px';
                iconDiv.style.borderRadius = '5px';
                iconDiv.style.display = 'flex';
                iconDiv.style.alignItems = 'center';
                iconDiv.style.justifyContent = 'center';
                iconDiv.style.width = '40px';
                iconDiv.style.height = '40px';

                if (iconInput.value.trim() === prefix + ' ' + iconClass) {
                    iconDiv.style.border = '2px solid #007bff';
                    iconDiv.style.backgroundColor = '#e7f1ff';
                }

                iconDiv.title = prefix + ' ' + iconClass;

                iconDiv.addEventListener('click', function() {
                    iconInput.value = prefix + ' ' + iconClass;
                    filterIcons(iconSearch.value.toLowerCase());
                });

                iconDiv.appendChild(iconElement);
                iconWrapper.appendChild(iconDiv);
            }
        });
    }

    iconSearch.addEventListener('keyup', function() {
        filterIcons(iconSearch.value.toLowerCase());
    });

    filterIcons('');
</script>

<script>
    var textRemove = new Choices(
        document.getElementById('choices-text-remove-button'), {
            delimiter: ',',
            editItems: true,
            removeItemButton: true,
        }
    );
</script>
