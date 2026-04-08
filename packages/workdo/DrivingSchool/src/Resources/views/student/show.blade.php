<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tbody>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <td>{{ isset($student->name) ? $student->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td>{{ isset($student->email) ? $student->email : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Date Of Birth') }}</th>
                        <td>{{ isset($student->dob) ? $student->dob : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Gender') }}</th>
                        <td>{{ isset($student->gender) ? $student->gender : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Address') }}</th>
                        <td>{{ isset($student->address) ? $student->address : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Phone NO.') }}</th>
                        <td>{{ isset($student->mobile_no) ? $student->mobile_no : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('City') }}</th>
                        <td>{{ isset($student->city) ? $student->city : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('State') }}</th>
                        <td>{{ isset($student->state) ? $student->state : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Country') }}</th>
                        <td>{{ isset($student->country) ? $student->country : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
