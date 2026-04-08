@php
    $company_setting = getCompanyAllSetting();
@endphp
<div class="card" id="school-sidenav">
    {{ Form::open(array('route' => 'school.setting.store','method' => 'post')) }}
    <div class="card-header p-3">
        <h5 class="">{{ __('School & Institute Management Settings') }}</h5>
    </div>
    <div class="card-body p-3 pb-0">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    {{Form::label('admission_prefix',__('Admission Prefix'),array('class'=>'form-label')) }}
                    {{Form::text('admission_prefix',!empty($company_setting['admission_prefix']) ? $company_setting['admission_prefix'] :'#ADMI',array('class'=>'form-control', 'placeholder' => 'Enter Contract Prefix'))}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end p-3">
        <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{Form::close()}}
</div>

