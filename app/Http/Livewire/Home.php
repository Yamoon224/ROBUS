<?php

namespace App\Http\Livewire;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletXbet;
use Livewire\Component;
use App\Http\Services\HomeService;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\WalletCommission;
use App\Models\PixelMobileZoneTransfer;
use Illuminate\Support\Facades\Cache;

class Home extends Component
{
    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        // 1. Eager loading des relations pour éviter les requêtes N+1
        $walletUser = sabdy()->load([
            'wallet' => function ($query) {
                $query->withTrashed()->with('currencie');
            },
            'country.currencie'
        ]);

        $currencie = $walletUser->wallet->currencie->name ?? 'N/A';

        // 2. Génération de la clé unique incluant l'utilisateur ET les filtres de date
        $cacheKey = $this->dashboardCacheKey($walletUser);

        // 3. Mise en cache globale (Augmentée à 5 minutes pour préserver les performances)
        $dashboardData = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($walletUser, $currencie) {
            
            $homeService = app(HomeService::class);

            // Nombre de transactions par statut
            $payment_error   = $homeService->getTransactionCount(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
            $payment_padding = $homeService->getTransactionCount(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');
            $payment_success = $homeService->getTransactionCount(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status');

            $transaction_error   = $homeService->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
            $transaction_padding = $homeService->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
            $transaction_success = $homeService->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');

            $bank_error   = $homeService->getTransactionCount(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
            $bank_padding = $homeService->getTransactionCount(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');
            $bank_success = $homeService->getTransactionCount(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status');

            $deposit_error   = $homeService->getTransactionCount(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
            $deposit_padding = $homeService->getTransactionCount(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');
            $deposit_success = $homeService->getTransactionCount(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status');

            $reload_count = $homeService->getTransactionCountReload(WalletReload::class);
            $reload_error   = $reload_count;
            $reload_padding = $reload_count;
            $reload_success = $reload_count;

            $phone_credit_error   = $homeService->getTransactionCount(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
            $phone_credit_padding = $homeService->getTransactionCount(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');
            $phone_credit_success = $homeService->getTransactionCount(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status');

            $inter_error   = $homeService->getTransactionCount(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
            $inter_padding = $homeService->getTransactionCount(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');
            $inter_success = $homeService->getTransactionCount(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status');

            $xbet_error   = $homeService->getTransactionCount(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
            $xbet_padding = $homeService->getTransactionCount(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');
            $xbet_success = $homeService->getTransactionCount(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status');

            $djamo_error   = $homeService->getTransactionCount(WalletDjamo::class, 'ERROR', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status');
            $djamo_padding = $homeService->getTransactionCount(WalletDjamo::class, 'PENDING', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status');
            $djamo_success = $homeService->getTransactionCount(WalletDjamo::class, 'SUCCESS', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status');

            $versment_error   = $homeService->getTransactionCount(WalletVersement::class, 'ERROR', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status');
            $versment_padding = $homeService->getTransactionCount(WalletVersement::class, 'PENDING', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status');
            $versment_success = $homeService->getTransactionCount(WalletVersement::class, 'SUCCESS', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status');

            $tresortMoney_error    = $homeService->getTransactionCount(WalletTresortMoney::class, 'ERROR', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status');
            $tresortMoney_pendding = $homeService->getTransactionCount(WalletTresortMoney::class, 'PENDING', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status');
            $tresortMoney_success  = $homeService->getTransactionCount(WalletTresortMoney::class, 'SUCCESS', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status');

            $push_error    = $homeService->getTransactionCount(WalletPush::class, 'ERROR', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status');
            $push_pendding = $homeService->getTransactionCount(WalletPush::class, 'PENDING', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status');
            $push_success  = $homeService->getTransactionCount(WalletPush::class, 'SUCCESS', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status');

            $pixelMobileZoneQuery = $this->pixelMobileZoneBaseQuery();
            $pixel_mobile_zone_success = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->where('pixel_mobile_zone_transfers.status', 'SUCCESS')
                ->count();
            $pixel_mobile_zone_pendding = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->whereIn('pixel_mobile_zone_transfers.status', ['PENDING', 'PROCESSING'])
                ->count();
            $pixel_mobile_zone_error = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->whereIn('pixel_mobile_zone_transfers.status', ['FAILED', 'ERROR'])
                ->count();
            $pixel_mobile_zone_refund = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->where('pixel_mobile_zone_transfers.status', 'REFUNDED')
                ->count();

            // Volumes financiers (Aujourd'hui)
            $payement_amount           = $homeService->getTodayTransactionAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
            $payement_amount_cie       = $homeService->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '8');
            $payement_amount_canal     = $homeService->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '7');
            $payement_amount_sodeci    = $homeService->getTodayTransactionProvidersAmountTotal(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount', '10');
            $transaction_amount        = $homeService->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
            $transaction_amount_robus  = $homeService->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '9');
            $transaction_amount_moov   = $homeService->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '3');
            $transaction_amount_wave   = $homeService->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '6');
            $transaction_amount_orange = $homeService->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '4');
            $transaction_amount_mtn    = $homeService->getTodayTransactionProviderAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount', '1');
            $reload_amount             = $homeService->getTodayReloadAmountTotal(WalletReload::class);
            $phonecredit_amount        = $homeService->getTodayTransactionAmountTotal(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
            $inter_amount              = $homeService->getTodayTransactionProviderTypeOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');

            $inter_amount_moneyRobus   = $homeService->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 1);
            $inter_amount_cie          = $homeService->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 5);
            $inter_amount_credit       = $homeService->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 6);
            $inter_amount_virement     = $homeService->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 7);
            $inter_amount_rechargement = $homeService->getTodayTransactionProviderOneAmountTotal(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received', 8);

            $bank_amount         = $homeService->getTodayTransactionAmountTotal(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
            $deposit_amount      = $homeService->getTodayTransactionAmountTotal(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
            $xbet_amount         = $homeService->getTodayTransactionAmountTotal(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
            $djamo_amount        = $homeService->getTodayTransactionAmountTotal(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by',  'wallet_djamos.status',  'wallet_djamos.amount');
            $push_amount         = $homeService->getTodayTransactionAmountTotal(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by',  'wallet_pushes.status',  'wallet_pushes.amount');
            $tresortMoney_amount = $homeService->getTodayTransactionAmountTotal(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
            $versment_amount     = $homeService->getTodayTransactionAmountTotal(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by',  'wallet_versements.status',  'wallet_versements.amount');
            $pixel_mobile_zone_amount = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->where('pixel_mobile_zone_transfers.status', 'SUCCESS')
                ->sum('pixel_mobile_zone_transfers.amount');

            // Volumes financiers (Mois)
            $payment_amount_month     = $homeService->getDaysTransaction(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount');
            $transaction_amount_month = $homeService->getDaysTransaction(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
            $reload_amount_month      = $homeService->getDaysReloadAmountTotal(WalletReload::class);
            $phonecredit_amount_month = $homeService->getDaysTransaction(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount');
            $inter_amount_month       = $homeService->getDaysTransaction(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount_received');
            $bank_amount_month        = $homeService->getDaysTransaction(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount');
            $deposit_amount_month     = $homeService->getDaysTransaction(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount');
            $xbet_amount_month        = $homeService->getDaysTransaction(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount');
            $djamo_amount_month       = $homeService->getDaysTransaction(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by',  'wallet_djamos.status',  'wallet_djamos.amount');
            $push_amount_month        = $homeService->getDaysTransaction(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by',  'wallet_pushes.status',  'wallet_pushes.amount');
            $tresortMoney_amount_month = $homeService->getDaysTransaction(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount');
            $versment_amount_month    = $homeService->getDaysTransaction(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by',  'wallet_versements.status',  'wallet_versements.amount');

            // Nombre total de transactions (Tous les jours de la période)
            $transaction_amount_days  = $homeService->getTransactionAllCount(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by');
            $reload_amount_days       = $homeService->getTransactionAllCount(WalletReload::class, 'wallet_reloads.wallet_id', 'wallet_reloads.created_at', 'wallet_reloads.updated_by');
            $payment_amount_days      = $homeService->getTransactionAllCount(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by');
            $phone_amount_days        = $homeService->getTransactionAllCount(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by');
            $inter_amount_days        = $homeService->getTransactionAllCount(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by');
            $bank_amount_days         = $homeService->getTransactionAllCount(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by');
            $deposit_amount_days      = $homeService->getTransactionAllCount(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by');
            $xbet_amount_days         = $homeService->getTransactionAllCount(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by');
            $push_amount_days         = $homeService->getTransactionAllCount(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by');
            $djamo_amount_days        = $homeService->getTransactionAllCount(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by');
            $versment_amount_days     = $homeService->getTransactionAllCount(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by');
            $tresortMoney_amount_days = $homeService->getTransactionAllCount(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by');
            $pixel_mobile_zone_amount_days = (clone $pixelMobileZoneQuery)
                ->whereBetween('pixel_mobile_zone_transfers.created_at', [now()->startOfDay(), now()->endOfDay()])
                ->count();

            // Métriques Utilisateurs (Utilisation directe du Service)
            $custumers         = $homeService->getUserTypeTotal('is_custumers', true);
            $underDistributors = $homeService->getUserTypeTotal('is_under_distributer', true);
            $supervisors       = $homeService->getUserTypeTotal('is_supervisor', true);
            $distributors      = $homeService->getUserTypeTotal('is_distributer', true);
            $companies         = $homeService->getUserTypeTotal('is_company', true);

            return [
                'is_custumers'          => $custumers,
                'is_distributors'       => $distributors,
                'is_underdistributors'  => $underDistributors, // Clé maintenant protégée par le cache frais
                'is_supervisors'        => $supervisors,
                'is_compagnies'         => $companies,

                'user_currencie'        => $currencie,
                'error_payment'         => $payment_error,
                'success_payment'       => $payment_success,
                'pendding_payment'      => $payment_padding,
                
                'error_transaction'    => $transaction_error,
                'success_transaction'  => $transaction_success,
                'pendding_transaction' => $transaction_padding,

                'djamo_error'           => $djamo_error,
                'djamo_success'         => $djamo_success,
                'djamo_padding'         => $djamo_padding,

                'versment_error'        => $versment_error,
                'versment_success'      => $versment_success,
                'versment_padding'      => $versment_padding,

                'tresortMoney_error'    => $tresortMoney_error,
                'tresortMoney_success'  => $tresortMoney_success,
                'tresortMoney_pendding' => $tresortMoney_pendding,

                'push_error'            => $push_error,
                'push_success'          => $push_success,
                'push_pendding'         => $push_pendding,

                'pixel_mobile_zone_error'    => $pixel_mobile_zone_error,
                'pixel_mobile_zone_success'  => $pixel_mobile_zone_success,
                'pixel_mobile_zone_pendding' => $pixel_mobile_zone_pendding,
                'pixel_mobile_zone_refund'   => $pixel_mobile_zone_refund,

                'error_xbet'            => $xbet_error,
                'success_xbet'          => $xbet_success,
                'pendding_xbet'         => $xbet_padding,

                'error_bank'            => $bank_error,
                'success_bank'          => $bank_success,
                'pendding_bank'         => $bank_padding,
                
                'error_deposit'         => $deposit_error,
                'success_deposit'       => $deposit_success,
                'pendding_deposit'      => $deposit_padding,

                'error_reload'          => $reload_error,
                'success_reload'        => $reload_success,
                'pendding_reload'       => $reload_padding,
                
                'error_phone_credit'    => $phone_credit_error,
                'success_phone_credit'  => $phone_credit_success,
                'pendding_phone_credit' => $phone_credit_padding,

                'error_inter'           => $inter_error,
                'success_inter'         => $inter_success,
                'pendding_inter'        => $inter_padding,

                'amount_djamo_days'        => $djamo_amount,
                'amount_push_days'         => $push_amount,
                'amount_tresortMoney_days' => $tresortMoney_amount,
                'amount_versment_days'     => $versment_amount,
                'amount_pixel_mobile_zone_days' => $pixel_mobile_zone_amount,

                'amount_payment_days'           => $payement_amount,
                'amount_payment_cie_days'       => $payement_amount_cie,
                'amount_payment_canal_days'     => $payement_amount_canal,
                'amount_payment_sodeci_days'    => $payement_amount_sodeci,
                'amount_transaction_days'       => $transaction_amount,
                'amount_transaction_robus_days' => $transaction_amount_robus,
                'amount_transaction_moov_days'  => $transaction_amount_moov,
                'amount_transaction_wave_days'  => $transaction_amount_wave,
                'amount_transaction_orange_days'=> $transaction_amount_orange,
                'amount_transaction_mtn_days'   => $transaction_amount_mtn,
                'amount_reload_days'            => $reload_amount,
                'amount_phone_credit_days'      => $phonecredit_amount,
                'amount_inter_days'             => $inter_amount,
                
                'amount_inter_moneyRobus' => $inter_amount_moneyRobus,
                'amount_inter_cie'        => $inter_amount_cie,
                'amount_inter_credit'     => $inter_amount_credit,
                'amount_inter_virement'   => $inter_amount_virement,
                'amount_inter_rechargement'=> $inter_amount_rechargement,
                'amount_bank_days'        => $bank_amount,
                'amount_deposit_days'     => $deposit_amount,
                'amount_xbet_days'        => $xbet_amount,

                'walletSolde'      => Wallet::where('sid_user_id', $walletUser->id)->withTrashed()->sum('amount'),
                'usersCountry'     => $walletUser->country->currencie->name ?? 'N/A',
                'sum_bank'         => $bank_amount,
                'sum_deposit'      => $deposit_amount,
                'sum_payment'      => $payement_amount,
                'sum_reload'       => $reload_amount,
                'sum_transaction'  => $transaction_amount,
                'sum_inter'        => $inter_amount,
                'sum_push'         => $push_amount,
                'sum_djamo'        => $djamo_amount,
                'sum_versment'     => $versment_amount,
                'sum_tresortMoney' => $tresortMoney_amount,
                'sum_pixelMobileZone' => $pixel_mobile_zone_amount,
                'sum_phoneCredit'  => $phonecredit_amount,
                'sum_xbet'         => $xbet_amount,
                
                'sum_bank_month'         => $bank_amount_month,
                'sum_deposit_month'      => $deposit_amount_month,
                'sum_payment_month'      => $payment_amount_month,
                'sum_reload_month'       => $reload_amount_month,
                'sum_transaction_month'  => $transaction_amount_month,
                'sum_inter_month'        => $inter_amount_month,
                'sum_phoneCredit_month'  => $phonecredit_amount_month,
                'sum_xbet_month'         => $xbet_amount_month,
                'sum_push_month'         => $push_amount_month,
                'sum_djamo_month'        => $djamo_amount_month,
                'sum_tresort_month'      => $tresortMoney_amount_month,
                'sum_versment_month'     => $versment_amount_month,

                'account_bank_days'        => $bank_amount_days,
                'account_deposit_days'     => $deposit_amount_days,
                'account_payment_days'     => $payment_amount_days,
                'account_reload_days'      => $reload_amount_days,
                'account_transaction_days' => $transaction_amount_days,
                'account_inter_days'       => $inter_amount_days,
                'account_phoneCredit_days' => $phone_amount_days,
                'account_xbet_days'        => $xbet_amount_days,
                'account_push_days'        => $push_amount_days,
                'account_djamo_days'       => $djamo_amount_days,
                'account_versment_days'    => $versment_amount_days,
                'account_tresort_days'     => $tresortMoney_amount_days,
                'account_pixel_mobile_zone_days' => $pixel_mobile_zone_amount_days,

                'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
                'walletAdmin'       => WalletAdmin::where('status', 1)->sum('amount'),
            ];
        });

        return view('livewire.home', $dashboardData);
    }

    /**
     * Clé de cache dynamique gérant l'ID utilisateur et les filtres temporels
     */
    private function dashboardCacheKey($walletUser): string
    {
        return 'home-dashboard:v2:' . $walletUser->id . ':' . md5($this->startDate . $this->endDate);
    }

    /**
     * Conservé au cas où, mais l'appel est fait directement via le HomeService dans le render
     */
    public function getUserTypeTotal($user, $status)
    {
        return SidUser::where($user, $status)->count();
    }

    private function pixelMobileZoneBaseQuery()
    {
        return PixelMobileZoneTransfer::join('wallets', 'pixel_mobile_zone_transfers.wallet_id', '=', 'wallets.id')
            ->when(sabdy()->is_under_distributer || sabdy()->is_api, function ($query) {
                return $query->where('wallets.sid_user_id', sabdy()->id);
            });
    }
}
