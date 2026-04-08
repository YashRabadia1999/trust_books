<?php

use Illuminate\Support\Facades\Route;
use Modules\BulkSms\Http\Controllers\GreetingSmsController;

/*
|--------------------------------------------------------------------------
| Web Routes - BulkSms Module
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['web', 'auth', 'verified']], function () {

    // Greeting SMS Routes
    Route::prefix('bulksms')->name('bulksms.')->group(function () {
        Route::get('/greeting', [GreetingSmsController::class, 'index'])->name('greeting.index');
        Route::post('/greeting/send', [GreetingSmsController::class, 'send'])->name('greeting.send');
        Route::get('/greeting/templates', [GreetingSmsController::class, 'getTemplates'])->name('greeting.templates');
    });
});
