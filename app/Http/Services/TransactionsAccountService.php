<?php

namespace App\Http\Services;

use App\Models\Custumers\Zone;
use App\Models\Sid\SidUser;
use App\Models\WithMerchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionsAccountService
{


    public function getLastTransactionsForWallet($walletId, $limit = 6)
    {
        return DB::table(function ($query) use ($walletId) {

            $query->from('wallet_payments')
                ->select('id', 'amount', 'created_at', DB::raw("'payment' as type"))
                ->where('wallet_id', $walletId)

                ->unionAll(
                    DB::table('wallet_banks')
                        ->select('id', 'amount', 'created_at', DB::raw("'bank' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_phone_credits')
                        ->select('id', 'amount', 'created_at', DB::raw("'phone_credit' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('bank_cards')
                        ->select('id', 'amount', 'created_at', DB::raw("'bank_card' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_xbets')
                        ->select('id', 'amount', 'created_at', DB::raw("'xbet' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_waves')
                        ->select('id', 'amount', 'created_at', DB::raw("'wave' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_pushes')
                        ->select('id', 'amount', 'created_at', DB::raw("'push' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_djamos')
                        ->select('id', 'amount', 'created_at', DB::raw("'djamo' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_tresort_money')
                        ->select('id', 'amount', 'created_at', DB::raw("'tresor' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_versements')
                        ->select('id', 'amount', 'created_at', DB::raw("'virement' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_bank_sends')
                        ->select('id', 'amount', 'created_at', DB::raw("'bank_send' as type"))
                        ->where('wallet_id', $walletId)
                )

                ->unionAll(
                    DB::table('wallet_deposits')
                        ->select('id', 'amount', 'created_at', DB::raw("'deposit' as type"))
                        ->where('wallet_id', $walletId)
                );
        }, 'transactions')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getTransactionUserWallet($wallet, $id, $num_wallet, $wallet_user)
    {
        return  $wallet::where('wallet_id', $id)
            ->orWhere($num_wallet,  $wallet_user)
            ->orderBy('created_at', 'desc')->paginate(20);
    }


    public function getTransactionsWallet($wallet, $id)
    {
        return  $wallet::where('wallet_id', $id)
            ->orderBy('created_at', 'desc')->paginate(20);
    }


    public function getTransactionsPaginatedWallet($wallet, $id, $page = 1, $perPage = 20)
    {
        return $wallet::where('wallet_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }



    public function updateWallet($withRemonte)
    {
        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount+'  .  $withRemonte->amount_with_draw),
        ]);
    }

    public function getTransactionWalletDetailsCount($wallet, $id, $status)
    {

        return $wallet::where('wallet_id', $id)->where('status', $status)
            ->count();
    }


    public function getTransactionserUDetailsCount($wallet, $id, $status)
    {

        return $wallet::where('updated_by', $id)->where('status', $status)
            ->count();
    }


    public function getTransactionOperatorsAmount($wallets, $id)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('updated_by', $id)->where('status', 'SUCCESS')->sum('amount');
    }



    public function getTransactionMasterCount($walletModel,  $status)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalcount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->where('status', $status)->count();

        return $totalcount;
    }



    public function getTransactionMasterMonthCount($walletModel)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalcount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereMonth('created_at', now()->month)->count();

        return $totalcount;
    }

    public function getTransactionMasterYearCount($walletModel)
    {


        $sidUsers = SidUser::where('master_id',  robus()->id)->get();
        $totalcount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalcount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereMonth('created_at', now()->month)->count();

        return $totalcount;
    }

    public function getTodayTransactionGainTotal($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  robus()->id)->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('commission_masters');


        return $totalAmount;
    }

    public function getMonthTransactionGain($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  robus()->id)->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('commission_masters');


        return $totalAmount;
    }



    public function getTransactionCount($wallets, $id, $status)
    {

        $wallet_counts = $wallets::where('wallet_id', $id)->where('status', $status)->count();

        return $wallet_counts;
    }

    public function getTransactionsAll($wallets, $wallet_solde, $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }


    public function getTransactionsTypeAll($wallets, $number_compte, $wallet_solde, $page = 20)
    {

        $wallet_all =   $wallets::where('wallet_id', $wallet_solde->id)
            ->orWhere($number_compte,  $wallet_solde->wallet_id)
            ->orderBy('created_at', 'desc')->paginate($page);

        return $wallet_all;
    }


    public function getUsersMasters($wallet_solde, $page = 20)
    {

        $master_users =   SidUser::where('master_id', $wallet_solde)
            ->orderBy('created_at', 'desc')->paginate($page);
        return $master_users;
    }


    public function getTransactionsTypeAllAmount($wallets, $number_compte, $wallet_solde)
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $wallet_solde->id)->orWhere($number_compte,  $wallet_solde->wallet_id)->where('status', 'SUCCESS')->sum('amount');
    }

    public function getTransactionsRecevedAllAmount($wallets, $number_compte, $wallet_solde)
    {


        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where($number_compte,  $wallet_solde->wallet_id)->where('status', 'SUCCESS')->sum('amount');
    }

    public function getTransactionAmount($wallets, $id)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->sum('amount');
    }




    public function getTransactionXbetAmount($wallets, $id, $choose)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $id)->where('choose_type_transaction', $choose)->where('status', 'SUCCESS')->sum('amount');
    }


    public function getTransactionCommissionAmount($wallets, $id)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->sum('commissions');
    }


    public function walletSumCommission($wallets, $id)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->sum('commission_masters');
    }


    public function walletSumCommissionCompany($wallets, $id)
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return $wallets::where('wallet_id', $id)->where('status', 'SUCCESS')->sum('commission_companie');
    }


    public function getWalletTransactions($wallets, $id)
    {
        return $wallets::where('wallet_id', $id)->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getAllUsers()
    {
        // Logique pour récupérer tous les utilisateurs
        return Zone::all();
    }

    public function getUserTransactions($wallets, $wallet_solde, $numWallet)
    {



        return $wallets::query()
            ->where(function ($query) use ($wallet_solde, $numWallet) {
                $query->where('wallet_id', $wallet_solde->id)
                    ->orWhere($numWallet, $wallet_solde->wallet_id);
            })

            ->limit(6)
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getUserAllTransactions($wallets, $wallet_solde)
    {


        return   $wallets::query()
            ->where(function ($query) use ($wallet_solde) {
                $query->where('wallet_id', $wallet_solde->id);
            })
            ->limit(6)
            ->orderBy('created_at', 'desc')
            ->get();
    }




    public function getUserAllCumulTransactions($wallets)
    {
        return   $wallets::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserAllCumulOrderTransactions($wallets)
    {
        return   $wallets::where('created_by', robus()->id)
            ->whereStatus(1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createWithCommission($amount_solde, $gain_amount, $typeRemonte)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        return WithMerchant::create([
            'amount_with_draw' =>  $gain_amount,
            'sid_user_id' =>   robus()->id,
            'ref' => 'RB_' . 'ROBUS' . '_' . $transactionCode,
            'amount_solde' =>   $amount_solde,
            'type_transaction' => $typeRemonte,
            'status' =>  "SUCCESS",
            'balance_before' =>  robus()->wallet->amount,
            'date_removed' => Carbon::now(),
        ]);
    }


    public function getUserRemonte($wallets, $userId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfDay();

        return    $wallets::query()
            ->where(function ($query) use ($userId) {
                $query->where('sid_user_id', $userId);
            })

            // ->where('created_at', '>=', $startOfWeek)
            // ->where('created_at', '<=', $endOfWeek)
            ->limit(6)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getWalletSumGainUser($wallet)
    {


        $totalAmount = 0;

        $amount = $wallet::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
        // Récupérer toutes les commissions des banques avec le statut 'SUCCESS'
        // $commissions = $wallet::where('created_by', robus()->id)->whereStatus('SUCCESS')->pluck('commissions');
        // // Calculer la somme des commissions
        // $totalCommissions = $commissions->sum();
        // return $totalCommissions;
    }

    public function getWalletSumGainMasterUser($wallet)
    {
        // Récupérer toutes les commissions des banques avec le statut 'SUCCESS'

        $commissions = $wallet::where('created_by', robus()->id)->whereStatus('SUCCESS')->pluck('commission_masters');

        // Calculer la somme des commissions
        $totalCommissions = $commissions->sum();

        return $totalCommissions;
    }

    public function getTodayTransactionMasterAmountTotal($walletModel)
    {

        $sidUsers = SidUser::where('master_id',  robus()->id)->get();

        $totalAmount = 0;

        // Une seule requête pour tous les membres au lieu d'une par membre
        $totalAmount += $walletModel::whereIn('wallet_id', $sidUsers->loadMissing('wallet')->pluck('wallet.id')->filter()->all())
            ->whereStatus('SUCCESS')
            // ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->sum('commission_masters');


        return $totalAmount;
    }


    public function getWalletTransactionDaysAccountGainUser($wallet)
    {

        $account_valeur = $wallet::whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->where('created_by', robus()->id)
            ->whereStatus('SUCCESS')
            ->count();

        return $account_valeur;
    }

    public function getWalletTransactionDaysAccountGainIntegrate($wallet)
    {

        $account_valeur = $wallet::whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->where('created_by', integrate()->id)
            ->whereStatus('SUCCESS')
            ->count();

        return $account_valeur;
    }

    public function getWalletTransactionDaysAccountStatusUser($wallet, $status)
    {

        $account_valeur = $wallet::whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->where('created_by', robus()->id)
            ->whereStatus($status)
            ->count();

        return $account_valeur;
    }

    public function getWalletTransactionDaysAccountStatusIntegrate($wallet, $status)
    {

        $account_valeur = $wallet::whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->where('created_by', integrate()->id)
            ->whereStatus($status)
            ->count();

        return $account_valeur;
    }




    public function getMonthTransactionAmount($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereMonth('created_at', now()->month)
            ->whereStatus('SUCCESS')
            ->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
        // // Récupérer toutes les amounts des banques avec le statut 'SUCCESS'
        // $amounts = $walletModel::whereStatus('SUCCESS')->whereMonth('created_at', now()->month)->pluck('amount');
        // // Calculer la somme des amounts
        // $totalAmounts = $amounts->sum();    
        // return $totalAmounts;
    }


    public function getMonthTransactionGainUser($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            // ->whereMonth('created_at', now()->month)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getMonthTransactionGainIntegrate($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', integrate()->wallet->id)
            // ->whereMonth('created_at', now()->month)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getYearTransactionGainUser($walletModel)
    {

        $year = Carbon::now()->year;

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereYear('created_at', $year)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getYearTransactionGainIntegrate($walletModel)
    {

        $year = Carbon::now()->year;

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', integrate()->wallet->id)
            ->whereYear('created_at', $year)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }



    public function getWalletTransactionMonthAccountStatusUser($wallet)
    {

        $account_valeur = $wallet::whereMonth('created_at', now()->month)
            ->where('created_by', robus()->id)
            ->count();

        return $account_valeur;
    }

    public function getWalletTransactionMonthAccountStatusIntegrate($wallet)
    {

        $account_valeur = $wallet::whereMonth('created_at', now()->month)
            ->where('created_by', integrate()->id)
            ->count();

        return $account_valeur;
    }

    public function getYearTransactionSingleGainUser($walletModel)
    {

        $totalAmount = $walletModel::where('created_by', robus()->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        return $totalAmount;
    }

    public function getYearTransactionSingleGainIntegrate($walletModel)
    {

        $totalAmount = $walletModel::where('created_by', integrate()->id)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        return $totalAmount;
    }



    public function getUnderDistributorsUser()
    {

        $under_distributors = SidUser::where('master_id', robus()->id)->get();

        return $under_distributors;
    }

    public function getMastersUser()
    {

        $under_distributors = SidUser::where('com_zone_id', robus()->id)->whereIsMaster(true)->get();

        return $under_distributors;
    }

    public function getComZonesUser()
    {

        $under_distributors = SidUser::where('com_zone_id', robus()->id)->get();

        return $under_distributors;
    }

    public function getCustmersUser()
    {

        $under_distributors = SidUser::where('com_zone_id', robus()->id)->whereIsCustumers(true)->get();

        return $under_distributors;
    }

    public function getUnderDistributorsComUser()
    {


        $under_distributors = SidUser::where('com_zone_id', robus()->id)->whereIsUnderDistributer(true)->get();

        return $under_distributors;
    }

    public function getComZoneUserCounters(): array
    {
        $counts = SidUser::where('com_zone_id', robus()->id)
            ->selectRaw('
                SUM(CASE WHEN is_custumers = 1 THEN 1 ELSE 0 END) as clients,
                SUM(CASE WHEN is_under_distributer = 1 THEN 1 ELSE 0 END) as under_distributors,
                SUM(CASE WHEN is_company = 1 THEN 1 ELSE 0 END) as companies,
                SUM(CASE WHEN is_master = 1 THEN 1 ELSE 0 END) as masters
            ')
            ->first();

        return [
            'clients' => (int) ($counts->clients ?? 0),
            'under_distributors' => (int) ($counts->under_distributors ?? 0),
            'companies' => (int) ($counts->companies ?? 0),
            'masters' => (int) ($counts->masters ?? 0),
        ];
    }

    public function getTodayTransactionCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionRobusRetraitCommissionAmountTotal($walletModel)
    {

        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTodayTransactionRobusDepotCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getTransactionCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }


    public function getTodayTransactionMobileCommissionAmountTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id',  robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        return $totalAmount;
    }

    public function getUpdateTransactionCommissionAmountNullTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }

    public function getUpdateTransactionCommissionCreatedAmountNullTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('created_by', robus()->id)
            ->whereStatus('SUCCESS')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('created_by', robus()->id)
            ->whereStatus('SUCCESS')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }


    public function getUpdateTransactionMoneyCommissionAmountNullTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', '!=', 9)
            ->update(['commissions' => 0]);

        return $totalAmount;
    }


    public function getUpdateTransactionDepotRobusCommissionAmountNullTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Depot')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }

    public function getUpdateTransactionRetraitRobusCommissionAmountNullTotal($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->sum('commissions');

        if ($amount !== null) {
            $totalAmount += $amount;
        }

        $walletModel::where('wallet_id', robus()->wallet->id)
            ->whereStatus('SUCCESS')
            ->where('provider_id', 9)
            ->where('type_transaction', 'Retrait')
            ->update(['commissions' => 0]);

        return $totalAmount;
    }
}
