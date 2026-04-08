<div class="modal-header">
    <h5 class="modal-title">{{ __('Bulk Upload Students') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p class="text-muted small mb-2">{{ __('Upload a CSV with headers: name,email,contact,class_name,grade_name') }}</p>
  
    {{ Form::open(['route' => 'school-student.bulk.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="mb-3">
        <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
    </div>
    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
    </div>
    {{ Form::close() }}
</div>

