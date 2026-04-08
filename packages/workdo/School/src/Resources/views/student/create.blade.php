@extends('layouts.main')
@section('page-title')
    {{ __('Create Student') }}
@endsection
@section('page-breadcrumb')
    {{ __('Student') }}
@endsection

@section('content')
    <div class="row">
        {{ Form::open(['route' => 'school-student.store', 'class' => 'w-100 needs-validation', 'enctype' => 'multipart/form-data','novalidate']) }}
        {{-- <div class="card">
            <div class="card-header">
                <h5>{{ __('Account Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <div class="form-group">
                                {{ Form::label('client', __('Client'), ['class' => 'form-label']) }}<x-required></x-required>
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
        </div> --}}
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Personal Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <div class="form-group">
                                {{ Form::label('class_name', __('Current class'), ['class' => 'form-label']) }}<x-required></x-required>
                                {!! Form::select('class_name', $classRoom, null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'placeholder' => __('Select Class'),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('grade_name', __('Level'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('grade_name', $grade, null, [
                                'class' => 'form-control',
                                'required' => 'required',
                                'placeholder' => __('Select Grade'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('name', __('First and Last Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Student Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('roll_number', __('Student Number'), ['class' => 'form-label']) }}
                            {{ Form::number('roll_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Student Number')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('std_date_of_birth', __('Date Of Birth'), ['class' => 'form-label']) }}
                            {!! Form::date('std_date_of_birth', date('Y-m-d'), [
                                'class' => 'form-control',
                                'placeholder' => 'Date Of Birth',
                                'max' => date('Y-m-d')
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('student_gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="d-flex radio-check">
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="male" value="male" name="student_gender"
                                        class="form-check-input code" checked="checked">
                                    <label class="custom-control-label" for="male">{{ __('Male') }}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="female" value="female" name="student_gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="female">{{ __('Female') }}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="other" value="other " name="student_gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="other">{{ __('Other') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('student_image', __('Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" style="width: -webkit-fill-available;">
                                    <input type="file" class="form-control" name="student_image" id="student_image"
                                        data-filename="student_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="blah" width="25%" class="mt-3">

                                </label>
                            </div>
                        </div>
                    </div>
                    @if (module_is_active('CustomField') && !$customFields->isEmpty())
                        <div class="col-sm-6 col-12">
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
                            {{ Form::label('std_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('std_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('std_state', __('Region'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('std_state', null, ['class' => 'form-control', 'placeholder' => __('Enter state'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('std_city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('std_city', null, ['class' => 'form-control', 'placeholder' => __('Enter city'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('std_zip_code', __('Zip Code'), ['class' => 'form-label']) }}
                            {{ Form::number('std_zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}
                        </div>
                    </div>
                    {{-- <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('contact', __('Mobile Number'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('contact', '', ['class' => 'form-control', 'placeholder' => 'Enter Number', 'required' => 'required']) }}
                        </div>
                    </div> --}}
                    <x-mobile divClass='col-sm-6 col-12' name="contact" label='Mobile Number' required></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::email('email', '', ['class' => 'form-control', 'placeholder' => 'Enter Email']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            @php
                                use Illuminate\Support\Str;
                                $generatedPassword = Str::random(8);
                            @endphp
                            {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="form-icon-user">
                                {{ Form::text('password', $generatedPassword, ['class' => 'form-control', 'minlength' => '6', 'readonly']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Father Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_name', __('Father Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('father_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Father Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    {{-- <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_number', __('Mobile Number'), ['class' => 'form-label']) }}
                            {{ Form::text('father_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Mobile Number'), 'required' => 'required']) }}
                        </div>
                    </div> --}}
                    <x-mobile divClass='col-sm-6 col-12' name="father_number" label='Mobile Number' required></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_occupation', __('Occupation'), ['class' => 'form-label']) }}
                            {{ Form::text('father_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('father_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        @php
                            $generatedPassword = Str::random(8);
                        @endphp
                        <div class="form-group">
                            {{ Form::label('father_password', __('Password'), ['class' => 'form-label']) }}
                            {{ Form::text('father_password', $generatedPassword, ['class' => 'form-control', 'minlength' => '6', 'readonly']) }}
                            @error('password')
                                <small class="invalid-password" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('father_image', __('Father Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" style="width: -webkit-fill-available;">
                                    <input type="file" class="form-control" name="father_image" id="father_image"
                                        data-filename="father_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image1').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image1" width="25%" class="mt-3">

                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('father_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('father_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Mother Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_name', __('Mother Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('mother_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mother Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <x-mobile divClass='col-sm-6 col-12' name="mother_number" label='Mobile Number' required></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_occupation', __('Occupation'), ['class' => 'form-label']) }}
                            {{ Form::text('mother_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('mother_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        @php
                            $generatedPassword = Str::random(8);
                        @endphp
                        <div class="form-group">
                            {{ Form::label('mother_password', __('Password'), ['class' => 'form-label']) }}
                            {{ Form::text('mother_password', $generatedPassword, ['class' => 'form-control', 'minlength' => '6', 'readonly']) }}
                            @error('password')
                                <small class="invalid-password" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('mother_image', __('Mother Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" style="width: -webkit-fill-available;">
                                    <input type="file" class="form-control" name="mother_image" id="mother_image"
                                        data-filename="mother_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image2').src = window.URL.createObjectURL(this.files[0])"
                                       >
                                    <img id="image2" width="25%" class="mt-3">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('mother_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('mother_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Health Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('blood_group', __('Blood Group'), ['class' => 'form-label']) }}
                            {{ Form::select('blood_group', [
                                '' => __('Select Blood Group'),
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-'
                            ], null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('emergency_contact', __('Emergency Contact'), ['class' => 'form-label']) }}
                            {{ Form::text('emergency_contact', null, ['class' => 'form-control', 'placeholder' => __('Enter Emergency Contact Number')]) }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('allergies', __('Allergies'), ['class' => 'form-label']) }}
                            {{ Form::textarea('allergies', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any known allergies (Optional)')]) }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('chronic_conditions', __('Chronic Conditions'), ['class' => 'form-label']) }}
                            {{ Form::textarea('chronic_conditions', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any chronic health conditions (Optional)')]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Attachments') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('attachments', __('Attachments'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" style="width: -webkit-fill-available;">
                                    <input type="file" class="form-control" name="attachments" id="attachments"
                                        data-filename="attachments" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image4').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image4" width="25%" class="mt-3">
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal-footer mb-4">
            <input type="button" value="{{ __('Cancel') }}"
                onclick="location.href = '{{ route('school-student.index') }}';" class="btn btn-light me-2 ">
            <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection
