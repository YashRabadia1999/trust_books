@extends('layouts.main')

@section('page-title', __('Exam Entry Details'))
@section('title', __('Exam Entry Details'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Exam Entry') }}</div>
            <div class="card-body">
                <p><strong>{{ __('Exam') }}:</strong> {{ $examEntry->exam->exam_name ?? '-' }}</p>
                <p><strong>{{ __('Student') }}:</strong> {{ $examEntry->student->name ?? '-' }}</p>
                <p><strong>{{ __('Marks Obtained') }}:</strong> {{ $examEntry->marks_obtained }}</p>
                <p><strong>{{ __('Assignment Marks') }}:</strong> {{ $examEntry->assignment_marks }}</p>
                <p><strong>{{ __('Total Marks') }}:</strong> {{ $examEntry->total_marks }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
