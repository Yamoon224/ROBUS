<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CountrieController;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransactionController;
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


Route::group(['middleware' => ['auth:web']], function () {


        Route::group(['prefix' => 'visitors'], function () {

                Route::get('gains/comissions_visitors', [AdminController::class, 'browse_gain_visitors'])->name('visitors.admin.browse_gain');
                Route::get('/transactions', [TransactionController::class, 'browse_visitors'])->name('transactions.visitors');
                Route::get('/historicals', [TransactionController::class, 'browse_historique_visitors'])->name('transactions.historiques.visitors');
                Route::get('services/countries',  [ServiceController::class, 'browse_visitores'])->name('services.browse_visitores');
                Route::get('services/showVisitors/{id}',  [ServiceController::class, 'edit_visitors'])->name('services.edit_visitors');
                Route::post('services/update_web/{id}',  [ServiceController::class, 'updateWeb'])->name('services.update.web');
                Route::get('pays_visitors/visitors', [CommissionController::class, 'browse_visitors'])->name('commissions.browse_visitors');

                // Visitors Historicals
                Route::get('visitors/inters_historical', [HistoricalController::class, 'browse_inter_visitors'])->name('visitors.inters.historical');
                Route::get('visitors/cartes_historical', [HistoricalController::class, 'browse_carte_visitors'])->name('visitors.cartes.historical');
                Route::get('visitors/phone_historical', [HistoricalController::class, 'browse_phone_visitors'])->name('visitors.phones.historical');
                Route::get('visitors/xbet_historical', [HistoricalController::class, 'browse_xbets_visitors'])->name('visitors.xbets.historical');
                Route::get('visitors/transfers_historical', [HistoricalController::class, 'browse_transfert_visitors'])->name('visitors.transfers.historical');


              
        });

          // New web Visitors
          Route::prefix('distributers_visitors')->group(function () {

                Route::get('/wallet_transactions/{key}', [CountrieController::class, 'show_visitors_transaction'])->name('visitors.countries.transactions.Show_visitors_distributer');
                Route::get('/wallet_deposits/{key}', [CountrieController::class, 'show_visitors_deposit'])->name('visitors.countries.deposits.Show_visitors_distributer');
                Route::get('/wallet_inters/{key}', [CountrieController::class, 'show_visitors_inter'])->name('visitors.countries.inters.Show_visitors_distributer');
                Route::get('/wallet_phones/{key}', [CountrieController::class, 'show_visitors_phone'])->name('visitors.countries.phones.Show_visitors_distributer');
                Route::get('/wallet_xbest/{key}', [CountrieController::class, 'show_visitors_xbets'])->name('visitors.countries.xbets.Show_visitors_distributer');
                Route::get('/view/{key}', [CountrieController::class, 'Show_distri_visitors_distributer'])->name('visitors.countries.Show_distri_distributer');
        });
        
        Route::group(['prefix' => 'visitors'], function () {
                Route::get('/countries',  [CountrieController::class, 'browse_visitors'])->name('countries.visitors');
        });
});
