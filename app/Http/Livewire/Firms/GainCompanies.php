<?php

namespace App\Http\Livewire\Firms;

use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\WithMerchant;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GainCompanies extends Component
{


    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchRealod = "";
    public $statusFilter = "";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public $deposit_gain;
    public $gain_amount;
    public $payement_gain;
    public $transaction_gain;
    public $transaction_gain_mobile;
    public $transaction_gain_depot;
    public $transaction_gain_retrait;
    public $phonecredit_gain;
    public $inter_gain;
    public $bank_gain;
    public $typeRemonte;
    public $operators;
    public $transaction_gain_empl;
    public $showRemonterTransButton = false;
    public $showRemonterCreditButton = false;
    public $showRemonterPaiementButton = false;
    public $showRemonterInterButton = false;
    public $showRemonterRechargementButton = false;
    public $showRemonterVirementButton = false;
    public $showRemonterDepotButton = false;
    public $showRemonterRetraitButton = false;
    public $showRemonterMoneyButton = false;

    public function mount()
    {
        $this->deposit_gain = $this->getTodayTransactionCommissionAmountTotal(WalletDeposit::class);
        $this->payement_gain = $this->getTodayTransactionCommissionAmountTotal(WalletPayment::class);
        $this->transaction_gain_mobile = $this->getTodayTransactionMobileCommissionAmountTotal(WalletTransaction::class);
        $this->transaction_gain = $this->getTodayTransactionCommissionAmountTotal(WalletTransaction::class);
        $this->transaction_gain_depot = $this->getTodayTransactionRobusDepotCommissionAmountTotal(WalletTransaction::class);
        $this->transaction_gain_retrait = $this->getTodayTransactionRobusRetraitCommissionAmountTotal(WalletTransaction::class);
        $this->transaction_gain_empl = $this->getEmployerTransactionCommission(WalletTransaction::class);
        $this->phonecredit_gain = $this->getTodayTransactionCommissionAmountTotal(WalletPhoneCredit::class);
        $this->inter_gain = $this->getTodayTransactionCommissionAmountTotal(WalletInter::class);
        $this->bank_gain = $this->getTodayTransactionCommissionAmountTotal(WalletBank::class);
        $this->operators = OperatorService::getOperatorsWithCommissions(firm()->id);

        $restrictionMap = [
            'Active Transactions' => 'showRemonterTransButton',
            'Active Crédits' => 'showRemonterCreditButton',
            'Active Paiements' => 'showRemonterPaiementButton',
            'Active International' => 'showRemonterInterButton',
            'Active Rechargements' => 'showRemonterRechargementButton',
            'Active Virements' => 'showRemonterVirementButton',
            'Active Depot' => 'showRemonterDepotButton',
            'Active Retrait' => 'showRemonterRetraitButton',
            'Active Money' => 'showRemonterMoneyButton',
        ];

        foreach ($this->operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }
    }

    public function edit($remonteGain, $typeRemonte, $state)
    {
        // $this->showRemonterTransButton = true;
        // $this->showRemonterCreditButton = true;
        // $this->showRemonterPaiementButton = true;
        // $this->showRemonterInterButton = true;
        // $this->showRemonterRechargementButton = true;
        // $this->showRemonterVirementButton = true;
        $this->gain_amount = $remonteGain;
        $this->typeRemonte = $typeRemonte;
        $this->emit($state);
    }


    public function cancelField()
    {
        // $this->showRemonterTransButton = true;
        // $this->showRemonterCreditButton = true;
        // $this->showRemonterPaiementButton = true;
        // $this->showRemonterInterButton = true;
        // $this->showRemonterRechargementButton = true;
        // $this->showRemonterVirementButton = true;
        $this->gain_amount = null;
        $this->emit('modal-hide', 'hide');
    }


    public function remonte()
    {

        $check = SidUser::find(firm()->id);

        if (!empty($check)) {

            $amount_solde = (float)firm()->wallet->amount + (float)$this->gain_amount;

            if ($this->gain_amount <= 0) {
                $this->emit('modal-hide', 'hide');

                $this->emit('error', 'Le montant de la commission est invalide ou null !!');
                return;
            }

            if ($this->typeRemonte == "Paiements") {

                $this->gain_amount =  $this->payement_gain;
            } else if ($this->typeRemonte == "Transactions") {

                $this->gain_amount =  $this->transaction_gain;
            } else if ($this->typeRemonte == "Crédits") {

                $this->gain_amount =  $this->phonecredit_gain;
            } else if ($this->typeRemonte == "International") {

                $this->gain_amount =  $this->inter_gain;
            } else if ($this->typeRemonte == "Virements") {

                $this->gain_amount =  $this->bank_gain;
            } else if ($this->typeRemonte == "Rechargements") {

                $this->gain_amount =  $this->deposit_gain;
            } else if ($this->typeRemonte == "Depots") {

                $this->gain_amount =  $this->transaction_gain_depot;
            } else if ($this->typeRemonte == "Retraits") {

                $this->gain_amount =  $this->transaction_gain_retrait;
            } else if ($this->typeRemonte == "Moneys") {

                $this->gain_amount =  $this->transaction_gain_mobile;
            } else if ($this->typeRemonte == "Transactions Salaire") {

                $this->gain_amount =  $this->transaction_gain_empl;
            } else {
                $this->emit('error', 'Type de remontée invalide');
                return;
            }

            $withRemonte = $this->createWithCommission($amount_solde, $this->gain_amount,  $this->typeRemonte);

            if ($withRemonte !== null) {

                $this->updateWallet($withRemonte);

                if ($this->typeRemonte == "Paiements") {

                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletPayment::class);
                    $this->payement_gain = 0;
                } else if ($this->typeRemonte == "Transactions") {

                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletTransaction::class);
                } else if ($this->typeRemonte == "Crédits") {
                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletPhoneCredit::class);
                    $this->phonecredit_gain = 0;
                } else if ($this->typeRemonte == "International") {
                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletInter::class);
                    $this->inter_gain = 0;
                } else if ($this->typeRemonte == "Virements") {
                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletBank::class);
                    $this->bank_gain = 0;
                } else if ($this->typeRemonte == "Rechargements") {

                    $this->getUpdateTransactionCommissionAmountNullTotal(WalletDeposit::class);
                    $this->deposit_gain = 0;
                } else if ($this->typeRemonte == "Depots") {
                    $this->getUpdateTransactionDepotRobusCommissionAmountNullTotal(WalletTransaction::class);
                    $this->transaction_gain_depot = 0;
                } else if ($this->typeRemonte == "Retraits") {
                    $this->getUpdateTransactionRetraitRobusCommissionAmountNullTotal(WalletTransaction::class);
                    $this->transaction_gain_retrait = 0;
                } else if ($this->typeRemonte == "Moneys") {

                    $this->getUpdateTransactionMoneyCommissionAmountNullTotal(WalletTransaction::class);
                    $this->transaction_gain_mobile = 0;
                } else if ($this->typeRemonte == "Transactions Salaire") {

                    $this->getUpdateTransactionEmplCommissionAmountNullTotal(WalletTransaction::class);
                    $this->transaction_gain_empl = 0;
                } else {
                    $this->emit('error', 'Type de remontée invalide');
                    return;
                }
                $msg = 'Vous avez transferé vos commission sur votre compte';
                $msg_title = 'Transfert de la commission ';
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $this->emit('modal-hide', 'hide');
                $this->emit('success', 'Votre commission a été remonté avec success !!');
            } else {
                $this->emit('modal-hide', 'hide');
                $this->emit('error', 'Erreur de recuperation du montant de la commission !');
            }
        }
    }

    private function createWithCommission($amount_solde, $gain_amount, $typeRemonte)
    {
        return WithMerchant::create([
            'amount_with_draw' =>  $gain_amount,
            'sid_user_id' =>   firm()->id,
            'amount_solde' =>   $amount_solde,
            'type_transaction' => $typeRemonte,
            'status' =>  "SUCCESS",
            'date_removed' => Carbon::now(),
        ]);
    }

    private function updateWallet($withRemonte)
    {
        firm()->wallet->update([
            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
            'sid_user_relod' => firm()->id,
            'amount' => DB::raw('amount+'  .  $withRemonte->amount_with_draw),
        ]);
    }



    public function render()
    {

        $walletUser = firm()->id;

        $transfersQuery =  WithMerchant::where('sid_user_id', $walletUser);

        if ($this->startDate && $this->endDate) {
            $transfersQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        if (!empty($this->searchTransferHistorical)) {
            $this->resetPage();
            $transfersQuery->where('amount_with_draw', 'LIKE', '%' . $this->searchTransferHistorical . '%');
        }

        if (!empty($this->statusTransferFilter)) {
            $transfersQuery->where('status', $this->statusTransferFilter);
        }

        // Amount transactions month
        $payement_amount = $this->getDaysTransactionUnderMonth(WalletPayment::class);
        $transaction_amount = $this->getDaysTransactionUnderMonth(WalletTransaction::class);
        $phonecredit_amount = $this->getDaysTransactionUnderMonth(WalletPhoneCredit::class);
        $inter_amount = $this->getDaysTransactionUnderMonth(WalletInter::class);
        $bank_amount = $this->getDaysTransactionUnderMonth(WalletBank::class);
        $deposit_amount = $this->getDaysTransactionUnderMonth(WalletDeposit::class);


        $sum_gain = $this->payement_gain + $this->transaction_gain + $this->phonecredit_gain + $this->inter_gain + $this->bank_gain + $this->deposit_gain;

        $transferHistoricals = $transfersQuery->orderByDesc('created_at')->paginate(30);

        return view('livewire.firms.gain-companies', [
            'WalletRemontes' => $this->WalletRemonts,
            'allRemontes' => $transferHistoricals,
            'company_gains' => $sum_gain,
            'user_currencie' => firm()->country->currencie->name,
            'amount_payments' => $payement_amount,
            'amount_transactions' => $transaction_amount,
            'amount_phone_credits' => $phonecredit_amount,
            'amount_inters' => $inter_amount,
            'amount_banks' => $bank_amount,
            'showRemonterTransButton' => $this->showRemonterTransButton,
            'showRemonterCreditButton' => $this->showRemonterCreditButton,
            'showRemonterPaiementButton' => $this->showRemonterPaiementButton,
            'showRemonterInterButton' => $this->showRemonterInterButton,
            'showRemonterRechargementButton' => $this->showRemonterRechargementButton,
            'showRemonterVirementButton' => $this->showRemonterVirementButton,
            'showRemonterMoneyButton' => $this->showRemonterMoneyButton,
            'showRemonterRetraitButton' => $this->showRemonterRetraitButton,
            'showRemonterDepotButton' => $this->showRemonterDepotButton,
            // Gains
            'gain_payments' => $this->payement_gain,
            'gain_transactions' => $this->transaction_gain,
            'gain_phone_credits' => $this->phonecredit_gain,
            'gain_inters' => $this->inter_gain,
            'gain_banks' => $this->bank_gain,
            'gain_deposits' => $this->deposit_gain,
            'gain_depots' => $this->transaction_gain_depot,
            'gain_retraits' => $this->transaction_gain_retrait,
            'gain_moneys' => $this->transaction_gain_mobile,
            'gainEmpl_transactions' => $this->transaction_gain_empl,
            // Commissions remonte
            'remonteDays' => $this->RealodsAdminDaysGain,
            'remonteYear' => $this->RealodsAdminGain,
        ]);
    }

    public function getEmployerTransactionCommission($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commission_companie');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }
    public function getWalletRemontsProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WithMerchant::where('sid_user_id', firm()->id)->whereStatus('SUCCESS')->sum('amount_with_draw');
    }

    public function getTransactionCountReload($walletModel)
    {


        $totalCount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            // ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->count();

        return $totalCount;
    }

    public function getTransactionUsersCount($walletModel, $status)
    {


        $sidUsers = SidUser::where('company_id',  firm()->company->id)
            ->where('id', '!=', firm()->id)->get();
        $totalcount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalcount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->where('status', $status)->count();

        return $totalcount;
    }


    public function getDaysTransactionUnderMonth($walletModel)
    {

        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->where('commission_companie', '!=', 0)
            ->sum('amount');


        return $totalAmount;
    }



    public function getTodayTransactionCommissionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->sum('commission_companie');


        return $totalAmount;
    }

    public function getTodayTransactionRobusRetraitCommissionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commission_companie');


        return $totalAmount;
    }

    public function getTodayTransactionRobusDepotCommissionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commission_companie');


        return $totalAmount;
    }

    public function getTodayTransactionMobileCommissionAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('company_id',  firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commission_companie');


        return $totalAmount;
    }


    public function getUpdateTransactionCommissionAmountNullTotal($walletModel)
    {
        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {
            $sidUserId = $sidUser->wallet->id;
            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }

            $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->update(['commission_companie' => 0]);
        }

        return $totalAmount;
    }

    public function getUpdateTransactionMoneyCommissionAmountNullTotal($walletModel)
    {
        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {
            $sidUserId = $sidUser->wallet->id;
            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', '!=', 9)
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }

            $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', '!=', 9)
                ->update(['commission_companie' => 0]);
        }

        return $totalAmount;
    }


    public function getUpdateTransactionDepotRobusCommissionAmountNullTotal($walletModel)
    {
        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {
            $sidUserId = $sidUser->wallet->id;
            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Depot')
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }

            $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Depot')
                ->update(['commission_companie' => 0]);
        }

        return $totalAmount;
    }

    public function getUpdateTransactionRetraitRobusCommissionAmountNullTotal($walletModel)
    {
        $sidUsers = SidUser::where('company_id', firm()->company->id)
            ->where('id', '!=', firm()->id)
            ->get();

        $totalAmount = 0;

        foreach ($sidUsers as $sidUser) {
            $sidUserId = $sidUser->wallet->id;
            $amount = $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Retrait')
                ->sum('commission_companie');

            if ($amount !== null) {
                $totalAmount += $amount;
            }

            $walletModel::where('wallet_id', $sidUserId)
                ->whereStatus('SUCCESS')
                ->where('provider_id', 9)
                ->where('type_transaction', 'Retrait')
                ->update(['commission_companie' => 0]);
        }

        return $totalAmount;
    }

    public function getUpdateTransactionEmplCommissionAmountNullTotal($walletModel)
    {

        $transactions = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->get();

        $totalAmount = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->commission_companie !== null) {
                $totalAmount += $transaction->commission_companie;
                $transaction->commission_companie = null;
                $transaction->save();
            }
        }

        return $totalAmount;
    }

    public function getRealodsAdminDaysGainProperty()
    {

        $reload1 = [];
        $reload2 = [];
        $reload3 = [];
        $reload4 = [];
        $reload5 = [];
        $reload6 = [];
        $reload7 = [];

        $userId = firm()->id;

        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::MONDAY) {

                $reload1[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::TUESDAY) {

                $reload2[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::WEDNESDAY) {

                $reload3[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::THURSDAY) {

                $reload4[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::FRIDAY) {

                $reload5[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SATURDAY) {

                $reload6[] = ($reload->amount_with_draw);
            }
        }
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SUNDAY) {

                $reload7[] = ($reload->amount_with_draw);
            }
        }
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7)];
        // dd($reloads);
        return $reloads;
    }
    public function getRealodsAdminGainProperty()
    {

        $reload1 = [];
        $reload2 = [];
        $reload3 = [];
        $reload4 = [];
        $reload5 = [];
        $reload6 = [];
        $reload7 = [];
        $reload8 = [];
        $reload9 = [];
        $reload10 = [];
        $reload11 = [];
        $reload12 = [];
        $userId = firm()->id;

        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount_with_draw);
        foreach (WithMerchant::where('sid_user_id', $userId)->where('status', 'SUCCESS')->where('created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->amount_with_draw);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }
}
