@extends('layouts.main')

@section('page-title')
    {{ __('Fee Details') }}
@endsection

@section('page-breadcrumb')
    {{ __('Fees') }}, {{ $fee->student->name ?? '' }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Fee Details') }}</h5>
            </div>
            <div class="card-body" id="printableArea">
                <!-- Invoice Header / Logo -->
                <div class="invoice-title mb-4">
                    <img src="{{ get_file(sidebar_logo()) }}" alt="{{ config('app.name', 'WorkDo') }}"
                         class="logo logo-lg" style="max-width: 150px">
                </div>

                <!-- Fee Details Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <td>{{ $fee->student->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Gender') }}</th>
                            <td>{{ $fee->student->student_gender ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td>{{ $fee->student->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Amount') }}</th>
                            <td>${{ number_format($fee->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <td>{{ company_date_formate($fee->date) }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Status') }}</th>
                            <td>
                                <span class="badge bg-{{ $fee->status == 'Paid' ? 'success' : 'danger' }}">
                                    {{ $fee->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Download PDF Button -->
                @if($fee->status == 'Paid')
                    <div class="text-end mt-3">
                        <button class="btn btn-primary" onclick="saveAsPDF()">
                            <i class="fa fa-download"></i> {{ __('Download') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('packages/workdo/School/src/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
<script>
function saveAsPDF() {
    var element = document.getElementById('printableArea');
    var opt = {
        margin: 0.2,
        filename: '{{ $fee->student->name ?? 'fee-details' }}.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 4, dpi: 72, letterRendering: true },
        jsPDF: { unit: 'in', format: 'A4' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>
@endpush
