<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CitieController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CountrieController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\ConverterController;
use App\Http\Controllers\Api\CurrencieController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Transfers\ReloadController;
use App\Http\Controllers\Api\Transfers\PaymentController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Transfers\BankCardController;
use App\Http\Controllers\Api\Transfers\VirementController;
use App\Http\Controllers\Api\Transfers\ReloadCardController;
use App\Http\Controllers\Api\Auth\AuthentificationController;
use App\Http\Controllers\Api\Transfers\PhoneCreditController;
use App\Http\Controllers\Api\Transfers\TransactionController;
use App\Http\Controllers\Api\Providers\InterProviderController;
use App\Http\Controllers\Api\Providers\PhoneProviderController;
use App\Http\Controllers\Api\Transfers\IntersPaymentController;
use App\Http\Controllers\Api\Providers\ReloadProviderController;
use App\Http\Controllers\Api\Providers\PaymentProviderController;
use App\Http\Controllers\Api\Providers\VirementProviderController;
use App\Http\Controllers\Api\Providers\ReloadCardProvideController;
use App\Http\Controllers\Api\Providers\TransactionProviderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::prefix('auth')->group(function () {

        Route::post('signUp', [AuthentificationController::class, 'signUp']);
        Route::post('login', [AuthentificationController::class, 'login']);
        Route::post('merchants/login', [AuthentificationController::class, 'loginAdmin']);
        Route::post('verify', [AuthentificationController::class, 'verify']);
        Route::post('resendCode', [AuthentificationController::class, 'resendCode']);
        Route::post('active_account', [AuthentificationController::class, 'activatePhone']);

        // Route::post('password/create', [PasswordResetController::class, 'create']);
        Route::post('password/reset', [PasswordResetController::class, 'reset']);

        Route::middleware('auth:api')->group(function () {
            Route::get('user', [AuthentificationController::class, 'user']);
            Route::post('logout', [AuthentificationController::class, 'logout']);
        });
    });
 
    Route::middleware(['auth:api'])->group(function () {

        Route::prefix('user')->group(function () {

            Route::get('dashboard', [UserController::class, 'dashboard']);
            Route::get('info', [UserController::class, 'info']);
            Route::get('info/kyc', [UserController::class, 'kyc']);
            Route::post('info/update', [UserController::class, 'updateInfo']);
            Route::post('info/active_compte', [UserController::class, 'updload_identity']);

            // Virements
            Route::post('virements/store', [VirementController::class, 'create_virement']);
            Route::get('virement/show/{id}', [VirementController::class, 'virement_show']);
            Route::post('virement/delete/{id}', [VirementController::class, 'delete_virement']);
            Route::get('search_virement/{amount_virment}', [VirementController::class, 'search_virement']);

            // Reload
            Route::post('reloads/store', [ReloadController::class, 'create_reload']);
            Route::get('reload/show/{id}', [ReloadController::class, 'reload_show']);
            Route::post('reload/delete/{id}', [ReloadController::class, 'delete_reload']);

            // Payments
            Route::post('payments/store', [PaymentController::class, 'create_payment']);
            Route::get('payment/show/{id}', [PaymentController::class, 'payment_show']);
            Route::post('payment/delete/{id}', [PaymentController::class, 'delete_payment']);
            Route::get('search_payment/{commission_id}', [PaymentController::class, 'search_payment']);
            Route::get('search_payment_offer/{commission_id}', [PaymentController::class, 'search_payment_offer']);


            // Reload cards
            Route::post('reload_cards/store', [ReloadCardController::class, 'create_reload_card']);
            Route::get('reload_card/show/{id}', [ReloadCardController::class, 'reload_card_show']);
            Route::post('reload_card/delete/{id}', [ReloadCardController::class, 'delete_reload_card']);
            Route::get('search_reload_card/{provider_id}/{commission_id}', [ReloadCardController::class, 'search_reload_card']);

            // Active cards
            Route::post('order_cards/store', [BankCardController::class, 'create_order_card']);
            Route::get('order_card/show/{id}', [BankCardController::class, 'order_card_show']);
            Route::post('order_cards_other/create', [BankCardController::class, 'create_order_card_other']);

            // Phone credit
            Route::post('phone_credits/store', [PhoneCreditController::class, 'create_phone_credit']);
            Route::get('phone_credit/show/{id}', [PhoneCreditController::class, 'phone_credit_show']);
            Route::post('phone_credit/delete/{id}', [PhoneCreditController::class, 'delete_phone_credit']);
            Route::get('search_phone_credit/{commission_id}', [PhoneCreditController::class, 'search_phone_credit']);

            // Inters
            Route::post('inters/store', [IntersPaymentController::class, 'create_inter']);
            Route::get('inter/show/{id}', [IntersPaymentController::class, 'inter_show']);
            Route::post('inter/delete/{id}', [IntersPaymentController::class, 'delete_inter']);
            Route::get('search_inter/{wallet_id}', [IntersPaymentController::class, 'search_transaction_inter']);
            // Route::get('search_payment_inter/{commission_id}', [IntersPaymentController::class, 'search_payment_robus_inter']);
            Route::get('paiement_invoice_inter/{invoice_commission_id}', [IntersPaymentController::class, 'paiement_invoice_inter']);
            Route::get('paiement_phone_credit_inter/{phone_credit_commission_id}', [IntersPaymentController::class, 'paiement_credit_inter']);
            Route::get('paiement_virement_inter/{paiement_virement_id}', [IntersPaymentController::class, 'paiement_virement_inter']);
            Route::get('paiement_reload_card_inter/{paiement_reload_card_id}', [IntersPaymentController::class, 'reload_card_inter']);
            Route::get('search_payment_mooney_inter/{paiement_mooney_id}', [IntersPaymentController::class, 'search_payment_mooney_inter']);
            Route::get('search_payment_inter_CIV/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_payment_inter_CIV']);
            Route::get('search_payment_inter_out/{country_id}/{paiement_amount}/{provider_out}', [IntersPaymentController::class, 'search_payment_robus_inter']);
            // Route::get('search_payment_inter_out_robus/{country_id}/{wallet}/{paiement_amount}', [IntersPaymentController::class, 'search_payment_robus_inter_out']);


            // Drawal transaction
            Route::post('transactions/first_step', [TransactionController::class, 'first_withdrawal']);
            Route::post('transactions/second_step', [TransactionController::class, 'second_withdrawal']);

            // Transactions Diff
            Route::post('transactions/store', [TransactionController::class, 'create_transaction']);
            // Route::get('transaction/show/{id}', [TransactionController::class, 'transaction_show']);
            Route::post('transaction/delete/{id}', [TransactionController::class, 'delete_transaction']);
            Route::get('search/{wallet_id}', [TransactionController::class, 'search_transaction']);
            // Route::get('search_user/{user_id}', [TransactionController::class, 'search_transaction_user']);
            Route::get('search_transaction/{commission_id}', [TransactionController::class, 'search_transaction_money_commission']);
            Route::get('search_transaction_robus_commission/{wallet}/{commission_id}', [TransactionController::class, 'search_transaction_commission_robus']);


            // Notifications
            Route::get('notifications', [NotificationController::class, 'index']);
            Route::get('notifications/{id}', [NotificationController::class, 'notification_show']);

            Route::get('/providers', [ProviderController::class, 'index']);
            Route::get('/offers', [ProviderController::class, 'offers']);

            // Comments
            Route::get('comments', [CommentController::class, 'index']);
            Route::post('comments/store', [CommentController::class, 'store']);
            Route::get('comment/show/{id}', [CommentController::class, 'show']);
            Route::post('comment/delete/{id}', [CommentController::class, 'delete']);

            Route::prefix('merchants')->group(function () {
                Route::get('historicals', [UserController::class, 'historical_distributer']);
                Route::get('historical/evolutions', [UserController::class, 'historical_distributer_evolution']);
                Route::get('historical/month_evolutions', [UserController::class, '']);
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
            });

             Route::get('/countries/inter', [CountrieController::class, 'index_inter_auth']);
             Route::get('/countries/inter/{id}', [CountrieController::class, 'index_selected_search']);

            Route::prefix('converters')->group(function () {
                Route::get('info_converters/{amount}/{currencency_name}', [ConverterController::class, 'converter_browse']);
            });
        });
    });



    Route::get('/faqs', [FaqController::class, 'index']);
    // Providersdasc
    // Route::get('transaction_providers', [TransactionProviderController::class, 'index']);
    // Route::get('inter_providers', [InterProviderController::class, 'index']);
    // Route::get('phone_providers', [PhoneProviderController::class, 'index']);
    // Route::get('payments_providers', [PaymentProviderController::class, 'index']);
    // Route::get('reload_card_providers', [ReloadCardProvideController::class, 'index']);
    // Route::get('reload_providers', [ReloadProviderController::class, 'index']);
    // Route::get('virement_providers', [VirementProviderController::class, 'index']);

    // Cities and Country
    Route::get('/countries', [CountrieController::class, 'index']);
    Route::get('/countrie/{id}', [CountrieController::class, 'index_search_country']);
    //Route::get('/countries/civ', [CountrieController::class, 'index_civ']);
    Route::get('/currencies', [CurrencieController::class, 'index']);
    
    // Cities and Country
    Route::post('/user/forget_password', [AuthentificationController::class, 'forgotPassword']);
    Route::get('/cities', [CitieController::class, 'index']);
});

Route::fallback(function () {
    return response()->json([
        'data' => [],
        'success' => false,
        'status' => 404,
        'message' => 'Invalid Route'
    ]);
});
