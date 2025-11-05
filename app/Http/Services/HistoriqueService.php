<?php

namespace App\Http\Services;



use App\Http\Services\ConvertionService;
use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoriqueService
{

    protected $convertionService;

    public function mount(ConvertionService $convertionService)
    {
        $this->convertionService = $convertionService;
    }

    public function generateDailyReport(
        string $modelClass,
        string $relationTableWallet,
        ?string $walletCreatedBy = null,
        ?string $walletAll = null,
        ?string $walletCreated = null,
        ?string $walletStatus = null,
        string $viewPath = 'pages.historicals.pdf_bank',
        string $fileName = 'reporting_virement.pdf',
    ) {

        $user = Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = collect();


        if ($user->is_under_distributer) {


            $query = $modelClass::join('wallets', $relationTableWallet, 'wallets.id')
                ->where('wallets.country_id', $user->country->id)
                ->where($walletCreatedBy, $user->id)
                ->whereDate($walletCreated, $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'ERROR', $walletAll);
        } elseif ($user->is_distributer) {



            $query = $modelClass::where('updated_by', $user->id)
                ->whereDate('created_at', $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'ERROR', $walletAll);
        } else {


            $query = $modelClass::whereDate('created_at', $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'ERROR', $walletAll);
        }

        $bank_historicals = $query->get();

        $pdf = Pdf::loadView($viewPath, compact(
            'bank_historicals',
            'account_penddings',
            'account_success',
            'account_errors'
        ))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download($fileName);
    }

    public function generateDailyFirmReport(
        string $modelClass,
        string $relationTableWallet,
        ?string $walletCreatedBy = null,
        ?string $walletAll = null,
        ?string $walletCreated = null,
        ?string $walletStatus = null,
        string $viewPath = 'pages.historicals.pdf_bank',
        string $fileName = 'reporting_virement.pdf',
    ) {

        $user = firm();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = collect();


        if ($user->is_under_distributer) {


            $query = $modelClass::join('wallets', $relationTableWallet, 'wallets.id')
                ->where('wallets.country_id', $user->country->id)
                ->where($walletCreatedBy, $user->id)
                ->whereDate($walletCreated, $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusUnderDistributor($modelClass, $relationTableWallet, $user, $walletCreatedBy, $walletCreated, $convertions, $walletStatus, 'ERROR', $walletAll);
        } elseif ($user->is_distributer) {



            $query = $modelClass::where('updated_by', $user->id)
                ->whereDate('created_at', $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusDistributor($modelClass,  $user,  $convertions, $walletStatus, 'ERROR', $walletAll);
        } else {


            $query = $modelClass::whereDate('created_at', $convertions)
                ->select($walletAll);

            $account_penddings =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'PENDING', $walletAll);
            $account_success =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'SUCCESS', $walletAll);
            $account_errors =  $this->walletStatusAdmin($modelClass,  $convertions, $walletStatus, 'ERROR', $walletAll);
        }

        $bank_historicals = $query->get();

        $pdf = Pdf::loadView($viewPath, compact(
            'bank_historicals',
            'account_penddings',
            'account_success',
            'account_errors'
        ))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download($fileName);
    }

    public function generateDailyDistributorReport(
        string $modelClass,
        string $relationTableWallet,
        ?string $relationWalletId = null,
        ?string $relationAdminTableWalletId = null,
        ?string $relationTableWalletId = null,
        $countrie,
        ?string $adminDescription = null,
        ?string $description = null,
        ?string $walletCreated = null,
        ?string $walletAll = null,
        ?string $walletStatus = null,
        string $viewPath = 'pages.historicals.pdf_bank',
        string $fileName = 'reporting_virement.pdf',
    ) {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = collect();

        if ($usersCountry->is_admin) {


            $query = $modelClass::join($relationTableWallet, $relationWalletId, $relationAdminTableWalletId)
                ->join('wallets', 'wallets.id', $relationTableWalletId)
                ->where('wallets.country_id', $countrie->id)
                ->where($adminDescription, $description)
                ->whereDate($walletCreated, $convertions)
                ->select($walletAll);

            $account_penddings =  $this->getCountWalletTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId, $countrie->id, $relationTableWalletId, $walletStatus, 'PENDING', $convertions, $adminDescription, $description, $walletAll);
            $account_success =  $this->getCountWalletTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId, $countrie->id, $relationTableWalletId, $walletStatus, 'SUCCESS', $convertions, $adminDescription, $description, $walletAll);
            $account_errors =  $this->getCountWalletTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId, $countrie->id, $relationTableWalletId, $walletStatus, 'ERROR', $convertions, $adminDescription, $description, $walletAll);
        } else {

            return null;
        }

        $bank_historicals = $query->get();

        $pdf = Pdf::loadView($viewPath, compact(
            'bank_historicals',
            'account_penddings',
            'account_success',
            'account_errors'
        ))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download($fileName);
    }

      public function generateDailyAllDistributorReport(
        string $modelClass,
        string $relationTableWallet,
        ?string $relationWalletId = null,
        ?string $relationAdminTableWalletId = null,
        ?string $relationTableWalletId = null,
        ?string $adminDescription = null,
        ?string $description = null,
        ?string $walletCreated = null,
        ?string $walletAll = null,
        ?string $walletStatus = null,
        string $viewPath = 'pages.historicals.pdf_bank',
        string $fileName = 'reporting_virement.pdf',
    ) {

        $usersCountry  =   Auth::guard('web')->user();
        $todays = Carbon::now();
        $convertions = $todays->toDateString();
        $bank_historicals = collect();

        if ($usersCountry->is_admin) {


            $query = $modelClass::join($relationTableWallet, $relationWalletId, $relationAdminTableWalletId)
                ->join('wallets', 'wallets.id', $relationTableWalletId)
                ->where($adminDescription, $description)
                ->whereDate($walletCreated, $convertions)
                ->select($walletAll);
                
            $account_penddings =  $this->getCountWalletAllTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId,  $relationTableWalletId, $walletStatus, 'PENDING', $convertions, $adminDescription, $description, $walletAll);
            $account_success =  $this->getCountWalletAllTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId,  $relationTableWalletId, $walletStatus, 'SUCCESS', $convertions, $adminDescription, $description, $walletAll);
            $account_errors =  $this->getCountWalletAllTransaction($modelClass, $relationTableWallet, $relationWalletId, $relationAdminTableWalletId,  $relationTableWalletId, $walletStatus, 'ERROR', $convertions, $adminDescription, $description, $walletAll);
        } else {

            return null;
        }

        $bank_historicals = $query->get();

        $pdf = Pdf::loadView($viewPath, compact(
            'bank_historicals',
            'account_penddings',
            'account_success',
            'account_errors'
        ))->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download($fileName);
    }

    private function getCountWalletTransaction($walletModel, $walletModelTransaction, $relationWalletId, $relationAdminTableWalletId, $countrie, $relationTableWalletId, string $walletStatus, string $status, $convertions, string $adminDescription, string $description, string $walletAll)
    {


        $results =  $walletModel::join($walletModelTransaction, $relationWalletId, $relationAdminTableWalletId)
            ->join('wallets', 'wallets.id', $relationTableWalletId)
            ->where('wallets.country_id', $countrie)
            ->where($walletStatus, $status)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where($adminDescription, $description)
            ->select($walletAll)
            ->count();

        return  $results;
    }

    private function getCountWalletAllTransaction($walletModel, $walletModelTransaction, $relationWalletId, $relationAdminTableWalletId, $relationTableWalletId, string $walletStatus, string $status, $convertions, string $adminDescription, string $description, string $walletAll)
    {


        $results =  $walletModel::join($walletModelTransaction, $relationWalletId, $relationAdminTableWalletId)
            ->join('wallets', 'wallets.id', $relationTableWalletId)
            ->where($walletStatus, $status)
            ->whereDate('admins_coms.created_at', $convertions)
            ->where($adminDescription, $description)
            ->select($walletAll)
            ->count();

        return  $results;
    }

    private function walletStatusUnderDistributor($walletTransaction, $walletRelation, $users, $walletCreatedBy, $walletCreated, $conversions, string $walletStatus, string $status, string $walletAll)
    {
        return $walletTransaction::join('wallets', $walletRelation, 'wallets.id')
            ->where('wallets.country_id', $users->country->id)
            ->where($walletCreatedBy, $users->id)
            ->whereDate($walletCreated, $conversions)
            ->where($walletStatus, $status)->select($walletAll)->count();
    }

    private function walletStatusDistributor($walletTransaction,  $users,  $conversions, string $walletStatus, string $status, string $walletAll)
    {
        return $walletTransaction::where('updated_by', $users->id)
            ->whereDate('created_at', $conversions)
            ->where($walletStatus, $status)->select($walletAll)->count();
    }

    private function walletStatusAdmin($walletTransaction,  $conversions, string $walletStatus, string $status, string $walletAll)
    {
        return $walletTransaction::whereDate('created_at', $conversions)
            ->where($walletStatus, $status)->select($walletAll)->count();
    }

    private function applyDateFilter($query, string $dateField, string $period)
    {
        $today = Carbon::today();

        switch ($period) {
            case 'thisWeek':
                $query->whereBetween($dateField, [$today->startOfWeek(), $today->endOfWeek()]);
                break;

            case 'thisMonth':
                $query->whereMonth($dateField, $today->month)
                    ->whereYear($dateField, $today->year);
                break;

            default: // today
                $query->whereDate($dateField, $today);
        }
    }

    public function getTransactionAllamount($walletModel)
    {

        return $walletModel::whereStatus('SUCCESS')
            ->sum('amount');
    }

    public function getWalletAdminCommissionProperty($walletTransaction, string $walletTransactionId, string $walletStatus, string $allWallets)
    {

        $data = [];
        foreach (
            $walletTransaction::join('wallets', $walletTransactionId, 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where($walletStatus, 'SUCCESS')->select($allWallets)->get() as $bank
        ) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletCommissionUserProperty($walletTransaction, string $walletTransactionId, string $walletCreated, string $walletStatus, string $allWallets)
    {

        $data = [];
        foreach (
            $walletTransaction::join('wallets', $walletTransactionId, 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where($walletCreated, Auth::guard('web')->user()->id)->where($walletStatus, 'SUCCESS')->select($allWallets)->get() as $bank
        ) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getTransactionsStatusProperty($walletTransaction, string $walletIsWebHistorical, string  $walletStatus, $statusTransferFilter, string $walletCreated, string  $walletUpdated)
    {

        $walletUser = Auth::guard('web')->user();

        $data = [];

        $wallets = $walletTransaction::where($walletIsWebHistorical, false)->where($walletStatus, $statusTransferFilter);


        if ($walletUser->is_under_distributer) {
            $wallets->where($walletCreated, $walletUser->id);
        } elseif ($walletUser->is_distributer) {
            $wallets->where($walletUpdated, $walletUser->id);
        } else {
            $wallets->where($walletCreated, '!=', $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getTransactionsAllDaysProperty($walletTransaction, $walletTransactionIsWeb, string $walletCreated, string  $walletUpdated)
    {


        $data = [];

        $wallets = $walletTransaction::where($walletTransactionIsWeb, false);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            $wallets->where($walletCreated, $walletUser->id);
        } elseif ($walletUser->is_distributer) {
            $wallets->where($walletUpdated, $walletUser->id);
        } else {

            $wallets->where($walletCreated, '!=', $walletUser->id)
                ->orWhere($walletCreated, $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getTransactionsDaysProperty($walletTransaction, $beginDate, $endDate, string $walletCreated, string  $walletUpdated)
    {


        $data = [];

        $wallets = $walletTransaction::whereIsWeb(false)->whereBetween('created_at', [$beginDate, $endDate]);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            $wallets->where($walletCreated, $walletUser->id);
        } elseif ($walletUser->is_distributer) {
            $wallets->where($walletUpdated, $walletUser->id);
        } else {
            $wallets->where($walletCreated, '!=', $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getTransactionAllCount($walletModel, $wallet_transactions, $transaction_days)
    {


        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }

    public function getCountWalletCountSuccess($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type, $wallet_id, $countrie, $wallet_success, $status)
    {

        $results =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->join('wallets', 'wallets.id', $wallet_id)
            ->where('wallets.country_id', $countrie)
            ->where($wallet_success, $status)
            ->where('admins_coms.is_web', false)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }

    public function getCountWalletVisitorsCountSuccess($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type, $wallet_success, $status)
    {

        $results =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where($wallet_success, $status)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select('admins_coms.*')
            ->count();

        return  $results;
    }


    public function getCumulsWalletTransactions($wallet, $wallet_transaction, $countryId, $wallet_commission, $wallet_success)
    {
        $cumulAmountComissions = $wallet::join('wallets', 'wallets.id', $wallet_transaction)
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countryId)
            ->where($wallet_commission, '>', 0) // Condition modifiée pour exclure les montants nuls
            ->where($wallet_success, 'SUCCESS')
            // ->groupBy($wallet_created)
            ->sum($wallet_commission);


        return $cumulAmountComissions;
    }

    public function getCumulsWalletDepositDistributers($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type, $wallet_id, $countrie)
    {


        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->join('wallets', 'wallets.id', $wallet_id)
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.is_web', false)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        return $result->sum('cumul_amount_chiffre');
    }

    public function getCumulsWalletDepositVisitorsDistributers($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
    {


        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->select(DB::raw('sum(admins_coms.amount) as cumul_amount_chiffre'))
            ->groupBy('admins_coms.created_at')
            ->get();


        return $result->sum('cumul_amount_chiffre');
    }

    public function getWalletLimitViews($wallet, $wallet_transaction, $countryId, $wallet_commission, $wallet_success, $wallet_created)
    {
        return $wallet::join('wallets', 'wallets.id', $wallet_transaction)
            ->join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countryId)
            ->where($wallet_commission, '!=', 0)
            ->where($wallet_success, 'SUCCESS')
            ->groupBy($wallet_created)
            ->limit(5)
            ->get();
    }

    public function getWalletBankDistributersLimitViews($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type, $wallet_id, $countrie)
    {

        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->join('wallets', 'wallets.id', $wallet_id)
            ->where('wallets.country_id', $countrie)
            ->where('admins_coms.is_web', false)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();


        return   $result;
    }

    public function getWalletBankVisitorsDistributersLimitViews($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
    {

        $result =  AdminsCom::join($wallet_transactions, $wallet_transaction_id, $wallet_transaction_type)
            ->where('admins_coms.is_web', true)
            ->where('admins_coms.description_distributer', 'Validation distributeur')
            ->groupBy('admins_coms.created_at')
            ->limit(5)
            ->get();


        return   $result;
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            ->where($wallet_status, $status)
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $walletMy, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereMonth($transaction_days, now()->month)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    public function getDaysTransactionCountrie($walletModel, $wallet_transaction, $countrie, $transaction_days, $walletMy, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', $countrie)
            ->whereMonth($transaction_days, now()->month)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    public function getTodayTransactionAmountCountrieTotal($walletModel, $wallet_transaction, $countrie, $transaction_days, $walletMy, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->where('wallets.country_id', $countrie)
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }


    public function getDaysReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth(DB::raw('created_at'), now()->month)
            ->sum('amount');

        return $totalAmount;
    }

    public function getTodayReloadAmountTotal($walletModel)
    {


        $totalAmount = $walletModel::where('wallet_id',  sabdy()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount;
    }

    public function getSDistributeurSumAdmin()
    {


        $data = [];

        foreach (
            Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
                ->where('sid_users.is_under_distributer', true)
                ->where('wallets.country_id', 107)
                ->select('wallets.*')
                ->get() as $wallet
        ) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }


    public function sumwalletCountry($countrie, $user_type)
    {
        return Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('wallets.country_id', $countrie)
            ->where($user_type, true)
            ->sum('amount');
    }

    public function getSDistributeurSumCountryAdmin($countrie)
    {


        $data = [];

        foreach (
            Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
                ->where('sid_users.is_under_distributer', true)
                ->where('wallets.country_id', $countrie)
                ->select('wallets.*')
                ->get() as $wallet
        ) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getCustumersSumCountrieAdmin($countrie)
    {


        $data = [];

        foreach (
            Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
                ->where('sid_users.is_custumers', true)
                ->where('wallets.country_id', $countrie)
                ->select('wallets.*')
                ->get() as $wallet
        ) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }


    public function getCustumersSumAdmin()
    {


        $data = [];

        foreach (
            Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
                ->where('sid_users.is_custumers', true)
                ->where('wallets.country_id', 107)
                ->select('wallets.*')
                ->get() as $wallet
        ) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }
}
