{{-- packages/workdo/School/src/Resources/views/exam/report-table.blade.php --}}
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Position</th>
                <th>Student Name</th>
                <th>Assignment Marks</th>
                <th>Exam Marks</th>
                <th>Total Marks</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $row)
            <tr>
                <td>{{ $row['position'] }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['assignment'] }}</td>
                <td>{{ $row['exam'] }}</td>
                <td>{{ number_format($row['total'], 2) }}</td>
                <td>
                    <a href="{{ route('school.exam.report.show', $row['id']) }}#student-exam" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
