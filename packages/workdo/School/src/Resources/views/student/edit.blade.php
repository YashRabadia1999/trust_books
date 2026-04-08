@extends('layouts.main')
@section('page-title')
{{ __('Edit Student') }}
@endsection
@section('page-breadcrumb')
{{ __('Student') }}
@endsection
@section('content')
<div class="row">
    @if (!empty($student))
    {{ Form::model($student, ['route' => ['school-student.update', $student->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data' , 'class' => 'needs-validation' ,'novalidate']) }}
    @else
    {{ Form::open(['route' => ['school-student.store'], 'method' => 'post' , 'class' => 'needs-validation' ,'novalidate']) }}
    @endif
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <div class="card">
        <div class="card-header">
            <h5>{{ __('Account Information') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        <div class="form-group">
                            {{ Form::label('client', __('Client'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('client', $client, isset($student->client) ? $student->client : null, [
                            'class' => 'form-control',
                            'required' => 'required',
                            'placeholder' => __('Select Client Name'),
                            ]) !!}
                        </div>
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
                <div class="col-sm-6 col-12 ">
                    <div class="form-group">
                        <div class="form-group">
                            {{ Form::label('class_name', __('Current class'), ['class' => 'form-label']) }}<x-required></x-required>
                            {!! Form::select('class_name', $classRoom, isset($student->class_name) ? $student->class_name : '', [
                            'class' => 'form-control',
                            'required' => 'required',
                            'placeholder' => __('Select Class'),
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12 ">
                    <div class="form-group">
                        {{ Form::label('grade_name', __('Grade'), ['class' => 'form-label']) }}<x-required></x-required>
                        {!! Form::select('grade_name', $grade, isset($student->grade_name) ? $student->grade_name : '', [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => __('Select Grade'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('name', __('Student Name'), ['class' => 'form-label']) }}<x-required></x-required>
                        {{ Form::text('name', !empty($user->name) ? $user->name : null, ['class' => 'form-control', 'placeholder' => __('Enter Student Name'), 'required' => 'required']) }}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('roll_number', __('Student Number'), ['class' => 'form-label']) }}
                        {{ Form::number('roll_number', !empty($student->roll_number) ? $student->roll_number : null, ['class' => 'form-control', 'placeholder' => __('Enter Roll Number')]) }}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('std_date_of_birth', __('Date Of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                        {!! Form::date('std_date_of_birth', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Date Of Birth',
                        'required' => 'required',
                        ]) !!}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('student_gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                        <div class="d-flex radio-check">
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="male"
                                    value="male" {{ isset($student->student_gender) && $student->student_gender == 'male' ? 'checked' : '' }}
                                    name="student_gender" class="form-check-input code" checked="checked">
                                <label class="custom-control-label" for="male">{{ __('Male') }}</label>
                            </div>
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="female" value="female"
                                    {{ isset($student->student_gender) && $student->student_gender == 'female' ? 'checked' : '' }}
                                    name="student_gender" class="form-check-input code">
                                <label class="custom-control-label" for="female">{{ __('Female') }}</label>
                            </div>
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="other" value="other"
                                    {{ isset($student->student_gender) && $student->student_gender == 'other' ? 'checked' : '' }}
                                    name="student_gender" class="form-check-input code">
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
                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                <img id="blah" width="25%" class="mt-3"
                                    src={{ isset($student->student_image) ? get_file($student->student_image) : '' }}>
                            </label>
                        </div>
                    </div>
                </div>
                @if(!empty($student))
                @if (module_is_active('CustomField') && !$customFields->isEmpty())
                <div class="col-sm-6 col-12">
                    <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                        @include('custom-field::formBuilder', [
                        'fildedata' => $student->customField,
                        ])
                    </div>
                </div>
                @endif
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
                        {{ Form::textarea('std_address', !empty($student->std_address) ? $student->std_address : null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('std_state', __('State'), ['class' => 'form-label']) }}<x-required></x-required>
                        {{ Form::text('std_state', !empty($student->std_state) ? $student->std_state : null, ['class' => 'form-control', 'placeholder' => __('Enter state'), 'required' => 'required']) }}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('std_city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
                        {{ Form::text('std_city', !empty($student->std_city) ? $student->std_city : null, ['class' => 'form-control', 'placeholder' => __('Enter city'), 'required' => 'required']) }}
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('std_zip_code', __('Zip Code'), ['class' => 'form-label']) }}<x-required></x-required>
                        {{ Form::number('std_zip_code', !empty($student->std_zip_code) ? $student->std_zip_code : null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required' => 'required']) }}
                    </div>
                </div>
                {{-- <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('contact', __('Mobile Number'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('contact', !empty($user->mobile_no) ? $user->mobile_no : null, ['class' => 'form-control', 'placeholder' => 'Enter Number', 'required' => 'required']) }}
            </div>
        </div> --}}
        <x-mobile divClass='col-sm-6 col-12' name="contact" label='Mobile Number' value="{{ !empty($user->mobile_no) ? $user->mobile_no : '' }}" required></x-mobile>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('email', !empty($user->email) ? $user->email : null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => 'required']) }}
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
            {{ Form::label('father_image', __('Father Photo'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <label for="Image" style=" width: -webkit-fill-available; ">
                    <input type="file" class="form-control" name="father_image" id="father_image"
                        data-filename="father_image" accept="image/*,.jpeg,.jpg,.png"
                        onchange="document.getElementById('image1').src = window.URL.createObjectURL(this.files[0])">
                    <img id="image1" width="25%" class="mt-3"
                        src={{ isset($student->father_image) ? get_file($student->father_image) : '' }}>

                </label>
            </div>
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
            {{-- <div class="col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('mother_number', __('Mobile Number'), ['class' => 'form-label']) }}
            {{ Form::text('mother_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Mobile Number'), 'required' => 'required']) }}
        </div>
    </div> --}}
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
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('mother_image', __('Mother Photo'), ['class' => 'form-label']) }}
            <div class="choose-file">
                <label for="Image" style=" width: -webkit-fill-available; ">
                    <input type="file" class="form-control" name="mother_image" id="mother_image"
                        data-filename="mother_image" accept="image/*,.jpeg,.jpg,.png"
                        onchange="document.getElementById('image2').src = window.URL.createObjectURL(this.files[0])">
                    <img id="image2" width="25%" class="mt-3"
                        src={{ isset($student->mother_image) ? get_file($student->mother_image) : '' }}>
                </label>
            </div>
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
                    ], $student->blood_group ?? null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <div class="form-group">
                    {{ Form::label('emergency_contact', __('Emergency Contact'), ['class' => 'form-label']) }}
                    {{ Form::text('emergency_contact', $student->emergency_contact, ['class' => 'form-control', 'placeholder' => __('Enter Emergency Contact Number')]) }}
                </div>
            </div>
            <div class="col-sm-12 col-12">
                <div class="form-group">
                    {{ Form::label('allergies', __('Allergies'), ['class' => 'form-label']) }}
                    {{ Form::textarea('allergies', $student->allergies, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any known allergies (Optional)')]) }}
                </div>
            </div>
            <div class="col-sm-12 col-12">
                <div class="form-group">
                    {{ Form::label('chronic_conditions', __('Chronic Conditions'), ['class' => 'form-label']) }}
                    {{ Form::textarea('chronic_conditions', $student->chronic_conditions, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter any chronic health conditions (Optional)')]) }}
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
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('attachments', __('Attachments'), ['class' => 'form-label']) }}
                    <div class="choose-file">
                        <label for="Image" class="w-100">
                            <input type="file" class="form-control" name="attachments" id="attachments"
                                data-filename="attachments" accept="image/*,.jpeg,.jpg,.png"
                                onchange="document.getElementById('image4').src = window.URL.createObjectURL(this.files[0])"
                                >
                            <img id="image4" width="25%" class="mt-3"
                                src={{ isset($student->attachments) ? get_file($student->attachments) : '' }}>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card table-border-style mb-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            @php
                            $fileName = isset($student->attachments) ? basename($student->attachments) : '';
                            $fileSize = isset($student->attachments) ? filesize($student->attachments) . ' bytes' : '';
                            $dateCreated = isset($student->attachments) ? filectime($student->attachments) : '';

                            $fileInfo[] = [
                            'File Name' => $fileName,
                            'File Size' => $fileSize,
                            'Date Created' => date('Y-m-d H:i:s', strtotime($dateCreated)),
                            ];
                            @endphp
                            <table class="table mb-0 pc-dt-simple" id="assets">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('File Name')}}</th>
                                        <th>{{__('File Size')}}</th>
                                        <th>{{__('Date Created')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fileInfo as $key => $file)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $file['File Name'] }}</td>
                                        <td>{{ $file['File Size'] }}</td>
                                        <td>{{ $file['Date Created'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer mb-4">
    <input type="button" value="{{ __('Cancel') }}"
        onclick="location.href = '{{ route('school-student.index') }}';" class="btn btn-light me-2">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
</div>
@endsection