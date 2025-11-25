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
    Route::get('prospect/documents',  [IntegrationController::class, 'prospect_documents'])->name('web.prospect_documents');
    Route::get('prospect/contact',  [IntegrationController::class, 'prospect_contact'])->name('web.prospect_contact');
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
    Route::prefix('historicals/prospects')->group(function () {
        
        Route::get('/transfers_historicals', [HistoricalController::class, 'browse_propect_transfer'])->name('propects.transferHistos.historical');
        Route::get('/reloads_historical', [HistoricalController::class, 'browse_reload'])->name('propects.relaods.historical');
        Route::get('/transfers_historical', [HistoricalController::class, 'browse_transferfirm'])->name('propects.transfers.historical');
        Route::get('/paiements_historical', [HistoricalController::class, 'browse_paiement'])->name('propects.paiements.historical');
        Route::get('/virements_historical', [HistoricalController::class, 'browse_virement'])->name('propects.virements.historical');

        // Reporting
        Route::get('virements/reporting_historical', [HistoricalController::class, 'reporting_historical'])->name('propects.virements.historical.reporting_historical');
        Route::get('reloads/reporting_reload_historical', [HistoricalController::class, 'reporting_reload_historical'])->name('propects.reloads.historical.reporting_reload_historical');
        Route::get('cards/reporting_card_historical', [HistoricalController::class, 'reporting_card_historical'])->name('propects.cards.historical.reporting_card_historical');
        Route::get('inters/reporting_inter_historical', [HistoricalController::class, 'reporting_inter_historical'])->name('propects.inters.historical.reporting_inter_historical');
        Route::get('payments/reporting_payment_historical', [HistoricalController::class, 'reporting_payment_historical'])->name('propects.payments.historical.reporting_payment_historical');
        Route::get('phones/reporting_phone_historical', [HistoricalController::class, 'reporting_phone_historical'])->name('propects.phones.historical.reporting_phone_historical');
        Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical'])->name('propects.transfers.historical.reporting_transfer_historical');
        Route::get('drawer_under_distributer/reporting_drawer_historical', [HistoricalController::class, 'reporting_drawer_historical'])->name('propects.transfers.historical.reporting_drawer_historical');

        // New Providers wallets transactions
        Route::get('pushs/reporting_push_historical', [HistoricalController::class, 'reporting_push_historical'])->name('propects.push.historical.reporting_push_historical');
        Route::get('versments/reporting_versment_historical', [HistoricalController::class, 'reporting_versment_historical'])->name('propects.versments.historical.reporting_versment_historical');
        Route::get('djamos/reporting_djamo_historical', [HistoricalController::class, 'reporting_djamo_historical'])->name('propects.djamos.historical.reporting_djamo_historical');
        Route::get('tresort_money/reporting_tresort_historical', [HistoricalController::class, 'reporting_tresort_historical'])->name('propects.tresort_money.historical.reporting_tresort_historical');

        Route::get('/inters_historical', [HistoricalController::class, 'browse_inter'])->name('propects.inters.historical');
        Route::get('/cartes_historical', [HistoricalController::class, 'browse_carte'])->name('propects.cartes.historical');
        Route::get('/phone_historical', [HistoricalController::class, 'browse_phone'])->name('propects.phones.historical');
        Route::get('/xbet_historical', [HistoricalController::class, 'browse_xbets'])->name('propects.xbets.historical');
        Route::get('/wave_historical', [HistoricalController::class, 'browse_waves'])->name('propects.waves.historical');
        Route::get('/drawer_historical', [HistoricalController::class, 'browse_drawer'])->name('propects.drawers.historical');

        Route::get('/versment_historical', [HistoricalController::class, 'browse_versments'])->name('propects.versments.historical');
        Route::get('/push_historical', [HistoricalController::class, 'browse_push'])->name('propects.pushs.historical');
        Route::get('/djamo_historical', [HistoricalController::class, 'browse_djamo'])->name('propects.djamos.historical');
        Route::get('/tresort_historical', [HistoricalController::class, 'browse_tresort'])->name('propects.tresorts.historical');
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
