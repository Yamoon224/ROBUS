<?php

namespace App\Http\Services;

use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use App\Repositories\TransactionRepository;
use App\Http\Services\OperatorRestrictionService;


class MobileWalletTransactionService
{

    /** @var  TransactionRepository */
    private $transctionRepository;
    protected $operatorRestrictionService;

    public function __construct(
        TransactionRepository $transactionReposi,
        OperatorRestrictionService $operatorRestrictionService,
    ) {
        $this->transctionRepository = $transactionReposi;
        $this->operatorRestrictionService = $operatorRestrictionService;
    }

    public function createRobusWalletTransactionDistributer($percent_robus, $provider, $transaction_amount_money, $date_piece, $num_piece_custumers, $name_custumers, $valuer_deposit_com, $transaction_phone)
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
                'choose_type_transaction' => 1,
                'amount_received'  => 0,
                'type_transaction' => 'Depot',
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

        if ($admin_civ->count() > 0) {

            $nmbre_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);

                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'wallet_bank_id' => null,
                    'wallet_deposit_id' => null,
                    'amount_with_draw' =>  0,
                    'wallet_inter_id' => null,
                    'wallet_payment_id' => null,
                    'wallet_phone_credit_id' => null,
                    'wallet_reload_id' => null,
                    'bank_card_id' => null,
                    'description' => 'Commission administrateur',
                    'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                    'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                ]);
            }
        }

        return true;
    }

    public function processCommissionsMobileDistributer($transaction_amount_money, $percent_robus, $walletTransaction, $admin_civ, $valeur_received)
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

        // Commissions Inters 
        if (robus()->is_under_distributer == 1) {

            // 50 %
            $valuer_admin = $percent_robus->percent_admin * $valeur_percent_valeur;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit *  $valeur_received;

            $valuer_companie = $percent_robus->percent_companie * $valeur_received;


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

            if (!empty($activeButtons['showRemonterMoneyButton'])) {

                $walletTransaction->update([
                    'commissions' => DB::raw('commissions+'  . $admin_valeur_convertion),
                ]);

                $account_accepted = robus()->is_accepted_companie ?? false;

                if ($account_accepted && !empty($activeCButtons['showRemonterMoneyCButton'])) {

                    $walletTransaction->increment('commission_companie', $percent_valeur_compagnie);
                }

                if ($admin_civ->count() > 0) {

                    $nmbre_admin = $admin_civ->count();

                    $valeur_single = robus()->country->name_groupe == 'MAG' ?  $percent_valeur_convertion / $nmbre_admin : $admin_valeur_convertion / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $admin_single = SidUser::find($user->id);

                        AdminsCom::create([
                            'amount' =>   $valeur_single,
                            'sid_user_id' =>   $admin_single->id,
                            'wallet_transaction_id' => $walletTransaction->id,
                            'wallet_bank_id' => null,
                            'wallet_deposit_id' => null,
                            'amount_with_draw' =>  0,
                            'wallet_inter_id' => null,
                            'wallet_payment_id' => null,
                            'wallet_phone_credit_id' => null,
                            'wallet_reload_id' => null,
                            'bank_card_id' => null,
                            'description' => robus()->country->name_groupe == 'MAG' ? 'Paiement commission sous-distributer' : 'Commission administrateur',
                            'amount_under' =>  robus()->is_under_distributer ? $valuer_deposit : 0,
                            'description_underdistributer' =>  robus()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                        ]);
                    }
                }
            } else {


                if ($admin_civ->count() > 0) {

                    $nmbre_admin = $admin_civ->count();

                    $valeur_single = $percents / $nmbre_admin;

                    foreach ($admin_civ as $user) {
                        $admin_single = SidUser::find($user->id);

                        AdminsCom::create([
                            'amount' =>   $valeur_single,
                            'sid_user_id' =>   $admin_single->id,
                            'wallet_transaction_id' => $walletTransaction->id,
                            'wallet_bank_id' => null,
                            'wallet_deposit_id' => null,
                            'amount_with_draw' =>  0,
                            'wallet_inter_id' => null,
                            'wallet_payment_id' => null,
                            'wallet_phone_credit_id' => null,
                            'wallet_reload_id' => null,
                            'bank_card_id' => null,
                            'description' =>  'Commission administrateur',
                            'amount_under' =>   0,
                            'description_underdistributer' =>  null
                        ]);
                    }
                }
            }
        }

        return true;
    }
}
