<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\DB;
use App\Repositories\TransactionRepository;
use App\Repositories\ReloadCardRepository;
use App\Repositories\OnexbetRepository;
use App\Repositories\IntersRepository;


class TransactionWebService
{

        /** @var  TransactionRepository */
        public $transctionRepository;
        public $reloadCardRepository;
        public $xbetRepository;
        public $intersRepository;


        public function __construct(
                TransactionRepository $transactionReposi,
                ReloadCardRepository $reloadCardReposi,
                OnexbetRepository $xbetRepository,
                IntersRepository $inter_Reposi
        ) {
                $this->transctionRepository = $transactionReposi;
                $this->reloadCardRepository = $reloadCardReposi;
                $this->xbetRepository = $xbetRepository;
                $this->intersRepository = $inter_Reposi;
        }

        //    Reload cards

        public function createRobusWalletReloadCard($amount_valeur, $provider, $percent_robus, $amount_cost, $num_carte, $name , $phone, $amountReceved)
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
                        'phone'  =>  $phone,
                        'status'  => "PENDING",
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
                                        'description_underdistributer' =>   null
                                ]);
                        }
                }

                return ['success' => true, 'admin' => $admin_civ];
        }
}
