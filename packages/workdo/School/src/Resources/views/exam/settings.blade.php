@extends('layouts.main')
@section('page-title', 'Exam Settings')
@section('title', 'Exam Settings')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h5>Exam Settings</h5>
            </div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('school.exam.settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="assignment_percentage" class="form-label">Assignment Percentage (%)</label>
                        <input type="number" name="assignment_percentage" id="assignment_percentage" 
                               class="form-control" value="{{ old('assignment_percentage', $setting->assignment_percentage ?? 0) }}" min="0" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="exam_percentage" class="form-label">Exam Percentage (%)</label>
                        <input type="number" name="exam_percentage" id="exam_percentage" 
                               class="form-control" value="{{ old('exam_percentage', $setting->exam_percentage ?? 0) }}" min="0" max="100" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
