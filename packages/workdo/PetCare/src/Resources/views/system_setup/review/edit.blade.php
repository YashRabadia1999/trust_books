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

{{ Form::model($petCareReview, ['route' => ['petcare.review.update', $reviewId], 'method' => 'PUT', 'class' => 'space-y-4 needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reviewer_name', __('Reviewer Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('reviewer_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Reviewer Name')]) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reviewer_email', __('Reviewer Email'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::email('reviewer_email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Email Address')]) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('review', __('Review'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::textarea('review', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Tell us about your experience...'), 'required' => 'required']) }}
            </div>
        </div>

        <div class="col-md-6 form-group">
            {{ Form::label('rating', __('Rating'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="star-rating d-flex gap-1" id="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star {{ $petCareReview->rating >= $i ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                @endfor
            </div>
            {{ Form::hidden('rating', old('rating', $petCareReview->rating), ['id' => 'rating-input']) }}
        </div>

        <div class="col-md-6 form-group">
            {!! Form::label('', __('Display Status'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="form-check form-switch">
                {!! Form::hidden('display_status', 0) !!}
                {!! Form::checkbox('display_status', 1, $petCareReview->display_status === 'on', ['class' => 'form-check-input input-primary','id' => 'customCheckdef1']) !!}
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
