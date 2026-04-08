@php
    $contactInfoPhoneNo = isset($petCareSystemSetup['contact_info_phone_no']->value)
        ? $petCareSystemSetup['contact_info_phone_no']->value
        : null;
    $contactInfoEmailAddress = isset($petCareSystemSetup['contact_info_email_address']->value)
        ? $petCareSystemSetup['contact_info_email_address']->value
        : null;
    $contactInfoSocialLinks = \Workdo\PetCare\Entities\PetCareSocialLink::where('workspace', $workspace->id)
        ->where('created_by', $workspace->created_by)
        ->get(['social_media_name', 'social_media_icon', 'social_media_link']);
@endphp
<!-- Top Bar -->
@if (!empty($contactInfoPhoneNo) && !empty($contactInfoEmailAddress))
    <div class="hidden lg:block bg-dark py-2 text-white">
        <div class="md:container w-full mx-auto px-4">
            <div class="flex justify-between items-center">
                @if (!empty($contactInfoPhoneNo) && !empty($contactInfoEmailAddress))
                    <div class="flex items-center gap-4 text-sm">
                        <a href="tel:{{ $contactInfoPhoneNo ?? '' }}"
                            class="flex items-center gap-2 hover:text-primary transition-all duration-300">
                            <i class="fas fa-phone"></i>
                            {{ $contactInfoPhoneNo ?? '' }}
                        </a>
                        <a href="mailto:{{ $contactInfoEmailAddress ?? '' }}"
                            class="flex items-center gap-2 hover:text-primary transition-all duration-300">
                            <i class="fa-solid fa-envelope"></i>
                            {{ $contactInfoEmailAddress ?? '' }}
                        </a>
                    </div>
                @endif

                {{-- Social Icons --}}
                @if (isset($contactInfoSocialLinks) && $contactInfoSocialLinks->isNotEmpty())
                    <div class="flex items-center gap-4">
                        @foreach ($contactInfoSocialLinks as $social)
                            <a href="{{ $social['social_media_link'] }}" target="_blank"
                                class="hover:text-primary transition duration-300"
                                title="{{ $social['social_media_name'] }}">
                                <i class="{{ $social['social_media_icon'] }} text-base"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
