@extends('layouts.main')

@section('page-title', __('Bulk Upload Exam Marks'))
@section('title', __('Bulk Upload Exam Marks'))

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('Upload Excel/CSV') }}</div>
            <div class="card-body">

                <form action="{{ route('school.exam.bulk.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    {{-- <div class="form-group mb-3">
                        <label>{{ __('Select Exam') }}</label>
                        <select name="exam_id" class="form-control" required>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="form-group mb-3">
                        <label>{{ __('Upload File') }}</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">{{ __('Upload') }}</button>
                    
                    <a href="{{ route('school.exam.bulk.sample') }}" class="btn btn-info">{{ __('Download Sample') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
