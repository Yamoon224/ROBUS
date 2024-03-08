<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\AdminHistoriqueController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ContactListController;
use App\Http\Controllers\CountrieController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PaimentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Historical\UnderHistorical\PaymentHisto;
use App\Http\Livewire\Login;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




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

Auth::routes(['register' => false]);

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

//User Admin
Route::group(['prefix' => 'privacy'], function () {
    // GET
    Route::view('/robus', 'pages.admin.pages.privacy')->name('privacy.robus');
});

//User Admin
Route::group(['prefix' => 'compagnie'], function () {
    Route::view('/robus', 'auth.login_companie')->name('admin.login_companie');
});

Route::group(['prefix' => 'masters'], function () {
    Route::view('/robus', 'auth.login_master')->name('admin.login_master');
});

Route::group(['prefix' => 'comzones'], function () {
    Route::view('/robus', 'auth.login_com_zone')->name('admin.login_com_zone');
});

Route::group(['prefix' => 'robus'], function () {
    // GET
    Route::view('/login', 'auth.login')->name('admin.login');
    Route::post('/check', [AdminController::class, 'check'])->name('admin.check');
});

Route::group(['prefix' => ''], function () {


    Route::group(['middleware' => ['auth:web']], function () {


        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');

        Route::get('/logout', [Login::class, 'logout'])->name('admin.logout');

        //Account managment
        Route::prefix('account')->group(function () {

            Route::get('/', [AdminController::class, 'showProfile'])->name('account.profile');
            Route::get('/manager', [AdminController::class, 'showProfileManager'])->name('account.profile.manager');
            Route::get('/security/update', [AdminController::class, 'security'])->name('account.security');
            Route::get('/security', [AdminController::class, 'securityManager'])->name('account.security.manager');
            Route::post('/updateProfilManager',  [AdminController::class, 'updateProfilManager'])->name('account.update.manager');
            Route::post('/updateProfil',  [AdminController::class, 'updateProfil'])->name('account.update');
            Route::post('update-password', [AdminController::class, 'UpdatePassword'])->name('account.security.update');
            Route::get('gains/comissions', [AdminController::class, 'browse_gain'])->name('admin.browse_gain');
            Route::get('gains/distributers', [AdminController::class, 'browse_gain_distributer'])->name('admin.browse_gain_distributer');
            Route::get('gains/under_distributers', [AdminController::class, 'browse_gain_under_distributer'])->name('admin.browse_gain_under_distributer');

            // NOTIFY  
            Route::get('/liste_notifications', [AdminController::class, 'get_notification'])->name('admin.show.get_notification');
            Route::get('/notifications', [AdminController::class, 'get_notifications'])->name('admin.show.notifications');
            Route::get('/get-notifications', [AdminController::class, 'get_unread_notifications'])->name('admin.get.unread.notifications');
            Route::get('/make-notifications-as-read', [AdminController::class, 'make_notifications_as_read'])->name('admin.make.notifications.as.read');
            Route::get('/notifsallRead', [AdminController::class, 'notifs_read_all'])->name('admin.make.makeReadAll');
            Route::get('/make_read_message', [AdminController::class, 'make_read_all_message'])->name('admin.make.make_read_message');
            Route::get('/make_read_single/{id}', [AdminController::class, 'notif_read_single'])->name('admin.make.makeReadSingle');
        });

        //masters
        Route::prefix('masters')->group(function () {
            Route::get('/browse', [MasterController::class, 'browse_masters'])->name('masters.admins.browse');
            Route::get('/add', [MasterController::class, 'add_masters'])->name('masters.admins.add');
            Route::get('/add_master',  [AdminController::class, 'add_master_zone'])->name('masters.admin.add_master_com');
            Route::post('/store', [MasterController::class, 'store_masters'])->name('masters.admin.store');
            Route::get('/show/{id}', [MasterController::class, 'show_masters'])->name('masters.admins.show');
            Route::post('/update/{id}', [MasterController::class, 'update_masters'])->name('masters.admins.update');
        });

        // Valuer Versement
        Route::prefix('levels')->group(function () {
            Route::get('/browse_level_bank', [LevelController::class, 'browse_levels_bank'])->name('levels.browse_levels_bank');
            Route::get('/browse_level_ivoire', [LevelController::class, 'browse_levels_ivoire'])->name('levels.browse_levels_ivoire');
            Route::get('/browse_levels_custumers', [LevelController::class, 'browse_levels_custumers'])->name('levels.browse_levels_custumers');
            Route::get('/browse_level_deposit', [LevelController::class, 'browse_levels_deposit'])->name('levels.browse_levels_deposit');
            Route::get('/browse_level_deposit_ivoire', [LevelController::class, 'browse_levels_deposit_ivoire'])->name('levels.browse_levels_deposit_ivoire');
            Route::get('/browse_level_deposit_under', [LevelController::class, 'browse_levels_deposit_ivoire_under'])->name('levels.browse_levels_deposit_ivoire_under');
        });
        //   Cards
        Route::prefix('cards')->group(function () {
            Route::get('/browse', [CardController::class, 'browse'])->name('cards.browse');
            Route::get('/add', [CardController::class, 'add'])->name('cards.add');
            Route::post('/store', [CardController::class, 'store'])->name('cards.store');
            Route::get('/show/{id}', [CardController::class, 'show'])->name('cards.show');
            Route::get('/edit/{id}', [CardController::class, 'update'])->name('cards.edit');
            Route::post('/update/{id}', [CardController::class, 'update_cards'])->name('cards.update_cards');
            Route::get('/cards/{id}/activate', [CardController::class, 'activate'])->name('cards.activate');
            Route::get('/read/{id}/delete', [CardController::class, 'delete'])->name('cards.delete');
        });

        //Com zones
        Route::prefix('comzones')->group(function () {
            Route::get('/browse', [UserController::class, 'browse_comzone'])->name('comzones.admins.browse');
            Route::get('/add', [AdminController::class, 'add_com_zone'])->name('comzones.admins.add');
            Route::post('/store', [MasterController::class, 'store_comzones'])->name('comzones.admin.store');
            Route::get('/show/{id}', [MasterController::class, 'show_comzone'])->name('comzones.admins.show');
            Route::post('/update/{id}', [MasterController::class, 'update_comzone'])->name('comzones.admins.update');
        });

        //Under distributors
        Route::prefix('underdistributors')->group(function () {
            Route::get('/browse', [UserController::class, 'browseunders'])->name('unders.admins.browse');
            Route::get('/add', [AdminController::class, 'addunders'])->name('unders.admins.add');
            Route::post('/store', [MasterController::class, 'storeunders'])->name('unders.admin.store');
            Route::get('/show/{id}', [AdminController::class, 'showunders'])->name('unders.admins.show');
            Route::post('/update/{id}', [MasterController::class, 'updateunders'])->name('unders.admins.update');
        });

        // distributors
        Route::prefix('distribitors')->group(function () {
            Route::get('/browse', [UserController::class, 'browsedistributors'])->name('operators.admins.browse');
            Route::get('/add', [AdminController::class, 'adddistributors'])->name('operators.admins.add');
            Route::post('/store', [MasterController::class, 'storedistributors'])->name('operators.admin.store');
            Route::get('/show/{id}', [AdminController::class, 'showdistributors'])->name('operators.admins.show');
            Route::post('/update/{id}', [MasterController::class, 'updatedistributors'])->name('operators.admins.update');
        });

        //   Users
        Route::prefix('users')->group(function () {
            Route::get('/browse', [UserController::class, 'browse'])->name('custumers.browse');
            Route::get('/browse_company', [UserController::class, 'browse_company'])->name('company.browse');
            // Route::get('/browse_comzone', [UserController::class, 'browse_comzone'])->name('comzone.browse');
            Route::get('/browse_invalidated', [UserController::class, 'browse_invalidated'])->name('custumers.browse_invalidated');
            Route::get('/show/{id}', [UserController::class, 'show'])->name('custumers.show');
            Route::get('/show_company/{id}', [UserController::class, 'show_company'])->name('company.show_company');
            Route::get('/show_comzone/{id}', [UserController::class, 'show_comzone'])->name('comzone.show_comzone');
            Route::post('/update_custumers/{id}', [UserController::class, 'update_custumers'])->name('custumers.update_custumers');
            Route::post('/update_silver_ceiling/{id}', [UserController::class, 'update_silver_ceiling'])->name('custumers.update_silver_ceiling');
            Route::post('/admin_silver_ceiling/{id}', [UserController::class, 'update_silver_admin_ceiling'])->name('custumers.update_silver_admin_ceiling');
            Route::get('/read/{id}/delete', [UserController::class, 'delete'])->name('custumers.delete');
            Route::get('/user/{id}/activate', [UserController::class, 'activate'])->name('custumers.activate');
            Route::get('/user/{id}/activate_wallet', [UserController::class, 'activate_wallet'])->name('custumers.activate_wallet');
        });

        //   Transactions
        Route::prefix('transactions')->group(function () {
            Route::get('/browse', [TransactionController::class, 'browse'])->name('transactions.browse');
            Route::get('/uv', [TransactionController::class, 'browse_uv'])->name('transactions.uv.browse');
            Route::get('/show/{id}', [TransactionController::class, 'show'])->name('transactions.show');
            Route::get('/read/{id}/delete', [TransactionController::class, 'delete'])->name('transactions.delete');
            Route::get('/historiques', [TransactionController::class, 'browse_historique'])->name('transactions.historiques');
        });


        //   Paiments
        Route::prefix('paiments')->group(function () {
            Route::get('/search', [PaimentController::class, 'search'])->name('paiments.search');
            Route::get('/browse', [PaimentController::class, 'browse'])->name('paiments.browse');
            Route::get('/show/{id}', [PaimentController::class, 'show'])->name('paiments.show');
            Route::get('/resend/{id}', [PaimentController::class, 'resend'])->name('paiements.rensend');
            Route::get('/read/{id}/delete', [PaimentController::class, 'delete'])->name('paiments.delete');
        });

        // Countries
        Route::group(['prefix' => 'countries'], function () {
            Route::get('/cities/{id}',  [CountrieController::class, 'countrie_cities']);
            Route::get('/add',  [CountrieController::class, 'add'])->name('countries.add');
            Route::post('/store', [CountrieController::class, 'store'])->name('countries.store');
            Route::get('/view/{key}', [CountrieController::class, 'Show'])->name('countries.show');
        });

        // Services
        Route::group(['prefix' => 'services'], function () {
            Route::get('/add',  [ServiceController::class, 'add'])->name('services.add');
            Route::post('/store',  [ServiceController::class, 'store'])->name('services.store');
            Route::get('/browse',  [ServiceController::class, 'browse'])->name('services.browse');
            Route::get('/show/{id}',  [ServiceController::class, 'edit'])->name('services.edit');
            Route::post('/update/{id}',  [ServiceController::class, 'update'])->name('services.update');
            Route::get('/offers',  [ServiceController::class, 'browse_offers'])->name('services.offers.browse');
        });
        // Countries Modify
        Route::group(['prefix' => 'list_countries'], function () {
            Route::get('/browse',  [CountrieController::class, 'browse'])->name('countries.browse');
            Route::get('/cities',  [CountrieController::class, 'cities'])->name('cities.browse');
            Route::get('/currencies',  [CountrieController::class, 'currencies'])->name('currencies.browse');
        });

        //   Costs Commissions
        Route::prefix('commissions')->group(function () {

            Route::post('/country_ids', [CommissionController::class, 'country_costs'])->name('costs.countrys_costs');
            // Bank Cost
            Route::get('/banks', [CommissionController::class, 'browse_banks'])->name('commissions.browse_banks');
            Route::get('/add_banks', [CommissionController::class, 'add_banks'])->name('commissions.add_banks');
            Route::get('/edit_banks', [CommissionController::class, 'edit_banks'])->name('commissions.edit_banks');
            Route::post('/store_banks', [CommissionController::class, 'store_banks'])->name('commissions.store_banks');

            // Payment Invoice
            Route::get('/payments', [CommissionController::class, 'browse_payments'])->name('commissions.browse_payments');
            Route::get('/payments/add', [CommissionController::class, 'add_payments'])->name('commissions.add_payments');
            Route::post('/payments/store', [CommissionController::class, 'store_payments'])->name('commissions.store_payments');
            Route::post('/countrys_amount', [CommissionController::class, 'country_amounts'])->name('commissions.country_amounts.payments');

            Route::get('/deposits', [CommissionController::class, 'browse_deposits'])->name('commissions.browse_deposits');
            Route::get('/inters', [CommissionController::class, 'browse_inters'])->name('commissions.browse_inters');
            Route::get('/phones', [CommissionController::class, 'browse_phones'])->name('commissions.browse_phones');
            Route::get('/reloads', [CommissionController::class, 'browse_reloads'])->name('commissions.browse_reloads');
            Route::get('/order_cards', [CommissionController::class, 'browse_orders'])->name('commissions.browse_orders');
            // Transactions
            Route::get('/transactions', [CommissionController::class, 'browse_transactions'])->name('commissions.browse_transactions');
            Route::get('/transactions/centre-afrique/mooney_civ', [CommissionController::class, 'browse_transactions_mooney_caf_civ'])->name('commissions.browse_transactions_mooney_caf_civ');
            Route::get('/transactions/centre-afrique/civ', [CommissionController::class, 'browse_transactions_civ_caf'])->name('commissions.browse_transactions_civ_caf');
            Route::get('/transactions/centre-afrique/inter_civ', [CommissionController::class, 'browse_transactions_inter_caf_civ'])->name('commissions.browse_transactions_inter_caf_civ');
            //1xbet Transactions
            Route::get('/onexbets', [CommissionController::class, 'browse_onexbets'])->name('commissions.browse_onexbets');
            // France
            Route::get('/transactions/france/mooney_civ', [CommissionController::class, 'browse_transactions_mooney_fr_civ'])->name('commissions.browse_transactions_mooney_fr_civ');
            Route::get('/transactions/france/civ', [CommissionController::class, 'browse_transactions_civ_fr'])->name('commissions.browse_transactions_civ_fr');
            Route::get('/transactions/france/inter_civ', [CommissionController::class, 'browse_transactions_inter_fr_civ'])->name('commissions.browse_transactions_inter_fr_civ');
            // Maroc
            Route::get('/transactions/maroc/inter_civ', [CommissionController::class, 'browse_transactions_inter_marco_civ'])->name('commissions.browse_transactions_inter_marco_civ');
            // Tunisie
            Route::get('/transactions/tunisie/inter_civ', [CommissionController::class, 'browse_transactions_inter_tunisie_civ'])->name('commissions.browse_transactions_inter_tunisie_civ');
        });

        Route::prefix('pays_commissions')->group(function () {

            Route::get('/west_africa', [CommissionController::class, 'browse_mobile_money'])->name('commissions.west_africa');
            Route::get('/centre_africa', [CommissionController::class, 'browse_centre_africa'])->name('commissions.centre_africa');
            Route::get('/europe', [CommissionController::class, 'browse_europe'])->name('commissions.europe');
            Route::get('/maghreb', [CommissionController::class, 'browse_maghredb'])->name('commissions.maghredb');
            Route::get('/usa', [CommissionController::class, 'browse_usa'])->name('commissions.usa');
        });

        //contacts
        Route::prefix('contacts')->group(function () {
            Route::get('/lists/brawse', [ContactListController::class, 'browse'])->name('contacts.list.browse');
            Route::get('/lists/show/{key}', [ContactListController::class, 'show'])->name('contacts.list.show');
        });

        //faqs
        Route::prefix('faqs')->group(function () {
            Route::get('/brawse', [FaqController::class, 'browse'])->name('faqs.browse');
        });

        //Operators
        Route::group(['prefix' => 'operators'], function () {
            // Route::get('/add',  [ServiceController::class, 'add_operators'])->name('operators.add');
            // Route::post('/store',  [ServiceController::class, 'store_operators'])->name('operators.store');
            Route::get('/browse',  [ServiceController::class, 'browse_operators'])->name('operators.browse');
            // Route::get('/show/{id}',  [ServiceController::class, 'edit_operators'])->name('operators.edit');
            // Route::post('/update/{id}',  [ServiceController::class, 'update_operators'])->name('operators.update');
        });

        Route::group(['prefix' => 'close_app'], function () {
            Route::get('/browse',  [ServiceController::class, 'browse_close_app'])->name('close_app.browse');
            Route::get('/browse_masters',  [ServiceController::class, 'browse_close_app_masters'])->name('browse_close_app_masters.browse');
            Route::get('/browse_comzones',  [ServiceController::class, 'browse_close_app_comzones'])->name('browse_close_app_comzones.browse');
        });

        //Invoices
        Route::prefix('invoices')->group(function () {
            Route::get('/rechargement/{reaload_id}', [InvoiceController::class, 'invoice_realod'])->name('reaload.invoice.print');
            Route::get('/transfers/{transfers_id}', [InvoiceController::class, 'transfers_uv'])->name('transfers.invoice.print');
            Route::get('/paiements/{paiements_id}', [InvoiceController::class, 'paiements_invoice'])->name('paiements.invoice.print');
            Route::get('/versements/{versements_id}', [InvoiceController::class, 'versements_invoice'])->name('virements.invoice.print');
            Route::get('/internationnal/{inters_id}', [InvoiceController::class, 'inters_invoice'])->name('inters.invoice.print');
            Route::get('/reload_cards/{relaod_card_id}', [InvoiceController::class, 'reload_card_invoice'])->name('reload_card.invoice.print');
            Route::get('/phone_credit/{phone_credit_id}', [InvoiceController::class, 'phone_credit_invoice'])->name('phone.invoice.print');
        });

        //Historical
        Route::prefix('historicals')->group(function () {
            Route::get('/reloads_historical', [HistoricalController::class, 'browse_reload'])->name('relaods.historical');
            Route::get('/transfers_historical', [HistoricalController::class, 'browse_transferfirm'])->name('transfers.historical');
            Route::get('/paiements_historical', [HistoricalController::class, 'browse_paiement'])->name('paiements.historical');
            Route::get('/virements_historical', [HistoricalController::class, 'browse_virement'])->name('virements.historical');

            // Reporting
            Route::get('virements/reporting_historical', [HistoricalController::class, 'reporting_historical'])->name('virements.historical.reporting_historical');
            Route::get('reloads/reporting_reload_historical', [HistoricalController::class, 'reporting_reload_historical'])->name('reloads.historical.reporting_reload_historical');
            Route::get('cards/reporting_card_historical', [HistoricalController::class, 'reporting_card_historical'])->name('cards.historical.reporting_card_historical');
            Route::get('inters/reporting_inter_historical', [HistoricalController::class, 'reporting_inter_historical'])->name('inters.historical.reporting_inter_historical');
            Route::get('payments/reporting_payment_historical', [HistoricalController::class, 'reporting_payment_historical'])->name('payments.historical.reporting_payment_historical');
            Route::get('phones/reporting_phone_historical', [HistoricalController::class, 'reporting_phone_historical'])->name('phones.historical.reporting_phone_historical');
            Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical'])->name('transfers.historical.reporting_transfer_historical');
            Route::get('drawer_under_distributer/reporting_drawer_historical', [HistoricalController::class, 'reporting_drawer_historical'])->name('transfers.historical.reporting_drawer_historical');

            Route::get('/inters_historical', [HistoricalController::class, 'browse_inter'])->name('inters.historical');
            Route::get('/cartes_historical', [HistoricalController::class, 'browse_carte'])->name('cartes.historical');
            Route::get('/phone_historical', [HistoricalController::class, 'browse_phone'])->name('phones.historical');
            Route::get('/xbet_historical', [HistoricalController::class, 'browse_xbets'])->name('xbets.historical');
            Route::get('/drawer_historical', [HistoricalController::class, 'browse_drawer'])->name('drawers.historical');
        });

        // Historique Sous distributer
        Route::prefix('under_commissions')->group(function () {


            Route::get('/wallet_payments/{key}', [CountrieController::class, 'show_under_payment'])->name('countries.payments.Show_under_distributer');
            Route::get('/wallet_transactions/{key}', [CountrieController::class, 'show_under_transaction'])->name('countries.transactions.Show_under_distributer');
            Route::get('/wallet_banks/{key}', [CountrieController::class, 'show_under_bank'])->name('countries.banks.Show_under_distributer');
            Route::get('/wallet_deposits/{key}', [CountrieController::class, 'show_under_deposit'])->name('countries.deposits.Show_under_distributer');
            Route::get('/wallet_reloads/{key}', [CountrieController::class, 'show_under_reload'])->name('countries.reloads.Show_under_distributer');
            Route::get('/wallet_inters/{key}', [CountrieController::class, 'show_under_inter'])->name('countries.inters.Show_under_distributer');
            Route::get('/wallet_phones/{key}', [CountrieController::class, 'sdrawer_historicalhow_under_phone'])->name('countries.phones.Show_under_distributer');


            Route::get('/view/{key}', [CountrieController::class, 'Show_under_distributer'])->name('countries.Show_under_distributer');

            // Reporting
            Route::get('virements/reporting_historical', [CommissionController::class, 'reporting_historical'])->name('under_commissions.virements.historical.reporting_historical');
            Route::get('reloads/reporting_reload_historical', [CommissionController::class, 'reporting_reload_historical'])->name('under_commissions.reloads.historical.reporting_reload_historical');
            Route::get('cards/reporting_card_historical', [CommissionController::class, 'reporting_card_historical'])->name('under_commissions.cards.historical.reporting_card_historical');
            Route::get('inters/reporting_inter_historical', [CommissionController::class, 'reporting_inter_historical'])->name('under_commissions.inters.historical.reporting_inter_historical');
            Route::get('payments/reporting_payment_historical', [CommissionController::class, 'reporting_payment_historical'])->name('under_commissions.payments.historical.reporting_payment_historical');
            Route::get('phones/reporting_phone_historical', [CommissionController::class, 'reporting_phone_historical'])->name('under_commissions.phones.historical.reporting_phone_historical');
            Route::get('transfers/reporting_transfer_historical', [CommissionController::class, 'reporting_transfer_historical'])->name('under_commissions.transfers.historical.reporting_transfer_historical');
        });

        // Historique  distributer
        Route::prefix('distributer_commissions')->group(function () {


            Route::get('/wallet_payments/{key}', [CountrieController::class, 'show_distri_payment'])->name('countries.payments.Show_distri_distributer');
            Route::get('/wallet_transactions/{key}', [CountrieController::class, 'show_distri_transaction'])->name('countries.transactions.Show_distri_distributer');
            Route::get('/wallet_banks/{key}', [CountrieController::class, 'show_distri_bank'])->name('countries.banks.Show_distri_distributer');
            Route::get('/wallet_deposits/{key}', [CountrieController::class, 'show_distri_deposit'])->name('countries.deposits.Show_distri_distributer');
            Route::get('/wallet_reloads/{key}', [CountrieController::class, 'show_distri_reload'])->name('countries.reloads.Show_distri_distributer');
            Route::get('/wallet_inters/{key}', [CountrieController::class, 'show_distri_inter'])->name('countries.inters.Show_distri_distributer');
            Route::get('/wallet_phones/{key}', [CountrieController::class, 'show_distri_phone'])->name('countries.phones.Show_distri_distributer');


            Route::get('/view/{key}', [CountrieController::class, 'Show_distri_distributer'])->name('countries.Show_distri_distributer');


            // Reporting days
            Route::get('virements/reporting_historical/{key}', [CommissionController::class, 'reporting_distri_historical'])->name('distri_commissions.virements.historical.reporting_historical');
            Route::get('reloads/reporting_reload_historical/{key}', [CommissionController::class, 'reporting_reload_distri_historical'])->name('distri_commissions.reloads.historical.reporting_reload_historical');
            Route::get('cards/reporting_card_historical/{key}', [CommissionController::class, 'reporting_card_distri_historical'])->name('distri_commissions.cards.historical.reporting_card_historical');
            Route::get('inters/reporting_inter_historical', [CommissionController::class, 'reporting_inter_distri_historical'])->name('distri_commissions.inters.historical.reporting_inter_historical');
            Route::get('payments/reporting_payment_historical/{key}', [CommissionController::class, 'reporting_payment_distri_historical'])->name('distri_commissions.payments.historical.reporting_payment_historical');
            Route::get('phones/reporting_phone_historical/{key}', [CommissionController::class, 'reporting_phone_distri_historical'])->name('distri_commissions.phones.historical.reporting_phone_historical');
            Route::get('transfers/reporting_transfer_historical/{key}', [CommissionController::class, 'reporting_transfer_distri_historical'])->name('distri_commissions.transfers.historical.reporting_transfer_historical');

            //  All Reporting
            Route::get('virement_alls/reporting_historical/{key}', [CommissionController::class, 'reporting_distri_all_historical'])->name('distri_commissions.virement_alls.historical.reporting_historical');
            Route::get('reload_alls/reporting_reload_historical/{key}', [CommissionController::class, 'reporting_reload_distri_all_historical'])->name('distri_commissions.reload_alls.historical.reporting_reload_historical');
            Route::get('card_alls/reporting_card_historical/{key}', [CommissionController::class, 'reporting_card_distri_all_historical'])->name('distri_commissions.card_alls.historical.reporting_card_historical');
            Route::get('inter_alls/reporting_inter_historical/{key}', [CommissionController::class, 'reporting_inter_distri_all_historical'])->name('distri_commissions.inter_alls.historical.reporting_inter_historical');
            Route::get('payment_alls/reporting_payment_historical/{key}', [CommissionController::class, 'reporting_payment_distri_all_historical'])->name('distri_commissions.payment_alls.historical.reporting_payment_historical');
            Route::get('phone_alls/reporting_phone_historical/{key}', [CommissionController::class, 'reporting_phone_distri_all_historical'])->name('distri_commissions.phone_alls.historical.reporting_phone_historical');
            Route::get('transfer_alls/reporting_transfer_historical/{key}', [CommissionController::class, 'reporting_transfer_distri_all_historical'])->name('distri_commissions.transfer_alls.historical.reporting_transfer_historical');
        });

        //Statisques
        Route::prefix('statistics')->group(function () {
            Route::get('/browse', [StatistiqueController::class, 'browse_states'])->name('browse.states');
            Route::get('/reloads_state', [StatistiqueController::class, 'browse_reload'])->name('relaods.states');
            Route::get('/transfers_state', [StatistiqueController::class, 'browse_transfer'])->name('transfers.states');
            Route::get('/paiements_state', [StatistiqueController::class, 'browse_paiement'])->name('paiements.states');
            Route::get('/virements_state', [StatistiqueController::class, 'browse_virement'])->name('virements.states');
            Route::get('/inters_state', [StatistiqueController::class, 'browse_inter'])->name('inters.states');
            Route::get('/cartes_state', [StatistiqueController::class, 'browse_carte'])->name('cartes.states');
            Route::get('/phone_state', [StatistiqueController::class, 'browse_phone'])->name('phones.states');
        });


        //Historical
        Route::prefix('rapports_users')->group(function () {

            // Reporting
            Route::get('virements/reporting_historical/{id}', [AdminHistoriqueController::class, 'reporting_historical'])->name('virements.historical.reporting_historical.admins');
            Route::get('reloads/reporting_reload_historical/{id}', [AdminHistoriqueController::class, 'reporting_reload_historical'])->name('reloads.historical.received.reporting_reload_historical.admins');
            Route::get('reloads/reporting_reload_historical_received/{id}', [AdminHistoriqueController::class, 'reporting_reload_received_historical'])->name('reloads.historical.reporting_reload_historical.admins');
            Route::get('cards/reporting_card_historical/{id}', [AdminHistoriqueController::class, 'reporting_card_historical'])->name('cards.historical.reporting_card_historical.admins');
            Route::get('inters/reporting_inter_historical/{id}', [AdminHistoriqueController::class, 'reporting_inter_historical'])->name('inters.historical.reporting_inter_historical.admins');
            // Reporting in date transactions
            Route::get('inter_days/reporting_inter_days_historical', [AdminHistoriqueController::class, 'searchByIntersDate'])->name('inters.historical.reporting_inter_days_historical.admins');
            Route::get('transactions_days/reporting_transaction_days_historical', [AdminHistoriqueController::class, 'searchByTransactionsDate'])->name('inters.historical.reporting_transaction_days_historical.admins');
            Route::get('payment_days/reporting_payment_days_historical', [AdminHistoriqueController::class, 'searchByPaymentsDate'])->name('inters.historical.reporting_payment_days_historical.admins');
            Route::get('banks_days/reporting_payment_days_historical', [AdminHistoriqueController::class, 'searchByBanksDate'])->name('inters.historical.reporting_bank_days_historical.admins');
            Route::get('xbets_days/reporting_payment_days_historical', [AdminHistoriqueController::class, 'searchByXbetsDate'])->name('inters.historical.reporting_xbet_days_historical.admins');
            Route::get('cartes_days/reporting_cartes_days_historical', [AdminHistoriqueController::class, 'searchByCartesDate'])->name('inters.historical.reporting_cartes_days_historical.admins');

            Route::get('payments/reporting_payment_historical/{id}', [AdminHistoriqueController::class, 'reporting_payment_historical'])->name('payments.historical.reporting_payment_historical.admins');
            Route::get('phones/reporting_phone_hisnOTItorical/{id}', [AdminHistoriqueController::class, 'reporting_phone_historical'])->name('phones.historical.reporting_phone_historical.admins');
            Route::get('transfers/reporting_transfer_historical/{id}', [AdminHistoriqueController::class, 'reporting_transfer_historical'])->name('transfers.historical.reporting_transfer_historical.admins');
            Route::get('transfers/reporting_transfer_historical_received/{id}', [AdminHistoriqueController::class, 'reporting_transfer_received_historical'])->name('transfers.historical.received.reporting_transfer_historical.admins');
            Route::get('echecs/reporting_echecs_historical_echecs/{id}', [AdminHistoriqueController::class, 'reporting_transfer_echecs_historical'])->name('echecs.historical.reporting_transfer_historical.admins');
            Route::get('drawer_under_distributer/reporting_drawer_historical/{id}', [AdminHistoriqueController::class, 'reporting_drawer_historical'])->name('transfers.historical.reporting_drawer_historical.admins');
        });
    });
});

// Registers 
Route::group(['prefix' => 'under_distributers'], function () {
    Route::get('/registers',  [AdminController::class, 'add_under_distributer'])->name('admin.add_under_distributer');
    Route::get('/step_two',  [AdminController::class, 'add_under_distributer_two'])->name('admin.add_under_distributer_two');
    Route::post('/store_step_one', [AdminController::class, 'store_under_distributerOne'])->name('admin.store_under_distributer');
    Route::post('/store_step_two', [AdminController::class, 'store_under_distributerTwo'])->name('admin.store_under_distributer_two');
});

// Countries
Route::get('countrie_distributers/cities/{id}',  [CountrieController::class, 'countrie_cities']);


Route::group(['prefix' => 'recover'], function () {
    Route::get('admin/reset/password', [ForgotPasswordController::class, 'showAdminRecover'])->name('recoverAdmin');
    Route::post('/forget_password', [ForgotPasswordController::class, 'reset_admin_password'])->name('adminRecover.password');
    Route::get('admin/reset/{token}', [ForgotPasswordController::class, 'showAdminResetPassword'])->name('resetAdmin');
    Route::post('admin/password', [ForgotPasswordController::class, 'submitAdminResetPassword'])->name('reset.admin.password');
});

Route::get('clear-config-robus',  function () {
    Artisan::call('config:clear');
    return "Config clear";
});
Route::get('clear-route-robus',  function () {
    Artisan::call('config:cache');
    return "Config setting update";
});


Route::get('clear-cache-robus',  function () {
    Artisan::call('config:cache');
    return "Config setting update";
});

Route::get('clear-view-mon_bara',  function () {
    Artisan::call('view:clear');
    return "Config cliar view";
});

Route::get('clear-route-mon_bara',  function () {
    Artisan::call('route:cache');
    return "Config clear route";
});

Route::get('/reads_notifications', function () {
    return Artisan::call('status:readNotifications');
});

Route::get('/reads_applications', function () {
    
    Artisan::call('app:close-status');
    Artisan::call('close-comzone-status');
    Artisan::call('app:master-status');


    return 'Artisan commands executed successfully.';
});


Auth::routes();

// Route::resource('projects', 'ProjectsController');