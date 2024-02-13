<?php

use App\Http\Livewire\LoginCompany;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\Admin\ComController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\Admin\AdminController;

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

Route::group(['middleware' => ['auth:comzone']], function () {


    Route::group(['prefix' => 'robus'], function () {
        Route::get('/browse', [AdminController::class, 'home_com_zones'])->name('comzones.home');
        Route::get('/logout', [LoginCompany::class, 'logout_comzones'])->name('comzones.logout');
    });

    //   Users
    Route::prefix('underdistributors')->group(function () {
        Route::get('/browse', [ComController::class, 'browse_underdistributors'])->name('comzones.underdistributors.browse');
        Route::get('/add', [ComController::class, 'add_underdistributors'])->name('comzones.underdistributors.add');
        Route::post('/store', [ComController::class, 'store_under_distributors'])->name('comzones.underdistributors.store');
        Route::get('/show/{id}', [ComController::class, 'show__underdistributors'])->name('comzones.underdistributors.show');
    });


    Route::prefix('custumers')->group(function () {
        Route::get('/browse', [ComController::class, 'browse_custumers'])->name('custumers.comzones.browse');
        Route::get('/add', [ComController::class, 'add_custumers'])->name('custumers.comzones.add');
        Route::get('/show/{id}', [ComController::class, 'show__custumers'])->name('custumers.comzones.show');
    });


    Route::prefix('reloads')->group(function () {
        Route::get('/browse', [ComController::class, 'browse_reloads'])->name('comzones.underdistributors.browse_reloads');
    });

    Route::prefix('unloads')->group(function () {
        Route::get('/browse', [ComController::class, 'browse_dumps'])->name('comzones.underdistributors.browse_dumps');
    });


    //Statisques
    Route::prefix('statistics')->group(function () {
        Route::get('/browse_unders', [StatistiqueController::class, 'browse_state_masters'])->name('comzones.underdistributors.transactions.historiques.states');
        Route::get('/browse_custumers', [StatistiqueController::class, 'browse_state_custumers'])->name('comzones.transactions.historiques.states');
    });

    //Historical
    Route::prefix('historicals')->group(function () {
        Route::get('/transfers_historicals', [HistoricalController::class, 'browse_transfer'])->name('comzones.transfers.historical');
        Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical_company'])->name('comzones.transfers.historical.reporting_transfer_historical');
    });

    //Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/transfers/{transfers_id}', [InvoiceController::class, 'transfers_uv_company'])->name('companies.transfers.invoice.print');
    });

    //Account managment
    Route::prefix('account')->group(function () {

        Route::get('/', [ComController::class, 'showprofile'])->name('comzones.account.profile');
        Route::get('/showprofil/{id}', [ComController::class, 'ShowProfil_comzones'])->name('comzones.ShowProfil');
        Route::get('/security/update', [ComController::class, 'security'])->name('comzones.account.security');
        Route::post('/read/{id}/update', [ComController::class, 'updateProfil'])->name('comzones.account.showUpdate');
        Route::post('update-password', [ComController::class, 'updatePassword'])->name('comzones.account.security.update');
        Route::post('/updateProfil',  [ComController::class, 'updateprofil'])->name('comzones.account.update');

        // NOTIFY  
        // Route::get('/liste_notifications', [ComController::class, 'get_notification'])->name('admin.show.get_notification');
        // Route::get('/notifications', [ComController::class, 'get_notifications'])->name('admin.show.notifications');
        // Route::get('/get-notifications', [ComController::class, 'get_unread_notifications'])->name('admin.get.unread.notifications');
        // Route::get('/make-notifications-as-read', [ComController::class, 'make_notifications_as_read'])->name('admin.make.notifications.as.read');
        // Route::get('/make_read_all', [ComController::class, 'make_read_all'])->name('admin.make.make_read_all');
        // Route::get('/make_read_single/{id}', [ComController::class, 'make_read_single'])->name('admin.make.make_read_single');
    });
});
