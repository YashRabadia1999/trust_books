@extends('layouts.main')

@section('page-title')
    {{ __('Social Links') }}
@endsection

@section('page-breadcrumb')
    {{ __('Social Links') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/PetCare/src/Resources/assets/css/all.min.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('pet-care::layouts.system-setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="">
                                {{ __('Social Links') }}
                            </h5>
                        </div>
                        <div class="col-1 text-end">
                            @permission('petcare_social_links create')
                                <a data-url="{{ route('petcare.social.links.create') }}" data-size="md" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="{{ __('Create') }}" title="{{ __('Create') }}"
                                    data-title="{{ __('Create Social Links') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Social Media Icon') }}</th>
                                <th scope="col">{{ __('Social Media Name') }}</th>
                                <th scope="col">{{ __('Social Media Link') }}</th>

                                @if (Laratrust::hasPermission('petcare_social_links edit') || Laratrust::hasPermission('petcare_social_links delete'))
                                    <th width="100px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($socialMediaLinks as $socialMediaLink)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($socialMediaLink->social_media_icon)
                                            <span class="{{ $socialMediaLink->social_media_icon }} fs-3"></span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>{{ $socialMediaLink->social_media_name ?? '' }}</td>

                                    <td>
                                        @if (!empty($socialMediaLink->social_media_link))
                                            <a href="{{ $socialMediaLink->social_media_link }}" target="_blank"
                                                class="text-primary">
                                                {{ Str::limit($socialMediaLink->social_media_link, 40) }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    @if (Laratrust::hasPermission('petcare_social_links edit') || Laratrust::hasPermission('petcare_social_links delete'))
                                        <td class="Action">
                                            <div class="d-flex gap-2">
                                                @permission('petcare_social_links edit')
                                                    <a class="bg-info btn btn-sm" href="javascript:void(0);"
                                                        data-url="{{ route('petcare.social.links.edit', \Illuminate\Support\Facades\Crypt::encrypt($socialMediaLink->id)) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit') }}" data-title="{{ __('Edit Social Link') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                @endpermission
                                                @permission('petcare_social_links delete')
                                                    {{ Form::open(['route' => ['petcare.social.links.destroy', \Illuminate\Support\Facades\Crypt::encrypt($socialMediaLink->id)], 'class' => 'm-0', 'id' => 'delete-form-' . $socialMediaLink->id]) }}
                                                    @method('DELETE')
                                                    <a class="bg-danger btn btn-sm show_confirm" href="javascript:void(0);"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action cannot be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $socialMediaLink->id }}">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {{ Form::close() }}
                                                @endpermission
                                            </div>
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
