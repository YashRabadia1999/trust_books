@extends('layouts.main')
@section('page-title')
    {{ __('Manage Test Type') }}
@endsection
@section('page-breadcrumb')
    {{ __('Test Type') }}
@endsection

@section('page-action')
    <div class="d-flex">
        @stack('addButtonHook')
        @permission('driving testtype create')
            <a data-url="{{ route('driving_test_type.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create Test Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                                    <th>{{ __('Test Type') }}</th>
                                    @if (Laratrust::hasPermission('driving testtype edit') || Laratrust::hasPermission('driving testtype delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($test_types as $test_type)
                                    <tr>
                                        <td>{{ $test_type->name }}</td>
                                        <td class="Action">
                                            <span>
                                                @permission('driving testtype edit')
                                                    <div class="action-btn me-2">
                                                        <a class="mx-3 btn btn-sm bg-info align-items-center"
                                                            data-url="{{route('driving_test_type.edit', $test_type->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Test Type') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission

                                                @permission('driving testtype delete')
                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['driving_test_type.destroy', $test_type->id], 'id' => 'delete-form-' . $test_type->id]) !!}
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
