@extends('layouts.main')
@section('page-title')
    {{ __('Pet Appointment Details') }}
@endsection
@section('page-breadcrumb')
    {{ __('Appointment Details') }}
@endsection
@push('css')
    <style>
        .pet-appointment-card .card .info.font-style {
            margin-bottom: 10px;
        }

        .pet-appointment-card .card .info.font-style strong {
            font-weight: 500;
            margin-right: 5px;
        }
    </style>
@endpush
@section('page-action')
    <div class="action-btn me-2">
        <a href="{{ route('pet.appointments.index') }}" class="btn-submit btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Back') }}">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="pet-appointment-card">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Owner Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Owner Name') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petOwner->owner_name) ? $petOwner->owner_name : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Email') }} :</strong>
                                    <span class="text-muted">{{ !empty($petOwner->email) ? $petOwner->email : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Contact Number') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petOwner->contact_number) ? $petOwner->contact_number : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Address') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petOwner->address) ? $petOwner->address : '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <h5>{{ __('Pet Details') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Pet Name') }} :</strong>
                                    <span class="text-muted">{{ !empty($pet->pet_name) ? $pet->pet_name : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Species') }} :</strong>
                                    <span class="text-muted">{{ !empty($pet->species) ? $pet->species : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Breed') }} :</strong>
                                    <span class="text-muted">{{ !empty($pet->breed) ? $pet->breed : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Age') }} :</strong>
                                    @php
                                        $dob = $pet->date_of_birth ?? null;
                                        $ageText = '-';

                                        if ($dob) {
                                            $dob = \Carbon\Carbon::parse($dob);
                                            $now = \Carbon\Carbon::now();
                                            $diff = $dob->diff($now);

                                            $years = $diff->y;
                                            $months = $diff->m;
                                            $days = $diff->d;

                                            $parts = [];

                                            if ($years > 0) {
                                                $parts[] = $years . ' ' . __('year') . ($years > 1 ? 's' : '');
                                            }
                                            if ($months > 0) {
                                                $parts[] = $months . ' ' . __('month') . ($months > 1 ? 's' : '');
                                            }
                                            if (empty($parts) && $days > 0) {
                                                $parts[] = $days . ' ' . __('day') . ($days > 1 ? 's' : '');
                                            }

                                            $ageText = count($parts) ? implode(', ', $parts) : __('0');
                                        }
                                    @endphp
                                    <span class="text-muted">{{ $ageText }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Gender') }} :</strong>
                                    <span class="text-muted">{{ !empty($pet->gender) ? $pet->gender : '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <h5>{{ __('Appointment Details') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Assigned Staff Member') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petAppointment->assigned_staff_id) ? $petAppointment->assignedStaff->name : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Appointment Date') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petAppointment->appointment_date) ? company_date_formate($petAppointment->appointment_date) : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Appointment Time') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petAppointment->appointment_time) ? $petAppointment->appointment_time : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Total Service/Package Amount') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petAppointment->total_service_package_amount) ? currency_format_with_sym($petAppointment->total_service_package_amount) : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Appointment Status') }} :</strong>
                                    @if ($petAppointment->appointment_status == 'pending')
                                        <span
                                            class="badge bg-warning p-2 px-3">{{ ucfirst($petAppointment->appointment_status) }}</span>
                                    @elseif ($petAppointment->appointment_status == 'approved')
                                        <span
                                            class="badge bg-success p-2 px-3">{{ ucfirst($petAppointment->appointment_status) }}</span>
                                    @elseif ($petAppointment->appointment_status == 'rejected')
                                        <span
                                            class="badge bg-danger p-2 px-3">{{ ucfirst($petAppointment->appointment_status) }}</span>
                                    @elseif ($petAppointment->appointment_status == 'completed')
                                        <span
                                            class="badge bg-primary p-2 px-3">{{ ucfirst($petAppointment->appointment_status) }}</span>
                                    @else
                                        <span
                                            class="badge bg-info p-2 px-3">{{ ucfirst($petAppointment->appointment_status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Notes') }} :</strong>
                                    <span
                                        class="text-muted">{{ !empty($petAppointment->notes) ? $petAppointment->notes : '-' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Services Name') }} :</strong>
                                    <span class="text-muted">
                                        @if ($selectedServices->isNotEmpty())
                                            <ul>
                                                @foreach ($selectedServices as $service)
                                                    <li>{{ $service->service_name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info font-style">
                                    <strong>{{ __('Packages Name') }} :</strong>
                                    <span class="text-muted">
                                        @if ($selectedPackages->isNotEmpty())
                                            <ul>
                                                @forelse($selectedPackages as $package)
                                                    <li>{{ $package->package_name }}</li>
                                                @empty
                                                    '-'
                                                @endforelse
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
