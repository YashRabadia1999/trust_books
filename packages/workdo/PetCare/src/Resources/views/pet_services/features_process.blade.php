@extends('layouts.main')
@section('page-title')
    {{ __('Service Features & Process') }}
@endsection
@section('page-breadcrumb')
    {!! __('Features & Process') !!}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css') }}">
@endpush
@section('page-action')
    <div class="action-btn me-2">
        <a href="{{ route('pet.services.index') }}" class="btn-submit btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Back') }}">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Service Included Features') }}</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['store.service.features',$serviceId], 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'id' => 'pet-appointment-form']) }}
                    <div class="row px-2">
                        <div class="col-12 border">
                            <div class="row py-3 border-bottom">
                                <div class="col">
                                    <h5>{{ __('Service Features') }}</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <button type="button" id="add-feature" class="btn btn-sm btn-primary btn-icon" title="{{ __('Add Feature') }}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="features-container">
                                @php $features = $features ?? []; @endphp

                                @if(count($features) > 0)
                                    @foreach ($features as $index => $feature)
                                        <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                            {{ Form::hidden("feature_id[]", $feature->id) }}
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    {{ Form::label("feature_icon[$index]", __('Choose Icon'), ['class' => 'form-label']) }}<x-required></x-required>
                                                    <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                                                    <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                                                    <input type="text" name="feature_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required value="{{ $feature['feature_icon'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="form-group">
                                                            {{ Form::label("feature_name[$index]", __('Feature Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                                            {{ Form::text("feature_name[]", $feature['feature_name'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Feature Name'), 'required']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            {{ Form::label("feature_description[$index]", __('Feature Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                            {{ Form::textarea("feature_description[]", $feature['feature_description'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required', 'rows' => 3]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                                                <button type="button" class="btn btn-danger btn-sm delete-feature" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                        {{ Form::hidden("feature_id[]", '') }}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                {{ Form::label("feature_icon[0]", __('Choose Icon'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                                                <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                                                <input type="text" name="feature_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <div class="form-group">
                                                        {{ Form::label("feature_name[0]", __('Feature Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::text("feature_name[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Feature Name'), 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {{ Form::label("feature_description[0]", __('Feature Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::textarea("feature_description[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required', 'rows' => 3]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                                            <button type="button" class="btn btn-danger btn-sm delete-feature" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer mt-3">
                        <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('pet.services.index') }}';" class="btn btn-light me-2">
                        @if(isset($features) && $features->count())
                            <input type="submit" id="submit" value="{{ __('Update') }}" class="btn btn-primary">
                        @else
                            <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
                        @endif
                    </div>                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Service Process Steps') }}</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['store.service.process.steps',$serviceId], 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'id' => 'service-process-form']) }}
                    <div class="row px-2">
                        <div class="col-12 border">
                            <div class="row py-3 border-bottom">
                                <div class="col">
                                    <h5>{{ __('Service Processes') }}</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <button type="button" id="add-process" class="btn btn-sm btn-primary btn-icon" title="{{ __('Add Process') }}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
        
                            <div id="process-container">
                                @php $processSteps = $processSteps ?? []; @endphp

                                @if(count($processSteps) > 0)
                                    @foreach ($processSteps as $index => $process)
                                        <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                            {{ Form::hidden("process_id[]", $process->id) }}
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            {{ Form::label("process_name[$index]", __('Process Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                                            {{ Form::text("process_name[]", $process->process_name ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Process Name'), 'required']) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            {{ Form::label("process_description[$index]", __('Process Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                            {{ Form::textarea("process_description[]", $process->process_description ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required', 'rows' => 3]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                                                <button type="button" class="btn btn-danger btn-sm delete-process" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                        {{ Form::hidden("process_id[]", '') }}
                                        <div class="col-md-11">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        {{ Form::label("process_name[0]", __('Process Name'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::text("process_name[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Process Name'), 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        {{ Form::label("process_description[0]", __('Process Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::textarea("process_description[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required', 'rows' => 3]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                                            <button type="button" class="btn btn-danger btn-sm delete-process" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
        
                    <div class="modal-footer mt-3">
                        <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('pet.services.index') }}';" class="btn btn-light me-2">
                        @if(isset($processSteps) && count($processSteps))
                            <input type="submit" id="submit" value="{{ __('Update') }}" class="btn btn-primary">
                        @else
                            <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
                        @endif
                    </div>                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>        
    </div>
@endsection


{{-- Include this in your Blade --}}
@push('scripts')

<script>
    const iconList = [
                        'fa-paw', 'fa-bath', 'fa-soap', 'fa-spray-can', 'fa-cut', 'fa-brush', 'fa-wind',
                        'fa-ear-listen', 'fa-heart', 'fa-check-circle', 'fa-certificate', 'fa-star', 'fa-gem',
                        'fa-leaf', 'fa-shield-alt', 'fa-hands-wash', 'fa-hand-sparkles', 'fa-hand-holding-heart',
                        'fa-umbrella', 'fa-smile', 'fa-globe', 'fa-clock', 'fa-calendar-check', 'fa-users',
                        'fa-bone', 'fa-dog', 'fa-cat', 'fa-thermometer-half', 'fa-water', 'fa-receipt',
                        'fa-gavel', 'fa-hands-helping','fa-stethoscope', 'fa-syringe', 'fa-notes-medical', 'fa-user-md', 'fa-medkit',
                        'fa-clipboard-list', 'fa-briefcase-medical', 'fa-bolt', 'fa-face-smile-beam',
                        'fa-comment-dots', 'fa-people-arrows', 'fa-shield-dog', 'fa-couch',
                        'fa-bell', 'fa-headset', 'fa-baby-carriage', 'fa-lightbulb', 'fa-check-double',
                        'fa-circle-check', 'fa-ribbon', 'fa-chart-line', 'fa-house-chimney','fa-tooth', 'fa-magic-wand-sparkles'
                    ];


    // Renders icon picker options inside the wrapper
    function renderIcons(wrapper, input, searchInput = '') {
        wrapper.innerHTML = '';
        const query = searchInput.toLowerCase();
        iconList.forEach(icon => {
            if (icon.includes(query)) {
                const div = document.createElement('div');
                div.classList.add('i-block');
                div.style.maxHeight = '35px';
                div.style.maxWidth = '35px';
                div.style.cursor = 'pointer';
                div.setAttribute('title', icon);

                const i = document.createElement('i');
                i.className = `icon fa-solid ${icon}`;
                div.appendChild(i);

                div.addEventListener('click', () => {
                    input.value = `fa-solid ${icon}`;
                    renderIcons(wrapper, input, searchInput);
                });

                if (input.value.trim() === `fa-solid ${icon}`) {
                    div.style.border = '2px solid #007bff';
                    div.style.borderRadius = '6px';
                    div.style.padding = '2px';
                }

                wrapper.appendChild(div);
            }
        });
    }

    // Initializes icon picker events for a repeater item
    function bindIconPicker(item) {
        const search = item.querySelector('.icon-search');
        const wrapper = item.querySelector('.icon-wrapper');
        const input = item.querySelector('.icon-input');

        if (search && wrapper && input) {
            renderIcons(wrapper, input);

            search.addEventListener('keyup', () => renderIcons(wrapper, input, search.value));
        }
    }


    // Features Repeater js
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('features-container');
        const addButton = document.getElementById('add-feature');

        // Bind existing items on load
        container.querySelectorAll('.repeater-item').forEach(bindIconPicker);

        // Add new feature
        addButton.addEventListener('click', function (e) {
            e.preventDefault();

            const newIndex = container.querySelectorAll('.repeater-item').length;
            const newItem = document.createElement('div');
            newItem.className = 'row g-3 py-3 border-bottom align-items-center repeater-item';
            newItem.innerHTML = `
                <input type="hidden" name="feature_id[]" value="">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">{{ __('Choose Icon') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                        <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                        <input type="text" name="feature_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label class="form-label">{{ __('Feature Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="feature_name[]" class="form-control" placeholder="{{ __('Enter Feature Name') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Feature Description') }} <span class="text-danger">*</span></label>
                                <textarea name="feature_description[]" class="form-control" placeholder="{{ __('Enter Description') }}" required rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                    <button type="button" class="btn btn-danger btn-sm delete-feature" title="{{ __('Delete') }}">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newItem);

            // Re-bind icon picker for new item
            bindIconPicker(newItem);
        });

        // Delete feature (delegated event)
        container.addEventListener('click', function (e) {
            if (e.target.closest('.delete-feature')) {
                e.preventDefault();
                const repeaterItems = container.querySelectorAll('.repeater-item');
                if (repeaterItems.length > 1) {
                    const itemToDelete = e.target.closest('.repeater-item');
                    itemToDelete.remove();
                } else {
                    alert('At least one feature must remain.');
                }
            }
        });
    });
</script>

{{-- Process Reapeter Js --}}
<script>
    $(document).ready(function() {
        const processContainer = $('#process-container');

        $('#add-process').on('click', function(e) {
            e.preventDefault();

            const index = processContainer.children('.repeater-item').length;

            const newItem = `
                <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                    <input type="hidden" name="process_id[]" value="">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="process_name[${index}]" class="form-label">{{ __('Process Name') }}<x-required></x-required></label>
                                    <input type="text" name="process_name[]" class="form-control" placeholder="{{ __('Enter Process Name') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="process_description[${index}]" class="form-label">{{ __('Process Description') }}<x-required></x-required></label>
                                    <textarea name="process_description[]" class="form-control" placeholder="{{ __('Enter Description') }}" required rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-center justify-content-end mt-2">
                        <button type="button" class="btn btn-danger btn-sm delete-process" title="{{ __('Delete') }}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            processContainer.append(newItem);
        });

        $(document).off('click', '.delete-process').on('click', '.delete-process', function(e) {
            e.preventDefault();
            const repeaterItem = $(this).closest('.repeater-item');
            if (processContainer.children('.repeater-item').length > 1) {
                repeaterItem.remove();
            } else {
                alert('{{ __("At least one process must remain.") }}');
            }
        });
    });
</script>
@endpush


