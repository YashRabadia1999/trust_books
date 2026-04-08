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
                        <th>{{ __('Class') }}</th>
                        <td>{{ isset($class->name) ? ucwords($class->name) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Teacher') }}</th>
                        <td>{{ isset($teacher->name) ? ucwords($teacher->name) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Progress Date') }}</th>
                        <td>{{ isset($report->progress_date) ? company_date_formate($report->progress_date) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Skills') }}</th>
                        <td>{{ isset($report->skills_assessed) ? ucfirst($report->skills_assessed) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Comments') }}</th>
                        <td>{{ isset($report->comments) ? ucfirst($report->comments) : '-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Rating') }}</th>
                        <td>{{ isset($report->rating) ? $report->rating : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
