{{ Form::model($pettyCashRequest, ['route' => ['petty-cash-request.approve', $pettyCashRequest->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered ">
                <tr role="row">
                    <th>{{ __('Requester Name') }}</th>
                    <td>{{ $pettyCashRequest->userName->name }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Request Categorie') }}</th>
                    <td>{{ $pettyCashRequest->categoryName->name }}</td>
                </tr>
                <tr>
                    <th>{{__('Status')}}</th>
                    <td>
                        @if($pettyCashRequest->status == 'Approved')
                            <span class="badge bg-success p-2 px-3 text-white">{{ucfirst($pettyCashRequest->status)}}</span>
                        @elseif($pettyCashRequest->status == 'Pending')
                            <span class="badge bg-warning p-2 px-3 text-white">{{ucfirst($pettyCashRequest->status)}}</span>
                        @else
                            <span class="badge bg-danger p-2 px-3 text-white">{{ucfirst($pettyCashRequest->status)}}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{__('Price')}}</th>
                    <td>{{ $pettyCashRequest->requested_amount }}</td>
                </tr>
                <tr>
                    <th>{{__('Remarks')}}</th>
                    <td>{{ $pettyCashRequest->remarks }}</td>
                </tr>
            </table>
        </div>
    </div>
    @if ($pettyCashRequest->status == 'pending')
        <div class="modal-footer">
            <a href=""></a>
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger" name="status">
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" name="status">
        </div>
    @endif
{{ Form::close() }}
