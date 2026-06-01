<?php

namespace App\Http\Resources;


use App\Http\Resources\InterTransactionUnderCollection;
use App\Http\Resources\OneXbetUnderCollection;
use App\Http\Resources\PaymentUnderCollection;
use App\Http\Resources\PhoneCreditUnderCollection;
use App\Http\Resources\ReloadCardUnderCollection;
use App\Http\Resources\TransactionUnderCollection;
use App\Http\Resources\VersementUnderCollection;
use App\Http\Resources\WaveUnderCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UnderCollection extends ResourceCollection
{
        /**
         * Transform the resource collection into an array.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
         */


        public function toArray($request)
        {
                return [
                        'data' => $this->collection->map(function ($data) {
                                $is_custumer = $data->is_custumers;
                                $is_under_distributer = $data->is_under_distributer;
                                $is_company = $data->is_company;
                                return [
                                        'id'      => $data->id,
                                        'firstname' => $data->firstname,
                                        'lastname' => $data->lastname,
                                        'email' => $data->email,
                                        'phone' => $data->phone,
                                        'status' => $data->status,
                                        'image' => $data->image,
                                        'type_users' => $is_custumer ? "Client" : ($is_under_distributer ? "Sous distributeur" : ($is_company ? "Compagnie" : "Master")),
                                        'wallet' => optional($data->wallet)->amount .  ' ' . optional($data?->wallet?->currencie)->code ?? null,
                                        'wallet_status' => optional($data->wallet)->status,
                                        'wallet_id' => optional($data->wallet)->wallet_id ?? null,
                                        'country' => optional($data->country)->name,
                                        'city' => optional($data->city)->name,
                                        'is_custumers' => $data->is_custumers,
                                        'is_accepted' => $data->is_accepted,
                                        'is_active' => $data->is_active,
                                        'is_distributer' => $data->is_distributer,
                                        'is_under_distributer' => $data->is_under_distributer,
                                        'is_company' => $data->is_company,
                                        'is_master' => $data->is_master,
                                        'screen' => [
                                                'screen_front' => optional($data->kyc)->screen,
                                                'screen_back' => optional($data->kyc)->screen_back,
                                                'image' => optional($data->kyc)->image,
                                                '       ' => optional($data->kyc)->motif,
                                        ],
                                        // 'transactions' =>   new TransactionUnderCollection($data?->wallet?->walletTransactions),
                                        // 'Paiements' =>   new PaymentUnderCollection($data->wallet->walletPayments),
                                        // 'Versements' =>   new VersementUnderCollection($data->wallet->walletBanks),
                                        // 'Credit telephoniques' =>   new PhoneCreditUnderCollection($data->wallet->walletPhoneCredits),
                                        // 'Rechargement de carte' =>   new ReloadCardUnderCollection($data->wallet->walletDeposits),
                                        // 'Rechargement d\'UV' =>   new reloadUnderCollection($data->wallet->walletReloads),
                                        // 'Commande de carte' =>   new relaoadCardUnderCollection($data->wallet->orderCards),
                                        // // 'Commande de carte' =>   new relaoadCardUnderCollection($data->wallet->orderCards),
                                        // 'Transfers inter' =>   new InterTransactionUnderCollection($data->wallet->walletInters),
                                        // '1xbet' =>   new OneXbetUnderCollection($data->wallet->walletXbets),
                                        // 'Achat UV wave' =>   new WaveUnderCollection($data->wallet->walletWaves),
                                ];
                        })
                ];
        }

        public function with($request)
        {
                return [
                        'success' => true,
                        'status' => 200
                ];
        }
}
