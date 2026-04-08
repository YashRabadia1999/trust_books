<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tbody>
                    <tr>
                        <th>{{ __('Student') }}</th>
                        <td>{{ isset($student->name) ? ucwords($student->name) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Licence Type') }}</th>
                        <td>{{ isset($licence_types->name) ? ucwords($licence_types->name) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Application Date') }}</th>
                        <td>{{ isset($licence_traking->application_date) ? company_date_formate($licence_traking->application_date) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Date') }}</th>
                        <td>{{ isset($licence_traking->test_date) ? company_date_formate($licence_traking->test_date) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Result') }}</th>
                        <td>{{ isset($licence_traking->test_result) ? ucwords($licence_traking->test_result) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Licence Issue Date') }}</th>
                        <td>{{ isset($licence_traking->licence_issue_date) ? company_date_formate($licence_traking->licence_issue_date) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Licence Number') }}</th>
                        <td>{{ isset($licence_traking->licence_number) ? $licence_traking->licence_number : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Licence Expiry Date') }}</th>
                        <td>{{ isset($licence_traking->licence_expiry_date) ? company_date_formate($licence_traking->licence_expiry_date) : '-'  }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
