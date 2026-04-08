@extends('layouts.main')
@section('page-title')
{{ __('Facebook') }}
@endsection
@section('page-breadcrumb')
{{ __('Facebook') }}
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/SocialMediaAnalytics/src/Resources/assets/css/custom.css') }}">
@endpush
@section('content')
@php
$totalWeeklyImpressions = array_sum(array_column($iweekly, 'y'));
$total28DaysImpressions = array_sum(array_column($idays28, 'y'));
@endphp
<div class="row instagram-card-wrp mb-4">
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group">
                            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                            <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                            <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                        </svg>
                    </div>
                    <h2 class="mb-0 h5">{{__('Fans')}}</h2>
                </div>
                <h3 class="mb-0">{{ $totalFans }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-success"><i class="ti ti-mood-smile"></i> </div>
                    <h2 class="mb-0 h5">{{__('Reactions')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalReactions }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-warning">
                        <i class="ti ti-message-circle"></i>
                    </div>
                    <h2 class="mb-0 h5">{{__('Comment')}}</h2>
                </div>
                <h3 class="mb-0"> {{ $totalComments }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xxl-3 col-sm-6 col-12">
        <div class="card h-100 mb-0">
            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                <div class="card-content d-flex align-items-center gap-2">
                    <div class="theme-avtar rounded-1 bg-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-message-plus">
                            <path d="M8 9h8" />
                            <path d="M8 13h6" />
                            <path d="M12.01 18.594l-4.01 2.406v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v5.5" />
                            <path d="M16 19h6" />
                            <path d="M19 16v6" />
                        </svg> </div>
                    <h2 class="mb-0 h5">{{__('Message Count')}}</h2>
                </div>
                <h3 class="mb-0">{{ $totalMessages }}</h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-12 d-flex">
        <div class="card w-100">
            <div class="card-header">
                <h5>{{__('Top Country')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('Country Name')}}</th>
                                <th>{{__('Country Code')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($fanData)
                            @foreach($fanData as $countryCode => $count)
                            <tr>
                                <td>{{ $countryCode }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12 d-flex">
        <div class="card w-100">
            <div class="card-header">
                <h5>{{__('Top Language')}}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('Language')}}</th>
                                <th>{{__('Language Code')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($fanLanguages)
                            @foreach ($fanLanguages as $locale => $count)
                            <tr>
                                <td> {{ $locale }}</td>
                                <td>  {{ $count }} </td>
                            </tr>
                            @endforeach
                            @endif  
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Post Type')}}</h5>
            </div>
            <div class="card-body">
                <div class="row post-card-wrp">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card h-100 mb-0">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-3">
                                <div class="card-content d-flex align-items-center gap-3">
                                    <div class="theme-avtar rounded-1 bg-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-photo-scan">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15 8h.01" />
                                            <path d="M6 13l2.644 -2.644a1.21 1.21 0 0 1 1.712 0l3.644 3.644" />
                                            <path d="M13 13l1.644 -1.644a1.21 1.21 0 0 1 1.712 0l1.644 1.644" />
                                            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                                            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                                            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                                            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                                        </svg>
                                    </div>
                                    <h2 class="mb-0 h5">{{__('Images')}}</h2>
                                </div>
                                <h3 class="mb-0"> {{ $imageCount }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card h-100 mb-0">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-3">
                                <div class="card-content d-flex align-items-center gap-3">
                                    <div class="theme-avtar rounded-1 bg-warning">
                                        <i class="ti ti-link"></i>
                                    </div>
                                    <h2 class="mb-0 h5">{{__('Link')}}</h2>
                                </div>
                                <h3 class="mb-0"> {{ $linkCount }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card h-100 mb-0">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center gap-3">
                                <div class="card-content d-flex align-items-center gap-3">
                                    <div class="theme-avtar rounded-1 bg-info"><i class="ti ti-video"></i> </div>
                                    <h2 class="mb-0 h5">{{__('Video')}}</h2>
                                </div>
                                <h3 class="mb-0">{{ $videoCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mb-4">
        <div class="row post-card-wrp">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card h-100 mb-0">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                        <div class="card-content d-flex align-items-center gap-2">
                            <div class="theme-avtar rounded-1 bg-danger">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3939_24)">
                                <path d="M17.9468 17.8119L17.7534 16.2418C17.6658 15.5316 17.4745 14.8381 17.1849 14.1841L16.6147 12.8954C16.277 12.0859 15.25 11.7026 14.4637 12.0898C13.8772 11.6652 13.1845 11.9914 12.6045 12.2745C12.204 12.1009 11.7296 12.226 11.3455 12.4292C10.7362 11.4087 10.325 8.59556 8.56454 9.33247C8.24958 9.47212 8.00724 9.72563 7.88306 10.047C7.75888 10.3684 7.76704 10.7191 7.90669 11.034L9.67013 15.019C8.9547 14.1291 6.08567 14.4436 6.94677 15.9101C6.94677 15.9101 8.00208 17.2207 8.53704 17.8854C8.73899 18.1359 8.97231 18.3593 9.23142 18.5501L13.4522 21.6555C13.6632 21.8115 13.9644 21.7681 14.1221 21.5536C14.279 21.3405 14.2334 21.0406 14.0203 20.8838L9.91032 17.86C9.64392 17.6641 9.40415 17.4347 9.19704 17.1768L7.87231 15.5316C8.24228 15.4044 8.73513 15.4671 8.97661 15.6845L10.6468 17.1876C11.0211 17.5421 11.6493 17.0896 11.4271 16.6217L8.78282 10.646C8.60407 10.2584 9.21981 9.97958 9.38911 10.3779C9.71267 11.1127 10.838 13.65 11.1405 14.3362C11.3867 14.9098 12.2762 14.5119 12.0171 13.9482C11.9449 13.7905 11.8031 13.4613 11.7326 13.3054C12.4025 12.9363 12.4158 13.2959 12.6655 13.8296C12.9121 14.4036 13.8011 14.0049 13.542 13.4416L13.344 12.9943C14.0753 12.5895 14.0418 13.0772 14.3176 13.6113C14.6433 14.2227 15.6174 13.7991 15.052 12.9023C15.3313 12.8585 15.6265 13.0205 15.7373 13.2822C15.7373 13.2822 16.0007 13.8777 16.2504 14.441C16.54 15.0954 16.7316 15.7885 16.8188 16.4988L16.995 17.9279C17.0251 18.1707 17.2313 18.3486 17.4702 18.3486C17.7556 18.3503 17.9837 18.0942 17.9463 17.8106L17.9468 17.8119Z" fill="white"/>
                                <path d="M20.1059 2.71647H18.5891V2.13596C18.5891 1.16443 17.7985 0.373809 16.827 0.373809H16.6805C15.7089 0.373809 14.9183 1.16443 14.9183 2.13596V2.71647H12.8356V2.01264C12.8356 1.04111 12.045 0.250488 11.0735 0.250488H10.927C9.95543 0.250488 9.1648 1.04111 9.1648 2.01264V2.71647H7.08211V2.09514C7.08211 1.12361 6.29148 0.332988 5.31996 0.332988H5.17344C4.20191 0.332988 3.41129 1.12361 3.41129 2.09514V2.71647H1.89449C0.849492 2.71647 0 3.56596 0 4.61053V18.9067C0 19.9512 0.849492 20.8007 1.89406 20.8007H5.25422C5.51891 20.8007 5.73332 20.5863 5.73332 20.3216C5.73332 20.0569 5.51891 19.8425 5.25422 19.8425H1.89406C1.37801 19.8425 0.958203 19.4227 0.958203 18.9067V7.596H21.0414V18.9062C21.0414 19.4223 20.6216 19.8421 20.1055 19.8421H17.9313C17.6666 19.8421 17.4522 20.0565 17.4522 20.3212C17.4522 20.5859 17.6666 20.8003 17.9313 20.8003H20.1055C21.1501 20.8003 21.9996 19.9508 21.9996 18.9062V4.61053C21.9996 3.56596 21.1501 2.71647 20.1055 2.71647H20.1059ZM15.8765 2.13596C15.8765 1.69295 16.237 1.33244 16.68 1.33244H16.8266C17.2696 1.33244 17.6301 1.69295 17.6301 2.13596V2.71647H15.8761V2.13596H15.8765ZM10.123 2.01264C10.123 1.56963 10.4835 1.20912 10.9265 1.20912H11.073C11.5161 1.20912 11.8766 1.56963 11.8766 2.01264V2.71647H10.1226V2.01264H10.123ZM4.36949 2.09471C4.36949 1.6517 4.73 1.29119 5.17301 1.29119H5.31953C5.76254 1.29119 6.12305 1.6517 6.12305 2.09471V2.71604H4.36906V2.09471H4.36949ZM0.958203 6.63779V4.61053C0.958203 4.09447 1.37801 3.67467 1.89406 3.67467H6.12348V4.04936C6.12348 4.49236 5.76297 4.85287 5.31996 4.85287C5.05527 4.85287 4.84086 5.06729 4.84086 5.33197C4.84086 5.59666 5.05527 5.81107 5.31996 5.81107C6.29148 5.81107 7.08211 5.02045 7.08211 4.04893V3.67424H11.8774V3.96686C11.8774 4.40986 11.5169 4.77037 11.0739 4.77037C10.8092 4.77037 10.5948 4.98479 10.5948 5.24947C10.5948 5.51416 10.8092 5.72857 11.0739 5.72857C12.0454 5.72857 12.8361 4.93795 12.8361 3.96643V3.67381H17.6314V4.08975C17.6314 4.53275 17.2709 4.89326 16.8279 4.89326C16.5632 4.89326 16.3487 5.10768 16.3487 5.37236C16.3487 5.63705 16.5632 5.85146 16.8279 5.85146C17.7994 5.85146 18.59 5.06084 18.59 4.08932V3.67338H20.1068C20.6228 3.67338 21.0427 4.09318 21.0427 4.60924V6.6365H0.958203V6.63779Z" fill="white"/>
                                <path d="M4.40498 9.18262H5.17111C5.44482 9.18262 5.6674 9.40477 5.6674 9.67891V10.445C5.6674 10.7187 5.44525 10.9413 5.17111 10.9413H4.40498C4.13127 10.9413 3.90869 10.7187 3.90869 10.445V9.67891C3.90869 9.40519 4.13127 9.18262 4.40498 9.18262Z" fill="white"/>
                                <path d="M18.1188 9.18262H18.885C19.1587 9.18262 19.3813 9.40477 19.3813 9.67891V10.445C19.3813 10.7187 19.1587 10.9413 18.885 10.9413H18.1188C17.8451 10.9413 17.6226 10.7187 17.6226 10.445V9.67891C17.6226 9.40519 17.8447 9.18262 18.1188 9.18262Z" fill="white"/>
                                <path d="M14.1979 9.18262H14.9641C15.2378 9.18262 15.4604 9.40477 15.4604 9.67891V10.445C15.4604 10.7187 15.2378 10.9413 14.9641 10.9413H14.1979C13.9242 10.9413 13.7017 10.7187 13.7017 10.445V9.67891C13.7017 9.40519 13.9238 9.18262 14.1979 9.18262Z" fill="white"/>
                                <path d="M4.40498 12.6382H5.17111C5.44482 12.6382 5.6674 12.8603 5.6674 13.1345V13.9006C5.6674 14.1743 5.44525 14.3969 5.17111 14.3969H4.40498C4.13127 14.3969 3.90869 14.1743 3.90869 13.9006V13.1345C3.90869 12.8608 4.13084 12.6382 4.40498 12.6382Z" fill="white"/>
                                <path d="M4.40498 16.0938H5.17111C5.44482 16.0938 5.6674 16.3159 5.6674 16.59V17.3562C5.6674 17.6299 5.44482 17.8525 5.17111 17.8525H4.40498C4.13127 17.8525 3.90869 17.6299 3.90869 17.3562V16.59C3.90869 16.3163 4.13084 16.0938 4.40498 16.0938Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_3939_24">
                                <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <h2 class="mb-0 h5">{{__('Weekly Total Clicks')}}</h2>
                        </div>
                        <h3 class="mb-0"> {{$weekly }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card h-100 mb-0">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                        <div class="card-content d-flex align-items-center gap-2">
                            <div class="theme-avtar rounded-1 bg-warning">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3941_37)">
                                <path d="M14.4367 6.75125C14.1911 6.68983 14.0417 6.44096 14.1031 6.19575C14.1732 5.91571 14.2085 5.6045 14.2085 5.27083C14.2085 2.87008 12.2551 0.916667 9.85433 0.916667C7.45358 0.916667 5.50016 2.87008 5.50016 5.27083C5.50016 6.41804 5.94154 7.49879 6.74362 8.31325C6.921 8.49383 6.9187 8.78396 6.73858 8.96179C6.6492 9.04933 6.53325 9.09333 6.41683 9.09333C6.29858 9.09333 6.17987 9.0475 6.09004 8.95675C5.11837 7.9695 4.5835 6.6605 4.5835 5.27083C4.5835 2.36454 6.94804 0 9.85433 0C12.7606 0 15.1252 2.36454 15.1252 5.27083C15.1252 5.67921 15.0802 6.06467 14.9922 6.41758C14.9308 6.66371 14.6792 6.81404 14.4367 6.75125Z" fill="white"/>
                                <path d="M6.4165 5.271C6.4165 3.37533 7.95834 1.8335 9.854 1.8335C11.7497 1.8335 13.2915 3.37533 13.2915 5.271C13.2915 5.524 13.0862 5.72933 12.8332 5.72933C12.5802 5.72933 12.3748 5.524 12.3748 5.271C12.3748 3.88087 11.2441 2.75016 9.854 2.75016C8.46388 2.75016 7.33317 3.88087 7.33317 5.271C7.33317 5.524 7.12784 5.72933 6.87484 5.72933C6.62184 5.72933 6.4165 5.524 6.4165 5.271Z" fill="white"/>
                                <path d="M3.8816 13.4784L5.82219 16.5933C6.29014 17.3445 6.87773 18.0054 7.56798 18.5582L8.53598 19.3323C8.64506 19.4194 8.70785 19.5504 8.70785 19.6898V20.6243C8.70785 21.3824 9.32477 21.9993 10.0829 21.9993H15.1245C15.8826 21.9993 16.4995 21.3824 16.4995 20.6243V19.5587C16.4995 19.3612 16.5408 19.17 16.6224 18.9908L17.7416 16.5236C18.128 15.6775 18.3329 14.737 18.3329 13.8043C18.3329 13.7947 18.3329 13.786 18.3319 13.7768C18.3324 13.7672 18.3329 13.7585 18.3329 13.7489V10.1968C18.3329 9.31219 17.6133 8.59261 16.7287 8.59261C16.3822 8.59261 16.0609 8.70307 15.7983 8.89053C15.5146 8.43678 15.0104 8.13428 14.437 8.13428C14.0905 8.13428 13.7692 8.24474 13.5066 8.43219C13.2229 7.97844 12.7187 7.67594 12.1454 7.67594C11.8992 7.67594 11.6664 7.73186 11.4579 7.83086V5.26969C11.4579 4.38511 10.7383 3.66553 9.85369 3.66553C8.9691 3.66553 8.24952 4.38511 8.24952 5.26969V13.8832L6.58439 11.9366C6.0431 11.3179 5.15714 11.1437 4.4261 11.509C4.07823 11.6832 3.81927 11.9976 3.71569 12.3725C3.6121 12.7474 3.67214 13.1494 3.88069 13.4775L3.8816 13.4784ZM4.59981 12.6177C4.6351 12.4912 4.71898 12.3885 4.83677 12.3299C5.19381 12.1507 5.62785 12.2368 5.89139 12.537L8.35952 15.4227C8.48419 15.568 8.68677 15.6198 8.86644 15.5547C9.04656 15.4878 9.16573 15.3164 9.16573 15.1248V5.27061C9.16573 4.89157 9.47419 4.58311 9.85323 4.58311C10.2323 4.58311 10.5407 4.89157 10.5407 5.27061V11.9164C10.5407 12.1699 10.7461 12.3748 10.9991 12.3748C11.2521 12.3748 11.4574 12.1699 11.4574 11.9164V9.28103C11.4574 8.90199 11.7659 8.59353 12.1449 8.59353C12.5239 8.59353 12.8324 8.90199 12.8324 9.28103V11.9164C12.8324 12.1699 13.0377 12.3748 13.2907 12.3748C13.5437 12.3748 13.7491 12.1699 13.7491 11.9164V9.73936C13.7491 9.36032 14.0575 9.05186 14.4366 9.05186C14.8156 9.05186 15.1241 9.36032 15.1241 9.73936V11.9164C15.1241 12.1699 15.3294 12.3748 15.5824 12.3748C15.8354 12.3748 16.0407 12.1699 16.0407 11.9164V10.1977C16.0407 9.81865 16.3492 9.51019 16.7282 9.51019C17.1073 9.51019 17.4157 9.81865 17.4157 10.1977V13.7498C17.4157 13.7594 17.4157 13.7681 17.4166 13.7777C17.4162 13.7869 17.4157 13.7956 17.4157 13.8052C17.4157 14.6073 17.2397 15.4163 16.907 16.1446L15.7873 18.6127C15.6516 18.912 15.5824 19.2305 15.5824 19.5592V20.6248C15.5824 20.8778 15.3766 21.0831 15.1241 21.0831H10.0824C9.82985 21.0831 9.62406 20.8778 9.62406 20.6248V19.6902C9.62406 19.2709 9.43614 18.8794 9.10844 18.6168L8.13998 17.8422C7.53085 17.355 7.01248 16.7716 6.59952 16.1088L4.65664 12.9903C4.58377 12.8757 4.56406 12.7447 4.59935 12.6177H4.59981Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_3941_37">
                                <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <h2 class="mb-0 h5">{{__('28 Days Total Clicks')}}</h2>
                        </div>
                        <h3 class="mb-0"> {{ $monthly }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card h-100 mb-0">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                        <div class="card-content d-flex align-items-center gap-2">
                            <div class="theme-avtar rounded-1 bg-info">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3941_62)">
                                <path d="M21.9271 17.5425C20.4293 15.0125 18.4088 13.619 16.2379 13.619C14.0669 13.619 12.0464 15.0125 10.5486 17.5425C10.4514 17.7072 10.4514 17.9118 10.5486 18.0766C12.0464 20.6066 14.0669 22 16.2379 22C18.4088 22 20.4293 20.6066 21.9271 18.0766C22.0243 17.9119 22.0243 17.7073 21.9271 17.5425ZM16.2379 20.9524C14.5252 20.9524 12.8924 19.8393 11.6136 17.8095C12.8924 15.7798 14.5252 14.6667 16.2379 14.6667C17.9505 14.6667 19.5833 15.7798 20.8621 17.8095C19.5833 19.8393 17.9505 20.9524 16.2379 20.9524ZM16.2379 15.7143C15.0829 15.7143 14.1427 16.654 14.1427 17.8095C14.1427 18.965 15.0829 19.9048 16.2379 19.9048C17.3928 19.9048 18.3331 18.965 18.3331 17.8095C18.3331 16.654 17.3928 15.7143 16.2379 15.7143ZM16.2379 18.8571C15.6598 18.8571 15.1903 18.3871 15.1903 17.8095C15.1903 17.232 15.6598 16.7619 16.2379 16.7619C16.8159 16.7619 17.2855 17.232 17.2855 17.8095C17.2855 18.3871 16.8159 18.8571 16.2379 18.8571ZM9.18895 18.8571H3.66661C2.22207 18.8571 1.0476 17.6821 1.0476 16.2381V9.42857H18.8569V12.5981C19.2143 12.7486 19.5643 12.926 19.9045 13.1379V5.76191C19.9045 3.74031 18.2594 2.09524 16.2379 2.09524H14.6665V0.52381C14.6665 0.234248 14.4322 0 14.1427 0C13.8531 0 13.6189 0.234248 13.6189 0.52381V2.09524H6.28562V0.52381C6.28562 0.234248 6.05138 0 5.76182 0C5.47226 0 5.23802 0.234248 5.23802 0.52381V2.09524H3.66661C1.64505 2.09524 0 3.74031 0 5.76191V16.2381C0 18.2597 1.64505 19.9048 3.66661 19.9048H9.87325C9.63869 19.5812 9.4122 19.2399 9.19723 18.8766C9.19356 18.8704 9.19252 18.8634 9.18895 18.8571ZM1.04771 5.76191C1.04771 4.31787 2.22218 3.14286 3.66672 3.14286H5.23813V4.71429C5.23813 5.00385 5.47237 5.2381 5.76193 5.2381C6.05149 5.2381 6.28573 5.00385 6.28573 4.71429V3.14286H13.619V4.71429C13.619 5.00385 13.8532 5.2381 14.1428 5.2381C14.4323 5.2381 14.6666 5.00385 14.6666 4.71429V3.14286H16.238C17.6825 3.14286 18.857 4.31787 18.857 5.76191V8.38095H1.04771V5.76191Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_3941_62">
                                <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <h2 class="mb-0 h5">{{__('Weekly Total Impressions')}}</h2>
                        </div>
                        <h3 class="mb-0">{{ $totalWeeklyImpressions }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card h-100 mb-0">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center gap-2">
                        <div class="card-content d-flex align-items-center gap-2">
                            <div class="theme-avtar rounded-1 bg-success">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_3941_48)">
                                <path d="M10.9999 18.9202C15.5319 18.9202 19.1135 14.5422 19.2631 14.3574C19.3951 14.1946 19.3951 13.9658 19.2631 13.8074C19.1135 13.6182 15.5319 9.24023 10.9999 9.24023C6.4679 9.24023 2.8863 13.6182 2.7367 13.803C2.6047 13.9658 2.6047 14.1946 2.7367 14.353C2.8863 14.5422 6.4679 18.9202 10.9999 18.9202ZM7.9199 14.0802C7.9199 12.3818 9.3015 11.0002 10.9999 11.0002C12.6983 11.0002 14.0799 12.3818 14.0799 14.0802C14.0799 15.7786 12.6983 17.1602 10.9999 17.1602C9.3015 17.1602 7.9199 15.7786 7.9199 14.0802ZM18.3347 14.0802C17.5163 14.9822 14.7179 17.7938 11.4091 18.0182C13.4023 17.8114 14.9599 16.1262 14.9599 14.0802C14.9599 12.0342 13.4023 10.349 11.4135 10.1422C14.7179 10.3666 17.5207 13.1782 18.3347 14.0802ZM10.5863 10.1422C8.5975 10.349 7.0399 12.0342 7.0399 14.0802C7.0399 16.1262 8.5975 17.8114 10.5863 18.0182C7.2775 17.7938 4.4791 14.9822 3.6607 14.0802C4.4791 13.1782 7.2819 10.3666 10.5863 10.1422Z" fill="white"/>
                                <path d="M10.9998 11.8799C9.7854 11.8799 8.7998 12.8655 8.7998 14.0799C8.7998 15.2943 9.7854 16.2799 10.9998 16.2799C12.2142 16.2799 13.1998 15.2943 13.1998 14.0799C13.1998 12.8655 12.2142 11.8799 10.9998 11.8799ZM10.9998 15.3999C10.2738 15.3999 9.6798 14.8059 9.6798 14.0799C9.6798 13.3539 10.2738 12.7599 10.9998 12.7599C11.7258 12.7599 12.3198 13.3539 12.3198 14.0799C12.3198 14.8059 11.7258 15.3999 10.9998 15.3999Z" fill="white"/>
                                <path d="M20.24 1.76H19.36V0.44C19.36 0.198 19.162 0 18.92 0C18.678 0 18.48 0.198 18.48 0.44V1.76H15.4V0.44C15.4 0.198 15.202 0 14.96 0C14.718 0 14.52 0.198 14.52 0.44V1.76H11.44V0.44C11.44 0.198 11.242 0 11 0C10.758 0 10.56 0.198 10.56 0.44V1.76H7.48V0.44C7.48 0.198 7.282 0 7.04 0C6.798 0 6.6 0.198 6.6 0.44V1.76H3.52V0.44C3.52 0.198 3.322 0 3.08 0C2.838 0 2.64 0.198 2.64 0.44V1.76H1.76C0.7876 1.76 0 2.5476 0 3.52V20.24C0 21.2124 0.7876 22 1.76 22H20.24C21.2124 22 22 21.2124 22 20.24V3.52C22 2.5476 21.2124 1.76 20.24 1.76ZM21.12 20.24C21.12 20.724 20.724 21.12 20.24 21.12H1.76C1.276 21.12 0.88 20.724 0.88 20.24V7.04H21.12V20.24ZM21.12 6.16H0.88V3.52C0.88 3.036 1.276 2.64 1.76 2.64H2.64V3.96C2.64 4.202 2.838 4.4 3.08 4.4C3.322 4.4 3.52 4.202 3.52 3.96V2.64H6.6V3.96C6.6 4.202 6.798 4.4 7.04 4.4C7.282 4.4 7.48 4.202 7.48 3.96V2.64H10.56V3.96C10.56 4.202 10.758 4.4 11 4.4C11.242 4.4 11.44 4.202 11.44 3.96V2.64H14.52V3.96C14.52 4.202 14.718 4.4 14.96 4.4C15.202 4.4 15.4 4.202 15.4 3.96V2.64H18.48V3.96C18.48 4.202 18.678 4.4 18.92 4.4C19.162 4.4 19.36 4.202 19.36 3.96V2.64H20.24C20.724 2.64 21.12 3.036 21.12 3.52V6.16Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_3941_48">
                                <rect width="22" height="22" fill="white"/>
                                </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <h2 class="mb-0 h5">{{__('28 Days Total Impressions')}}</h2>
                        </div>
                        <h3 class="mb-0">{{ $total28DaysImpressions }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-4">
                <h5>{{__('Total Page Action')}}</h5>               
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-4">
                <h5>{{__('Facebook Page Impressions')}}</h5>                
            </div>
            <div class="card-body">
                <div id="impressionsChart"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-12 col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Audience Growth Over Time[Fans]')}}</h5>
            </div>
            <div class="card-body">
                <div id="audienceGrowthChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script>
    const chartData = @json($daily);

    const categories = chartData.map(item => new Date(item.date).toLocaleDateString());
    const values = chartData.map(item => item.value);

    const options = {
        chart: {
            type: 'area',  // Changed to 'area'
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Page Clicks',
            data: values
        }],
        xaxis: {
            categories: categories
        },
        stroke: {
            curve: 'smooth'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        title: {
            text: 'Daily Facebook Page Clicks',
            align: 'center'
        },
        dataLabels: {
            enabled: false
        }
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
<script>
    const chartOptions = {
        chart: {
            type: 'bar', // Changed from 'line' to 'bar'
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [
            {
                name: 'Daily',
                data: @json($idaily) // format: [{x: '2025-04-01T00:00:00+0000', y: 100}, ...]
            },
            {
                name: 'Weekly',
                data: @json($iweekly)
            },
            {
                name: '28 Days',
                data: @json($idays28)
            }
        ],
        xaxis: {
            type: 'datetime',
            title: {
                text: 'Date'
            },
            labels: {
                format: 'yyyy-MM-dd'
            }
        },
        yaxis: {
            title: {
                text: 'Impressions'
            },
            min: 0
        },
        tooltip: {
            x: {
                format: 'yyyy-MM-dd'
            }
        },
        title: {
            text: 'Page Impressions (Daily, Weekly, 28 Days)',
            align: 'left'
        },
        plotOptions: {
            bar: {
                columnWidth: '70%',
                dataLabels: {
                    position: 'top'
                }
            }
        }
    };

    const charts = new ApexCharts(document.querySelector("#impressionsChart"), chartOptions);
    charts.render();
</script>
<script>
    const audienceGrowthData = @json($audienceGrowthData); // Pass the data from PHP to JS

    // Transform the data into a format that can be used by the charting library
    const chartDatas = audienceGrowthData.map(item => ({
        x: new Date(item.date),
        y: item.fans_count,
    }));

    // ApexCharts configuration for displaying the audience growth chart
    const Audienceoption = {
        chart: {
            type: 'area', // Changed from 'line' to 'area'
            height: 350,
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Audience Growth',
            data: chartDatas
        }],
        xaxis: {
            type: 'datetime',
            title: {
                text: 'Date'
            }
        },
        yaxis: {
            title: {
                text: 'Page Fans Count'
            },
            min: 0
        },
        stroke: {
            curve: 'smooth'
        },
        markers: {
            size: 4
        },
        tooltip: {
            x: {
                format: 'yyyy-MM-dd'
            }
        },
        title: {
            text: 'Audience Growth Over Time',
            align: 'left'
        }
    };

    const audiencechart = new ApexCharts(document.querySelector("#audienceGrowthChart"), Audienceoption);
    audiencechart.render();
</script>


@endpush