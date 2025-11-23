<?php

use App\Http\Controllers\Api\Auth\AuthentificationController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ConverterController;
use App\Http\Controllers\Api\CountrieController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\Providers\InterProviderController;
use App\Http\Controllers\Api\Providers\PaymentProviderController;
use App\Http\Controllers\Api\Providers\PhoneProviderController;
use App\Http\Controllers\Api\Providers\ReloadCardProvideController;
use App\Http\Controllers\Api\Providers\ReloadProviderController;
use App\Http\Controllers\Api\Providers\TransactionProviderController;
use App\Http\Controllers\Api\Providers\VirementProviderController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Integration
|--------------------------------------------------------------------------
|
| Routes protégées via Passport client_credentials
| Middleware: auth.api_client
|
*/

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('prospects/login', [AuthentificationController::class, 'loginProspect']);
    });

    Route::middleware(['auth.api_client', 'throttle:api'])->group(function () {

        Route::prefix('user')->group(function () {

            Route::get('dashboard', [UserController::class, 'dashboard']);
            Route::get('dashboardCommissions', [UserController::class, 'dashboardCommissions']);
            Route::get('blockCommissions', [UserController::class, 'BlockCommissions']);
            Route::get('historicals', [UserController::class, 'dashboard_historique']);

            // Notifications
            Route::get('notifications', [NotificationController::class, 'index']);
            Route::get('notifications/{id}', [NotificationController::class, 'notification_show']);

            Route::get('providers', [ProviderController::class, 'index']);
            Route::get('offers', [ProviderController::class, 'offers']);

            // Comments
            Route::get('comments', [CommentController::class, 'index']);
            Route::post('comments/store', [CommentController::class, 'store']);
            Route::get('comment/show/{id}', [CommentController::class, 'show']);
            Route::post('comment/delete/{id}', [CommentController::class, 'delete']);

            Route::prefix('merchants')->group(function () {
                Route::get('historicals', [UserController::class, 'historical_distributer']);
                Route::get('historical/evolutions', [UserController::class, 'historical_distributer_evolution']);
                Route::get('historical/month_evolutions', [UserController::class, 'historical_month_distributer']);
                Route::get('historical/year_evolutions', [UserController::class, 'historical_year_distributer']);
                Route::post('update_drawal/commission_drawal', [UserController::class, 'drawal_commissions']);
            });

            Route::prefix('providers')->group(function () {
                Route::get('transaction_providers', [TransactionProviderController::class, 'index']);
                Route::get('inter_providers', [InterProviderController::class, 'index']);
                Route::get('phone_providers', [PhoneProviderController::class, 'index']);
                Route::get('payments_providers', [PaymentProviderController::class, 'index']);
                Route::get('reload_card_providers', [ReloadCardProvideController::class, 'index']);
                Route::get('reload_providers', [ReloadProviderController::class, 'index']);
                Route::get('virement_providers', [VirementProviderController::class, 'index']);
                Route::get('orders_providers', [ReloadCardProvideController::class, 'indexOrders']);
                Route::get('xbets_providers', [ReloadCardProvideController::class, 'indexXbets']);
            });

            Route::get('countries/inter', [CountrieController::class, 'index_inter_auth']);
            Route::get('countries/inter/{id}', [CountrieController::class, 'index_selected_search']);

            Route::prefix('converters')->group(function () {
                Route::get('info_converters/{amount}/{currencency_name}', [ConverterController::class, 'converter_browse']);
            });

        });

    });

});

// Fallback route
Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
