<?php

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

use Illuminate\Support\Facades\Route;
use Workdo\DrivingSchool\Http\Controllers\DrivingClassController;
use Workdo\DrivingSchool\Http\Controllers\DrivingInvoiceController;
use Workdo\DrivingSchool\Http\Controllers\DrivingLessonController;
use Workdo\DrivingSchool\Http\Controllers\DrivingLicenceTrakingController;
use Workdo\DrivingSchool\Http\Controllers\DrivingLicenceTypeController;
use Workdo\DrivingSchool\Http\Controllers\DrivingProgressReportController;
use Workdo\DrivingSchool\Http\Controllers\DrivingSchoolDashboardController;
use Workdo\DrivingSchool\Http\Controllers\DrivingStudentController;
use Workdo\DrivingSchool\Http\Controllers\DrivingTestHubController;
use Workdo\DrivingSchool\Http\Controllers\DrivingTestTypeController;
use Workdo\DrivingSchool\Http\Controllers\DrivingVehicleController;

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:DrivingSchool']], function () {
    Route::resource('driving-student', DrivingStudentController::class);
    Route::resource('driving-vehicle', DrivingVehicleController::class);
    Route::resource('driving-class', DrivingClassController::class);
    Route::resource('lesson', DrivingLessonController::class);
    Route::resource('drivinginvoice', DrivingInvoiceController::class);
    Route::resource('driving_licence_type', DrivingLicenceTypeController::class);
    Route::resource('driving_test_type', DrivingTestTypeController::class);
    Route::resource('driving_test_hub', DrivingTestHubController::class);
    Route::resource('licence_traking', DrivingLicenceTrakingController::class);
    Route::resource('progress_report', DrivingProgressReportController::class);
    Route::post('class-progress_report', [DrivingProgressReportController::class,'class'])->name('report.class');

    Route::get('drivingschool-dashboard', [DrivingSchoolDashboardController::class, 'index'])->name('driving-dashboard.index');

    Route::post('/driving-store-attendance', [DrivingLessonController::class, 'storeAttendance'])->name('driving-store.attendance');
    Route::get('driving-lesson/{id}/status/change', [DrivingLessonController::class, 'statusChange'])->name('driving-lesson.status.change');

    Route::post('driving-invoice/section', [DrivingInvoiceController::class, 'InvoiceSectionGet'])->name('student.section');
    Route::post('driving-invoice/student/get/item', [DrivingInvoiceController::class, 'StudentGetItem'])->name('student.get.item');
    Route::post('driving-invoice/student/item', [DrivingInvoiceController::class, 'items'])->name('student.item');

    Route::post('driving-invoice-pay-show/{id}', [DrivingInvoiceController::class, 'invoicePayForm'])->name('driving-invoice.pay.form');
    Route::any('driving-invoice-pay/{id}', [DrivingInvoiceController::class, 'invoicePay'])->name('driving-invoice.pay');

    Route::post('driving-invoice-chnagestatus', [DrivingInvoiceController::class, 'chnageStatus'])->name('driving.invoice.changestatus');
    Route::get('driving-invoice/pdf/{id}', [DrivingInvoiceController::class, 'invoice'])->name('driving.invoice.pdf');

    Route::post('invoice/item/destroy', [DrivingInvoiceController::class, 'itemDestroy'])->name('invoice.item.destroy');
});
