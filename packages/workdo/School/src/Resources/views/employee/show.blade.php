@extends('layouts.main')
@section('page-title')
{{ __('Teacher Details') }}
@endsection
@section('page-breadcrumb')
{{ __('Teacher Details') }},{{ $employee['name'] ?? '' }}
@endsection
@push('css')
<style>
   .teacher-card {
   min-height: 204px;
   }
   .teacher-photo {
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
   .teacher-photo-text {
       /* color: #e74c3c; */
       font-weight: bold;
       text-align: center;
   }
   .academic-details-section {
       /* border: 2px solid #e74c3c; */
       border-radius: 8px;
       padding: 20px;
       margin-top: 20px;
   }
   .academic-details-title {
       /* color: #e74c3c; */
       font-weight: bold;
       margin-bottom: 15px;
   }
   .academic-card {
       background: white;
       border: 1px solid #ddd;
       border-radius: 8px;
       padding: 15px;
       margin: 10px;
       min-height: 120px;
   }
</style>
@endpush
@push('scripts')
<script>
   $(document).ready(function() {
       $('[data-bs-toggle="tooltip"]').tooltip();
   });
</script>
@endpush

@section('content')
<div class="page-header">
   <div class="page-block">
      <div class="row align-items-center">
         <div class="col-md-4">
         </div>
         <div class="col-md-8 mt-4">
            <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
               <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="teacher-details-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-details" type="button">{{ __('Details') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="teacher-students-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-students" type="button">{{ __('Students') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="teacher-assignments-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-assignments" type="button">{{ __('Assignments') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="teacher-exam-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-exam" type="button">{{ __('Exam') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="teacher-project-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-project" type="button">{{ __('Project') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="teacher-statement-tab" data-bs-toggle="pill"
                     data-bs-target="#teacher-statement" type="button">{{ __('Statement') }}</button>
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
               <div class="tab-pane fade active show" id="teacher-details" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  <div class="row">
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card teacher-detail-box">
                           <div class="card-body teacher-card">
                              <h5 class="card-title">{{ __('Picture') }}</h5>
                              <div class="teacher-photo">
                                 <div class="teacher-photo-text">{{ __('picture') }}</div>
                              </div>
                              <p class="card-text mb-0"><strong>{{ __('Name') }}:</strong> {{ $employee->name ?? '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Gender') }}:</strong> {{ $employee->gender ?? '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Age') }}:</strong> {{ !empty($employee->dob) ? \Carbon\Carbon::parse($employee->dob)->age : '-' }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card teacher-detail-box">
                           <div class="card-body teacher-card">
                              <h5 class="card-title">{{ __('Class ( Classes )') }}</h5>
                              <p class="card-text mb-0"><strong>{{ __('Subject(s)') }}:</strong>
                                 @if(isset($subjects) && count($subjects))
                                    {{ $subjects->pluck('name')->join(', ') }}
                                 @else
                                    -
                                 @endif
                              </p>
                              <p class="card-text mb-0"><strong>{{ __('Academic Year') }}:</strong> {{ !empty($employee->company_doj) ? company_date_formate($employee->company_doj) : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Term') }}:</strong> {{ $employee->current_term ?? '-' }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card teacher-detail-box">
                           <div class="card-body teacher-card">
                              <h5 class="card-title">{{ __('Other Details') }}</h5>
                              <p class="card-text mb-0"><strong>{{ __('Phone') }}:</strong> {{ $employee->phone ?? '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Email') }}:</strong> {{ $employee->email ?? '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Address') }}:</strong> {{ $employee->address ?? '-' }}</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="academic-details-section">
                     <h5 class="academic-details-title">{{ __('Other details when we select any of the tabs up there.') }}</h5>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="academic-card">
                              <p class="mb-0">{{ __('Other details appear here if any of the tabs are selected') }}</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="tab-pane fade" id="teacher-students" role="tabpanel"
                  aria-labelledby="pills-user-tab-2">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Students') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="teacher_students">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Student') }}</th>
                                          <th>{{ __('Class') }}</th>
                                          <th>{{ __('Parent') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="3" class="text-center">
                                             <p>{{ __('No students found') }}</p>
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

               <div class="tab-pane fade" id="teacher-assignments" role="tabpanel"
                  aria-labelledby="pills-user-tab-3">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Assignments') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="teacher_assignments">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Title') }}</th>
                                          <th>{{ __('Class') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="4" class="text-center">
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

               <div class="tab-pane fade" id="teacher-exam" role="tabpanel"
                  aria-labelledby="pills-user-tab-4">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Exam') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="teacher_exam">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Exam') }}</th>
                                          <th>{{ __('Class') }}</th>
                                          <th>{{ __('Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="4" class="text-center">
                                             <p>{{ __('No exam records found') }}</p>
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

               <div class="tab-pane fade" id="teacher-project" role="tabpanel"
                  aria-labelledby="pills-user-tab-5">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Project') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="teacher_project">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Project Name') }}</th>
                                          <th>{{ __('Class') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="4" class="text-center">
                                             <p>{{ __('No projects found') }}</p>
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

               <div class="tab-pane fade" id="teacher-statement" role="tabpanel"
                  aria-labelledby="pills-user-tab-6">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Statement') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="teacher_statement">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Date') }}</th>
                                          <th>{{ __('Description') }}</th>
                                          <th>{{ __('Debit') }}</th>
                                          <th>{{ __('Credit') }}</th>
                                          <th>{{ __('Balance') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td colspan="5" class="text-center">
                                             <p>{{ __('No statement records found') }}</p>
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
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
