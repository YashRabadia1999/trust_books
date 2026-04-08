{{ Form::model($report, ['route' => ['progress_report.update', $report->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('student_id', __('Student'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('student_id', $student, null, ['class' => 'form-control', 'id' => 'student_id', 'required' => 'required', 'placeholder' => __('Select Student')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('class_id', __('Class'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('class_id', [], null, ['class' => 'form-control', 'id' => 'class_id', 'required' => 'required', 'placeholder' => __('Select Class')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('teacher_id', __('Teacher'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-icon-user">
                {{ Form::select('teacher_id', $teacher, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __( 'Select Teacher')]) }}
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {!! Form::label('progress_date', __('Progress Date'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('progress_date', old('progress_date'), [
                'class' => 'form-control ',
                'autocomplete' => 'off',
                'required' => 'required',
            ]) !!}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('skills_assessed', __('Skills Assessed'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('skills_assessed', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Skills')]) }}
        </div>
        <div class="form-group col-lg-6 col-md-4 col-sm-6">
            {{ Form::label('rating', __('Rating'), ['class' => 'form-label']) }}<x-required></x-required>
            {!! Form::select('rating', [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                ],
                null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Choose Rating')])
            !!}
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('comments', __('Remarks'), ['class' => ' form-label']) }}
            {!! Form::textarea('comments', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => __('Enter Remarks')]) !!}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var student_id = $('#student_id').val();
        getclass(student_id);

        $('#student_id').on('change', function() {
            var selection = $(this).val();

            getclass(selection);

        });

        function getclass(selection) {

            $('#class_id').empty();

            $('#class_id').append('<option value="">Please Select</option>');

            $.ajax({
                type: 'post',
                url: "{{ route('report.class') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: selection,
                },
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(response) {

                    var val = {{ isset($report->class_id) ? $report->class_id : '' }};

                    $.each(response, function(key, value) {
                        var isSelected = (value.id == val) ? 'selected' : '';

                        $('#class_id').append('<option value="' + value.id + '"  ' + isSelected + '>' + value.name + '</option>');
                    });

                    $('[for="class_id"]').html(response.class);
                    $(".loader-wrapper").addClass('d-none');
                },
            });
        }
        $('#class_id').trigger('change');
    });
</script>
