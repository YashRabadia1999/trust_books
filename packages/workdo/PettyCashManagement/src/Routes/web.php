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
use Workdo\PettyCashManagement\Http\Controllers\PettyCashController;
use Workdo\PettyCashManagement\Http\Controllers\PettyCashCategoriesController;
use Workdo\PettyCashManagement\Http\Controllers\PettyCashRequestController;
use Workdo\PettyCashManagement\Http\Controllers\ReimbursementController;
use Workdo\PettyCashManagement\Http\Controllers\PettyCashExpenseController;



// Route::group(['middleware' => 'PlanModuleCheck:PettyCashManagement'], function () {
//     Route::prefix('pettycashmanagement')->group(function () {
//         //
//     });
// });

Route::group(['middleware' => ['web', 'auth', 'verified','PlanModuleCheck:PettyCashManagement']], function () {
    Route::resource('petty-cash', PettyCashController::class);
    Route::resource('petty-cash-request', PettyCashRequestController::class);
    Route::resource('cash_categories', PettyCashCategoriesController::class);
    Route::resource('reimbursement', ReimbursementController::class);
    Route::resource('patty_cash_expense', PettyCashExpenseController::class);

    Route::put('petty-cash-request/approve/{id}', [PettyCashRequestController::class, 'approve'])->name('petty-cash-request.approve');
    Route::get('petty-cash-request/description/{id}',[PettyCashRequestController::class, 'description'])->name('petty-cash-request.description');
    Route::put('reimbursement/approve/{id}', [ReimbursementController::class, 'approve'])->name('reimbursement.approve');
    Route::get('reimbursement/description/{id}',[ReimbursementController::class, 'description'])->name('reimbursement.description');
});
