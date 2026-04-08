@extends('layouts.main')
@section('page-title')
    {{ __('Manage Alumini') }}
@endsection
@section('title')
    {{ __('Alumini') }}
@endsection
@section('page-breadcrumb')
    {{ __('Alumini') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-action')
<div>
    @permission('school_alumni create')
    <a data-size="" data-url="{{ route('school-alumini.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
        data-title="{{ __('Create Alumini') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
        <i class="ti ti-plus"></i>
    </a>
@endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}

<script type="text/javascript">
    $(document).on('change', '#student_id', function() {
        var student_id = $(this).val();
        getstudentinfo(student_id);
    });

    function getstudentinfo(student_id) {
        var data = {
            "student_id": student_id,
            "_token": "{{ csrf_token() }}",
        }

        $.ajax({
            url: '{{ route('school.getstudentinfo') }}',
            method: 'POST',
            data: data,
            success: function(data) {
                $('input[name="contact"]').val(data.contact);
                $('input[name="email"]').val(data.email);
            }
        });
    }
</script>
@endpush
