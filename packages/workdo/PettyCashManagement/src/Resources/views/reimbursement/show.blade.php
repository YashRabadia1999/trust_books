{{ Form::model($reimbursement, ['route' => ['reimbursement.approve', $reimbursement->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-bordered ">
                <tr role="row">
                    <th>{{ __('Requester Name') }}</th>
                    <td>{{ $reimbursement->user->name }}</td>
                </tr>
                <tr role="row">
                    <th>{{ __('Request Categorie') }}</th>
                    <td>{{ $reimbursement->category->name }}</td>
                </tr>

                <tr>
                    <th>{{__('Status')}}</th>
                    <td>
                        @if($reimbursement->status == 'Approved')
                            <span class="badge bg-success p-2 px-3 text-white">{{ucfirst($reimbursement->status)}}</span>
                        @elseif($reimbursement->status == 'Pending')
                            <span class="badge bg-warning p-2 px-3 text-white">{{ucfirst($reimbursement->status)}}</span>
                        @else
                            <span class="badge bg-danger p-2 px-3 text-white">{{ucfirst($reimbursement->status)}}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{__('Price')}}</th>
                    <td>{{ $reimbursement->amount }}</td>
                </tr>
                <tr>
                    <th>{{__('Existing Receipt:')}}</th>
                    <td>
                        @if($reimbursement->receipt_path)
                            <img src="{{ asset($reimbursement->receipt_path) }}" alt="Receipt" class="img-thumbnail" style="max-width: 200px;">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{__('Remarks')}}</th>
                    <td>{{ $reimbursement->description }}</td>
                </tr>
            </table>
        </div>
    </div>
    @if ($reimbursement->status == 'pending')
        <div class="modal-footer">
            <a href=""></a>
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger" name="status">
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success" name="status">
        </div>
    @endif
{{ Form::close() }}
