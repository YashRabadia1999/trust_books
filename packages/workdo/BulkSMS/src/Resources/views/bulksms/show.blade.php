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
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>                                    
                                    <th>{{ __('Status') }}</th>   
                                    @if (Laratrust::hasPermission('group_contact delete'))
                                    <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody> 
                                @if($contacts)
                                    @foreach ($contacts as $contact)
                                        <tr>                
                                            <td>{{ $loop->iteration  }}</td>                                        
                                            <td>{{ ($contact->name ?? '') }}</td>                                        
                                            <td>{{ ($contact->mobile_no ?? '') }}</td>                                                                                
                                            <td>{{ ucfirst($contact->status ?? '') }}</td>   
                                            <td>
                                                @permission('bulksms_send delete')
                                                    <div class="action-btn">
                                                        {{ Form::open(['route' => ['bulksms.bulk.remove', $contact->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="btn btn-sm  align-items-center bs-pass-para show_confirm bg-danger"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $contact->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                            </td>                                                                                                                                          
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
