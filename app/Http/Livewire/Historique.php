<?php

namespace App\Http\Livewire;

use App\Http\Services\HistoriqueService;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\WithMerchant;
use App\Models\PixelMobileZoneTransfer;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletVersement;


class Historique extends Component
{

    protected $historiqueService;
    public  $startDate = "";
    public  $endDate =  "";
    // Transactions
    public $showTransButton = false;
    public $showCreditButton = false;
    public $showPaiementButton = false;
    public $showInterButton = false;
    public $showRechargementButton = false;
    public $showVirementButton = false;
    public $showRetraitButton = false;
    public $showDepotButton = false;
    public $showMobileButton = false;
    public $showXbetButton = false;
    public $showCommissionButton = false;
    public $showRecharButton = false;
    public $showVersementButton = false;
    public $showDjamoButton = false;
    public $showPushButton = false;
    public $showTresortMoneyButton = false;
    public $showBankSendButton= false;
    public $showWaveButton = false;
    public $showPixelMobileZoneButton = false;
    public $operators;
    public $operatorsCompagnie;
    public $operatorsTransaction;

    public function mount(HistoriqueService $historiqueService)
    {

        $this->historiqueService = $historiqueService;

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
        $this->operatorsTransaction = OperatorService::getOperatorsWithTranactions(sabdy()->id);
        $account_accepted = sabdy()->is_accepted_companie ?? false;
        $company_id = sabdy()->company_id ?? null;
        if ($company_id && $company_id != 0 && $company_id != 1) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }
        if ($sidUserId) {
            $this->operatorsCompagnie = OperatorService::getOperatorsWithCommissions($sidUserId);
        } else {
            $this->operatorsCompagnie = [];
        }

        $restrictionMapTransaction = [
            'Historiques credit' => 'showCreditButton',
            'Historiques facture' => 'showPaiementButton',
            'Historiques international' => 'showInterButton',
            'Historiques carte' => 'showRechargementButton',
            'Historiques bancaire' => 'showVirementButton',
            'Historiques retrait' => 'showRetraitButton',
            'Historiques mobile money' => 'showDepotButton',
            'Historiques Xbet' => 'showXbetButton',
            'Historiques Commission' => 'showCommissionButton',
            'Historiques Rechargement' => 'showRecharButton',
            'Historiques Versement' => 'showVersementButton',
            'Historiques Transfert' => 'showTransButton',
            'Historiques Wave' => 'showWaveButton',
            'Historiques Djamo' => 'showDjamoButton',
            'Historiques Push' => 'showPushButton',
            'Historiques Bank send' => 'showBankSendButton',
            'Historiques Tresort Money' => 'showTresortMoneyButton',
            'Historiques Mobile Zone' => 'showPixelMobileZoneButton',
        ];

        foreach ($this->operatorsTransaction as $operator) {
            $restrictions = explode(',', $operator->name);
            foreach ($restrictionMapTransaction as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }
    }


    public function render()
    {


        $walletUser = sabdy();

        // Account valeurs transactions
        $payment_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletPayment::class, 'ERROR', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_padding =app(HistoriqueService::class)->getTransactionCountHisto(WalletPayment::class, 'PENDING', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $payment_success =app(HistoriqueService::class)->getTransactionCountHisto(WalletPayment::class, 'SUCCESS', 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by', 'wallet_payments.status',  'wallet_payments.is_web', false);
        $transaction_error =app(HistoriqueService::class)->getTransactionCountHisto(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $transaction_padding =app(HistoriqueService::class)->getTransactionCountHisto(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $transaction_success =app(HistoriqueService::class)->getTransactionCountHisto(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status',  'wallet_transactions.is_web', false);
        $bank_error =app(HistoriqueService::class)->getTransactionCountHisto(WalletBank::class, 'ERROR', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_padding =app(HistoriqueService::class)->getTransactionCountHisto(WalletBank::class, 'PENDING', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $bank_success =app(HistoriqueService::class)->getTransactionCountHisto(WalletBank::class, 'SUCCESS', 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by', 'wallet_banks.status',  'wallet_banks.is_web', false);
        $deposit_error =app(HistoriqueService::class)->getTransactionCountHisto(WalletDeposit::class, 'ERROR', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_padding =app(HistoriqueService::class)->getTransactionCountHisto(WalletDeposit::class, 'PENDING', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $deposit_success =app(HistoriqueService::class)->getTransactionCountHisto(WalletDeposit::class, 'SUCCESS', 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by', 'wallet_deposits.status',  'wallet_deposits.is_web', false);
        $reload_error = app(HistoriqueService::class)->getTransactionCountReload(WalletReload::class);
        $reload_padding = app(HistoriqueService::class)->getTransactionCountReload(WalletReload::class);
        $reload_success = app(HistoriqueService::class)->getTransactionCountReload(WalletReload::class);

        $phone_credit_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletPhoneCredit::class, 'ERROR',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletPhoneCredit::class, 'PENDING', 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);
        $phone_credit_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletPhoneCredit::class, 'SUCCESS',  'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by', 'wallet_phone_credits.status',  'wallet_phone_credits.is_web', false);

        $inter_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletInter::class, 'ERROR',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletInter::class, 'PENDING', 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);
        $inter_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletInter::class, 'SUCCESS',  'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by', 'wallet_inters.status',  'wallet_inters.is_web', false);

        $xbet_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletXbet::class, 'ERROR',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletXbet::class, 'PENDING', 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);
        $xbet_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletXbet::class, 'SUCCESS',  'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by', 'wallet_xbets.status',  'wallet_xbets.is_web', false);

        $wave_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletWave::class, 'ERROR',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletWave::class, 'PENDING', 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);
        $wave_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletWave::class, 'SUCCESS',  'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by', 'wallet_waves.status',  'wallet_waves.is_web', false);

        // Push
        $push_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletPush::class, 'ERROR',  'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);
        $push_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletPush::class, 'PENDING', 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);
        $push_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletPush::class, 'SUCCESS',  'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by', 'wallet_pushes.status',  'wallet_pushes.is_web', false);
         
        $bank_send_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletBankSend::class, 'ERROR',  'wallet_bank_sends.wallet_id', 'wallet_bank_sends.created_at', 'wallet_bank_sends.updated_by', 'wallet_bank_sends.status',  'wallet_bank_sends.is_web', false);
        $bank_send_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletBankSend::class, 'PENDING', 'wallet_bank_sends.wallet_id', 'wallet_bank_sends.created_at', 'wallet_bank_sends.updated_by', 'wallet_bank_sends.status',  'wallet_bank_sends.is_web', false);
        $bank_send_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletBankSend::class, 'SUCCESS',  'wallet_bank_sends.wallet_id', 'wallet_bank_sends.created_at', 'wallet_bank_sends.updated_by', 'wallet_bank_sends.status',  'wallet_bank_sends.is_web', false);

        $versment_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletVersement::class, 'ERROR',  'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);
        $versement_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletVersement::class, 'PENDING', 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);
        $versement_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletVersement::class, 'SUCCESS',  'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by', 'wallet_versements.status',  'wallet_versements.is_web', false);

        $tresort_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletTresortMoney::class, 'ERROR',  'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);
        $tresort_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletTresortMoney::class, 'PENDING', 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);
        $tresort_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletTresortMoney::class, 'SUCCESS',  'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by', 'wallet_tresort_money.status',  'wallet_tresort_money.is_web', false);


        $djamo_error = app(HistoriqueService::class)->getTransactionCountHisto(WalletDjamo::class, 'ERROR',  'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);
        $djamo_padding = app(HistoriqueService::class)->getTransactionCountHisto(WalletDjamo::class, 'PENDING', 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);
        $djamo_success = app(HistoriqueService::class)->getTransactionCountHisto(WalletDjamo::class, 'SUCCESS',  'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by', 'wallet_djamos.status',  'wallet_djamos.is_web', false);

        $pixelMobileZoneQuery = $this->pixelMobileZoneBaseQuery();
        $pixel_mobile_zone_error = (clone $pixelMobileZoneQuery)->whereIn('pixel_mobile_zone_transfers.status', ['FAILED', 'ERROR', 'REFUNDED'])->count();
        $pixel_mobile_zone_padding = (clone $pixelMobileZoneQuery)->whereIn('pixel_mobile_zone_transfers.status', ['PENDING', 'PROCESSING'])->count();
        $pixel_mobile_zone_success = (clone $pixelMobileZoneQuery)->where('pixel_mobile_zone_transfers.status', 'SUCCESS')->count();

        // Amount transactions days
        $payement_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletPayment::class, 'wallet_payments.wallet_id', 'wallet_payments.created_at', 'wallet_payments.updated_by',  'wallet_payments.status',  'wallet_payments.amount',  'wallet_payments.is_web', false);
        $transaction_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount',  'wallet_transactions.is_web', false);
        $reload_amount = app(HistoriqueService::class)->getTodayReloadAmountTotal(WalletReload::class);
        $phonecredit_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletPhoneCredit::class, 'wallet_phone_credits.wallet_id', 'wallet_phone_credits.created_at', 'wallet_phone_credits.updated_by',  'wallet_phone_credits.status',  'wallet_phone_credits.amount',  'wallet_phone_credits.is_web', false);
        $inter_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletInter::class, 'wallet_inters.wallet_id', 'wallet_inters.created_at', 'wallet_inters.updated_by',  'wallet_inters.status',  'wallet_inters.amount',  'wallet_inters.is_web', false);
        $bank_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletBank::class, 'wallet_banks.wallet_id', 'wallet_banks.created_at', 'wallet_banks.updated_by',  'wallet_banks.status',  'wallet_banks.amount',   'wallet_banks.is_web', false);
        $deposit_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletDeposit::class, 'wallet_deposits.wallet_id', 'wallet_deposits.created_at', 'wallet_deposits.updated_by',  'wallet_deposits.status',  'wallet_deposits.amount',  'wallet_deposits.is_web', false);
        $xbet_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletXbet::class, 'wallet_xbets.wallet_id', 'wallet_xbets.created_at', 'wallet_xbets.updated_by',  'wallet_xbets.status',  'wallet_xbets.amount',  'wallet_xbets.is_web', false);
        $wave_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletWave::class, 'wallet_waves.wallet_id', 'wallet_waves.created_at', 'wallet_waves.updated_by',  'wallet_waves.status',  'wallet_waves.amount',  'wallet_waves.is_web', false);

        $push_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletPush::class, 'wallet_pushes.wallet_id', 'wallet_pushes.created_at', 'wallet_pushes.updated_by',  'wallet_pushes.status',  'wallet_pushes.amount',  'wallet_pushes.is_web', false);
        $tresort_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletTresortMoney::class, 'wallet_tresort_money.wallet_id', 'wallet_tresort_money.created_at', 'wallet_tresort_money.updated_by',  'wallet_tresort_money.status',  'wallet_tresort_money.amount',  'wallet_tresort_money.is_web', false);
        $versement_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletVersement::class, 'wallet_versements.wallet_id', 'wallet_versements.created_at', 'wallet_versements.updated_by',  'wallet_versements.status',  'wallet_versements.amount',  'wallet_versements.is_web', false);
        $djamo_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletDjamo::class, 'wallet_djamos.wallet_id', 'wallet_djamos.created_at', 'wallet_djamos.updated_by',  'wallet_djamos.status',  'wallet_djamos.amount',  'wallet_djamos.is_web', false);
        $bankSend_amount = app(HistoriqueService::class)->getTodayTransactionAmountTotalHisto(WalletBankSend::class, 'wallet_bank_sends.wallet_id', 'wallet_bank_sends.created_at', 'wallet_bank_sends.updated_by',  'wallet_bank_sends.status',  'wallet_bank_sends.amount',  'wallet_bank_sends.is_web', false);
        $pixel_mobile_zone_amount = (clone $pixelMobileZoneQuery)
            ->whereDate('pixel_mobile_zone_transfers.created_at', Carbon::today())
            ->where('pixel_mobile_zone_transfers.status', 'SUCCESS')
            ->sum('pixel_mobile_zone_transfers.amount');



        return view('livewire.historique', [

            'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
            'sum_error' => $payment_error  + $transaction_error + $bank_error  +  $deposit_error +  $reload_error + $phone_credit_error +   $inter_error + $xbet_error + $push_error + $versment_error + $tresort_error + $djamo_error  + $bank_send_error + $pixel_mobile_zone_error,
            'sum_success' => $payment_success  + $transaction_success + $bank_success  +  $deposit_success +  $reload_success + $phone_credit_success +   $inter_success +  $xbet_success + $push_success + $versement_success + $tresort_success + $djamo_success  + $bank_send_success + $pixel_mobile_zone_success,
            'pending_success' => $payment_padding  + $transaction_padding + $bank_padding  +  $deposit_padding +  $reload_padding + $phone_credit_padding +   $inter_padding  + $xbet_padding + $push_padding + $tresort_padding + $djamo_padding + $versement_padding  + $bank_send_padding + $pixel_mobile_zone_padding,
            'wallet_Banks_last' => WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallet_banks.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_banks.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_banks.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->orderBy('wallet_banks.created_at', 'desc')
                ->select('wallet_banks.*')
                ->limit(5)
                ->get(),
            'Wallet_Deposits_last' => WalletDeposit::join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                ->where('wallet_deposits.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_deposits.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_deposits.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_deposits.*')
                ->limit(5)->orderBy('wallet_deposits.created_at', 'desc')->get(),
            'Wallet_Inters_last' => WalletInter::join('wallets', 'wallet_inters.wallet_id', 'wallets.id')
                ->where('wallet_inters.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_inters.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_inters.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_inters.*')->limit(5)->orderBy('wallet_inters.created_at', 'desc')->get(),
            'Wallet_Payments_last' => WalletPayment::join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                ->where('wallet_payments.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_payments.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_payments.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_payments.*')->limit(5)->orderBy('wallet_payments.created_at', 'desc')->get(),
            'Wallet_Transactions_last' => WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallet_transactions.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_transactions.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_transactions.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_transactions.*')->limit(5)->orderBy('wallet_transactions.created_at', 'desc')->get(),
            'Wallet_PhoneCredits_last' => WalletPhoneCredit::join('wallets', 'wallet_phone_credits.wallet_id', 'wallets.id')
                ->where('wallet_phone_credits.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_phone_credits.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    return $query->where('wallet_phone_credits.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_phone_credits.*')
                ->limit(5)->orderBy('wallet_phone_credits.created_at', 'desc')->get(),
            'Wallet_Reloads_last' => WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->select('wallet_reloads.*')
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('wallet_reloads.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->limit(5)->orderBy('wallet_reloads.created_at', 'desc')->get(),
            'Wallet_xbet_last' => WalletXbet::join('wallets', 'wallet_xbets.wallet_id', 'wallets.id')
                ->where('wallet_xbets.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_xbets.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_xbets.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_xbets.*')->limit(5)->orderBy('wallet_xbets.created_at', 'desc')->get(),
            'Wallet_wave_last' => WalletWave::join('wallets', 'wallet_waves.wallet_id', 'wallets.id')
                ->where('wallet_waves.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_waves.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_waves.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_waves.*')->limit(5)->orderBy('wallet_waves.created_at', 'desc')->get(),
            'With_drawer_merchant' => WithMerchant::limit(5)
                ->when(sabdy()->is_under_distributer, function ($query) {
                    return $query->where('sid_user_id', sabdy()->id);
                })->orderBy('date_removed', 'desc')->get(),

            'Wallet_virement_last' => WalletVersement::join('wallets', 'wallet_versements.wallet_id', 'wallets.id')
                ->where('wallet_versements.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_versements.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_versements.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_versements.*')->limit(5)->orderBy('wallet_versements.created_at', 'desc')->get(),

            // New
             'Wallet_push_last' => WalletPush::join('wallets', 'wallet_pushes.wallet_id', 'wallets.id')
                ->where('wallet_pushes.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_pushes.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_pushes.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_pushes.*')->limit(5)->orderBy('wallet_pushes.created_at', 'desc')->get(),

         'Wallet_bank_send_last' => WalletBankSend::join('wallets', 'wallet_bank_sends.wallet_id', 'wallets.id')
                ->where('wallet_bank_sends.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_bank_sends.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_bank_sends.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_bank_sends.*')->limit(5)->orderBy('wallet_bank_sends.created_at', 'desc')->get(),
            
              'Wallet_tresort_last' => WalletTresortMoney::join('wallets', 'wallet_tresort_money.wallet_id', 'wallets.id')
                ->where('wallet_tresort_money.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_tresort_money.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_tresort_money.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_tresort_money.*')->limit(5)->orderBy('wallet_tresort_money.created_at', 'desc')->get(),
               
                 'Wallet_djamo_last' => WalletDjamo::join('wallets', 'wallet_djamos.wallet_id', 'wallets.id')
                ->where('wallet_djamos.is_web', false)
                ->when(sabdy()->is_admin, function ($query) {
                    // Si c'est un administrateur, ne filtrez pas les résultats.
                    return $query;
                })
                ->when(sabdy()->is_under_distributer, function ($query) {
                    // Si c'est un sous-distributeur, filtrez par 'created_by'.
                    return $query->where('wallet_djamos.created_by', sabdy()->id);
                })
                ->when(sabdy()->is_distributer, function ($query) {
                    // Si c'est un distributeur, filtrez par 'updated_by'.
                    return $query->where('wallet_djamos.updated_by', sabdy()->id);
                })
                ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query))
                ->select('wallet_djamos.*')->limit(5)->orderBy('wallet_djamos.created_at', 'desc')->get(),

            'Pixel_Mobile_Zone_last' => (clone $pixelMobileZoneQuery)
                ->with([
                    'senderService.provider',
                    'senderService.country',
                    'recipientService.provider',
                    'recipientService.country',
                ])
                ->select('pixel_mobile_zone_transfers.*')
                ->limit(5)
                ->orderBy('pixel_mobile_zone_transfers.created_at', 'desc')
                ->get(),

                
            'usersCountry' =>  $walletUser->country->currencie->name,
            'sum_bank' =>  $bank_amount,
            'sum_versment' =>  $versement_amount,
            'sum_push' =>  $push_amount,
            'sum_tresortMoney' =>  $tresort_amount,
            'sum_djamo' =>  $djamo_amount,
            'sum_bankSend' =>  $bankSend_amount,
            'sum_mobile_zone' => $pixel_mobile_zone_amount,
            'sum_deposit' => $deposit_amount,
            'sum_payment' => $payement_amount,
            'sum_reload' => $reload_amount,
            'sum_transaction' => $transaction_amount,
            'sum_inter' => $inter_amount,
            'sum_phoneCredit' => $phonecredit_amount,
            'sum_xbets' => $xbet_amount,
            'sum_waves' => $wave_amount,
            // Bloque valeurs
            'showTransButton' => $this->showTransButton,
            'showCreditButton' => $this->showCreditButton,
            'showPaiementButton' => $this->showPaiementButton,
            'showInterButton' => $this->showInterButton,
            'showRechargementButton' => $this->showRechargementButton,
            'showVirementButton' => $this->showVirementButton,
            'showRetraitButton' => $this->showRetraitButton,
            'showDepotButton' => $this->showDepotButton,
            'showXbetButton' => $this->showXbetButton,
            'showCommissionButton' => $this->showCommissionButton,
            'showRecharButton' => $this->showRecharButton,
            'showWaveButton' => $this->showWaveButton,
            'showVersementButton' => $this->showVersementButton,
            'showDjamoButton' => $this->showDjamoButton,
            'showPushButton' => $this->showPushButton,
            'showBankSendButton' => $this->showBankSendButton,
            'showTresortMoneyButton' => $this->showTresortMoneyButton,
            'showPixelMobileZoneButton' => $this->showPixelMobileZoneButton,
            'sum_under_distributers' => $this->historiqueService->getSDistributeurSumAdmin(),
            'sum_custumers' => $this->historiqueService->getCustumersSumAdmin(),

        ]);
    }

    private function scopeComZoneLinkedUsers($query)
    {
        return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
            $subQuery->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
        });
    }

    private function pixelMobileZoneBaseQuery()
    {
        return PixelMobileZoneTransfer::join('wallets', 'pixel_mobile_zone_transfers.wallet_id', '=', 'wallets.id')
            ->when(sabdy()->is_under_distributer || sabdy()->is_api, function ($query) {
                return $query->where('wallets.sid_user_id', sabdy()->id);
            })
            ->when(sabdy()->is_com_zone, fn ($query) => $this->scopeComZoneLinkedUsers($query));
    }

}
