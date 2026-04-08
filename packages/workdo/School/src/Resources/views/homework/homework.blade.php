{{ Form::open(['url' => 'getstdhomework/' . $homework->id, 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('subject', __('Subject'), ['class' => 'form-label']) }}
                {{ Form::text('subject', isset($homework->subject_name) ? $homework->subject_name->subject_name : '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Subject')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('student_homework', __('Homework'), ['class' => 'form-label']) }}
                <div class="choose-file">
                    <label for="Image" class="w-100">
                        <input type="file" class="form-control" name="student_homework" id="student_homework"
                            data-filename="student_homework" accept="image/*,.jpeg,.jpg,.png" required="required"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            required>
                        <img id="blah" width="25%" class="mt-3">
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Upload'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
