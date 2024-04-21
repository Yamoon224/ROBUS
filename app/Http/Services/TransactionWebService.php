<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletInter;
use App\Repositories\IntersRepository;
use App\Repositories\OnexbetRepository;
use App\Repositories\PhoneCreditRepository;
use App\Repositories\ReloadCardRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;


class TransactionWebService
{

        /** @var  TransactionRepository */
        public $transctionRepository;
        public $reloadCardRepository;
        public $xbetRepository;
        public $intersRepository;
        public $phoneCreditRepository;


        public function __construct(
                TransactionRepository $transactionReposi,
                ReloadCardRepository $reloadCardReposi,
                OnexbetRepository $xbetRepository,
                IntersRepository $inter_Reposi,
                PhoneCreditRepository $phoneCreditReposi
        ) {
                $this->transctionRepository = $transactionReposi;
                $this->reloadCardRepository = $reloadCardReposi;
                $this->xbetRepository = $xbetRepository;
                $this->intersRepository = $inter_Reposi;
                $this->phoneCreditRepository = $phoneCreditReposi;
        }

        //    Reload cards

        public function createRobusWalletReloadCard($amount_valeur, $provider, $percent_robus, $amount_cost, $num_carte, $name, $phone, $lastDigits, $amountReceved)
        {

                return  $this->reloadCardRepository->create([
                        'amount_solde' => 0,
                        'wallet_id' =>  0,
                        'amount' => $amount_valeur,
                        'description'  => $description ?? 'Demande de rechargement de carte',
                        'num_carte'  => $num_carte,
                        'name'  => $name,
                        'deposit_cost_id' => $percent_robus->id,
                        'costs_amount' =>  $amount_cost,
                        'created_by' => 0,
                        'provider_id'  =>  $provider,
                        'phone'  =>  '+225' .  $phone,
                        'status'  => "PENDING",
                        'last_digits' => $lastDigits,
                        'is_web' => true,
                        'amount_received'  =>  $amountReceved ?? 0.0,
                ]);
        }

        public function createRobusWalletReloadPushCard($amount_valeur, $percent_robus, $amount_cost, $num_carte, $name, $phone, $amountReceved)
        {

                return  $this->reloadCardRepository->create([
                        'amount_solde' => 0,
                        'wallet_id' =>  0,
                        'amount' => $amount_valeur,
                        'description'  => $description ?? 'Demande de rechargement de carte',
                        'num_carte'  => $num_carte,
                        'name'  => $name,
                        'deposit_cost_id' => $percent_robus->id,
                        'costs_amount' =>  $amount_cost,
                        'created_by' => 0,
                        'provider_id'  =>  91,
                        'phone'  => '+225' .  $phone,
                        'status'  => "PENDING",
                        'last_digits' => null,
                        'is_web' => true,
                        'is_push' => true,
                        'amount_received'  =>  $amountReceved ?? 0.0,
                ]);
        }

        public function updateamountReloadCardReceveid($admin_civ, $admin_valeur, $walletOrderCard)
        {

                if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();
                        $valeur_single = $admin_valeur / $count_admin;

                        foreach ($admin_civ as $user) {

                                $admin_single = SidUser::find($user->id);

                                AdminsCom::create([
                                        'amount' => $valeur_single,
                                        'sid_user_id' => $admin_single->id,
                                        'wallet_deposit_id' =>  $walletOrderCard->id,
                                        'description' => 'Commission administrateur',
                                        'amount_under' =>  0,
                                        'description_underdistributer' =>   null,
                                        'is_web' => true,
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }

        // Transactions

        public function createRobusWalletTransactionMoneys($percent_robus, $provider, $transaction_amount_money, $name_custumers, $valuer_deposit_com, $transaction_phone, $phoneBenef, $amountReceved)
        {


                return $this->transctionRepository->create(
                        [
                                'wallet_id' =>  0,
                                'amount' => $transaction_amount_money,
                                'description'  => $transaction_description ?? null,
                                'transaction_cost_id' => $percent_robus->id,
                                'phone'   => '+225' .   $transaction_phone,
                                'created_by' => 0,
                                'provider_id'  =>   $provider,
                                'num_wallet'   =>  null,
                                'costs_amount' => $valuer_deposit_com,
                                'name_complet' => $name_custumers ?? null,
                                'num_piece' =>  null,
                                'validated_date' =>  null,
                                'choose_type_transaction' => 1,
                                'type_transaction' => 'Depot',
                                'status'  => "PENDING",
                                'is_web'  => true,
                                'phone_benef' => '+225' .  $phoneBenef ?? null,
                                'amount_solde' => 0.0 ?? null,
                                'amount_received'  =>  $amountReceved ?? 0.0,
                        ]
                );
        }


        public function updateamountTransactionReceveid($admin_civ, $admin_valeur, $walletTransaction)
        {

                if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();
                        $valeur_single = $admin_valeur / $count_admin;

                        foreach ($admin_civ as $user) {

                                $admin_single = SidUser::find($user->id);

                                AdminsCom::create([
                                        'amount' => $valeur_single,
                                        'sid_user_id' => $admin_single->id,
                                        'wallet_transaction_id' => $walletTransaction->id,
                                        'description' => 'Commission administrateur',
                                        'amount_under' =>  0,
                                        'description_underdistributer' =>   null ,
                                        'is_web' => true,
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }

        // Phone Credits

        public function createRobusWalletTransactionPhoneWeb($amount_valeur, $provider, $amount_cost, $phone_benef, $phone, $name_sender, $amountReceved)
        {



                return $this->phoneCreditRepository->create([
                        'wallet_id' =>  0,
                        'amount' => $amount_valeur,
                        'description'  => 'Demande de rechargement de credit',
                        'phone_credit_cost_id' => 3,
                        'created_by' => 0,
                        'amount_solde'  => 0.0,
                        'provider_id'  =>  $provider,
                        'costs_amount' => $amount_cost,
                        'phone'  =>  '+225' . $phone,
                        'phone_benef'  =>  '+225' . $phone_benef,
                        'status'  => "PENDING",
                        'name_benef'  =>   null,
                        'name_sender'  => $name_sender ?? null,
                        'is_web'  => true,
                        'amount_received'  =>  $amountReceved ?? 0.0,
                ]);
        }


        public function updateamountTransactionPhoneCreditReceveid($admin_civ, $admin_valeur, $walletPhone)
        {

                if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();
                        $valeur_single = $admin_valeur / $count_admin;

                        foreach ($admin_civ as $user) {

                                $admin_single = SidUser::find($user->id);

                                AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_phone_credit_id' => $walletPhone->id,
                                        'description' => 'Commission administrateur',
                                        'is_web' => true,
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }

        // Robus Transactions

        public function createRobusWalletTransactionWeb($transaction_amount_robus, $percent_robus, $amountCost, $compte_number_robus, $allName, $phone, $amountReceived, $amount_drawal)
        {



                return  $this->transctionRepository->create(
                        [
                                'wallet_id' => 0,
                                'amount' => $transaction_amount_robus,
                                'description' => "Transfert d'argent vers Robus",
                                'transaction_cost_id' =>  $percent_robus->id,
                                'phone' => '+225' . $phone,
                                'costs_amount' => $amountCost,
                                'amount_received' => $amountReceived,
                                'num_wallet' => strtoupper($compte_number_robus),
                                'name_complet' => $allName,
                                'num_piece' => null,
                                'validated_date' => null,
                                'created_by' => 0,
                                'provider_id' => 9,
                                'choose_type_transaction' => 1,
                                'type_transaction' => 'Depot',
                                'type_received' => 'Transfert',
                                'status'  => "PENDING",
                                'is_web' => true,
                                'amount_solde' => 0.0,
                                'amount_solde_drawal' => $amount_drawal ?? null
                        ]
                );
        }


        // 1Xbet transactions
        public function createRobusWalletTransactionOneBet($amount_valeur, $numberAcompte, $amount_cost, $fullName, $phone, $amountReceved)
        {


                return $this->xbetRepository->create([
                        'wallet_id' =>   0,
                        'amount' => $amount_valeur,
                        'description'  => 'Demande de depot 1Xbet',
                        'Onexbet_id' => 1,
                        'created_by' => 0,
                        'amount_solde'  => 0.0  ?? null,
                        'provider_id'  =>  87,
                        'costs_amount' => $amount_cost,
                        'choose_type_transaction' => 1,
                        'num_acompte'  =>  $numberAcompte,
                        'full_name' =>  $fullName,
                        'status'  => "PENDING",
                        'is_web'  => true,
                        'phone'  => '+225' . $phone ?? null,
                        'amount_received'  =>  $amountReceved ?? 0.0,
                ]);
        }

        public function updateamount1XbetReceveid($admin_civ, $admin_valeur, $walletxbet)
        {

                if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();
                        $valeur_single = $admin_valeur / $count_admin;

                        foreach ($admin_civ as $user) {

                                $admin_single = SidUser::find($user->id);

                                AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_xbet_id' => $walletxbet->id,
                                        'description' => 'Commission administrateur',
                                        'is_web' => true,
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }

        // Tranactions Inters
        public function createMobileWalletInter($amount_transaction, $percent_robus, $country_robus, $provider_payment, $phone , $phone_benef , $name_custumers ,  $name_visitors, $amount_cost , $amountReceved)
        {

                $provider = Provider::where('provider_type_id', 8)->whereName($provider_payment)->first();

                return WalletInter::create([
                        'wallet_id' => 0,
                        'amount' => $amount_transaction,
                        'description' => null,
                        'inter_cost_id' => null,
                        'transaction_cost_id' => $percent_robus->id,
                        'phone' => '+255' . $phone ?? null,
                        'phone_benef' => $country_robus->indecative . $phone_benef ?? null,
                        'number_compte' => null,
                        'created_by' => 1,
                        'provider_id' => $provider->id,
                        'name_complet' => $name_custumers ?? null,
                        'name_benef' => $name_visitors ?? null,
                        'costs_amount' => $amount_cost,
                        'provider_type' => 1,
                        'status' => 'PENDING',
                        'country_id' => $country_robus->id,
                        'choose_type_transaction' => 1,
                        'amount_solde' => 0.0 ?? null,
                        'is_web'  => true,
                        'amount_received'  =>  $amountReceved ?? 0.0,
                ]);
        }


        public function updateamountInterReceveid($admin_civ, $admin_valeur, $walletInter)
        {

                if ($admin_civ->count() > 0) {

                        $count_admin = $admin_civ->count();
                        $valeur_single = $admin_valeur / $count_admin;

                        foreach ($admin_civ as $user) {

                                $admin_single = SidUser::find($user->id);

                                AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_inter_id' =>  $walletInter->id,
                                        'description' => 'Commission administrateur',
                                        'is_web' => true,
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }
}
