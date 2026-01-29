<?php

namespace App\Http\Services;

use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\TransactionRepository;
use App\Http\Services\OperatorRestrictionService;
use App\Http\Services\CommissionService;


class MobileWalletTransactionService
{

    /** @var  TransactionRepository */
    private $transctionRepository;
    protected $operatorRestrictionService;
    public $commissionServices;

    public function __construct(
        TransactionRepository $transactionReposi,
        OperatorRestrictionService $operatorRestrictionService,
        CommissionService $commissionServices

    ) {
        $this->transctionRepository = $transactionReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
        $this->commissionServices = $commissionServices;
    }

    public function createRobusDrawalWalletTransactionDistributer($percent_robus, $provider, $transaction_amount_money, $date_piece, $num_piece_custumers, $name_custumers, $valuer_deposit_com, $transaction_phone, $choose_type_transaction, $type, $numberPiece)
    {

        $val_amount = $transaction_amount_money;

        $amount_solde = (float)robus()->wallet->amount - (float)$val_amount;

        return $this->transctionRepository->create(
            [
                'wallet_id' =>   robus()->wallet->id,
                'amount' => $transaction_amount_money,
                'description'  => $transaction_description ?? null,
                'transaction_cost_id' => $percent_robus->id,
                'phone'   => robus()->country->indecative . $transaction_phone,
                'created_by' => robus()->id,
                'provider_id'  =>   $provider,
                'num_wallet'   =>  null,
                'costs_amount' => $valuer_deposit_com,
                'name_complet' => $name_custumers ?? null,
                'num_piece' => $num_piece_custumers ?? null,
                'validated_date' => $date_piece ?? null,
                'choose_type_transaction' => $choose_type_transaction ?? 1,
                'amount_received'  => 0,
                'type_transaction' => $choose_type_transaction == 1 ? 'Depot' : 'Retrait',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null,
                'type_piece' => $type ?? null,
                'num_piece' => $numberPiece ?? null
            ]
        );
    }
    public function createRobusWalletTransactionDistributer($percent_robus, $provider, $transaction_amount_money, $date_piece, $num_piece_custumers, $name_custumers, $valuer_deposit_com, $transaction_phone, $choose_type_transaction)
    {

        $val_amount = $transaction_amount_money;

        $amount_solde = (float)robus()->wallet->amount - (float)$val_amount;

        return $this->transctionRepository->create(
            [
                'wallet_id' =>   robus()->wallet->id,
                'amount' => $transaction_amount_money,
                'description'  => $transaction_description ?? null,
                'transaction_cost_id' => $percent_robus->id,
                'phone'   => robus()->country->indecative . $transaction_phone,
                'created_by' => robus()->id,
                'provider_id'  =>   $provider,
                'num_wallet'   =>  null,
                'costs_amount' => $valuer_deposit_com,
                'name_complet' => $name_custumers ?? null,
                'num_piece' => $num_piece_custumers ?? null,
                'balance_before' => robus()->wallet->amount,
                'validated_date' => $date_piece ?? null,
                'choose_type_transaction' => $choose_type_transaction ?? 1,
                'amount_received'  => 0,
                'type_transaction' => $choose_type_transaction == 1 ? 'Depot' : 'Retrait',
                'status'  => "PENDING",
                'amount_solde' => $amount_solde ?? null
            ]
        );
    }



    public function updateAmountRobusReceivedMobile($walletTransaction, $valeur_received)
    {

        $commission_transaction_val_inter_convertion_send = (float)  $valeur_received;

        if ($commission_transaction_val_inter_convertion_send) {

            $walletTransaction->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {

            return false;
        }
    }

    public function processCommissionsMobile($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = robus()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        $valeur_percent_valeur  = $percent_robus->percent * $transaction_amount_money;

        $valuer_deposit = $percent_robus->percent_deposit *  $valeur_percent_valeur;

        $valuer_admin = $valeur_percent_valeur;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {

            $admin_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {

            $admin_valeur_convertion = (float)   $valuer_admin;
        } else {

            $admin_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
        }

        $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);

        return true;
    }

    public function processCommissionsDrawalMobile($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = robus()->wallet;


        // Préparez les valeurs mises à jour
        // $updatedValues = [
        //     'reload_by' => robus()->firstname . ' ' . robus()->lastname,
        //     'sid_user_relod' => robus()->id,
        //     'amount' => DB::raw('amount - ' . $commission),
        // ];

        // $wallet->update($updatedValues);

        $valeur_percent_valeur  = $percent_robus->percent * $transaction_amount_money;

        $valuer_admin = $valeur_percent_valeur;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {

            $admin_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {

            $admin_valeur_convertion = (float)   $valuer_admin;
        } else {

            $admin_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
        }

        $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $admin_valeur_convertion);

        return true;
    }


    public function processCommissionsMobileDistributer($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();
        $wallet = robus()->wallet;
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;
        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }

        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        $valeur_percent_valeur  = $percent_robus->percent * $transaction_amount_money;

        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_valeur;

            $valuer_deposit = $percent_robus->percent_deposit *  $commission;

            $valuer_companie = $percent_robus->percent_companie * $commission;

            if ($country_civ->currencie->value > robus()->country->currencie->value) {
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) robus()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
                $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) robus()->country->currencie->value;
            } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
                $admin_valeur_convertion =   (float)  $valuer_deposit;
                $percent_valeur_convertion =   (float)  $valuer_admin;
                $percent_valeur_compagnie =   (float)  $valuer_companie;
            } else {
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) robus()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
                $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) robus()->country->currencie->value;
            }

            if (!empty($activeButtons['showRemonterMoneyButton'])) {

                $walletTransaction->update([
                    'commissions' => DB::raw('commissions+'  . $admin_valeur_convertion),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterMoneyCButton'])) {

                    $walletTransaction->increment('commission_companie', $percent_valeur_compagnie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_compagnie, $admin_valeur_convertion);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_convertion, $percent_valeur_convertion);
                }
            }
        }

        return true;
    }

    public function processCommissionsDrawalDistributer($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ)
    {

        $commission =  $transaction_amount_money;
        $country_civ = Country::whereId(107)
            ->first();
        $wallet = robus()->wallet;
        $userId = robus()->id;
        $activeButtons = $this->operatorRestrictionService->getActiveButtons($userId);
        $account_accepted = robus()->is_accepted_companie ?? false;
        $company_id = robus()->company_id ?? null;
        if ($company_id) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }

        if ($sidUserId) {
            $activeCButtons = $this->operatorRestrictionService->getActiveCButtons($sidUserId);
        } else {
            $activeCButtons = [];
        }

        // Préparez les valeurs mises à jour
        // $updatedValues = [
        //     'reload_by' => robus()->firstname . ' ' . robus()->lastname,
        //     'sid_user_relod' => robus()->id,
        //     'amount' => DB::raw('amount - ' . $commission),
        // ];

        // $wallet->update($updatedValues);

        $valeur_percent_valeur  = $percent_robus->percent * $transaction_amount_money;

        // Commissions Inters 
        if (robus()->is_under_distributer == 1 || robus()->is_api == 1) {

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_valeur;

            // 20%
            $valuer_deposit = $percent_robus->percent_with_drawal *  $commission;

            $valuer_companie = $percent_robus->percent_companie * $commission;


            if ($country_civ->currencie->value > robus()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) robus()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
                $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) robus()->country->currencie->value;
                $percents =   (float)  $valeur_percent_valeur *   (float) robus()->country->currencie->value;
            } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_deposit;
                $percent_valeur_convertion =   (float)  $valuer_admin;
                $percent_valeur_compagnie =   (float)  $valuer_companie;
                $percents =   (float)  $valeur_percent_valeur;
            } else {
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_deposit *   (float) robus()->country->currencie->value;
                $percent_valeur_convertion =   (float)  $valuer_admin *   (float) robus()->country->currencie->value;
                $percent_valeur_compagnie =   (float)  $valuer_companie *   (float) robus()->country->currencie->value;
                $percents =   (float)  $valeur_percent_valeur *   (float) robus()->country->currencie->value;
            }

            if (!empty($activeButtons['showRemonterRetraitButton'])) {

                $walletTransaction->update([
                    'commissions' => DB::raw('commissions+'  . $admin_valeur_convertion),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterRetraitCButton'])) {

                    $walletTransaction->increment('commission_companie', $percent_valeur_compagnie);

                    $this->commissionServices->distributeAdminCommissionAcceptedCompanies($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_compagnie, $admin_valeur_convertion);
                } else {
                    $this->commissionServices->distributeAdminCommissionAccepteds($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_convertion, $percent_valeur_convertion);
                }
            } else {

                $this->commissionServices->distributeAdminCommissions($admin_civ, 'wallet_transaction_id', $walletTransaction->id, $percent_valeur_convertion);
            }
        }

        return true;
    }
}
