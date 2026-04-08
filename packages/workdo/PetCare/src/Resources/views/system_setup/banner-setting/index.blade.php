@extends('layouts.main')

@section('page-title')
    {{ __('Banner Setting') }}
@endsection

@section('page-breadcrumb')
    {{ __('Banner Setting') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                {{ Form::open(['route' => 'petcare.banner.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true, 'enctype' => 'multipart/form-data']) }}
                <div class="card-header">
                    <div class="col-12">
                        <h5>{{ __('Banner Setting') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mt-2 row-gap">
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">                                
                                {{ Form::label('banner_tagline', __('Tagline'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('banner_tagline', !empty($petcare_system_setup['banner_tagline']) ? $petcare_system_setup['banner_tagline'] : null, ['class' => 'form-control', 'placeholder' => __('Best Pet Care Service Company'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="form-group mb-0">
                                {{ Form::label('banner_heading_title', __('Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('banner_heading_title', !empty($petcare_system_setup['banner_heading_title']) ? $petcare_system_setup['banner_heading_title'] : null, ['class' => 'form-control', 'placeholder' => __('Provide Attention and Care for all the Pets.'), 'required']) }}
                            </div>
                        </div>
                        <div class="form-group col-md-6 mb-0">
                            {{ Form::label('banner_decorative_image', __('Banner Decorative Image'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="banner_decorative_image" class="form-label">
                                    <input type="file" name="banner_decorative_image" id="banner_decorative_image"
                                        class="form-control me-3" style="width: 365px;"
                                        onchange="document.getElementById('banner_decorative_image_preview').src = window.URL.createObjectURL(this.files[0])">
                                </label>
                                <p class="text-danger d-none" id="validation">{{ __('This field is required.') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <img id="banner_decorative_image_preview" class="mt-2 mb-0" width="30%" src="{{ !empty($petcare_system_setup['banner_decorative_image']) ? asset($petcare_system_setup['banner_decorative_image']) : '' }}" />
                        </div>
                        <div class="col-sm-12 col-12">
                            <div class="form-group mb-0">
                                {{ Form::label('banner_sub_title', __('Sub Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::textarea('banner_sub_title', !empty($petcare_system_setup['banner_sub_title']) ? $petcare_system_setup['banner_sub_title'] : null, ['class' => 'form-control', 'placeholder' => __('Ensure every pet receives proper attention and care for their well-being and happiness'), 'rows' => 3, 'required']) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5 px-2">
                        <div class="col-12 border">
                            <div class="row py-3 border-bottom">
                                <div class="col">
                                    <h5>{{ __('Banner Images') }}</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <button type="button" id="add-image" class="btn btn-sm btn-primary btn-icon" title="{{ __('Add Image') }}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="image-container">
                                @php
                                    $bannerImages = $decodedbannerImages ?? [];
                                @endphp
                            
                                @if(count($bannerImages) > 0)
                                    @foreach ($bannerImages as $index => $image)                                    
                                        <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    {{ Form::label("banner_images[$index]", __('Banner Image'), ['class' => 'form-label']) }}
                                                    {{ Form::file("banner_images[$index]", ['class' => 'form-control']) }}
                                                    @if (!empty($image))
                                                        <div class="mt-2">
                                                            <img src="{{ asset($image) }}" class="img-thumbnail" style="max-height: 150px; max-width:150px;">
                                                        </div> 
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                <button type="button" class="btn btn-danger btn-sm delete-image" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash text-white fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 py-3 border-bottom align-items-center repeater-item no-delete">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                {{ Form::label('banner_images[0]', __('Banner Image'), ['class' => 'form-label']) }}<x-required></x-required>
                                                {{ Form::file('banner_images[0]', ['class' => 'form-control', 'required']) }}
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-danger btn-sm delete-image" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash text-white fs-5"></i>
                                            </button>
                                        </div>
                                    </div>                                
                                @endif
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input class="btn btn-primary" type="submit" value="{{ __('Save Changes') }}">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const container = $('#image-container');

    // Add new banner image input
    $('#add-image').on('click', function(e) {
        e.preventDefault();

        const newItem = `
            <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                <div class="col-md-10">
                    <div class="form-group">
                        <label class="form-label">{{ __('Banner Image') }} <span class="text-danger">*</span></label>
                        <input type="file" name="banner_images[]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm delete-image" title="{{ __('Delete') }}">
                        <i class="ti ti-trash text-white fs-5"></i>
                    </button>
                </div>
            </div>
        `;
        container.append(newItem);
    });

    // Delete banner image input (delegated event handling)
    $(document).off('click', '.delete-image').on('click', '.delete-image', function(e) {
        e.preventDefault();

        const totalItems = container.children('.repeater-item').length;
        const repeaterItem = $(this).closest('.repeater-item');

        if (totalItems > 1) {
            repeaterItem.remove();
        } else {
            alert('At least one banner image must remain.');
        }
    });
});
</script>
@endpush




