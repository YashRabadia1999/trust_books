@php
    $currantLang = getActiveLanguage();
@endphp
@if (!empty($student))
    {{ Form::model($student, ['route' => ['driving-student.update', $student->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
@else
    {{ Form::open(['route' => ['driving-student.store'], 'method' => 'post']) }}
@endif
<div class="modal-body">
    <div class="row">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::text('name', !empty($user->name) ? $user->name : null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
            </div>
        </div>

        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {{ Form::email('email', !empty($user->email) ? $user->email : null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email')]) }}
            </div>
        </div>

        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="d-flex radio-check mt-2">
                <div class="col-md-6 custom-control custom-radio custom-control-inline">
                    <input type="radio" id="g_male" value="Male" name="gender" class="form-check-input"
                        checked="checked"style="margin-right: 5px;">
                    <label class="form-check-label" for="g_male"
                        style="margin-right: 30px;">{{ __('Male') }}</label>
                </div>
                <div class="col-md-6 custom-control custom-radio ms-1 custom-control-inline">
                    <input type="radio" id="g_female" value="Female" name="gender" class="form-check-input"
                        style="margin-right: 5px;">
                    <label class="form-check-label " for="g_female">{{ __('Female') }}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::date('dob', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => __('Select Date of Birth'), 'max' => date('Y-m-d')]) }}
            </div>
        </div>

        <div class="col-lg-6 col-md-4 col-sm-6">
            <x-mobile></x-mobile>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('pin_code', __('Pin Code'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::number('pin_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Pin Code'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('language', __('Language'), ['class' => 'form-label']) }}
            <select name="language" class="form-select" id="language">
                @foreach (\Workdo\DrivingSchool\Entities\DrivingStudent::flagOfCountry() as $key => $lang)
                    <option value="{{ $key }}"
                        {{ isset($student) && $student->language == $key ? 'selected' : '' }}>
                        {{ Str::upper($lang) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-12 col-md-4 col-sm-6">
            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __('Enter Address'), 'rows' => '3', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
