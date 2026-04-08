@extends('layouts.main')
@section('page-title')
    {{ __('Manage Licence Type') }}
@endsection
@section('page-breadcrumb')
    {{ __('Licence Type') }}
@endsection

@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('driving licencetype create')
            <a data-url="{{ route('driving_licence_type.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create Licence Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('driving-school::driving_types_setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 " >
                            <thead>
                                <tr>
                                    <th>{{ __('Licence Type') }}</th>
                                    @if (Laratrust::hasPermission('driving licencetype edit') || Laratrust::hasPermission('driving licencetype delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($licence_types as $licence_type)
                                    <tr>
                                        <td>{{ $licence_type->name }}</td>
                                        <td class="Action">
                                            <span>
                                                @permission('driving licencetype edit')
                                                    <div class="action-btn me-2">
                                                        <a class="mx-3 btn btn-sm bg-info align-items-center"
                                                            data-url="{{route('driving_licence_type.edit', $licence_type->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Licence Type') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission

                                                @permission('driving licencetype delete')
                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['driving_licence_type.destroy', $licence_type->id], 'id' => 'delete-form-' . $licence_type->id]) !!}
                                                            <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title="{{__('Delete')}}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-bs-original-title="Delete"
                                                                aria-label="Delete">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            </span>
                                        </td>
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
    </div>
@endsection
