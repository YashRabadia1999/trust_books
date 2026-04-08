<?php

use Illuminate\Support\Facades\Route;
use Workdo\School\Http\Controllers\AdmissionController;
use Workdo\School\Http\Controllers\BookIssueController;
use Workdo\School\Http\Controllers\ClassroomController;
use Workdo\School\Http\Controllers\HealthRecordController;
use Workdo\School\Http\Controllers\HostelStudentController;
use Workdo\School\Http\Controllers\LibraryBookController;
use Workdo\School\Http\Controllers\SchoolAluminiController;
use Workdo\School\Http\Controllers\SchoolAssessmentController;
use Workdo\School\Http\Controllers\SchoolAssessmentResultController;
use Workdo\School\Http\Controllers\SchoolAttendanceController;
use Workdo\School\Http\Controllers\SchoolBranchController;
use Workdo\School\Http\Controllers\SchoolBusController;
use Workdo\School\Http\Controllers\SchoolController;
use Workdo\School\Http\Controllers\USSDController;
use Workdo\School\Http\Controllers\SchoolDepartmentController;
use Workdo\School\Http\Controllers\SchoolDesignationController;
use Workdo\School\Http\Controllers\SchoolEmployeeController;
use Workdo\School\Http\Controllers\SchoolEventController;
use Workdo\School\Http\Controllers\SchoolFeesController;
use Workdo\School\Http\Controllers\SchoolFeesStructureController;
use Workdo\School\Http\Controllers\SchoolFeeSetupController;
use Workdo\School\Http\Controllers\SchoolGradeController;
use Workdo\School\Http\Controllers\SchoolHomeworkController;
use Workdo\School\Http\Controllers\SchoolHostelController;
use Workdo\School\Http\Controllers\SchoolMeetingController;
use Workdo\School\Http\Controllers\SchoolNoticeController;
use Workdo\School\Http\Controllers\SchoolParentController;
use Workdo\School\Http\Controllers\SchoolRoomController;
use Workdo\School\Http\Controllers\SchoolStudentController;
use Workdo\School\Http\Controllers\SchoolTransportFeesController;
use Workdo\School\Http\Controllers\SchoolTransportRouteController;
use Workdo\School\Http\Controllers\SubjectController;
use Workdo\School\Http\Controllers\TeacherTimetableController;
use Workdo\School\Http\Controllers\TimetableController;
use Workdo\School\Http\Controllers\AcademicYearController;
use Workdo\School\Http\Controllers\TermController;
use Workdo\School\Http\Controllers\AssignmentEntryController;
use Workdo\School\Http\Controllers\SchoolInvoiceController;
use Workdo\School\Http\Controllers\ExamSettingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['web', 'auth', 'verified']], function () {
    Route::get('dashboard/ussd', [USSDController::class, 'ussdindex'])->name('ussd.dashboard');
    Route::get('/ussd/transactions', [USSDController::class, 'getAllTransactions'])->name('ussd.transactions');
    Route::get('dashboard/ussd/transactions-data', [USSDController::class, 'getTransactions'])->name('ussd.transactions.data');
    Route::get('dashboard/ussd/customers-data', [USSDController::class, 'getCustomers'])->name('ussd.customers.data');
    
});
Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:School']], function () {
    Route::prefix('school')->group(function () {
        Route::resource('admission', AdmissionController::class);
    });

    Route::get('dashboard/school', [SchoolController::class, 'index'])->name('school.dashboard');
    
    Route::post('school/setting/store', [AdmissionController::class, 'setting'])->name('school.setting.store');
    Route::get('admission/pdf/{id}', [AdmissionController::class, 'pdf'])->name('admission.pdf');
    Route::get('student/{id}/admission', [AdmissionController::class, 'admissionconvert'])->name('admission.convert');
    Route::post('student_convert/{id}', [AdmissionController::class, 'convert'])->name('student.convert');

    Route::resource('classroom', ClassroomController::class);
    Route::resource('subject', SubjectController::class);
    Route::resource('timetable', TimetableController::class);
    Route::post('/getsubject', [TimetableController::class, 'getsubject'])->name('getsubject');

    Route::get('school-student/bulk-upload', [SchoolStudentController::class, 'bulkUploadForm'])->name('school-student.bulk.form');
    Route::post('school-student/bulk-upload', [SchoolStudentController::class, 'bulkUploadStore'])->name('school-student.bulk.store');
    Route::get('school-student/bulk-sample', [SchoolStudentController::class, 'downloadSample'])->name('school-student.bulk.sample');
    Route::resource('school-student', SchoolStudentController::class)->names('school-student');
    Route::resource('schoolemployee', SchoolEmployeeController::class);
    Route::resource('school-parent', SchoolParentController::class);
    Route::resource('school-grade', SchoolGradeController::class);
    Route::resource('school-attendance', SchoolAttendanceController::class);
    Route::resource('school-bulkattendance', SchoolAttendanceController::class);


    // branch
    Route::resource('schoolbranches', SchoolBranchController::class);
    Route::get('schoolbranchesnameedit', [SchoolBranchController::class, 'BranchNameEdit'])->name('schoolbranchesname.edit');
    Route::post('school-branch-setting', [SchoolBranchController::class, 'saveBranchName'])->name('schoolbranchesname.update');

    // department
    Route::resource('schooldepartment', SchoolDepartmentController::class);
    Route::get('schooldepartmentnameedit', [SchoolDepartmentController::class, 'DepartmentsNameEdit'])->name('schooldepartmentname.edit');
    Route::post('school-department-settings', [SchoolDepartmentController::class, 'saveDepartmentName'])->name('schooldepartmentname.update');

    //Designation
    Route::resource('schooldesignation', SchoolDesignationController::class);
    Route::get('schooldesignationnameedit', [SchoolDesignationController::class, 'DesignationNameEdit'])->name('schooldesignationname.edit');
    Route::post('school-designation-settings', [SchoolDesignationController::class, 'saveDesignationName'])->name('schooldesignationname.update');

    Route::post('school/employee/getdepartment', [SchoolEmployeeController::class, 'getDepartment'])->name('schoolemployee.getdepartment');
    Route::post('school/employee/getdesignation', [SchoolEmployeeController::class, 'getdDesignation'])->name('schoolemployee.getdesignation');

    //homework
    Route::resource('school-homework', SchoolHomeworkController::class);
    Route::post('/getschoolsubject', [SchoolHomeworkController::class, 'getschoolsubject'])->name('getschoolsubject');
    Route::get('/gethomework/{id}', [SchoolHomeworkController::class, 'gethomework'])->name('gethomework');
    Route::post('/getstdhomework/{id}', [SchoolHomeworkController::class, 'getstdhomework'])->name('getstdhomework');
    Route::resource('/submit-homework', SchoolHomeworkController::class);
    Route::get('/viewhomework', [SchoolHomeworkController::class, 'viewhomework'])->name('viewhomework');
    Route::get('homeworkcontent/{id}', [SchoolHomeworkController::class, 'content'])->name('homework.content');

    //teacher timetable
    Route::resource('teacher-timetable', TeacherTimetableController::class);

    //attendance
    Route::post('school-student/getclass', [SchoolAttendanceController::class, 'getClass'])->name('student.getclassRoom');
    Route::get('schoolstudent-bulkattendance', [SchoolAttendanceController::class, 'bulkAttendance'])->name('student.bulkattendance');
    Route::post('schoolstudent-bulkattendance-store', [SchoolAttendanceController::class, 'BulkAttendanceData'])->name('student.bulkattendance.store');

    //school fees
    Route::resource('school-fees', SchoolFeesController::class);
    Route::resource('school-fee-structure', SchoolFeesStructureController::class);
    Route::resource('school-fee-setup', SchoolFeeSetupController::class);
    
    // Enhanced fee setup routes
    Route::post('school-fee-setup/{id}/generate-invoices', [SchoolFeeSetupController::class, 'generateInvoices'])->name('school-fee-setup.generate-invoices');
    Route::post('school-fee-setup/{id}/send-notifications', [SchoolFeeSetupController::class, 'sendNotifications'])->name('school-fee-setup.send-notifications');

    //library
    Route::resource('library-books', LibraryBookController::class);
    Route::resource('library-books-issue', BookIssueController::class);

    //transport
    Route::resource('school-bus', SchoolBusController::class);
    Route::resource('school-transport-routes', SchoolTransportRouteController::class);
    Route::resource('school-transport-fees', SchoolTransportFeesController::class);

    //alumini
    Route::resource('school-alumini', SchoolAluminiController::class);
    Route::post('school-getstudentinfo', [SchoolAluminiController::class, 'getStudentInfo'])->name('school.getstudentinfo');

    //event
    Route::resource('school-event', SchoolEventController::class);
    Route::get('school-event-calendar', [SchoolEventController::class, 'calendarView'])->name('school.event.calendar');
    Route::get('/school-event/{id}/description', [SchoolEventController::class,'description'])->name('school.event.description');


    //hostel
    Route::resource('school-hostel', SchoolHostelController::class);
    Route::resource('school-room', SchoolRoomController::class);
    Route::resource('hostel-student', HostelStudentController::class);

    //assessment
    Route::resource('school-assessment', SchoolAssessmentController::class);
    Route::resource('school-assessment-result', SchoolAssessmentResultController::class);

    //noticeboard
    Route::resource('school-notice', SchoolNoticeController::class);

    //health record
    Route::resource('school-health-record', HealthRecordController::class);
    
    //student fees management
    Route::post('school-student/{id}/add-fee', [SchoolFeesController::class, 'addFeeForStudent'])->name('school-student.add-fee');
    Route::post('school-fees/{id}/process-payment', [SchoolFeesController::class, 'processPayment'])->name('school-fees.process-payment');
    Route::get('school-fees/{id}/payment-history', [SchoolFeesController::class, 'paymentHistory'])->name('school-fees.payment-history');

    //meeting
    Route::resource('school-meeting', SchoolMeetingController::class);

    //Academic year
    Route::resource('academic-year', AcademicYearController::class)->names([
    'index' => 'school.academic-year.index',
    'create' => 'school.academic-year.create',
    'store' => 'school.academic-year.store',
    'show' => 'school.academic-year.show',
    'edit' => 'school.academic-year.edit',
    'update' => 'school.academic-year.update',
    'destroy' => 'school.academic-year.destroy',
]);


Route::resource('term', TermController::class)->names([
    'index' => 'school.term.index',
    'create' => 'school.term.create',
    'store' => 'school.term.store',
    'edit' => 'school.term.edit',
    'update' => 'school.term.update',
    'destroy' => 'school.term.destroy',
]);


Route::resource('assignment', AssignmentEntryController::class)->names([
    'index'   => 'school.assignment.index',
    'create'  => 'school.assignment.create',
    'store'   => 'school.assignment.store',
    'edit'    => 'school.assignment.edit',
    'update'  => 'school.assignment.update',
    'destroy' => 'school.assignment.destroy',
]);
Route::resource('school-invoice', SchoolInvoiceController::class)->names('school-invoice');
Route::get('school-invoice/{id}', [SchoolFeesController::class, 'showInvoice'])->name('school-invoice.show');

// Exam Marks Bulk Upload
Route::resource('exam-entries', ExamEntryController::class)->except(['show']);

Route::get('exam/bulk-upload', [\Workdo\School\Http\Controllers\ExamEntryController::class, 'bulkUploadForm'])->name('school.exam.bulk.form');
Route::post('exam/bulk-upload', [\Workdo\School\Http\Controllers\ExamEntryController::class, 'bulkUploadStore'])->name('school.exam.bulk.store');
Route::get('exam/bulk-sample', [\Workdo\School\Http\Controllers\ExamEntryController::class, 'downloadSample'])->name('school.exam.bulk.sample');

// Exam Report
Route::get('exam/report', [\Workdo\School\Http\Controllers\ExamReportController::class, 'index'])->name('school.exam.report.index');
Route::get('exam/report/{student}', [\Workdo\School\Http\Controllers\ExamReportController::class, 'show'])->name('school.exam.report.show');
Route::get('exam-report/student/{student_id}', [\Workdo\School\Http\Controllers\ExamReportController::class, 'show'])
    ->name('school.exam.report.show');


// Exams
Route::resource('exam', \Workdo\School\Http\Controllers\ExamEntryController::class)->names([
    'index'   => 'school.exam.index',
    'create'  => 'school.exam.create',
    'store'   => 'school.exam.store',
    'edit'    => 'school.exam.edit',
    'update'  => 'school.exam.update',
    'destroy' => 'school.exam.destroy',
]);


// Custom exam settings routes
Route::get('school/exam/settings', [ExamSettingController::class, 'index'])->name('school.exam.settings.index');
Route::post('school/exam/settings', [ExamSettingController::class, 'update'])->name('school.exam.settings.update');
});


