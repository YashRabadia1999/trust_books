@extends('layouts.main')
@section('page-title')
    {{ __('Create Invoice') }}
@endsection
@section('title')
    {{ __('Invoice') }},
    {{ __('Create') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice') }},
    {{ __('Create') }}
@endsection

@section('content')
    {{ Form::open(['route' => 'drivinginvoice.store', 'class' => 'w-100', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('invoice_number', __('Invoice Number'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    <input type="text" class="form-control" value="{{ $invoice_number }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}<x-required></x-required>
                                <div class="form-icon-user">
                                    {{ Form::date('issue_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Select Issue Date')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}<x-required></x-required>
                                <div class="form-icon-user">
                                    {{ Form::date('due_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => __('Select Due Date')]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                {{ Form::label('student', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
                                <div class="form-icon-user">
                                    <select class="form-control" name="student" required='required'>
                                        <option value="">{{ __('Select Student') }}</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student['id'] }}">
                                                {{ $student['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- items --}}
        <div class="col-12">
            <div class="row">
                <div class="card repeater">
                    <div class="item-section py-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-12 d-flex align-items-center justify-content-md-end px-5">
                                <div class="all-button-box">
                                    <a href="#" data-repeater-create="" class="btn btn-sm btn-primary" title="{{ __('Add Item') }}" data-toggle="modal" data-target="#add-bank">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="loader" class="card card-flush">
                        <div class="card-body">
                            <div class="row">
                                <img class="loader" src="{{ asset('public/images/loader.gif') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 section_div">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('drivinginvoice.index') }}';" class="btn btn-light me-2">
            <input type="submit" id="submit" value="{{ __('Create') }}" class="btn btn-primary">
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

<script>
    $(document).on('change', "[name='student']", function() {
        var student_id = $(this).val();

        // Function to retrieve sections
        function SectionGet() {
            $.ajax({
                type: 'post',
                url: "{{ route('student.section') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: student_id,
                    action: 'create', // Fixed typo here
                },
                beforeSend: function() {
                    $("#loader").removeClass('d-none');
                },
                success: function(response) {
                    if (response.is_success) { // Check for success status
                        $('.section_div').html(response.html);
                        $("#loader").addClass('d-none');
                        // Update pro_name if title is defined
                        if (typeof response.title !== 'undefined') { // Access title from response
                            $('.pro_name').text(response.title);
                        }
                        // Call custom JsSearchBox function
                        JsSearchBox();
                    } else {
                        $('.section_div').html('');
                        toastrs('Error', response.message, 'error'); // Display error message from response
                    }
                },
                error: function(xhr, status, error) {
                    toastrs('Error', 'An error occurred while processing your request.', 'error');
                }
            });
        }
        // Call SectionGet function
        SectionGet();
    });
</script>

@endpush
