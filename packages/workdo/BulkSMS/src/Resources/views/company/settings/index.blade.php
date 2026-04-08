<div class="card" id="bulksms-sidenav">
    {{ Form::open(['route' => 'bulksms.setting.save', 'method' => 'post']) }}
    <div class="card-header p-3">
        <div class="row align-items-center">
            <div class="col-10 ">
                <h5 class="">{{ __('Bulk SMS Settings') }}</h5>
                <small>{{ __('Edit your Bulk SMS settings') }}</small>
            </div>
        </div>
    </div>
    <div class="card-body pb-0 p-3">
        <div class="row">
            <div class="form-group col-md-6">
                <label class="form-label ">{{ __('BulkSMS Username') }}</label> <br>
                <input class="form-control" placeholder="{{ __('Enter BulkSMS Username') }}" name="bulksms_username"
                    type="text"
                    value="{{ isset($settings['bulksms_username']) ? $settings['bulksms_username'] : '' }}"
                    id="bulksms_username">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label ">{{ __('BulkSMS Password') }}</label> <br>
                <input class="form-control" placeholder="{{ __('Enter BulkSMS Password') }}" name="bulksms_password"
                    type="text"
                    value="{{ isset($settings['bulksms_password']) ? $settings['bulksms_password'] : '' }}"
                    id="bulksms_password">
            </div>
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Sender IDs') }}</label>
                <small
                    class="form-text text-muted d-block mb-2">{{ __('Enter multiple sender IDs separated by commas (e.g., SENDER1, SENDER2, SENDER3)') }}</small>
                <input class="form-control" placeholder="{{ __('Enter Sender IDs separated by commas') }}"
                    name="bulksms_sender_ids" type="text"
                    value="{{ isset($settings['bulksms_sender_ids']) ? $settings['bulksms_sender_ids'] : '' }}"
                    id="bulksms_sender_ids">
            </div>
        </div>
    </div>

    <div class="card-footer text-end p-3">
        <input class="btn btn-print-invoice  btn-primary" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}

</div>
