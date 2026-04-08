@extends('layouts.main')

@section('page-title')
    {{ __('Packages Page Setting') }}
@endsection

@section('page-breadcrumb')
    {{ __('Packages Page Setting') }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css')}}">
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                {{ Form::open(['route' => 'petcare.packages.page.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true]) }}
                <div class="card-header">
                    <div class="col-12">
                        <h5>{{ __('Packages Page Setting') }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="mb-4">{{ __('Section 1 : Payment & Policies') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('payment_policies_tagline_label', __('Payment Policies Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('payment_policies_tagline_label', $petcare_system_setup['payment_policies_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Policies'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('payment_policies_heading_title', __('Payment Policies Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('payment_policies_heading_title', $petcare_system_setup['payment_policies_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Payment & Policies'), 'required']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 px-2">
                        <div class="col-12 border">
                            <div class="row py-3 border-bottom">
                                <div class="col">
                                    <h5>{{ __('Payment & Policies') }}</h5>
                                </div>
                                <div class="col-auto text-end">
                                    <button type="button" id="add-policy" class="btn btn-sm btn-primary btn-icon" title="{{ __('Add Policy') }}">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="payment-policies-container">
                                @php
                                    $paymentPolicies = $paymentPolicies ?? [];
                                @endphp
                                @if(count($paymentPolicies) > 0)
                                    @foreach ($paymentPolicies as $index => $item)
                                        <div class="row g-3 py-3 border-bottom align-items-center repeater-item">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{ Form::label("policy_icon[$index]", __('Choose Icon'), ['class' => 'form-label']) }}<x-required></x-required>
                                                    <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                                                    <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                                                    <input type="text" name="policy_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required
                                                        value="{{ $item['policy_icon'] ?? '' }}">
                                                </div>
                                            </div>                            
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label("policy_title[$index]", __('Policy Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                                                    {{ Form::text("policy_title[]", $item['policy_title'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Policy Title'), 'required'=>'required']) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label("policy_tag[$index]", __('Policy Tag'), ['class' => 'form-label']) }}
                                                                    {{ Form::text("policy_tag[]", $item['policy_tag'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Policy Tag')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                            
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            {{ Form::label("policy_description[$index]", __('Policy Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                            {{ Form::textarea("policy_description[]", $item['policy_description'] ?? '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required'=>'required', 'rows'=>3]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                            
                                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                                <button type="button" class="btn btn-danger btn-sm delete-policy" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row g-3 py-3 border-bottom repeater-item">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label("policy_icon[0]", __('Choose Icon'), ['class' => 'form-label']) }}<x-required></x-required>
                                                <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                                                <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                                                <input type="text" name="policy_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required value="">
                                            </div>
                                        </div>     
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label("policy_title[0]", __('Policy Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                                                {{ Form::text("policy_title[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Policy Title'), 'required'=>'required']) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label("policy_tag[0]", __('Policy Tag'), ['class' => 'form-label']) }}
                                                                {{ Form::text("policy_tag[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Policy Tag')]) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                             
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        {{ Form::label("policy_description[0]", __('Policy Description'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::textarea("policy_description[]", '', ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required'=>'required', 'rows'=>3]) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                            
                                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                                            <button type="button" class="btn btn-danger btn-sm delete-policy" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash"></i>
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
            // Icon Picker Js
            var iconList = [
                                'fa-credit-card', 'fa-money-bill-wave', 'fa-receipt', 'fa-shield-alt',
                                'fa-calendar-times', 'fa-check-circle', 'fa-undo', 'fa-lock',
                                'fa-percentage', 'fa-dollar-sign', 'fa-handshake', 'fa-gavel',
                                'fa-exclamation-triangle', 'fa-leaf', 'fa-globe', 'fa-shipping-fast',
                                'fa-coins', 'fa-ban', 'fa-certificate', 'fa-paw', 'fa-dog', 'fa-cat',
                                'fa-house-user', 'fa-heartbeat', 'fa-hand-holding-heart',
                                'fa-file-invoice', 'fa-file-contract', 'fa-balance-scale', 'fa-university',
                                'fa-id-card', 'fa-file-signature', 'fa-wallet', 'fa-calendar-check',
                                'fa-comment-dollar', 'fa-fingerprint', 'fa-user-shield', 'fa-clipboard-check',
                                'fa-calendar-minus', 'fa-user-lock', 'fa-hand-holding-usd', 'fa-exclamation-circle'
                            ];
            
            function renderIcons(wrapper, input, searchInput = '') {
                wrapper.innerHTML = '';
                iconList.forEach(icon => {
                    if (icon.includes(searchInput.toLowerCase())) {
                        const div = document.createElement('div');
                        div.classList.add('i-block');
                        div.style.maxHeight = '35px';
                        div.style.maxWidth = '35px';
                        div.style.cursor = 'pointer';
                        div.setAttribute('title', icon);
            
                        const i = document.createElement('i');
                        i.className = `icon fa-solid ${icon}`;
            
                        i.addEventListener('click', () => {
                            input.value = `fa-solid ${icon}`;
                            renderIcons(wrapper, input, searchInput);
                        });
            
                        div.appendChild(i);
            
                        if (input.value.trim() === `fa-solid ${icon}`) {
                            div.style.border = '2px solid #007bff';
                            div.style.borderRadius = '6px';
                            div.style.padding = '2px';
                        }
            
                        wrapper.appendChild(div);
                    }
                });
            }
            
            function bindIconSearch(container) {
                const wrapper = container.querySelector('.icon-wrapper');
                const input = container.querySelector('.icon-input');
                const search = container.querySelector('.icon-search');
            
                if (wrapper && input && search) {
                    renderIcons(wrapper, input);
            
                    search.addEventListener('keyup', () => {
                        renderIcons(wrapper, input, search.value);
                    });
                }
            }
            
            document.addEventListener('DOMContentLoaded', function () {
                // Bind existing repeater items on page load
                document.querySelectorAll('#payment-policies-container .repeater-item').forEach(item => {
                    bindIconSearch(item);
                });
            });


            // Reapeter Js
            $(document).ready(function() {
                const container = $('#payment-policies-container');
            
                $('#add-policy').on('click', function(e) {
                    e.preventDefault();
            
                    const index = container.children('.repeater-item').length;
            
                    const newItem = `
                        <div class="row g-3 py-3 border-bottom repeater-item">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Choose Icon') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control icon-search mb-2" placeholder="{{ __('Search...') }}">
                                    <div class="i-main icon-wrapper" style="max-height: 80px; overflow-y: auto;"></div>
                                    <input type="text" name="policy_icon[]" class="form-control icon-input mt-2" placeholder="{{ __('Selected Icon') }}" readonly required value="">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Policy Title') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="policy_title[]" class="form-control" placeholder="{{ __('Enter Policy Title') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Policy Tag') }}</label>
                                                    <input type="text" name="policy_tag[]" class="form-control" placeholder="{{ __('Enter Policy Tag') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Policy Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="policy_description[]" class="form-control" placeholder="{{ __('Enter Description') }}" required rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <button type="button" class="btn btn-danger btn-sm delete-policy" title="{{ __('Delete') }}">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
            
                    const newElement = $(newItem).appendTo(container);
            
                    bindIconSearch(newElement[0]);
                });
            
                $(document).off('click', '.delete-policy').on('click', '.delete-policy', function(e) {
                    e.preventDefault();
            
                    const totalItems = container.children('.repeater-item').length;
                    const repeaterItem = $(this).closest('.repeater-item');
            
                    if (totalItems > 1) {
                        repeaterItem.remove();
                    } else {
                        alert('At least one policy must remain.');
                    }
                });
            
                $('#payment-policies-container .repeater-item').each(function() {
                    bindIconSearch(this);
                });
            });
    </script>    
@endpush




