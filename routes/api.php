<?php

use App\Http\Controllers\Api\Auth\AuthentificationController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CitieController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ComZoneController;
use App\Http\Controllers\Api\ConverterController;
use App\Http\Controllers\Api\CountrieController;
use App\Http\Controllers\Api\CurrencieController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\MasterController;
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
use App\Http\Controllers\Api\Transfers\VersementController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\PixelPaymentController;




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

    Route::prefix('paystack')->group(function () {
        // Webhook Paystack (POST uniquement - sans middleware CSRF)
        Route::post('/webhook', [PaystackWebhookController::class, 'handleWebhook'])
            ->name('paystack.webhook');

        // Vérification manuelle d'une transaction
        Route::get('/verify/{reference}', [PaystackWebhookController::class, 'verifyTransaction'])
            ->name('paystack.verify');

        // Générer une référence unique
        Route::post('/generate-reference', [PaystackWebhookController::class, 'generateReference'])
            ->name('paystack.generate-reference');
    });

    Route::prefix('auth')->group(function () {

        Route::post('signUp', [AuthentificationController::class, 'signUp']);
        Route::post('login', [AuthentificationController::class, 'loginCustumers']);
        Route::post('masters/login', [AuthentificationController::class, 'loginMaster']);
        Route::post('comzones/login', [AuthentificationController::class, 'loginComZone']);
        Route::post('merchants/login', [AuthentificationController::class, 'loginUnderDistributor']);
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

    Route::middleware(['auth:api', 'check.suspended'])->group(function () {

        Route::prefix('paystack')->group(function () {
            // Initialiser un paiement Paystack (nécessite authentification)
            Route::post('/initialize', [PaystackWebhookController::class, 'initializePayment'])
                ->name('paystack.initialize');

            // Vérifier transaction avec POST (alternative)
            Route::post('/verify/{reference}', [PaystackWebhookController::class, 'verifyTransaction'])
                ->name('paystack.verify.post');
        });

        Route::prefix('pixelInnov')->group(function () {
            // Initialiser un paiement Paystack (nécessite authentification)
            Route::post('/initialize', [PixelPaymentController::class, 'initializePayment'])
                ->name('pixelInnov.initialize');

            // Vérifier transaction avec POST (alternative)
            Route::post('/verify/{reference}', [PixelPaymentController::class, 'verifyTransaction'])
                ->name('pixelInnov.verify.post');
        });
        // User and Under distributors APIS
        Route::prefix('user')->group(function () {

            Route::get('dashboard', [UserController::class, 'dashboard']);
            Route::get('messages', [UserController::class, 'messages_send']);
            Route::get('dashboardCommissions', [UserController::class, 'dashboardCommissions']);
            Route::get('blockCommissions', [UserController::class, 'BlockCommissions']);
            Route::get('historicals', [UserController::class, 'dashboard_historique']);
            Route::get('info', [UserController::class, 'info']);
            Route::get('info/kyc', [UserController::class, 'kyc']);
            Route::post('info/update', [UserController::class, 'updateInfo']);
            Route::post('info/active_compte', [UserController::class, 'updload_identity']);

            // Virements
            Route::post('virements/store', [VirementController::class, 'create_virement']);
            Route::get('virement/show/{id}', [VirementController::class, 'virement_show']);
            Route::post('virement/delete/{id}', [VirementController::class, 'delete_virement']);
            Route::get('search_virement/{amount_virment}', [VirementController::class, 'search_virement']);
            Route::get('search_virement_received/{amount_virment_received}', [VirementController::class, 'search_virement_received']);

            // 1Xbets
            Route::post('oneXbet/store', [OnexbetTransactionController::class, 'create_OnexbetTranaction']);
            Route::post('oneXbet/delete/{inter_providersid}', [OnexbetTransactionController::class, 'delete_wallet_xbet']);
            Route::get('search_onexbet/{amount_wave}/{is_drawal?}/{is_net_amount?}', [OnexbetTransactionController::class, 'search_xbet']);
            Route::get('search_first_onexbet/{wallet_id}/{is_drawal?}', [OnexbetTransactionController::class, 'search_first_xbet']);
            Route::get('search_onexbet_received/{amount_received}', [OnexbetTransactionController::class, 'search_xbet_received']);

            // WithTransactions
            Route::post('push/store/{is_drawal?}', [withTransactionController::class, 'create_PushTransaction']);
            Route::post('bank_send/store', [withTransactionController::class, 'create_bankSend']);
            Route::post('versments/store/{is_drawal?}', [VersementController::class, 'create_versement']);
            Route::post('djamo/store/{is_drawal?}', [withTransactionController::class, 'create_DjamoTransaction']);
            Route::post('tresorts/store/{is_drawal?}', [withTransactionController::class, 'create_TresortTransaction']);
            Route::post('push/delete/{id}', [withTransactionController::class, 'delete_wallet_Push']);
            Route::post('djamo/delete/{id}', [withTransactionController::class, 'delete_wallet_Djamo']);
            Route::post('tresorts/delete/{id}', [withTransactionController::class, 'delete_wallet_Tresort']);

            Route::get('search_push/{amount_send}/{is_drawal?}', [withTransactionController::class, 'search_Push']);
            Route::get('search_bank_send/{amount_send}/{country_id}', [withTransactionController::class, 'search_bank_send']);
            Route::get('search_drawal_mooney/{amount_send}/{provider}', [withTransactionController::class, 'search_drawal_mooney']);
            Route::get('search_versement/{amount_send}/{provider}', [VersementController::class, 'search_versement']);
            Route::get('search_djamo/{amount_send}/{is_drawal?}', [withTransactionController::class, 'search_Djamo']);
            Route::get('search_tresorts/{amount_send}/{is_drawal?}', [withTransactionController::class, 'search_Tresort']);
            Route::get('search_push_received/{amount_received}', [withTransactionController::class, 'search_received_Push']);
            Route::get('search_djamo_received/{amount_received}', [withTransactionController::class, 'search_received_Djamo']);
            Route::get('search_versement_received/{amount_received}/{provider}', [VersementController::class, 'search_versement_received']);
            Route::get('search_tresort_received/{amount_received}', [withTransactionController::class, 'search_receveid_Tresort']);


            // 1Xbets
            Route::post('wave/store', [WaveTransactionController::class, 'create_WaveTranaction']);
            Route::post('wave/delete/{id}', [WaveTransactionController::class, 'delete_wallet_wave']);
            Route::get('search_wave/{amount_wave}', [WaveTransactionController::class, 'search_wave']);
            Route::get('search_wave_received/{amount_received}', [WaveTransactionController::class, 'search_wave_received']);

            // Reload
            Route::post('reloads/store', [ReloadController::class, 'create_reload']);
            Route::get('reload/show/{id}', [ReloadController::class, 'reload_show']);
            Route::post('reload/delete/{id}', [ReloadController::class, 'delete_reload']);
            Route::get('search_reload/{amount_reload}', [ReloadController::class, 'search_reload']);
            Route::get('search_share_reload/{amount_reload}', [ReloadController::class, 'search_share_reload']);
            Route::get('search_pixel_in_reload/{amount_reload}', [ReloadController::class, 'search_pixel_in_reload']);
            Route::get('search_pixel_out_reload/{amount_reload}', [ReloadController::class, 'search_pixel_out_reload']);

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
            Route::get('search_phone_credit/{commission_id}/{is_net_amount?}', [PhoneCreditController::class, 'search_phone_credit']);
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

            // Inter new send
            Route::get('search_inter_push/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_Push']);
            Route::get('search_inter_versement/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_versement']);
            Route::get('search_inter_djamo/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_Djamo']);
            Route::get('search_inter_tresorts/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_Tresort']);
            Route::get('search_inter_push_received/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_received_Push']);
            Route::get('search_inter_djamo_received/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_received_Djamo']);
            Route::get('search_inter_versement_received/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_versement_received']);
            Route::get('search_inter_tresort_received/{country_id}/{paiement_amount}', [IntersPaymentController::class, 'search_receveid_Tresort']);

            // Drawal transaction
            Route::post('transactions/first_step', [TransactionController::class, 'first_withdrawal']);
            Route::post('transactions/drawal_mooney', [TransactionController::class, 'mooney_withdrawal']);
            Route::post('transactions/drawal_robus', [TransactionController::class, 'robus_withdrawal']);
            Route::post('transactions/second_step', [TransactionController::class, 'second_withdrawal']);

            Route::post('transactions/store', [TransactionController::class, 'create_transaction']);
            Route::post('transaction/delete/{id}', [TransactionController::class, 'delete_transaction']);
            Route::get('search/{wallet_id}', [TransactionController::class, 'search_transaction']);
            Route::get('search_transaction/{commission_id}', [TransactionController::class, 'search_transaction_money_commission']);
            Route::get('search_transaction_received/{commission_id}', [TransactionController::class, 'search_transaction_money_commission_received']);
            Route::get('search_transaction_robus_commission/{wallet}/{commission_id}', [TransactionController::class, 'search_transaction_commission_robus']);
            Route::get('search_transaction_robus_received_commission/{wallet}/{commission_id}', [TransactionController::class, 'search_transaction_commission_receved_robus']);
            Route::get('search_transaction_robus_drawals/{commission_id}', [TransactionController::class, 'search_transaction_drawal_robus']);
            Route::get('search_transaction_robus_received_drawals/{commission_id}', [TransactionController::class, 'search_transaction_drawal_received_robus']);

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

            Route::get('/countries/inter', [CountrieController::class, 'index_inter_auth']);
            Route::get('/countries/inter/{id}', [CountrieController::class, 'index_selected_search']);

            Route::prefix('converters')->group(function () {
                Route::get('info_converters/{amount}/{currencency_name}', [ConverterController::class, 'converter_browse']);
            });
        });

        // Masters APIS
        Route::prefix('masters')->group(function () {
            Route::get('dashboard', [UserController::class, 'dashboard_masters']);
            Route::get('info', [UserController::class, 'info_masters']);
            Route::get('info/kyc', [UserController::class, 'kyc_masters']);
            Route::post('info/update', [UserController::class, 'update_masters']);
            Route::post('info/active_compte', [UserController::class, 'updload_identity_masters']);
            Route::get('under_distributors', [UserController::class, 'masters_under_distributors']);

            // Providers transactions
            Route::get('/providers', [ProviderController::class, 'masters_providers']);

            Route::post('unloadings/store', [TransactionController::class, 'create_transaction_master']);
            Route::post('reloads/store', [TransactionController::class, 'create_reload_master']);
            Route::post('add_under_distributors/store', [UserController::class, 'add_under_distributor']);
            Route::post('create_under_distributors/store', [MasterController::class, 'create_under_distributor']);
            Route::post('create_custumers/store', [MasterController::class, 'create_custumers']);
            Route::get('citie_masters', [MasterController::class, 'citie_masters']);

            // Historique Masters   
            Route::get('historicals', [UserController::class, 'historical_distributer_master']);
            Route::get('historical/evolutions', [UserController::class, 'historical_distributer_evolution_master']);
            Route::get('historical/month_evolutions', [UserController::class, '']);
            Route::get('historical/year_evolutions', [UserController::class, 'historical_year_distributer']);
            Route::post('update_drawal/commission_drawal', [UserController::class, 'drawal_commission_masters']);
        });

        // COM ZONE APIS
        Route::prefix('comzones')->group(function () {
            Route::get('dashboard', [UserController::class, 'dashboard_comzones']);
            Route::get('info', [UserController::class, 'info_masters']);
            Route::get('info/kyc', [UserController::class, 'kyc_masters']);
            Route::post('info/update', [UserController::class, 'update_masters']);
            Route::post('info/active_compte', [UserController::class, 'updload_identity_masters']);
            Route::get('under_distributors', [UserController::class, 'comzone_under_distributors']);
            Route::get('masters_distributors', [UserController::class, 'masters_com_zones']);
            Route::get('custumers_distributors', [UserController::class, 'custumers_com_zones']);

            // Providers transactions
            Route::get('/providers', [ProviderController::class, 'comzones_providers']);

            // create Users
            Route::post('add_masters/store', [ComZoneController::class, 'store_under_masters']);
            Route::post('add_custumers/store', [ComZoneController::class, 'store_under_custumers']);
            Route::post('add_under_distributors/store', [ComZoneController::class, 'store_under_distributors']);
        });

        // Unders distributors
        Route::prefix('undersdistributors')->group(function () {
            Route::get('locations', [UserController::class, 'locations_unders']);
            Route::get('locations/{id}', [UserController::class, 'locations_unders_zone']);
        });
    });


    Route::get('/faqs', [FaqController::class, 'index']);
    // Cities and Country
    Route::get('/countries', [CountrieController::class, 'index']);
    Route::get('/countrie/{id}', [CountrieController::class, 'index_search_country']);
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
