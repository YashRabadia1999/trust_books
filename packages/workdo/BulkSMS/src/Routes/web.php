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
use Workdo\BulkSMS\Http\Controllers\BulkSMSContactController;
use Workdo\BulkSMS\Http\Controllers\BulkSMSController;
use Workdo\BulkSMS\Http\Controllers\BulksmsGroupController;
use Workdo\BulkSMS\Http\Controllers\SingleSMSController;
use Workdo\BulkSMS\Http\Controllers\CustomerMessageController;
use Workdo\BulkSMS\Http\Controllers\ExcelSMSController;
use Workdo\BulkSMS\Http\Controllers\Company\SettingsController;
use Workdo\BulkSMS\Http\Controllers\GreetingSmsController;

Route::group(['middleware' => ['web', 'auth', 'verified', 'PlanModuleCheck:BulkSMS']], function () {
    Route::resource('bulksms-contacts', BulkSMSContactController::class);
    Route::resource('bulksms-group', BulksmsGroupController::class);

    // Customer Message Templates
    Route::resource('customer-messages', CustomerMessageController::class);
    Route::get('customer-messages/{id}/send-single', [CustomerMessageController::class, 'sendSingle'])->name('customer-messages.send-single');
    Route::get('customer-messages/{id}/send-bulk', [CustomerMessageController::class, 'sendBulk'])->name('customer-messages.send-bulk');

    // Excel SMS Upload
    Route::get('excel-sms/create', [ExcelSMSController::class, 'create'])->name('excel-sms.create');
    Route::post('excel-sms/store', [ExcelSMSController::class, 'store'])->name('excel-sms.store');
    Route::get('excel-sms/download-sample', [ExcelSMSController::class, 'downloadSample'])->name('excel-sms.download-sample');

    Route::post('bulksms-settings/store', [SettingsController::class, 'store'])->name('bulksms.setting.save');
    Route::get('bulksms/contact/load/data', [BulkSMSContactController::class, 'loadDataModal'])->name('bulksms.contact.load.data');
    Route::post('bulksms/contact/load/customers-users', [BulkSMSContactController::class, 'loadCustomersAndUsers'])->name('bulksms.contact.load.customers.users');
    Route::get('bulksms/contact/import/export', [BulkSMSContactController::class, 'fileImportExport'])->name('bulksms.contact.file.import');
    Route::get('bulksms/contact/import/modal', [BulkSMSContactController::class, 'fileImportModal'])->name('bulksms.contact.import.modal');
    Route::post('bulksms/contact/import', [BulkSMSContactController::class, 'fileImport'])->name('bulksms.contact.import');
    Route::post('bulksms/contact/data/import/', [BulkSMSContactController::class, 'contactImportdata'])->name('bulksms.contact.import.data');
    Route::delete('group/{groupId}/contact/{mobile}', [BulksmsGroupController::class, 'removeContact'])->name('group.contact.remove');
    //Route::resource('bulksms-single-sms', SingleSMSController::class);
    Route::resource('bulksms-single-sms', SingleSMSController::class)->names([
        'store' => 'bulksms.single-sms.store'
    ]);
    Route::post('bulksms/single-sms/save-message', [SingleSMSController::class, 'storeMessageTemplate'])->name('bulksms-single-sms.save-message');
    Route::get('bulksms/single/message/{id}', [SingleSMSController::class, 'message'])->name('bulksms.single.message');
    Route::resource('bulksms-send-sms', BulkSMSController::class);
    Route::get('bulksms/bulk/message/{id}', [BulkSMSController::class, 'message'])->name('bulksms.bulk.message');
    Route::delete('bulksms/bulk/delete/{id}', [BulkSMSController::class, 'removeSms'])->name('bulksms.bulk.remove');

    // Greeting SMS Routes
    Route::get('bulksms/greeting', [GreetingSmsController::class, 'index'])->name('bulksms.greeting.index');
    Route::post('bulksms/greeting/send', [GreetingSmsController::class, 'send'])->name('bulksms.greeting.send');
    Route::get('bulksms/greeting/templates', [GreetingSmsController::class, 'getTemplates'])->name('bulksms.greeting.templates');
});
