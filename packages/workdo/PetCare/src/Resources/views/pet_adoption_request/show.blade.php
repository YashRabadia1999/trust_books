@extends('layouts.main')
@section('page-title')
{{ __('Adoption Request Details') }}
@endsection
@section('page-breadcrumb')
{{ __('Request Details') }}
@endsection
@push('css')
<style>
    .pet-adoption-request-card .card .info.font-style {
        margin-bottom: 10px;
    }

    .pet-adoption-request-card .card .info.font-style strong {
        font-weight: 500;
        margin-right: 5px;
    }
</style>
@endpush
@section('page-action')
<div class="action-btn me-2">
    <a href="{{ route('pet.adoption.request.index') }}" class="btn-submit btn btn-sm btn-primary" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Back') }}">
        <i class=" ti ti-arrow-back-up"></i>
    </a>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="pet-adoption-request-card">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __("Adopter's Details") }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Adoption Request Id') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionRequest->adoption_request_number) ? Workdo\PetCare\Entities\PetAdoptionRequest::PetAdoptionRequestNumberFormat($petAdoptionRequest->adoption_request_number) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Adopter Name') }} :</strong>
                                <span
                                    class="text-muted">{{ !empty($petAdoptionRequest->adopter_name) ? $petAdoptionRequest->adopter_name : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Email') }} :</strong>
                                <span
                                    class="text-muted">{{ !empty($petAdoptionRequest->email) ? $petAdoptionRequest->email : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Contact Number') }} :</strong>
                                <span
                                    class="text-muted">{{ !empty($petAdoptionRequest->contact_number) ? $petAdoptionRequest->contact_number : '-' }}</span>
                            </div>
                        </div>                        
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Address') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionRequest->address) ? $petAdoptionRequest->address : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Appointment Status') }} :</strong>
                                @if ($petAdoptionRequest->request_status == 'pending')
                                <span class="badge bg-warning p-2 px-3">{{ ucfirst($petAdoptionRequest->request_status) }}</span>
                                @elseif ($petAdoptionRequest->request_status == 'approved')
                                <span class="badge bg-success p-2 px-3">{{ ucfirst($petAdoptionRequest->request_status) }}</span>
                                @elseif ($petAdoptionRequest->request_status == 'rejected')
                                <span class="badge bg-danger p-2 px-3">{{ ucfirst($petAdoptionRequest->request_status) }}</span>
                                @elseif ($petAdoptionRequest->request_status == 'completed')
                                <span class="badge bg-primary p-2 px-3">{{ ucfirst($petAdoptionRequest->request_status) }}</span>
                                @else
                                <span class="badge bg-secondary p-2 px-3">{{ ucfirst($petAdoptionRequest->request_status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Reason For Adoption') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionRequest->reason_for_adoption) ? $petAdoptionRequest->reason_for_adoption : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info font-style">
                                <h5>{{ __('Pet Adoption Details') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Pet Adoption Id') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->pet_name) ? Workdo\PetCare\Entities\PetAdoption::PetAdoptionNumberFormat($petAdoptionDetails->adoption_number) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Pet Name') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->pet_name) ? $petAdoptionDetails->pet_name : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Species') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->species) ? $petAdoptionDetails->species : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Breed') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->breed) ? $petAdoptionDetails->breed : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Adoption Amount') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->adoption_amount) ?  currency_format_with_sym($petAdoptionDetails->adoption_amount) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Availability') }} :</strong>
                                @php
                                $availability = $petAdoptionDetails->availability;
                                $status_color = ['available_now' => 'success','coming_soon' => 'warning','adopted' => 'primary','not_available' => 'danger',];
                                $color = $status_color[$availability] ?? 'secondary';
                                $label = \Workdo\PetCare\Entities\PetAdoption::$availability[$availability] ?? $availability;
                                @endphp
                                <span class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ $label }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Gender') }} :</strong>
                                <span class="text-muted">{{ !empty($petAdoptionDetails->gender) ? $petAdoptionDetails->gender : '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Age') }} :</strong>
                                @php
                                    $dob = $petAdoptionDetails->date_of_birth ?? null;
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
                                <strong>{{ __('Classification Tags') }} :</strong>
                                <span class="text-muted">
                                    @if (!empty($petAdoptionDetails->classification_tags))
                                        @foreach (explode(',', $petAdoptionDetails->classification_tags) as $tag)
                                            <span class="badge p-2 px-3 bg-primary">{{ trim($tag) }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Health Condition') }} :</strong>
                                <span class="text-muted">
                                @php $health = $petAdoptionDetails->health_status; $healthLabel =
                                \Workdo\PetCare\Entities\PetAdoption::$health_status[$health] ?? $health;
                                @endphp 
                                {{ !empty($healthLabel) ? $healthLabel : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info font-style">
                                <strong>{{ __('Description') }} :</strong>
                                <span class="text-muted">
                                        {{ !empty($petAdoptionDetails->description) ? $petAdoptionDetails->description : '-' }}
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