{{ Form::open(['route' => 'pet.services.store', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate','enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12" id="icons">
                {{ Form::label('service_icon', __('Service Icon'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="form-group col-md-12">
                    <input type="text" id="icon-search" class="form-control mb-4" placeholder="{{ __('search . .') }}">
                </div>
                <div class="form-group col-md-12">
                    <div class="i-main" id="icon-wrapper" style="max-height: 100px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 10px;">
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <input type="text" id="icon-input" name="service_icon" class="form-control"
                        placeholder="{{ __('Selected Icon') }}" readonly>
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('service_name', __('Service Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('service_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Service Name')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('price', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.01', 'placeholder' => __('Enter Price')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('duration', __('Duration (minute)'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('duration', null, ['class' => 'form-control', 'required' => 'required', 'min' => '1', 'placeholder' => __('Enter Duration in minute')]) }}
                </div>
            </div>
            <div class="form-group col-md-6 mb-0">
                {{ Form::label('service_image', __('Image'), ['class' => 'form-label']) }}<x-required></x-required>
                <div class="choose-file">
                    <label for="service_image" class="form-label">
                        <input type="file" name="service_image" id="service_image" class="form-control me-3" style="width: 365px;" onchange="document.getElementById('service_image_preview').src = window.URL.createObjectURL(this.files[0])" required>
                    </label>
                    <p class="text-danger d-none" id="validation">{{ __('This field is required.') }}</p>
                    <img id="service_image_preview" class="mt-2 mb-0" width="35%" src="" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'rows' => 3]) }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {{ Form::button(__('Cancel'), ['type' => 'button', 'class' => 'btn btn-light', 'data-bs-dismiss' => 'modal'])}}
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}


{{-- Service Icon Js --}}
<script type="text/javascript">
    var iconlist = [
                    'fa-paw', 'fa-dog', 'fa-cat', 'fa-bone', 'fa-shower', 'fa-cut',
                    'fa-stethoscope', 'fa-syringe', 'fa-notes-medical', 'fa-hospital',
                    'fa-house-user', 'fa-walking', 'fa-drumstick-bite', 'fa-user-md',
                    'fa-bath', 'fa-heartbeat', 'fa-medkit', 'fa-calendar-check',
                    'fa-clipboard-check', 'fa-tags', 'fa-suitcase', 'fa-pills',
                    'fa-baby-carriage', 'fa-hand-holding-heart', 'fa-briefcase-medical',
                    'fa-dove', 'fa-fish', 'fa-bugs', 'fa-snowflake', 'fa-mitten'
                ];


    var iconWrapper = document.getElementById('icon-wrapper');
    var iconSearch = document.getElementById('icon-search');
    var iconInput = document.getElementById('icon-input');

    function filterIcons(searchText) {
        iconWrapper.innerHTML = '';

        iconlist.forEach(function(iconClass) {
            if (iconClass.toLowerCase().includes(searchText)) {
                const iconDiv = document.createElement('div');
                const iconElement = document.createElement('i');

                iconElement.classList.add('fas', iconClass);
                iconElement.style.fontSize = '24px';

                iconDiv.classList.add('i-block');
                iconDiv.setAttribute('data-clipboard-text', 'fas ' + iconClass);
                iconDiv.setAttribute('data-bs-toggle', 'tooltip');
                iconDiv.setAttribute('title', 'fas ' + iconClass);
                iconDiv.style.cursor = 'pointer';
                iconDiv.style.padding = '6px';
                iconDiv.style.borderRadius = '5px';
                iconDiv.style.display = 'flex';
                iconDiv.style.alignItems = 'center';
                iconDiv.style.justifyContent = 'center';
                iconDiv.style.width = '40px';
                iconDiv.style.height = '40px';

                iconDiv.addEventListener('click', function() {
                    document.getElementById('icon-input').value = ('fas') + ' ' + iconClass;
                });

                iconDiv.appendChild(iconElement);
                iconWrapper.appendChild(iconDiv);
            }
        });
    }

    filterIcons('');

    iconSearch.addEventListener('keyup', function() {
        var searchText = iconSearch.value.toLowerCase();
        filterIcons(searchText);
    });
</script>