<style>
    .star-rating .star {
        font-size: 24px;
        cursor: pointer;
        color: #ccc;
        transition: color 0.2s;
    }
    .star-rating .star.filled {
        color: #f1c40f !important;
    }
</style>

{{ Form::model($serviceReview, ['route' => ['service.review.update', $serviceReviewId], 'method' => 'PUT', 'class' => 'space-y-4 needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('service_reviewer_name', __('Reviewer Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('service_reviewer_name',$serviceReview->reviewer_name ?? '',['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Reviewer Name')]) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('service_reviewer_email', __('Reviewer Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('service_reviewer_email',$serviceReview->reviewer_email ?? '',['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email Address')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('service_id', __('Service'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('service_id', $services,$serviceReview->service_id ?? null, ['class' => 'form-control service-select', 'placeholder' => __('Select Service'), 'id' => 'service_id_select','required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('service_review', __('Review'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('service_review',$serviceReview->review ?? '', ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Tell us about your experience...'), 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('service_rating', __('Rating'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="star-rating d-flex gap-1" id="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star {{ $serviceReview->rating >= $i ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                @endfor
            </div>
            {{ Form::hidden('service_rating', old('service_rating', $serviceReview->rating), ['id' => 'service-rating-input']) }}
        </div>        

        <div class="col-md-6 form-group">
            {!! Form::label('service_display_status', __('Display Status'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="form-check form-switch">
                {!! Form::hidden('service_display_status', 0) !!}
                {!! Form::checkbox('service_display_status', 1,(isset($serviceReview->display_status) && $serviceReview->display_status === 'on'),['class' => 'form-check-input input-primary','id' => 'customCheckdef1']) !!}
                <label class="form-check-label" for="customCheckdef1"></label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        $(document).on('click', '.star-rating .star', function() {
            const rating = $(this).data('value');
            const $container = $(this).closest('.star-rating');
            const $hiddenInput = $container.siblings('input[type="hidden"]');

            $hiddenInput.val(rating);

            $container.find('.star').each(function(index) {
                $(this).toggleClass('filled', index < rating);
            });
        });
    });
</script>
