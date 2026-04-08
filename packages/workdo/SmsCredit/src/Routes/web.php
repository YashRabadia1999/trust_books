<?php

use Illuminate\Support\Facades\Route;
use Workdo\SmsCredit\Http\Controllers\SmsCreditController;
use Workdo\SmsCredit\Http\Controllers\Company\SettingsController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('sms-credit', [SmsCreditController::class, 'index'])->name('sms-credit.index');
    Route::get('sms-credit/create', [SmsCreditController::class, 'create'])->name('sms-credit.create');
    Route::post('sms-credit', [SmsCreditController::class, 'store'])->name('sms-credit.store');
    Route::get('sms-credit/{id}', [SmsCreditController::class, 'show'])->name('sms-credit.show');
    Route::get('sms-credit/{id}/check-status', [SmsCreditController::class, 'checkStatus'])->name('sms-credit.check-status');
    Route::get('sms-credit/balance/view', [SmsCreditController::class, 'balance'])->name('sms-credit.balance');

    // Settings
    Route::post('sms-credit-settings/store', [SettingsController::class, 'store'])->name('sms-credit.settings.store');

    // AJAX
    Route::post('sms-credit/calculate', [SmsCreditController::class, 'calculateCredits'])->name('sms-credit.calculate');
});// Payment callback (no auth required)
Route::post('sms-credit/payment/callback', [SmsCreditController::class, 'paymentCallback'])->name('sms-credit.payment.callback');
Route::get('sms-credit/payment/success', [SmsCreditController::class, 'paymentSuccess'])->name('sms-credit.payment.success');
