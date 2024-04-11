<?php

use App\Http\Controllers\TransactionWebController;
use Illuminate\Support\Facades\Route;


// Transactions 
Route::group(['prefix' => 'robus'], function () {
    Route::get('reloads/cartes',  [TransactionWebController::class, 'transactions_reloads'])->name('web.transactions_reloads');
    Route::post('reloads/store',  [TransactionWebController::class, 'transactions_reload_store'])->name('web.transactions_reloads.stores');
    Route::get('reloads/push',  [TransactionWebController::class, 'transactions_pushs'])->name('web.transactions_pushs');
    Route::get('reloads/accompte',  [TransactionWebController::class, 'transactions_robus'])->name('web.transactions_robus');
    Route::get('deposits/accompte',  [TransactionWebController::class, 'transactions_deposits'])->name('web.transactions_deposits');
    Route::get('deposits/inters',  [TransactionWebController::class, 'transactions_inters'])->name('web.transactions_inters');
    Route::get('reloads/xbets',  [TransactionWebController::class, 'transactions_xbets'])->name('web.transactions_xbets');
});
