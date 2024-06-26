<?php

use App\Http\Livewire\LoginCompany;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|------------------------------------------ --------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:master']], function () {


    Route::group(['prefix' => 'robus'], function () {
        Route::get('/browse', [AdminController::class, 'home_masters'])->name('masters.home');
        Route::get('/logout', [LoginCompany::class, 'logout_masters'])->name('masters.logout');
    });

    //   Users
    Route::prefix('underdistributors')->group(function () {
        Route::get('/browse', [MasterController::class, 'browse_underdistributors'])->name('underdistributors.browse');
        Route::get('/add', [MasterController::class, 'add_underdistributors'])->name('masters.underdistributors.add');
        Route::post('/store', [MasterController::class, 'store_under_distributors'])->name('masters.underdistributors.store');
        Route::get('/show/{id}', [MasterController::class, 'show__underdistributors'])->name('underdistributors.show');
    });

    Route::prefix('reloads')->group(function () {
        Route::get('/browse', [MasterController::class, 'browse_reloads'])->name('underdistributors.browse_reloads');
    });

    Route::prefix('unloads')->group(function () {
        Route::get('/browse', [MasterController::class, 'browse_dumps'])->name('underdistributors.browse_dumps');
    });




    Route::prefix('custumers')->group(function () {
        Route::get('/browse', [MasterController::class, 'browse_custumers'])->name('custumers.masters.browse');
        Route::get('/add', [MasterController::class, 'add_custumers'])->name('custumers.masters.add');
        Route::get('/show/{id}', [MasterController::class, 'show__custumers'])->name('custumers.masters.show');
    });


    //Statisques
    Route::prefix('statistics')->group(function () {
        Route::get('/browse_unders', [StatistiqueController::class, 'browse_state_masters'])->name('underdistributors.transactions.historiques.states');
        Route::get('/browse_custumers', [StatistiqueController::class, 'browse_state_custumers'])->name('masters.transactions.historiques.states');
    });

    //Historical
    Route::prefix('historicals')->group(function () {
        Route::get('/transfers_historicals', [HistoricalController::class, 'browse_transfer'])->name('masters.transfers.historical');
        Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical_company'])->name('masters.transfers.historical.reporting_transfer_historical');
    });

    //Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/transfers/{transfers_id}', [InvoiceController::class, 'transfers_uv_company'])->name('companies.transfers.invoice.print');
    });

    //Account managment
    Route::prefix('account')->group(function () {

        Route::get('/', [MasterController::class, 'showprofile'])->name('masters.account.profile');
        Route::get('/showprofil/{id}', [MasterController::class, 'ShowProfil_masters'])->name('masters.ShowProfil');
        Route::get('/security/update', [MasterController::class, 'security'])->name('masters.account.security');
        Route::post('/read/{id}/update', [MasterController::class, 'updateProfil'])->name('masters.account.showUpdate');
        Route::post('update-password', [MasterController::class, 'updatePassword'])->name('masters.account.security.update');
        Route::post('/updateProfil',  [MasterController::class, 'updateprofil'])->name('masters.account.update');

        // NOTIFY  
        Route::get('/liste_notifications', [MasterController::class, 'get_notification'])->name('admin.show.get_notification');
        Route::get('/notifications', [MasterController::class, 'get_notifications'])->name('admin.show.notifications');
        Route::get('/get-notifications', [MasterController::class, 'get_unread_notifications'])->name('admin.get.unread.notifications');
        Route::get('/make-notifications-as-read', [MasterController::class, 'make_notifications_as_read'])->name('admin.make.notifications.as.read');
        Route::get('/make_read_all', [MasterController::class, 'make_read_all'])->name('admin.make.make_read_all');
        Route::get('/make_read_single/{id}', [MasterController::class, 'make_read_single'])->name('admin.make.make_read_single');
    });
});
