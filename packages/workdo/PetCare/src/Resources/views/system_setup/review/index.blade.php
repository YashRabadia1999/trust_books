@extends('layouts.main')

@section('page-title')
    {{ __('Pet Care Reviews') }}
@endsection

@section('page-breadcrumb')
    {{ __('Pet Care Reviews') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                {{ Form::open(['route' => 'petcare.review.setting.store', 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true]) }}
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Reviews Setting') }}</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('review_tagline_label', __('Review Tagline Label'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('review_tagline_label', $petcare_system_setup['review_tagline_label'] ?? null, ['class' => 'form-control', 'placeholder' => __('Client Reviews'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('review_heading_title', __('Review Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('review_heading_title', $petcare_system_setup['review_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('What Pet Parents Say'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('review_form_heading_title', __('Review Form Heading Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('review_form_heading_title', $petcare_system_setup['review_form_heading_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Share Your Experience'), 'required']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('review_form_sub_title', __('Review Form Sub Title'), ['class' => 'form-label']) }}<x-required></x-required>
                                {{ Form::text('review_form_sub_title', $petcare_system_setup['review_form_sub_title'] ?? null, ['class' => 'form-control', 'placeholder' => __('Help other pet parents'), 'required']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                </div>
                {{ Form::close() }}
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="">
                                {{ __('Reviews') }}
                            </h5>
                        </div>
                        <div class="col-1 text-end">
                            @permission('petcare_review create')
                                <a data-url="{{ route('petcare.review.create') }}" data-size="md" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="{{ __('Create') }}" title="{{ __('Create') }}"
                                    data-title="{{ __('Create Review') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-0 ">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Reviewer Name') }}</th>
                                <th scope="col">{{ __('Rating') }}</th>
                                <th scope="col">{{ __('Review') }}</th>
                                <th scope="col">{{ __('Display Status') }}</th>
                                <th scope="col">{{ __('Review Status') }}</th>
                                @if (Laratrust::hasPermission('petcare_review action') || Laratrust::hasPermission('petcare_review edit') || Laratrust::hasPermission('petcare_review delete'))
                                    <th width="100px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($petCareReviews as $key => $petCareReview)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $petCareReview->reviewer_name }}</td>
                                    <td>
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="ti ti-star {{ $i < $petCareReview->rating ? 'text-warning' : '' }} "></i>
                                        @endfor
                                    </td>
                                    <td>
                                        <a class="action-item" data-url="{{ route('petcare.review.details',$petCareReview->id) }}" data-ajax-popup="true"
                                            data-bs-toggle="tooltip" title="{{ __('Review') }}"
                                            data-title="{{ __('Review') }}">
                                            <i class="fa fa-comment"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $displayStatus = $petCareReview->display_status;
                                            $status_color = [
                                                'on' => 'success',
                                                'off' => 'danger',
                                            ];
                                            $color = $status_color[$displayStatus] ?? 'secondary';
                                        @endphp
                                        <span class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ ucfirst($displayStatus) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $ReviewStatus = $petCareReview->review_status;
                                            $status_color = [
                                                'pending' => 'warning',
                                                'approved' => 'primary',
                                                'rejected' => 'danger',
                                            ];
                                            $color = $status_color[$ReviewStatus] ?? 'secondary';
                                        @endphp
                                        <span
                                            class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ ucfirst($ReviewStatus) }}</span>
                                    </td>                                    
                                    @if (Laratrust::hasPermission('petcare_review action') || Laratrust::hasPermission('petcare_review edit') || Laratrust::hasPermission('petcare_review delete'))
                                        <td class="Action">
                                            <span>
                                                @permission('petcare_review action')
                                                <div class="action-btn me-2">
                                                    <a class="mx-3 btn btn-sm align-items-center bg-primary"
                                                        data-url="{{ route('petcare.review.action', \Illuminate\Support\Facades\Crypt::encrypt($petCareReview->id)) }}" data-ajax-popup="true"
                                                        data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Review Action') }}"
                                                        data-bs-original-title="{{ __('Action') }}">
                                                        <i class="ti ti-caret-right text-white"></i>
                                                    </a>
                                                </div>
                                                @endpermission
                                                @permission('petcare_review edit')
                                                    <div class="action-btn me-2">
                                                        <a class="bg-info btn btn-sm  align-items-center"
                                                            data-url="{{ route('petcare.review.edit',\Illuminate\Support\Facades\Crypt::encrypt($petCareReview->id)) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Review') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('petcare_review delete')
                                                    <div class="action-btn">
                                                        {{ Form::open(['route' => ['petcare.review.destroy',\Illuminate\Support\Facades\Crypt::encrypt($petCareReview->id)], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $petCareReview->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
