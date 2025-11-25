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
use App\Http\Controllers\Api\Transfers\BankCardController;
use App\Http\Controllers\Api\Transfers\IntersPaymentController;
use App\Http\Controllers\Api\Transfers\OnexbetTransactionController;
use App\Http\Controllers\Api\Transfers\PaymentController;
use App\Http\Controllers\Api\Transfers\PhoneCreditController;
use App\Http\Controllers\Api\Transfers\ReloadCardController;
use App\Http\Controllers\Api\Transfers\ReloadController;
use App\Http\Controllers\Api\Transfers\TransactionController;
use App\Http\Controllers\Api\Transfers\VirementController;
use App\Http\Controllers\Api\Transfers\WaveTransactionController;
use App\Http\Controllers\Api\Transfers\withTransactionController;
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

    Route::middleware(['auth.api_client', 'throttle:api' , 'check.suspended'])->group(function () {

        Route::prefix('user')->group(function () {

            Route::get('dashboard', [UserController::class, 'dashboard']);
            Route::get('dashboardCommissions', [UserController::class, 'dashboardCommissions']);
            Route::get('blockCommissions', [UserController::class, 'BlockCommissions']);
            Route::get('historicals', [UserController::class, 'dashboard_historique']);

            // Notifications
            Route::get('notifications', [NotificationController::class, 'indexIntegrate']);
            Route::get('notifications/{id}', [NotificationController::class, 'notification_show']);

            Route::get('providers', [ProviderController::class, 'indexProvidersProspect']);
            Route::get('offers', [ProviderController::class, 'offers']);
        

            Route::prefix('merchants')->group(function () {
                Route::get('historicals', [UserController::class, 'historical_integrate']);
                Route::get('historical/evolutions', [UserController::class, 'historical_distributer_evolution_integrate']);
                Route::get('historical/month_evolutions', [UserController::class, 'historical_month_integrate']);
                Route::get('historical/year_evolutions', [UserController::class, 'historical_year_integrate']);
            });


          // Virements
            Route::post('virements/store', [VirementController::class, 'create_virement']);
            Route::get('virement/show/{id}', [VirementController::class, 'virement_show']);
            Route::post('virement/delete/{id}', [VirementController::class, 'delete_virement']);
            Route::get('search_virement/{amount_virment}', [VirementController::class, 'search_virement']);
            Route::get('search_virement_received/{amount_virment_received}', [VirementController::class, 'search_virement_received']);

            // 1Xbets
            Route::post('oneXbet/store', [OnexbetTransactionController::class, 'create_OnexbetTranaction']);
            Route::post('oneXbet/delete/{id}', [OnexbetTransactionController::class, 'delete_wallet_xbet']);
            Route::get('search_onexbet/{amount_wave}', [OnexbetTransactionController::class, 'search_xbet']);
            Route::get('search_onexbet_received/{amount_received}', [OnexbetTransactionController::class, 'search_xbet_received']);

            // WithTransactions
            Route::post('push/store', [withTransactionController::class, 'create_PushTransaction']);
            Route::post('djamo/store', [withTransactionController::class, 'create_DjamoTransaction']);
            Route::post('tresorts/store', [withTransactionController::class, 'create_TresortTransaction']);
            Route::post('push/delete/{id}', [withTransactionController::class, 'delete_wallet_Push']);
            Route::post('djamo/delete/{id}', [withTransactionController::class, 'delete_wallet_Djamo']);
            Route::post('tresorts/delete/{id}', [withTransactionController::class, 'delete_wallet_Tresort']);
            Route::get('search_push/{amount_send}', [withTransactionController::class, 'search_Push']);
            Route::get('search_djamo/{amount_send}', [withTransactionController::class, 'search_Djamo']);
            Route::get('search_tresorts/{amount_send}', [withTransactionController::class, 'search_Tresort']);
            Route::get('search_push_received/{amount_received}', [withTransactionController::class, 'search_received_Push']);
            Route::get('search_djamo_received/{amount_received}', [withTransactionController::class, 'search_received_Djamo']);
            Route::get('search_tresort_received/{amount_received}', [withTransactionController::class, 'search_receveid_Tresort']);


            // 1Xbets
            Route::post('wave/store', [WaveTransactionController::class, 'create_WaveTranaction']);
            Route::post('wave/delete/{id}', [WaveTransactionController::class, 'delete_wallet_wave']);
            Route::get('search_wave/{amount_wave}', [WaveTransactionController::class, 'search_wave']);
            Route::get('search_wave_received/{amount_received}', [WaveTransactionController::class, 'search_wave_received']);

            // Payments
            Route::post('payments/store', [PaymentController::class, 'create_payment']);
            Route::get('payment/show/{id}', [PaymentController::class, 'payment_show']);
            Route::post('payment/delete/{id}', [PaymentController::class, 'delete_payment']);
            Route::get('search_payment/{commission_id}', [PaymentController::class, 'search_payment']);
            Route::get('search_payment_received/{amount_received}', [PaymentController::class, 'search_payment_received']);
            Route::get('search_payment_offer/{commission_id}', [PaymentController::class, 'search_payment_offer']);

            // Reload cards
            Route::post('reload_cards/store', [ReloadCardController::class, 'create_reload_card']);
            Route::get('reload_card/show/{id}', [ReloadCardController::class, 'reload_card_show']);
            Route::post('reload_card/delete/{id}', [ReloadCardController::class, 'delete_reload_card']);
            Route::get('search_reload_card/{provider_id}/{commission_id}', [ReloadCardController::class, 'search_reload_card']);
            Route::get('search_reload_card_received/{provider_id}/{commission_id}', [ReloadCardController::class, 'search_reload_card_received']);

            // Active cards
            Route::post('order_cards/store', [BankCardController::class, 'create_order_card']);
            Route::get('order_card/show/{id}', [BankCardController::class, 'order_card_show']);
            Route::post('order_cards_other/create', [BankCardController::class, 'create_order_card_other']);
            Route::post('remonte_commissions/{gain_amount}/{commissions}/create', [UserController::class, 'remonteCommissions']);

            // Phone credit
            Route::post('phone_credits/store', [PhoneCreditController::class, 'create_phone_credit']);
            Route::get('phone_credit/show/{id}', [PhoneCreditController::class, 'phone_credit_show']);
            Route::post('phone_credit/delete/{id}', [PhoneCreditController::class, 'delete_phone_credit']);
            Route::get('search_phone_credit/{commission_id}', [PhoneCreditController::class, 'search_phone_credit']);
            Route::get('search_phone_credit_received/{amount_received}', [PhoneCreditController::class, 'search_phone_received']);

            // Inters
            Route::post('inters/store', [IntersPaymentController::class, 'create_inter']);
            Route::get('inter/show/{id}', [IntersPaymentController::class, 'inter_show']);
            Route::post('inter/delete/{id}', [IntersPaymentController::class, 'delete_inter']);
            Route::get('search_inter/{wallet_id}', [IntersPaymentController::class, 'search_transaction_inter']);
            
            // Route::get('search_payment_inter/{commission_id}', [IntersPaymentController::class, 'search_payment_robus_inter']);
            Route::get('paiement_invoice_inter/{invoice_commission_id}', [IntersPaymentController::class, 'paiement_invoice_inter']);
            Route::get('paiement_invoice_inter_received/{amount_received}', [IntersPaymentController::class, 'paiement_invoice_received']);
            Route::get('paiement_phone_credit_inter/{phone_credit_commission_id}', [IntersPaymentController::class, 'paiement_credit_inter']);
            Route::get('paiement_phone_credit_inter_received/{amount_received}', [IntersPaymentController::class, 'paiement_credit_received']);
            Route::get('paiement_virement_inter/{paiement_virement_id}', [IntersPaymentController::class, 'paiement_virement_inter']);
            Route::get('paiement_virement_inter_received/{amount_received}', [IntersPaymentController::class, 'paiement_virement_received']);
            Route::get('paiement_reload_card_inter/{paiement_reload_card_id}', [IntersPaymentController::class, 'reload_card_inter']);
            Route::get('paiement_reload_card_inter_received/{amount_received}', [IntersPaymentController::class, 'paiement_reload_card_received']);
            // New Update annuler
            Route::get('search_payment_mooney_inter/{country_id}/{paiement_amount}/{provider_out}', [IntersPaymentController::class, 'search_payment_mooney_inter']);
            Route::get('search_payment_inter_out/{country_id}/{paiement_amount}/{provider_out}', [IntersPaymentController::class, 'search_payment_robus_inter']);
            Route::get('search_payment_inter_out_received/{country_id}/{paiement_amount}/{provider_out}', [IntersPaymentController::class, 'search_payment_robus_inter_out']);

            // Drawal transaction
            Route::post('transactions/first_step', [TransactionController::class, 'first_withdrawal']);
            Route::post('transactions/second_step', [TransactionController::class, 'second_withdrawal']);

            // Transactions Diff
            Route::post('transactions/store', [TransactionController::class, 'create_transaction']);
            // Route::get('transaction/show/{id}', [TransactionController::class, 'transaction_show']);
            Route::post('transaction/delete/{id}', [TransactionController::class, 'delete_transaction']);
            Route::get('search/{wallet_id}', [TransactionController::class, 'search_transaction']);
            Route::get('search_transaction/{commission_id}', [TransactionController::class, 'search_transaction_money_commission']);
            Route::get('search_transaction_received/{commission_id}', [TransactionController::class, 'search_transaction_money_commission_received']);
            Route::get('search_transaction_robus_commission/{wallet}/{commission_id}', [TransactionController::class, 'search_transaction_commission_robus']);
            Route::get('search_transaction_robus_received_commission/{wallet}/{commission_id}', [TransactionController::class, 'search_transaction_commission_receved_robus']);
            Route::get('search_transaction_robus_drawals/{commission_id}', [TransactionController::class, 'search_transaction_drawal_robus']);
            Route::get('search_transaction_robus_received_drawals/{commission_id}', [TransactionController::class, 'search_transaction_drawal_received_robus']);


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
