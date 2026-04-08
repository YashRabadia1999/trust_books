@extends('layouts.main')
@section('page-title')
    {{ __('Create Parent') }}
@endsection
@section('page-breadcrumb')
    {{ __('Parent') }}
@endsection
@section('content')
    <div class="row">
        {{ Form::open(['route' => 'school-parent.store', 'class' => 'w-100', 'enctype' => 'multipart/form-data' , 'class' => 'needs-validation','novalidate']) }}
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Account Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('client', __('Client'), ['class' => 'form-label']) }}
                            {!! Form::select('client', $client, null, [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => __('Select Client Name'),
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Personal Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('name', __('Parent Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Parent Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('date_of_birth', __('Date Of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::date('date_of_birth', date('Y-m-d'), [
                                'class' => 'form-control',
                                'placeholder' => 'Date Of Birth',
                                'required' => 'required',
                                'max' => date('Y-m-d')
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('student', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('student', $student, null, [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => __('Select Student Name'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('relation', __('Relation'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('relation', $realtion, null, [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => __('Select Relation'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="d-flex radio-check">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="male" value="male" name="gender"
                                        class="form-check-input code" checked="checked">
                                    <label class="custom-control-label" for="male">{{ __('Male') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="female" value="female" name="gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="female">{{ __('Female') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="other" value="other " name="gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="other">{{ __('Other') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (module_is_active('CustomField') && !$customFields->isEmpty())
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                @include('custom-field::formBuilder')
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Contact Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                            {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter state')]) }}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                            {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter city')]) }}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}
                            {{ Form::number('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}
                        </div>
                    </div>
                    <x-mobile divClass='col-md-4 col-sm-6 col-12' name="contact" label='Mobile Number' placeholder='Enter Number' required></x-mobile>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="form-icon-user">
                                {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'minlength' => '6', 'placeholder' => 'Enter Password']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('parent_image', __('Photo'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="choose-file">
                                <label for="Image" style="
                                width: -webkit-fill-available;">
                                    <input type="file" class="form-control" name="parent_image" id="parent_image"
                                        data-filename="parent_image" accept="image/*,.jpeg,.jpg,.png" required="required"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="blah" width="25%" class="mt-3">

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer mb-4">
        <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('school-parent.index') }}';"
            class="btn btn-light me-2 ">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary ">
    </div>
    {{ Form::close() }}
    </div>
@endsection
