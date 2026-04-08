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
                        <th>{{ __('Teacher') }}</th>
                        <td>{{ isset($users->name) ? ucwords($users->name) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Type') }}</th>
                        <td>{{ isset($test_types->name) ? ucwords($test_types->name) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Date') }}</th>
                        <td>{{ isset($test_hub->test_date) ? company_date_formate($test_hub->test_date) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Score') }}</th>
                        <td>{{ isset($test_hub->test_score) ? $test_hub->test_score : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Test Result') }}</th>
                        <td>{{ isset($test_hub->test_result) ? ucwords($test_hub->test_result) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Remarks') }}</th>
                        <td>{{ isset($test_hub->remarks) ? ucfirst($test_hub->remarks) : '-'  }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
