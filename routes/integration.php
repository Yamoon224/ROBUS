<?php

use App\Http\Livewire\LoginCompany;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\Invoices\InvoiceController;

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

Route::group(['prefix' => 'robus'], function () {

    Route::get('prospect/register',  [IntegrationController::class, 'prospect_registers'])->name('web.prospect_registers');
    Route::post('prospect/store',  [IntegrationController::class, 'prospect_store'])->name('web.prospect_store.stores');
    Route::get('search-countrie/{id}',  [IntegrationController::class, 'searchCountries']);
});

Route::group(['middleware' => ['auth:integration']], function () {


    Route::group(['prefix' => 'prospects'], function () {
        Route::get('/browse', [IntegrationController::class, 'home_propects'])->name('propects.home');
        Route::get('/logout', [LoginCompany::class, 'logout_prospect'])->name('propects.logout');
    });


    //Statisques
    Route::prefix('statistics')->group(function () {
        Route::get('/prospect/browse', [StatistiqueController::class, 'browse_state_prospects'])->name('propects.transactions.historiques.states');
    });

    //Historical
    Route::prefix('historicals')->group(function () {
        Route::get('/transfers_historicals', [HistoricalController::class, 'browse_transfer'])->name('propects.transfers.historical');
        Route::get('/reloads_historicals', [HistoricalController::class, 'browse_reloads'])->name('propects.reloads.historical');
        Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical_company'])->name('propects.transfers.historical.reporting_transfer_historical');
        Route::get('reloads_days/reporting_reloads_days_historical', [HistoricalController::class, 'searchByReloadsDate'])->name('propects.historical.reporting_transaction_days_historical.companies');
    });

    //Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/transfers/{transfers_id}', [InvoiceController::class, 'transfers_uv_company'])->name('propects.transfers.invoice.print');
    });

    Route::prefix('commissions')->group(function () {
        Route::get('/browse', [IntegrationController::class, 'browse_commissions'])->name('propects.browse_commissions');
    });

    Route::prefix('integrations')->group(function () {
        Route::get('/browse', [IntegrationController::class, 'browse_integrations'])->name('propects.integrations');
    });

    Route::prefix('identifications')->group(function () {
        Route::get('/add', [IntegrationController::class, 'add_identifications'])->name('propects.identifications.add');
        Route::post('/update', [IntegrationController::class, 'update_identifications'])->name('propects.update_identifications');
        Route::get('/browse', [IntegrationController::class, 'browse_identifications'])->name('propects.identifications');
    });

    //Account managment account
    Route::prefix('prospect')->group(function () {
        Route::get('/profil', [AdminController::class, 'show_prospect_profile'])->name('propects.account.profile');
        Route::get('/showProfil/{id}', [AdminController::class, 'ShowProfil_propects'])->name('propects.ShowProfil');
        Route::get('/security/update', [AdminController::class, 'security_prospects'])->name('propects.account.security');
        Route::post('/read/{id}/update', [AdminController::class, 'showUpdate_companies'])->name('propects.showUpdate');
        Route::post('update-password', [AdminController::class, 'UpdatePassword_prospect'])->name('propects.account.security.update');
        Route::post('/updateProfil',  [AdminController::class, 'updateProspect_company'])->name('propects.account.update');
        Route::get('/liste_notifications', [AdminController::class, 'get_notification'])->name('admin.show.get_notification');
        Route::get('/notifications', [AdminController::class, 'get_notifications'])->name('admin.show.notifications');
        Route::get('/get-notifications', [AdminController::class, 'get_unread_notifications'])->name('admin.get.unread.notifications');
        Route::get('/make-notifications-as-read', [AdminController::class, 'make_notifications_as_read'])->name('admin.make.notifications.as.read');
        Route::get('/make_read_all', [AdminController::class, 'make_read_all'])->name('admin.make.make_read_all');
        Route::get('/make_read_single/{id}', [AdminController::class, 'make_read_single'])->name('admin.make.make_read_single');
    });
});
