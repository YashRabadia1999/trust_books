@extends('layouts.main')
@section('page-title', 'Exam Report')
@section('title', 'Exam Report')

@section('content')

<div class="row mb-3">
    <div class="col-md-3">
        <select id="academic_year_id" class="form-control">
            <option value="">Select Academic Year</option>
            @foreach($years as $year)
                <option value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="term_id" class="form-control">
            <option value="">Select Term</option>
            @foreach($terms as $term)
                <option value="{{ $term->id }}">{{ $term->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select id="classroom_id" class="form-control">
            <option value="">Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button id="filterBtn" class="btn btn-primary">Filter</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Exam Report</h5>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div id="reportTable">
                    @if(count($report) > 0)
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
                                          <a href="{{ route('school.exam.report.show', $row['id']) }}#student-exam" class="btn btn-sm btn-info">
                                            View
                                        </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-center">No data available for the selected filters.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    $('#filterBtn').click(function(){
        let year = $('#academic_year_id').val();
        let term = $('#term_id').val();
        let class_id = $('#classroom_id').val();

        $.ajax({
            url: "{{ route('school.exam.report.index') }}",
            type: 'GET',
            data: {academic_year_id: year, term_id: term, classroom_id: class_id},
            beforeSend: function(){
                $('#reportTable').html('<p class="text-center">Loading...</p>');
            },
            success: function(data){
                $('#reportTable').html(data);
            },
            error: function(){
                alert('Something went wrong!');
            }
        });
    });
});
</script>
@endpush
