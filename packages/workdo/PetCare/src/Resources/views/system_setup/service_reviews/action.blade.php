{{ Form::model($serviceReview, ['route' => ['service.review.change.action', $serviceReviewId], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>{{ __('Reviewer Name') }}</th>
                <td>{{ $serviceReview->reviewer_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Reviewer Email') }}</th>
                <td>{{ $serviceReview->reviewer_email ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Service Name') }}</th>
                <td>{{ $serviceReview->service->service_name ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ __('Rating') }}</th>
                <td>
                    @for ($i = 0; $i < 5; $i++)
                        <i class="ti ti-star {{ $i < $serviceReview->rating ? 'text-warning' : '' }} "></i>
                    @endfor
                </td>
            </tr>
            <tr>
                <th>{{ __('Review') }}</th>
                <td>
                    <div class="text-wrap text-break text-justify" style="text-align: justify;">
                        {{ $serviceReview->review ?? '-' }}
                    </div>
                </td>
            </tr>
            <tr>
                <th>{{ __('Display Status') }}</th>
                <td>
                    @php
                        $displayStatus = $serviceReview->display_status;
                        $status_color = [
                            'on' => 'success',
                            'off' => 'danger',
                        ];
                        $color = $status_color[$displayStatus] ?? 'secondary';
                    @endphp
                    <span class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ ucfirst($displayStatus) }}</span>
                </td>
            </tr>
            <tr>
                <th>{{ __('Review Status') }}</th>
                <td>
                    @php
                        $ReviewStatus = $serviceReview->review_status;
                        $status_color = [
                            'pending' => 'warning',
                            'approved' => 'primary',
                            'rejected' => 'danger',
                        ];
                        $color = $status_color[$ReviewStatus] ?? 'secondary';
                    @endphp
                    <span class="badge fix_badges bg-{{ $color }} p-2 px-3">{{ ucfirst($ReviewStatus) }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>
@if (\Auth::user()->isAbleTo('service_review action'))
    @if ($serviceReview->review_status == 'pending')
        <div class="modal-footer">
            <button type="submit" class="btn btn-danger" name="review_status"
                value="rejected">{{ __('Reject') }}</button>
            <button type="submit" class="btn btn-success" name="review_status"
                value="approved">{{ __('Approved') }}</button>
        </div>
    @endif
@endif
{{ Form::close() }}
