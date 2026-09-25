<?php

use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CountrieController;
use App\Http\Controllers\PixelPaymentController;
use App\Http\Controllers\TransactionWebController;
use Illuminate\Support\Facades\Route;




// Transactions 
Route::group(['prefix' => 'robus'], function () {

    Route::get('reloads/cartes',  [TransactionWebController::class, 'transactions_reloads'])->name('web.transactions_reloads');
    Route::get('reloads/push',  [TransactionWebController::class, 'transactions_pushs'])->name('web.transactions_pushs');
    Route::get('reloads/accompte',  [TransactionWebController::class, 'transactions_robus'])->name('web.transactions_robus');
    Route::get('transactions/accompte',  [TransactionWebController::class, 'transactions_transactions'])->name('web.transactions_transactions');
    Route::get('deposits/inters',  [TransactionWebController::class, 'transactions_inters'])->name('web.transactions_inters');
    Route::get('reloads/xbets',  [TransactionWebController::class, 'transactions_xbets'])->name('web.transactions_xbets');
    // Stores
    Route::post('reloads/store',  [TransactionWebController::class, 'transactions_reload_store'])->name('web.transactions_reloads.stores');
    Route::post('push/store',  [TransactionWebController::class, 'transactions_push_store'])->name('web.transactions_push.stores');
    Route::post('transactions/store',  [TransactionWebController::class, 'transactions_deposits_store'])->name('web.transactions_deposits_store.stores');
    Route::post('xbets/store',  [TransactionWebController::class, 'transactions_xbets_store'])->name('web.transactions_xbets_store.stores');
    Route::post('robus/store',  [TransactionWebController::class, 'transactions_robus_store'])->name('web.transactions_robus_store.stores');
    Route::post('inters/store',  [TransactionWebController::class, 'transactions_inters_store'])->name('web.transactions_inters_store.stores');

    // Search wallets
    Route::get('search-wallet',  [TransactionWebController::class, 'searchWallet'])->name('search.wallets');
    Route::get('search-countrie/{id}',  [TransactionWebController::class, 'searchCountries']);
    Route::get('search-percent/{country_id}',  [TransactionWebController::class, 'searchProviderPercent']);
    Route::get('search-indice/{country_id}',  [TransactionWebController::class, 'searchCountrieName']);

    // Pixel Innov - Transfert mobile web public
    Route::prefix('pixelInnov/mobile-transfer')->name('web.pixelInnov.mobile-transfer.')->group(function () {
        Route::get('services', [PixelPaymentController::class, 'listMobileTransferServicesWeb'])->name('services');
        Route::post('calculate', [PixelPaymentController::class, 'calculateMobileZoneTransferWeb'])->name('calculate');
        Route::post('initialize', [PixelPaymentController::class, 'initializeMobileZoneTransferWeb'])->name('initialize');
        Route::post('/reloadinitialize', [PixelPaymentController::class, 'initializeWebPayment'])
            ->name('pixelInnov.reload.initialize');
        Route::post('/reloadcalculate', [PixelPaymentController::class, 'calculateAmount'])
            ->name('pixelInnov.reload.calculate');
        Route::post('status', [PixelPaymentController::class, 'checkMobileZoneTransferStatusWeb'])->name('status');
    });
});
