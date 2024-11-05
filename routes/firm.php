<?php

use App\Http\Livewire\LoginCompany;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirmController;
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

Route::group(['middleware' => ['auth:company']], function () {


    Route::group(['prefix' => 'robus'], function () {
        Route::get('/browse', [AdminController::class, 'home_company'])->name('companies.home');
        Route::get('/logout', [LoginCompany::class, 'logout'])->name('companies.logout');
    });

    //   Users
    Route::prefix('employes')->group(function () {
        Route::get('/browse', [FirmController::class, 'browse_employes'])->name('employers.browse');
        Route::get('/add', [FirmController::class, 'add_employes'])->name('employers.add');
        Route::get('/show/{id}', [FirmController::class, 'show__employes'])->name('employes.show');
    });

    Route::prefix('underdistributors')->group(function () {
        Route::get('/browse', [FirmController::class, 'browse_underDistributors'])->name('underdistributors.browse');
        // Route::get('/add', [FirmController::class, 'add_employes'])->name('underdistributors.add');
        Route::get('/show/{id}', [FirmController::class, 'show__unders'])->name('underdistributors.show');
    });

    //   Users
    Route::prefix('categories')->group(function () {
        Route::get('/browse', [FirmController::class, 'browse_categories'])->name('categories.employers.browse');
    });


    //Statisques
    Route::prefix('statistics')->group(function () {
        Route::get('/browse', [StatistiqueController::class, 'browse_state_employes'])->name('employes.transactions.historiques.states');
    });

    //Historical
    Route::prefix('historicals')->group(function () {
        Route::get('/transfers_historicals', [HistoricalController::class, 'browse_transfer'])->name('employes.transfers.historical');
        Route::get('transfers/reporting_transfer_historical', [HistoricalController::class, 'reporting_transfer_historical_company'])->name('employers.transfers.historical.reporting_transfer_historical');
    });

    //Invoices
    Route::prefix('invoices')->group(function () {
        Route::get('/transfers/{transfers_id}', [InvoiceController::class, 'transfers_uv_company'])->name('companies.transfers.invoice.print');
    });

    //Account managment
    Route::prefix('account')->group(function () {

        Route::get('/', [AdminController::class, 'show_profile'])->name('employes.account.profile');
        Route::get('/showProfil/{id}', [AdminController::class, 'ShowProfil_employes'])->name('employes.ShowProfil');
        Route::get('/security/update', [AdminController::class, 'security_employers'])->name('employes.account.security');
        Route::post('/read/{id}/update', [AdminController::class, 'showUpdate_companies'])->name('employes.showUpdate');
        Route::post('update-password', [AdminController::class, 'UpdatePassword_companie'])->name('employes.account.security.update');
        Route::post('/updateProfil',  [AdminController::class, 'updateProfil_company'])->name('employers.account.update');
        Route::get('/liste_notifications', [AdminController::class, 'get_notification'])->name('admin.show.get_notification');
        Route::get('/notifications', [AdminController::class, 'get_notifications'])->name('admin.show.notifications');
        Route::get('/get-notifications', [AdminController::class, 'get_unread_notifications'])->name('admin.get.unread.notifications');
        Route::get('/make-notifications-as-read', [AdminController::class, 'make_notifications_as_read'])->name('admin.make.notifications.as.read');
        Route::get('/make_read_all', [AdminController::class, 'make_read_all'])->name('admin.make.make_read_all');
        Route::get('/make_read_single/{id}', [AdminController::class, 'make_read_single'])->name('admin.make.make_read_single');
    });
});
