<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        @permission('petcare_brand_setting manage')
            <a href="{{ route('petcare.brand.setting.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-brand-setting*') ? 'active' : '' }}">{{ __('Brand Setting') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_banner_setting manage')
            <a href="{{ route('petcare.banner.setting.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-banner-setting*') ? 'active' : '' }}">{{ __('Banner Setting') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_review manage')
            <a href="{{ route('petcare.review.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-review*') ? 'active' : '' }}">{{ __('Pet Care Reviews') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_packages_page_setting manage')
            <a href="{{ route('petcare.packages.page.setting.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-packages-page-setting*') ? 'active' : '' }}">{{ __('Packages Page Setting') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('service_review manage')
            <a href="{{ route('service.review.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-service/review*') ? 'active' : '' }}">{{ __('Service Reviews') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_about_us manage')
            <a href="{{ route('petcare.about.us.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-about-us-page-setting*') ? 'active' : '' }}">{{ __('About Us') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_faq manage')
            <a href="{{ route('petcare.faq.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-faq-page-setting*') || request()->is('*pet-care-faq-page/question-answer-page*') ? 'active' : '' }}">{{ __('FAQ') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_contact_us manage')
            <a href="{{ route('petcare.contact.us.setting.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-contact-us/setting/page*') ? 'active' : '' }}">{{ __('Contact Us') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_social_links manage')
            <a href="{{ route('petcare.social.links.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-social-links-setting*') ? 'active' : '' }}">{{ __('Social Links') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
        @permission('petcare_additional_setting manage')
            <a href="{{ route('petcare.additional.setting.index')}}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('*pet-care-additional-setting*') ? 'active' : '' }}">{{ __('Additional Setting') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endpermission
    </div>
</div>