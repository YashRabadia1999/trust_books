@extends('layouts.main')
@section('page-title')
    {{ __('Edit Invoice') }}
@endsection
@section('title')
    {{ __('Invoice') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice') }},
    {{ __('Edit') }}
@endsection

@section('content')
    {{ Form::model($drivinginvoice, ['route' => ['drivinginvoice.update', $drivinginvoice->id], 'method' => 'PUT', 'class' => 'w-100', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
    <input type="hidden" name="acction_type" id="acction_type" value="edit">
    <input type="hidden" name="driving_invoice" id="driving_invoice" value="{{$drivinginvoice->id}}">
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
                                {{ Form::select('student_id', $students->pluck('name', 'id'), null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select Student')]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- items --}}
        <div class="col-12">
            <div class="row">
                <div class="card repeater" data-value='{!! json_encode($drivinginvoice->items) !!}'>
                    <div class="item-section py-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-12 d-flex align-items-center justify-content-md-end px-5">
                                <div class="all-button-box ">
                                    <a href="#" data-repeater-create="" class="btn btn-sm btn-primary" title="{{ __('Create') }}" data-toggle="tooltip" data-target="#add-bank">
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
            <input type="submit" id="submit" value="{{ __('Update') }}" class="btn btn-primary">
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')

<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.repeater.min.js') }}"></script>

<script>
    $(document).on('click', '[data-repeater-delete]', function ()
        {
            var el = $(this).parent().parent();
            var id = $(el.find('.id')).val();

            $.ajax({
                url: '{{route('invoice.item.destroy')}}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function (data) {
                    if (data.error) {
                            toastrs('Error', data.error, 'error');
                    } else {
                            toastrs('Success', data.success, 'success');
                    }
                },
                error: function (data) {
                    toastrs('Error','{{ __("something went wrong please try again") }}', 'error');
                },
            });
        });

        $(document).ready(function() {
        var student_id = $(this).val();
        var acction = $("#acction_type").val();

        // Function to retrieve sections
        function SectionGet() {
            $.ajax({
                type: 'post',
                url: "{{ route('student.section') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    student_id: student_id,
                    action: acction,
                },
                beforeSend: function() {
                    $("#loader").removeClass('d-none');
                },
                success: function(response) {
                    if (response != false) {
                        $('.section_div').html(response.html);
                        $("#loader").addClass('d-none');
                        // Update pro_name if title is defined
                        if (typeof title !== 'undefined') {
                            $('.pro_name').text(title);
                        }
                        // Call custom JsSearchBox function
                        JsSearchBox();
                    } else {
                        $('.section_div').html('');
                        toastrs('Error', 'Something went wrong please try again!', 'error');
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


    $(document).ready(function() {
        $("input[name='student']:checked").trigger('change');
    });


    $(document).on('change', "[name='student']", function() {
        SectionGet(val);
    });
</script>
@endpush
