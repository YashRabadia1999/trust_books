@extends('layouts.main')
@section('page-title')
{{ __('Parent Details') }}
@endsection
@section('page-breadcrumb')
{{ __('Parent Details') }},{{ $parent['name'] }}
@endsection
@push('css')
<style>
   .parent-card {
   min-height: 204px;
   }
   .parent-photo {
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
   .parent-photo-text {
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
       // Initialize tooltips
       $('[data-bs-toggle="tooltip"]').tooltip();
   });
</script>
@endpush
@section('page-action')
<div class="d-flex">
   @permission('parent edit')
   <a href="{{ route('parent.edit', $parent->id) }}" class="btn btn-sm btn-primary me-2">
   {{ __('Edit Parent') }}
   </a>
   @endpermission
   @permission('parent delete')
   <a href="#" class="btn btn-sm btn-danger me-2" onclick="confirmDelete()">
   {{ __('Delete Parent') }}
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
                  <button class="nav-link active" id="parent-details-tab" data-bs-toggle="pill"
                     data-bs-target="#parent-details" type="button">{{ __('Details') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="parent-invoices-tab" data-bs-toggle="pill"
                     data-bs-target="#parent-invoices" type="button">{{ __('Invoices') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="parent-revenue-tab" data-bs-toggle="pill"
                     data-bs-target="#parent-revenue" type="button">{{ __('Revenue') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="parent-project-tab" data-bs-toggle="pill"
                     data-bs-target="#parent-project" type="button">{{ __('Project') }}</button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="parent-statement-tab" data-bs-toggle="pill"
                     data-bs-target="#parent-statement" type="button">{{ __('Statement') }}</button>
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
               <div class="tab-pane fade active show" id="parent-details" role="tabpanel"
                  aria-labelledby="pills-user-tab-1">
                  <div class="row">
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card parent-detail-box">
                           <div class="card-body parent-card">
                              <h5 class="card-title">{{ __('Picture Details') }}</h5>
                             
                              <div class="parent-photo">
                                @if(!empty($parent->parent_image))
                                   <img src="{{ get_file($parent->parent_image) }}" alt="{{ $parent->name }}" style="max-width:100%; max-height:100%; object-fit:cover; border-radius:6px;">
                                @else
                                   <div class="parent-photo-text">{{ __('Parent photo') }}</div>
                                @endif
                             </div>
                              <p class="card-text mb-0"><strong>{{ __('Name') }}:</strong> {{ !empty($parent->name) ? $parent->name : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Email') }}:</strong> {{ !empty($parent->email) ? $parent->email : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Phone') }}:</strong> {{ !empty($parent->phone) ? $parent->phone : '-' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Address') }}:</strong> {{ !empty($parent->address) ? $parent->address : '-' }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card parent-detail-box">
                           <div class="card-body parent-card">
                              <h5 class="card-title">{{ __('Names of kids Associated with the same phone number as parents') }}</h5>
                              @if (!empty($parent->students) && count($parent->students) > 0)
                                 @foreach ($parent->students as $student)
                                    <p class="card-text mb-0"><strong>{{ $student->name }}</strong> - {{ $student->class->class_name ?? 'N/A' }}</p>
                                 @endforeach
                              @else
                                 <p class="card-text mb-0">{{ __('No students found') }}</p>
                              @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 col-lg-4 col-xl-4">
                        <div class="card parent-detail-box">
                           <div class="card-body parent-card">
                              <h5 class="card-title">{{ __('Academic Year - Term') }}</h5>
                              <p class="card-text mb-0"><strong>{{ __('Current Academic Year') }}:</strong> {{ !empty($parent->academic_year) ? $parent->academic_year : date('Y') }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Current Term') }}:</strong> {{ !empty($parent->term) ? $parent->term : 'Term 1' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Total Kids') }}:</strong> {{ !empty($parent->parent) ? count($parent->parent) : '0' }}</p>
                              <p class="card-text mb-0"><strong>{{ __('Last Updated') }}:</strong> {{ !empty($parent->updated_at) ? $parent->updated_at->format('Y-m-d') : '-' }}</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  
                  <!-- Academic Details Section -->
                  <div class="academic-details-section">
                     <h5 class="academic-details-title">{{ __('Academic details of selected parent, by Year and term') }}</h5>
                     <div class="row">
                        <div class="col-md-4">
                           <div class="academic-card">
                              <h6>{{ __('Parent Performance') }}</h6>
                              <p class="mb-0"><strong>{{ __('Attendance') }}:</strong> 85%</p>
                              <p class="mb-0"><strong>{{ __('Assignments') }}:</strong> 12/15</p>
                              <p class="mb-0"><strong>{{ __('Exams') }}:</strong> 3/4</p>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="academic-card">
                              <h6 style="color: #e74c3c;">{{ __('Current Term') }}</h6>
                              <p class="mb-0"><strong>{{ __('Term') }}:</strong> Term 1</p>
                              <p class="mb-0"><strong>{{ __('Year') }}:</strong> 2024</p>
                              <p class="mb-0"><strong>{{ __('Class') }}:</strong> Grade 5A</p>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="academic-card">
                              <h6 style="color: #e74c3c;">{{ __('Recent Activities') }}</h6>
                              <p class="mb-0"><strong>{{ __('Last Assignment') }}:</strong> Math</p>
                              <p class="mb-0"><strong>{{ __('Last Exam') }}:</strong> Science</p>
                              <p class="mb-0"><strong>{{ __('Next Event') }}:</strong> Parent Meeting</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               
               <div class="tab-pane fade" id="parent-invoices" role="tabpanel"
                  aria-labelledby="pills-user-tab-2">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Invoices') }} 
                                 <small class="text-muted">(Link to School fees generated for the parent)</small>
                              </h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="parent_invoices">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Invoice No') }}</th>
                                          <th>{{ __('Student') }}</th>
                                          <th>{{ __('Amount') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          <th>{{ __('Action') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(!empty($invoices) && count($invoices) > 0)
                                          @foreach($invoices as $inv)
                                             <tr>
                                                <td>{{ $inv['number'] ?? ($inv['id'] ?? '-') }}</td>
                                                <td>{{ $inv['student_name'] ?? '-' }}</td>
                                                <td>{{ $inv['amount'] ?? '-' }}</td>
                                                <td>{{ $inv['due_date'] ?? '-' }}</td>
                                                <td>{{ $inv['status'] ?? '-' }}</td>
                                                <td>-</td>
                                             </tr>
                                          @endforeach
                                       @else
                                          <tr>
                                             <td colspan="6" class="text-center">
                                                <p>{{ __('No invoices found') }}</p>
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
               
               <div class="tab-pane fade" id="parent-revenue" role="tabpanel"
                  aria-labelledby="pills-user-tab-3">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Revenue') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="parent_revenue">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Date') }}</th>
                                          <th>{{ __('Amount') }}</th>
                                          <th>{{ __('Payment Method') }}</th>
                                          <th>{{ __('Reference') }}</th>
                                          <th>{{ __('Status') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(!empty($revenues) && count($revenues) > 0)
                                          @foreach($revenues as $rev)
                                             <tr>
                                                <td>{{ $rev['date'] ?? '-' }}</td>
                                                <td>{{ $rev['amount'] ?? '-' }}</td>
                                                <td>{{ $rev['method'] ?? '-' }}</td>
                                                <td>{{ $rev['reference'] ?? '-' }}</td>
                                                <td>{{ $rev['status'] ?? '-' }}</td>
                                             </tr>
                                          @endforeach
                                       @else
                                          <tr>
                                             <td colspan="5" class="text-center">
                                                <p>{{ __('No revenue records found') }}</p>
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
               
               <div class="tab-pane fade" id="parent-project" role="tabpanel"
                  aria-labelledby="pills-user-tab-4">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Project') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="parent_project">
                                    <thead>
                                       <tr>
                                          <th>{{ __('Project Name') }}</th>
                                          <th>{{ __('Student') }}</th>
                                          <th>{{ __('Due Date') }}</th>
                                          <th>{{ __('Status') }}</th>
                                          <th>{{ __('Grade') }}</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if(!empty($projects) && count($projects) > 0)
                                          @foreach($projects as $prj)
                                             <tr>
                                                <td>{{ $prj['name'] ?? '-' }}</td>
                                                <td>{{ $prj['student_name'] ?? '-' }}</td>
                                                <td>{{ $prj['due_date'] ?? '-' }}</td>
                                                <td>{{ $prj['status'] ?? '-' }}</td>
                                                <td>{{ $prj['grade'] ?? '-' }}</td>
                                             </tr>
                                          @endforeach
                                       @else
                                          <tr>
                                             <td colspan="5" class="text-center">
                                                <p>{{ __('No projects found') }}</p>
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
               
               <div class="tab-pane fade" id="parent-statement" role="tabpanel"
                  aria-labelledby="pills-user-tab-5">
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-body table-border-style">
                              <h5 class="d-inline-block mb-5">{{ __('Statement') }}</h5>
                              <div class="table-responsive">
                                 <table class="table mb-0 pc-dt-simple" id="parent_statement">
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
                                       @if(!empty($statements) && count($statements) > 0)
                                          @foreach($statements as $st)
                                             <tr>
                                                <td>{{ $st['date'] ?? '-' }}</td>
                                                <td>{{ $st['description'] ?? '-' }}</td>
                                                <td>{{ $st['debit'] ?? '-' }}</td>
                                                <td>{{ $st['credit'] ?? '-' }}</td>
                                                <td>{{ $st['balance'] ?? '-' }}</td>
                                             </tr>
                                          @endforeach
                                       @else
                                          <tr>
                                             <td colspan="5" class="text-center">
                                                <p>{{ __('No statement records found') }}</p>
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
@endsection
