@extends('layouts.main')
@section('breadcrumb')
@endsection
@section('page-title')
{{ __('Dashboard') }}
@endsection

@section('page-breadcrumb')
{{ __('School Management') }}
@endsection

@section('content')

<div class="row row-gap mb-4 ">
    <div class="col-xl-12 col-12">
        <div class="dashboard-card">
            <img src="{{ asset('assets/images/layer.png')}}" class="dashboard-card-layer" alt="layer">
            <div class="card-inner">
                <div class="card-content">
                    <h2>{{Auth::user()->ActiveWorkspaceName()}}</h2>
                    <p>{{ __('Streamline school management with features for student enrollment, attendance tracking, grade management, and communication tools to enhance efficiency and engagement.') }}</p>
                </div>
                <div class="card-icon  d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85" height="62" viewBox="0 0 85 62" fill="none">
                        <path d="M78.6842 45.2377C79.3065 44.7801 79.7971 44.1664 80.1065 43.4585C80.4159 42.7507 80.5331 41.9738 80.4463 41.2062C80.3595 40.4386 80.0717 39.7075 79.6121 39.0866C79.1525 38.4658 78.5372 37.9771 77.8284 37.67V31.6338L74.2995 33.1336V37.67C73.5903 37.977 72.9747 38.4656 72.5148 39.0867C72.0549 39.7077 71.767 40.4391 71.6801 41.207C71.5933 41.9749 71.7107 42.7521 72.0203 43.4601C72.33 44.1681 72.821 44.7819 73.4437 45.2395C72.8898 45.6464 72.4392 46.1777 72.1281 46.7907C71.817 47.4036 71.6542 48.081 71.6528 48.7684V53.1777C71.6528 53.6457 71.8387 54.0945 72.1696 54.4254C72.5005 54.7563 72.9493 54.9422 73.4173 54.9422H78.7106C79.1786 54.9422 79.6274 54.7563 79.9583 54.4254C80.2892 54.0945 80.4751 53.6457 80.4751 53.1777V48.7666C80.4737 48.0793 80.3109 47.4019 79.9998 46.7889C79.6888 46.176 79.2381 45.6447 78.6842 45.2377Z" fill="#18BF6B" />
                        <path opacity="0.6" d="M69.0079 35.374V48.431C69.0175 49.9489 68.6321 51.4431 67.8897 52.7671C67.1473 54.0911 66.0733 55.1993 64.7733 55.9828C58.0267 59.9231 50.3541 61.9995 42.5412 61.9995C34.7282 61.9995 27.0557 59.9231 20.3091 55.9828C19.009 55.1993 17.9351 54.0911 17.1927 52.7671C16.4502 51.4431 16.0649 49.9489 16.0744 48.431V35.374L37.7242 44.5315C39.2494 45.1721 40.887 45.502 42.5412 45.502C44.1954 45.502 45.833 45.1721 47.3581 44.5315L69.0079 35.374Z" fill="#18BF6B" />
                        <path d="M81.4192 16.5426L45.9749 1.54481C44.8857 1.08661 43.7159 0.850586 42.5342 0.850586C41.3526 0.850586 40.1828 1.08661 39.0936 1.54481L3.64923 16.5426C2.6912 16.9479 1.8738 17.6264 1.29913 18.4934C0.724448 19.3605 0.417969 20.3776 0.417969 21.4178C0.417969 22.458 0.724448 23.4752 1.29913 24.3422C1.8738 25.2093 2.6912 25.8878 3.64923 26.293L39.0936 41.2909C40.1827 41.7495 41.3525 41.9858 42.5342 41.9858C43.716 41.9858 44.8858 41.7495 45.9749 41.2909L81.4192 26.293C82.3773 25.8878 83.1947 25.2093 83.7693 24.3422C84.344 23.4752 84.6505 22.458 84.6505 21.4178C84.6505 20.3776 84.344 19.3605 83.7693 18.4934C83.1947 17.6264 82.3773 16.9479 81.4192 16.5426Z" fill="#18BF6B" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-12">
        <div class="row dashboard-wrp">
            <div class="{{ Auth::user()->type == 'company' ? 'col-sm-4 col-12' : 'col-sm-3 col-12' }}">
                <div class="dashboard-project-card">
                    <div class="card-inner  d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="ti ti-user text-danger"></i>
                            </div>
                            <a href="{{ route('school-student.index') }}"><h3 class="mt-3 mb-0 text-danger">{{ __('Total Student') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalStudent'] }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="{{ Auth::user()->type == 'company' ? 'col-sm-4 col-12' : 'col-sm-3 col-12' }}">
                <div class="dashboard-project-card">
                    <div class="card-inner  d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="ti ti-building"></i>
                            </div>
                            <a href="{{ route('school-parent.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Parent') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalParent'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="{{ Auth::user()->type == 'company' ? 'col-sm-4 col-12' : 'col-sm-3 col-12' }}">
                <div class="dashboard-project-card">
                    <div class="card-inner d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="ti ti-currency-dollar-singapore"></i>
                            </div>
                            <a href="{{ route('classroom.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Class') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalClass'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="{{ Auth::user()->type == 'company' ? 'col-sm-4 col-12' : 'col-sm-3 col-12' }}">
                <div class="dashboard-project-card">
                    <div class="card-inner d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="ti ti-receipt"></i>
                            </div>
                            <a href="{{ route('subject.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Subject') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalSubject'] }}</h3>
                    </div>
                </div>
            </div>
            @if (Auth::user()->type == 'company')
            <div class="col-sm-4 col-12">
                <div class="dashboard-project-card">
                    <div class="card-inner  d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <a href="{{ route('schoolemployee.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Teacher') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalTeacher'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-12">
                <div class="dashboard-project-card">
                    <div class="card-inner d-flex justify-content-between">
                        <div class="card-content">
                            <div class="theme-avtar bg-white">
                                <i class="ti ti-brand-apple-arcade"></i>
                            </div>
                            <a href="{{ route('admission.index') }}"><h3 class="mt-3 mb-0">{{ __('Total Admission') }}</h3></a>
                        </div>
                        <h3 class="mb-0">{{ $data['totalAdmission'] }}</h3>
                    </div>
                </div>
            </div>
            @endif
            @stack('scholarship_dashboard_cards')
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th> {{ __('Image') }}</th>
                                <th> {{ __('Title') }}</th>
                                <th> {{ __('Class') }}</th>
                                <th> {{ __('Subject') }}</th>
                                <th> {{ __('Submission Date') }}</th>
                                <th> {{ __('Content') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($homeworks as $homework)
                            @if ($homework->student_homework != null)
                            <tr>
                                <td width="200">
                                    <a class="image-fixsize" href="{{ get_file($homework->homework) }}" target="_blank">
                                        <img src=" {{ get_file($homework->homework) }} "
                                            class="rounded border-2 border border-primary">
                                    </a>
                                </td>
                                <td>{{ !empty($homework->title) ? $homework->title : '' }}</td>
                                <td>{{ isset($homework->className) ? $homework->className->class_name : '-' }}</td>
                                <td>{{ isset($homework->subjectName) ? $homework->subjectName->subject_name : '-'}}</td>
                                <td>{{ $homework->submission_date }}</td>
                                <td> <a class="action-item" data-url="{{ route('homework.content', $homework->id) }}"
                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                        data-title="{{ __('Content') }}"><i class="fa fa-comment"></i>
                                    </a></td>
                                <td>
                                    <a href="{{ get_file($homework->student_homework) }}" download=""
                                        class="btn btn-sm btn-primary text-white" data-bs-toggle="tooltip" target="_blank"
                                        title="{{ __('Download') }}"><span
                                            class="ti ti-download"></span></a>
                                </td>
                            </tr>
                            @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stack('scholarship_dashboard_charts')
@endsection
