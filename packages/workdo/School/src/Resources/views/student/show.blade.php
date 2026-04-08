@extends('layouts.main')
@section('page-title')
{{ __('Student Detail') }}
@endsection
@section('page-breadcrumb')
{{ __('Student Detail') }}{{ $student ? ','.$student->name : '' }}
@endsection

@push('css')
<style>
   .student-card {
   min-height: 204px;
   }
   .student-photo {
       width: 100px;
       height: 100px;
       /* border: 2px solid #e74c3c; */
       border-radius: 8px;
       display: flex;
       align-items: center;
       justify-content: center;
       background-color: #f8f9fa;
       margin-bottom: 15px;
   }
   .student-photo-text {
       /* color: #e74c3c; */
       font-weight: bold;
       text-align: center;
   }
</style>
@endpush
@push('scripts')
<script>
   $(document).ready(function() {
       // Initialize tooltips
       $('[data-bs-toggle="tooltip"]').tooltip();
   });
</script>
@endpush
@section('page-action')
<div class="d-flex">
   @permission('student edit')
   <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-primary me-2">
   {{ __('Edit Student') }}
   </a>
   @endpermission
   @permission('student delete')
   <a href="#" class="btn btn-sm btn-danger me-2" onclick="confirmDelete()">
   {{ __('Delete Student') }}
   </a>
   @endpermission
</div>
@endsection
@section('content')
<div class="page-header">
   <div class="page-block">
      <div class="row align-items-center">
         <div class="col-md-4">
         </div>
         <div class="col-md-8 mt-4">
            <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
               <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="student-details-tab" data-bs-toggle="pill"
                     data-bs-target="#student-details" type="button">{{ __('Details') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="student-assignments-tab" data-bs-toggle="pill"
                     data-bs-target="#student-assignments" type="button">{{ __('Assignments') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="student-exam-tab" data-bs-toggle="pill"
                     data-bs-target="#student-exam" type="button">{{ __('Exam') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="student-health-tab" data-bs-toggle="pill"
                     data-bs-target="#student-health" type="button">{{ __('Health Details') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="student-fees-tab" data-bs-toggle="pill"
                     data-bs-target="#student-fees" type="button">{{ __('Fees & Invoices') }}</button>
               </li>
            </ul>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-sm-12 ">
      <div class="row">
         <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">
               <div class="tab-pane fade active show" id="student-details" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  <div class="row">
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card student-detail-box">
                           <div class="card-body student-card">
                              <h5 class="card-title">{{ __('Student Info') }}</h5>
                             <div class="student-photo">
                                @if(!empty($student->student_image))
                                   <img src="{{ get_file($student->student_image) }}" alt="{{ $student->name }}" style="max-width:100%; max-height:100%; object-fit:cover; border-radius:6px;">
                                @else
                                   <div class="student-photo-text">{{ __('Student photo') }}</div>
                                @endif
                             </div>
                              <p class="card-text mb-0"><strong>{{ __('Name') }}:</strong> {{ !empty($student->name) ? $student->name : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Gender') }}:</strong> {{ !empty($student->student_gender) ? $student->student_gender : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Age') }}:</strong> {{ !empty($student->std_date_of_birth) ? \Carbon\Carbon::parse($student->std_date_of_birth)->age : '-' }}</p>
                              @if (!empty($customFields) && count($student->customField) > 0)
                              @foreach ($customFields as $field)
                              <p class="card-text mb-0">
                                 <strong>{{ $field->name }} :
                                 </strong>{{ !empty($student->customField[$field->id]) ? $student->customField[$field->id] : '-' }}
                              </p>
                              @endforeach
                              @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card student-detail-box">
                           <div class="card-body student-card">
                              <h5 class="card-title">{{ __('Academic Info') }}</h5>
                              <p class="card-text mb-0"><strong>{{ __('Academic Year') }}:</strong> {{ !empty($student->academic_year) ? $student->academic_year : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Term') }}:</strong> {{ !empty($student->term) ? $student->term : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Class') }}:</strong> {{ !empty($student->class_name) ? $student->class->class_name : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Total attendance for the term') }}:</strong> {{ !empty($student->attendance_count) ? $student->attendance_count : '0' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Total Assignment submitted') }}:</strong> {{ !empty($student->assignment_count) ? $student->assignment_count : '0' }}</p>
                           </div>
                        </div>
                     </div>
                 
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card student-detail-box">
                           <div class="card-body student-card">
                              <h5 class="card-title">{{ __('Parents Information') }}</h5>
                              <p class="card-text mb-0"><strong>{{ __('Father Name') }}:</strong> {{ !empty($student->father_name) ? $student->father_name : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Mother Name') }}:</strong> {{ !empty($student->mother_name) ? $student->mother_name : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Father Contact') }}:</strong> {{ !empty($student->father_number) ? $student->father_number : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Mother Contact') }}:</strong> {{ !empty($student->mother_number) ? $student->mother_number : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Guardian Name') }}:</strong> {{ !empty($student->guardian_name) ? $student->guardian_name : '-' }}</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="card pb-0">
                           <div class="card-body">
                              <h5 class="card-title">{{ __('Student Details') }}</h5>
                              <div class="row">
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <h6 class="card-text mb-0">{{ __('Student ID') }}</h6>
                                       <p class="report-text mb-3">
                                          {{ !empty($student->roll_number) ? $student->roll_number : '-' }}
                                       </p>
                                       <h6 class="card-text mb-0">{{ __('Email') }}</h6>
                                       <p class="report-text mb-0">
                                          {{ !empty($student->email) ? $student->email : '-' }}
                                       </p>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <h6 class="card-text mb-0">{{ __('Date of Birth') }}</h6>
                                       <p class="report-text mb-3">
                                          {{ !empty($student->std_date_of_birth) ? $student->std_date_of_birth : '-' }}
                                       </p>
                                       <h6 class="card-text mb-0">{{ __('Contact') }}</h6>
                                       <p class="report-text mb-0">{{ !empty($student->contact) ? $student->contact : '-' }}</p>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <h6 class="card-text mb-0">{{ __('Address') }}</h6>
                                       <p class="report-text mb-3">
                                          {{ !empty($student->std_address) ? $student->std_address : '-' }}
                                       </p>
                                       <h6 class="card-text mb-0">{{ __('City') }}</h6>
                                       <p class="report-text mb-0">
                                          {{ !empty($student->std_city) ? $student->std_city : '-' }}
                                       </p>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                       <h6 class="card-text mb-0">{{ __('State') }}</h6>
                                       <p class="report-text mb-3">
                                          {{ !empty($student->std_state) ? $student->std_state : '-' }}
                                       </p>
                                       <h6 class="card-text mb-0">{{ __('Zip Code') }}</h6>
                                       <p class="report-text mb-0">{{ !empty($student->std_zip_code) ? $student->std_zip_code : '-' }}</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="student-assignments" role="tabpanel"
                  aria-labelledby="pills-user-tab-2">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Assignments') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="student_assignments">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Assignment') }}</th>
                                          <th>{{ __('Subject') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          <th>{{ __('Grade') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="5" class="text-center">
                                             <p>{{ __('No assignments found') }}</p>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="student-exam" role="tabpanel"
                  aria-labelledby="pills-user-tab-3">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Exam Results') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="student_exams">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Exam') }}</th>
                                          <th>{{ __('Acedemic Year') }}</th>
                                          <th>{{ __('Marks Obtained') }}</th>
                                          <th>{{ __('Assignment Marks') }}</th>
                                          <th>{{ __('Total Marks') }}</th>
                                         
                                       </tr>
                                    </thead>
                                    <tbody>
                                       
                                             @if($exams->count() > 0)
                                                @foreach($exams as $examEntry)
                                                      <tr>
                                                         <td>{{ $examEntry->exam->exam_name ?? '-' }}</td>
                                                         <td>{{ $examEntry->academicYear->name ?? '-' }}</td>
                                                         <td>{{ $examEntry->marks_obtained ?? '-' }}</td>
                                                         <td>{{ $examEntry->assignment_marks ?? '-' }}</td>
                                                        <td>{{ 
                                                               floatval($examEntry->marks_obtained ?? 0) + floatval($examEntry->assignment_marks ?? 0) 
                                                            }}</td>
                                                      </tr>
                                                @endforeach
                                             @else
                                                <tr>
                                                      <td colspan="6" class="text-center">{{ __('No exam results found') }}</td>
                                                </tr>
                                             @endif
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="student-health" role="tabpanel"
                  aria-labelledby="pills-user-tab-4">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Health Details') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="student_health">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Blood Group') }}</th>
                                          <th>{{ __('Allergies') }}</th>
                                          <th>{{ __('Medical Conditions') }}</th>
                                          <th>{{ __('Emergency Contact') }}</th>
                                          <th>{{ __('Last Checkup') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>{{ !empty($student->blood_group) ? $student->blood_group : '-' }}</td>
                                          <td>{{ !empty($student->allergies) ? $student->allergies : '-' }}</td>
                                          <td>{{ !empty($student->medical_conditions) ? $student->medical_conditions : '-' }}</td>
                                          <td>{{ !empty($student->emergency_contact) ? $student->emergency_contact : '-' }}</td>
                                          <td>{{ !empty($student->last_checkup) ? $student->last_checkup : '-' }}</td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="student-fees" role="tabpanel"
                  aria-labelledby="pills-user-tab-5">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           {{-- <div class="card-body table-border-style">
                              <div class="d-flex justify-content-between align-items-center mb-5">
                                <h5 class="mb-0">{{ __('Fees & Invoices') }}</h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeeModal">
                                    <i class="fas fa-plus"></i> {{ __('Add Fee') }}
                                </button>
                            </div> --}}
                              
                            <!-- Add Fee Modal -->
                            {{-- <div class="modal fade" id="addFeeModal" tabindex="-1" aria-labelledby="addFeeModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addFeeModalLabel">{{ __('Add New Fee') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                          @if($student)
                                             {{ Form::open(['route' => ['school-student.add-fee', $student->id], 'method' => 'POST']) }}
                                             ...
                                             {{ Form::close() }}
                                          @else
                                             <p class="text-center text-muted">{{ __('No student selected') }}</p>
                                          @endif
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-6 col-12">
                                                    <div class="form-group">
                                                        {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Amount'), 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-12">
                                                    <div class="form-group">
                                                        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::date('date', now()->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-12">
                                                    <div class="form-group">
                                                        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}<x-required></x-required>
                                                        {{ Form::select('status', ['Paid' => 'Paid', 'Unpaid' => 'Unpaid'], 'Unpaid', ['class' => 'form-control', 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            {{ Form::submit(__('Add Fee'), ['class' => 'btn btn-primary']) }}
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div> --}}
                              
                            <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="student_fees">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Fee Amount') }}</th>
                                          <th>{{ __('Paid Amount') }}</th>
                                          <th>{{ __('Due Amount') }}</th>
                                          <th>{{ __('Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          <th>{{ __('Actions') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(!empty($student->fees) && count($student->fees) > 0)
                                          @foreach($student->fees as $fee)
                                             <tr>
                                                <td>{{ '$' . number_format($fee->amount, 2) }}</td>
                                                <td>{{ '$' . number_format($fee->paid_amount ?? 0, 2) }}</td>
                                                <td class="{{ ($fee->due_amount ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                   {{ '$' . number_format($fee->due_amount ?? 0, 2) }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($fee->date)->format('d M Y') }}</td>
                                                <td>
                                                   @php
                                                      $statusClass = match($fee->status) {
                                                         'Paid' => 'success',
                                                         'Partially Paid' => 'warning',
                                                         default => 'danger'
                                                      };
                                                   @endphp
                                                   <span class="badge bg-{{ $statusClass }}">{{ $fee->status }}</span>
                                                </td>
                                                <td>
                                                   @if($fee->status == 'Unpaid' || $fee->status == 'Partially Paid')
                                                      <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#payFeeModal{{ $fee->id }}">
                                                         {{ __('Pay Fee') }}
                                                      </button>
                                                   @endif
                                                   <a href="{{ route('school-fees.show', encrypt($fee->id)) }}" class="btn btn-sm btn-info">
                                                      {{ __('View') }}
                                                   </a>
                                                </td>
                                             </tr>

                                             {{-- 🧾 Payment Modal for each fee --}}
                                             @if($fee->status == 'Unpaid' || $fee->status == 'Partially Paid')
                                                <div class="modal fade" id="payFeeModal{{ $fee->id }}" tabindex="-1" aria-labelledby="payFeeModalLabel{{ $fee->id }}" aria-hidden="true">
                                                   <div class="modal-dialog">
                                                      <div class="modal-content">
                                                         <div class="modal-header">
                                                            <h5 class="modal-title" id="payFeeModalLabel{{ $fee->id }}">{{ __('Process Payment') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                         </div>

                                                         {{ Form::open(['route' => ['school-fees.process-payment', $fee->id], 'method' => 'POST']) }}
                                                         <div class="modal-body">
                                                            <div class="alert alert-info">
                                                               <strong>{{ __('Fee Amount:') }}</strong> ${{ number_format($fee->amount, 2) }}<br>
                                                               <strong>{{ __('Paid:') }}</strong> ${{ number_format($fee->paid_amount ?? 0, 2) }}<br>
                                                               <strong>{{ __('Due:') }}</strong> ${{ number_format($fee->due_amount ?? 0, 2) }}
                                                            </div>

                                                            <div class="row">
                                                               <div class="col-sm-6 col-12">
                                                                  <div class="form-group">
                                                                     {{ Form::label('amount', __('Payment Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                                                                     {{ Form::number('amount', $fee->due_amount ?? $fee->amount, [
                                                                        'class' => 'form-control', 
                                                                        'step' => '0.01', 
                                                                        'max' => $fee->due_amount ?? $fee->amount, 
                                                                        'min' => '0.01', 
                                                                        'required' => true
                                                                     ]) }}
                                                                  </div>
                                                               </div>

                                                               <div class="col-sm-6 col-12">
                                                                  <div class="form-group">
                                                                     {{ Form::label('payment_method', __('Payment Method'), ['class' => 'form-label']) }}<x-required></x-required>
                                                                     {{ Form::select('payment_method', [
                                                                        'Cash' => 'Cash',
                                                                        'Bank Transfer' => 'Bank Transfer',
                                                                        'Check' => 'Check',
                                                                        'Credit Card' => 'Credit Card',
                                                                        'Online Payment' => 'Online Payment',
                                                                        'Other' => 'Other'
                                                                     ], 'Cash', ['class' => 'form-control', 'required' => true]) }}
                                                                  </div>
                                                               </div>

                                                               <div class="col-sm-6 col-12">
                                                                  <div class="form-group">
                                                                     {{ Form::label('payment_date', __('Payment Date'), ['class' => 'form-label']) }}<x-required></x-required>
                                                                     {{ Form::date('payment_date', now()->format('Y-m-d'), ['class' => 'form-control', 'required' => true]) }}
                                                                  </div>
                                                               </div>

                                                               <div class="col-sm-6 col-12">
                                                                  <div class="form-group">
                                                                     {{ Form::label('reference_number', __('Reference Number'), ['class' => 'form-label']) }}
                                                                     {{ Form::text('reference_number', null, ['class' => 'form-control', 'placeholder' => __('Enter reference number (optional)')]) }}
                                                                  </div>
                                                               </div>

                                                               <div class="col-sm-12 col-12">
                                                                  <div class="form-group">
                                                                     {{ Form::label('notes', __('Notes'), ['class' => 'form-label']) }}
                                                                     {{ Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter payment notes (optional)')]) }}
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>

                                                         <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                                            {{ Form::submit(__('Process Payment'), ['class' => 'btn btn-primary']) }}
                                                         </div>
                                                         {{ Form::close() }}
                                                      </div>
                                                   </div>
                                                </div>
                                             @endif
                                          @endforeach
                                       @else
                                          <tr>
                                             <td colspan="6" class="text-center">
                                                <p>{{ __('No fees records found') }}</p>
                                             </td>
                                          </tr>
                                       @endif
                                    </tbody>

                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    // DataTable initialization for fees
    $('#student_fees').DataTable({
        "pageLength": 10,
        "order": [[1, "desc"]], // Sort by date descending
        "language": {
            "emptyTable": "No fees records found",
            "zeroRecords": "No fees records found"
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Check URL hash to activate tab
    var hash = window.location.hash;
    if(hash) {
        // Remove active classes from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');

        // Activate the tab button
        $('[data-bs-target="' + hash + '"]').addClass('active');

        // Activate the tab content
        $(hash).addClass('show active');
    }

    // Optional: Update hash when user clicks tabs
    $('.nav-link').on('shown.bs.tab', function(e) {
        window.location.hash = $(e.target).attr('data-bs-target');
    });
});
</script>

@endpush
@endsection
