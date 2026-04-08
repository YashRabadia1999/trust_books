{{ Form::model($homework, ['route' => ['school-homework.update', $homework->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data' , 'class' => 'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Homework Title'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('classroom', __('Class'), ['class' => 'form-label']) }}<x-required></x-required>
                {!! Form::select('classroom', $classRoom, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Class'),
                ]) !!}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('subject', __('Subject'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('subject', $subjects, $selectedSubject, ['class' => 'form-control', 'placeholder' => 'Select Subject', 'id' => 'subject']) }}
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="form-group">
                {{ Form::label('submission_date', __('Submission Date'), ['class' => 'form-label']) }}
                {{ Form::date('submission_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('content', __('Homework Content'), ['class' => 'form-label']) }}
            {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => '3']) !!}
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('homework', __('Homework'), ['class' => 'form-label']) }}
                <div class="choose-file">
                    <label for="Image" class="w-100">
                        <input type="file" class="form-control" name="homework" id="homework"
                            data-filename="homework" accept="image/*,.jpeg,.jpg,.png"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            style = "width:365px">
                        <img id="blah" width="25%" class="mt-3"
                            src="{{ isset($homework->homework) ? get_file($homework->homework) : '' }}">

                    </label>
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-sm-6 col-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder', [
                        'fildedata' => $homework->customField,
                    ])
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary ']) }}
</div>
{{ Form::close() }}


{{--
<script>
    $(document).ready(function() {
        var selectedClass = $('select[name=classroom]').val();
        getSubjects(selectedClass);

        // Update subjects when the classroom dropdown changes
        $(document).on('change', 'select[name=classroom]', function() {
            var selectedClass = $(this).val();
            getSubjects(selectedClass);
        });

        // Set the selected subject for editing
        var selectedSubject = '{{ $selectedSubject }}';
        if (selectedSubject) {
            $('select[name=subject]').val(selectedSubject);
        }
    });

    function getSubjects(classroomId) {
        $.ajax({
            url: '{{ route('getsubject') }}',
            type: 'POST',
            data: {
                "classroom_id": classroomId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#subject').empty();
                $('#subject').append('<option value="">{{ __('Select Subject') }}</option>');
                $.each(data, function(key, value) {
                    $('#subject').append('<option value="' + key + '">' + value + '</option>');
                });

                // Set the selected subject for editing if available
                var selectedSubject = '{{ $selectedSubject }}';
                if (selectedSubject) {
                    $('#subject').val(selectedSubject);
                }
            }
        });
    }
</script> --}}
<script>
    $(document).on('change', 'select[name=classroom]', function() {
        var selectedClass = $(this).val();
        getSubjects(selectedClass);
    });

    function getSubjects(classroomId) {
        $.ajax({
            url: '{{ route('getschoolsubject') }}',
            type: 'POST',
            data: {
                "classroom_id": classroomId,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#subject').empty();
                $('#subject').append('<option value="">{{ __('Select Subject') }}</option>');
                $.each(data, function(key, value) {
                    $('#subject').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    }
</script>
