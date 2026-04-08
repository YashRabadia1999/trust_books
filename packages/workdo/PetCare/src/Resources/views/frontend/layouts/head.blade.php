@php
    $petCareFavicon = isset($petCareSystemSetup['petcare_favicon']->value) ? $petCareSystemSetup['petcare_favicon']->value : 'packages/workdo/PetCare/src/Resources/assets/image/favicon.png';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta name="author" content="petcare | WorkDo Dash">
    <meta name="description" content="Professional pet care and grooming services. Book appointments, shop pet products, and give your furry friends the care they deserve.">
    <meta name="keywords" content="Petcare | WorkDo Dash">
    <link rel="icon" href="{{ check_file($petCareFavicon) ? get_file($petCareFavicon) : asset('packages/workdo/PetCare/src/Resources/assets/image/favicon.png') }}">
    <title>@yield('page-title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/tailwind.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">    
</head> 