@extends('layouts.main')
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-title')
    {{ __(isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : 'Branch') }}
@endsection
@section('page-breadcrumb')
{{ __('Branch') }}
@endsection
@section('page-action')
<div>
    @permission('school_branch create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Branch') }}" data-url="{{route('schoolbranches.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('school::school_setup')
    </div>
    @permission('school_branch name edit')
    <div class="col-sm-9">
        <div class="card">
            <div class="d-flex justify-content-between">
                <div class="card-body table-border-style">
                    <h4>{{isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')}}</h4>
                </div>
                <div class="d-flex align-items-center px-4">
                    <div class="action-btn bg-info">
                        <a  class="mx-3 btn btn-sm  align-items-center"
                            data-url="{{ route('schoolbranchesname.edit') }}"
                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                            data-title="{{ __('Edit '.(isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')))}}"
                            data-bs-original-title="{{ __('Edit Name') }}">
                            <i class="ti ti-pencil text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
     @endpermission
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 " >
                        <thead>
                            <tr>
                                <th>{{ isset($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')}}</th>
                                @if (Laratrust::hasPermission('school_branch edit') || Laratrust::hasPermission('school_branch delete'))
                                <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody >
                                @forelse($branches as $branch)
                                    <tr>
                                        <td>{{ !empty($branch->name) ? $branch->name : '' }}</td>
                                        <td class="Action">
                                            <span>
                                                @permission('school_branch edit')
                                                    <div class="action-btn  me-2">
                                                        <a  class="mx-3 btn bg-info btn-sm  align-items-center"
                                                            data-url="{{ URL::to('schoolbranches/' . $branch->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Branch') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('school_branch delete')
                                                <div class="action-btn  me-2">
                                                    {{Form::open(array('route'=>array('schoolbranches.destroy', $branch->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a
                                                            class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$branch->id}}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                    {{Form::close()}}
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

