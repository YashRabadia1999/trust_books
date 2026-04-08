@extends('layouts.main')
@section('page-title')
    {{ __('Edit Admission') }}
@endsection
@section('page-breadcrumb')
    {{ __('Admission') }}
@endsection

@section('content')
    <div class="row">
        {{ Form::model($admission, ['route' => ['admission.update', $admission->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation','novalidate']) }}
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Student Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('admission_id', __('Number'), ['class' => 'form-label']) }}
                            <input type="text" class="form-control" value="{{ $admission_number }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::date('date', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Date',
                                'required' => 'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('student_name', __('Student Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('student_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Student Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('date_of_birth', __('Date Of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::date('date_of_birth', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Date Of Birth',
                                'required' => 'required',
                                'max' => date('Y-m-d')
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="d-flex radio-check">
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="male" value="male"
                                        @if ($admission->gender == 'male') checked @endif name="gender"
                                        class="form-check-input code" checked="checked">
                                    <label class="custom-control-label" for="male">{{ __('Male') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="female" value="female"
                                        @if ($admission->gender == 'female') checked @endif name="gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="female">{{ __('Female') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="other" value="other"
                                        @if ($admission->gender == 'other') checked @endif name="gender"
                                        class="form-check-input code">
                                    <label class="custom-control-label" for="other">{{ __('Other') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('blood_group', __('Blood Group'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('blood_group', null, ['class' => 'form-control', 'placeholder' => __('Enter Blood Group'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter state'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter city'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::number('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <x-mobile divClass='col-sm-6 col-12' name="phone" label='Mobile Number' required></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('previous_school', __('Previous School'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('previous_school', null, ['class' => 'form-control', 'placeholder' => __('Enter Previous School Name'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('student_image', __('Photo'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="student_image" id="student_image"
                                        data-filename="student_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="blah" width="25%" class="mt-3"
                                        src={{ get_file($admission->student_image) }}>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('medical_history', __('Medical History'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('medical_history', null, ['class' => 'form-control', 'placeholder' => __('Enter Medical History'), 'required' => 'required']) }}
                        </div>
                    </div>
                    @if (module_is_active('CustomField') && !$customFields->isEmpty())
                        <div class="col-sm-6 col-12">
                            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                @include('custom-field::formBuilder', [
                                    'fildedata' => $admission->customField,
                                ])
                            </div>
                        </div>
                    @endif
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
                    <x-mobile divClass='col-sm-6 col-12' name="father_number" label='Mobile Number' required></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_occupation', __('Occupation'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('father_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('father_email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('father_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('father_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('father_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('father_image', __('Father Photo'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="father_image" id="father_image"
                                        data-filename="father_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="blah" width="25%" class="mt-3"
                                        src="{{ get_file($admission->father_image) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4>{{ __('Mother Information') }}</h4>
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
                            {{ Form::label('mother_occupation', __('Occupation'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('mother_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('mother_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('mother_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('mother_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_image', __('Mother Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="mother_image" id="mother_image"
                                        data-filename="mother_image" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image2').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image2" width="25%" class="mt-3"
                                        src="{{ get_file($admission->mother_image) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('guardian', __('If Guardian is'), ['class' => 'form-label me-2']) }}
                    <div class="d-flex radio-check">
                    <div class="form-check form-check-inline">
                    <input type="radio" id="father" value="father"
                        {{ isset($guardianData['guardian']) && $guardianData['guardian'] == 'father' ? 'checked' : '' }}
                        name="guardian" class="form-check-input code">
                    <label class="custom-control-label me-2" for="father">{{ __('Father') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                    <input type="radio" id="mother" value="mother"
                        {{ isset($guardianData['guardian']) && $guardianData['guardian'] == 'mother' ? 'checked' : '' }}
                        name="guardian" class="form-check-input code">
                    <label class="custom-control-label me-2" for="mother">{{ __('Mother') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                    <input type="radio" id="others" value="others"
                        {{ isset($guardianData['guardian']) && $guardianData['guardian'] == 'other' ? 'checked' : '' }}
                        name="guardian" class="form-check-input code">
                    <label class="custom-control-label" for="others">{{ __('Other') }}</label>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card guardian-details" id="father_guardian">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_name', __('Father Name'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Father Name')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_realtion', __('Relation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_realtion', null, ['class' => 'form-control', 'placeholder' => __('Enter Relation')]) }}
                        </div>
                    </div>

                    <x-mobile divClass='col-sm-6 col-12' name="guardian_number" label='Mobile Number'></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_occupation', __('Occupation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('guardian_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('guardian_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>

                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_father_image', __('Father Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="guardian_father_image"
                                        id="guardian_father_image" data-filename="guardian_father_image"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image4').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image4" width="25%" class="mt-3"
                                        src="{{ isset($guardianData['guardian_father_image']) ? get_file($guardianData['guardian_father_image']) : '' }}">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card guardian-details" id="mother_guardian">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_name', __('Mother Name'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Mother Name')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_realtion', __('Relation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_realtion', null, ['class' => 'form-control', 'placeholder' => __('Enter Relation'), 'value' => 'Mother']) }}
                        </div>
                    </div>

                    <x-mobile divClass='col-sm-6 col-12' name="guardian_number" label='Mobile Number'></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_occupation', __('Occupation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('guardian_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('guardian_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('guardian_mother_image', __('Mother Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="guardian_mother_image"
                                        id="guardian_mother_image" data-filename="guardian_mother_image"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image5').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image5" width="25%" class="mt-3"
                                        src="{{ isset($guardianData['guardian_mother_image']) ? get_file($guardianData['guardian_mother_image']) : '' }}">

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card guardian-details" id="others_guardian">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_name', __('Name'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_realtion', __('Guardian Relation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_realtion', null, ['class' => 'form-control', 'placeholder' => __('Enter Relation')]) }}
                        </div>
                    </div>

                    <x-mobile divClass='col-sm-6 col-12' name="guardian_number" label='Mobile Number'></x-mobile>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_occupation', __('Occupation'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_occupation', null, ['class' => 'form-control', 'placeholder' => __('Enter Occupation')]) }}
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('guardian_email', __('Email'), ['class' => 'form-label']) }}
                            {{ Form::text('guardian_email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('guardian_address', __('Address'), ['class' => 'form-label']) }}
                            {{ Form::textarea('guardian_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('guardian_other_image', __('Photo'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="guardian_other_image"
                                        id="guardian_other_image" data-filename="guardian_other_image"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image6').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image6" width="25%" class="mt-3"
                                        src="{{ isset($guardianData['guardian_other_image']) ? get_file($guardianData['guardian_other_image']) : '' }}">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Student Documents') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('leaving_certificate', __('Leaving Certificate'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="leaving_certificate"
                                        id="leaving_certificate" data-filename="leaving_certificate"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image7').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image7" width="25%" class="mt-3"
                                        src="{{ get_file($admission->leaving_certificate) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('marksheet', __('Mark sheet of the last exam'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="marksheet" id="marksheet"
                                        data-filename="marksheet" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image8').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image8" width="25%" class="mt-3"
                                        src="{{ get_file($admission->marksheet) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('birth_certificate', __('Birth Certificate'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="birth_certificate"
                                        id="birth_certificate" data-filename="birth_certificate"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image9').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image9" width="25%" class="mt-3"
                                        src="{{ get_file($admission->birth_certificate) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('address_proof', __('Address Proof'), ['class' => 'form-label']) }}<x-required></x-required>
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="address_proof" id="address_proof"
                                        data-filename="address_proof" accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image10').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image10" width="25%" class="mt-3"
                                        src="{{ get_file($admission->address_proof) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('bonafide_certificate', __('Bonafide Certificate'), ['class' => 'form-label']) }}
                            <div class="choose-file">
                                <label for="Image" class="w-100">
                                    <input type="file" class="form-control" name="bonafide_certificate"
                                        id="bonafide_certificate" data-filename="bonafide_certificate"
                                        accept="image/*,.jpeg,.jpg,.png"
                                        onchange="document.getElementById('image11').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                    <img id="image11" width="25%" class="mt-3"
                                        src="{{ get_file($admission->bonafide_certificate) }}">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer mb-4">
            <input type="button" value="{{ __('Cancel') }}"
                onclick="location.href = '{{ route('admission.index') }}';" class="btn btn-light me-2 ">
            <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var selectedValue = $('input[name=guardian]:checked').val();
            if (selectedValue == 'father') {
                $('#father_guardian').show();
                $('#mother_guardian').hide();
                $('#others_guardian').hide();
                var fatherNameValue = $("[name='father_name']").val();
                var fatherNumberValue = $("[name='father_number']").val();
                var fatherOccupationValue = $("[name='father_occupation']").val();
                var fatherEmailValue = $("[name='father_email']").val();
                var fatherAddressValue = $("[name='father_address']").val();
                $("[name='guardian_name']").val(fatherNameValue);
                $("[name='guardian_realtion']").val('Father');
                $("[name='guardian_number']").val(fatherNumberValue);
                $("[name='guardian_occupation']").val(fatherOccupationValue);
                $("[name='guardian_email']").val(fatherEmailValue);
                $("[name='guardian_address']").val(fatherAddressValue);
            } else if (selectedValue == 'mother') {
                $('#father_guardian').hide();
                $('#mother_guardian').show();
                $('#others_guardian').hide();
                var motherNameValue = $("[name='mother_name']").val();
                var motherNumberValue = $("[name='mother_number']").val();
                var motherOccupationValue = $("[name='mother_occupation']").val();
                var motherEmailValue = $("[name='mother_email']").val();
                var motherAddressValue = $("[name='mother_address']").val();

                $("[name='guardian_name']").val(motherNameValue);
                $("[name='guardian_realtion']").val('Mother');
                $("[name='guardian_number']").val(motherNumberValue);
                $("[name='guardian_occupation']").val(motherOccupationValue);
                $("[name='guardian_email']").val(motherEmailValue);
                $("[name='guardian_address']").val(motherAddressValue);
            } else {
                if (selectedValue == 'others') {
                    $('#father_guardian').hide();
                    $('#mother_guardian').hide();
                    $('#others_guardian').show();
                    $("[name='guardian_name']").val('');
                    $("[name='guardian_realtion']").val('');
                    $("[name='guardian_number']").val('');
                    $("[name='guardian_occupation']").val('');
                    $("[name='guardian_email']").val('');
                    $("[name='guardian_address']").val('');
                }
            }
        });
        $(document).on('click', '#father', function() {
            var selectedValue = $(this).val();
            if (selectedValue == 'father') {
                $('#father_guardian').show();
                $('#mother_guardian').hide();
                $('#others_guardian').hide();
                var fatherNameValue = $("[name='father_name']").val();
                var fatherNumberValue = $("[name='father_number']").val();
                var fatherOccupationValue = $("[name='father_occupation']").val();
                var fatherEmailValue = $("[name='father_email']").val();
                var fatherAddressValue = $("[name='father_address']").val();
                $("[name='guardian_name']").val(fatherNameValue);
                $("[name='guardian_realtion']").val('Father');
                $("[name='guardian_number']").val(fatherNumberValue);
                $("[name='guardian_occupation']").val(fatherOccupationValue);
                $("[name='guardian_email']").val(fatherEmailValue);
                $("[name='guardian_address']").val(fatherAddressValue);
            }
        });

        $(document).on('click', '#mother', function() {
            var selectedValue = $(this).val();
            if (selectedValue == 'mother') {
                $('#father_guardian').hide();
                $('#mother_guardian').show();
                $('#others_guardian').hide();
                var motherNameValue = $("[name='mother_name']").val();
                var motherNumberValue = $("[name='mother_number']").val();
                var motherOccupationValue = $("[name='mother_occupation']").val();
                var motherEmailValue = $("[name='mother_email']").val();
                var motherAddressValue = $("[name='mother_address']").val();

                $("[name='guardian_name']").val(motherNameValue);
                $("[name='guardian_realtion']").val('Mother');
                $("[name='guardian_number']").val(motherNumberValue);
                $("[name='guardian_occupation']").val(motherOccupationValue);
                $("[name='guardian_email']").val(motherEmailValue);
                $("[name='guardian_address']").val(motherAddressValue);
            }
        });
        $(document).on('click', '#others', function() {
            var selectedValue = $(this).val();
            if (selectedValue == 'others') {
                $('#father_guardian').hide();
                $('#mother_guardian').hide();
                $('#others_guardian').show();
                $("[name='guardian_name']").val('');
                $("[name='guardian_realtion']").val('');
                $("[name='guardian_number']").val('');
                $("[name='guardian_occupation']").val('');
                $("[name='guardian_email']").val('');
                $("[name='guardian_address']").val('');
            }
        })
    </script>
     <script>
        $(document).ready(function() {
            $('.guardian-details').hide();
            $('input[name="guardian"]').change(function() {
                $('.guardian-details').hide();
                var selectedGuardian = $(this).val();
                $('#' + selectedGuardian + '_guardian').show();
            });
        });
    </script>
@endpush
