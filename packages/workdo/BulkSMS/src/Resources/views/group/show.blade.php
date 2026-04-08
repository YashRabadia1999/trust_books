@extends('layouts.main')

@section('page-title')
    {{ __('Contact Details') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Contact') }}
@endsection

@section('content')
<div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="no">{{ __('No') }}</th>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Phone') }}</th>                                    
                                    @if (Laratrust::hasPermission('group_contact delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach ($contacts as $contact)
                                    <tr>                                        
                                        <td>{{ $loop->iteration }}</td>                                        
                                        <td>{{ ($contact->name) }}</td>                                        
                                        <td>{{ ($contact->mobile_no) }}</td>                                        
                                        @if (Laratrust::hasPermission('group_contact delete'))
                                            <td class="text-end">                                              
                                                @permission('group_contact delete')                                                    
                                                    <div class="action-btn">
                                                        {{ Form::open(['route' => ['group.contact.remove', $bulksmsGroup->id, $contact->mobile_no], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $bulksmsGroup->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
