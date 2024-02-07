<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\ResourceCollection;


class TransactionUnderCollection extends ResourceCollection
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
                                $wallet_send = Wallet::where('wallet_id',  $data->num_wallet)->first();

                                if ($wallet_send != null) {
                                        $wallet_send = Wallet::where('wallet_id',  $data->num_wallet)->first();
                                } else {

                                        $wallet_send = null;
                                }               

                                return [
                                        'id' => (int) $data->id,
                                        'provider'  =>  optional($data->provider)->name,
                                        'amount' =>  number_format((float)$data->amount, 2, '.', ' '),
                                        'name_user_desti' =>  Wallet::where('wallet_id',  $data->num_wallet)->first() != null ? (optional($wallet_send)->sidUser->firstname) . ' ' . (optional($wallet_send)->sidUser->lastname) : null,
                                        'phone_user_desti' => Wallet::where('wallet_id',  $data->num_wallet)->first() != null ?  optional($wallet_send)->sidUser->phone : $data->phone,
                                        'name_user_send' => isset(optional($data->wallet)->sidUser->firstname) ? (optional($data->wallet)->sidUser->firstname) . ' ' . (optional($data->wallet)->sidUser->lastname) : null,
                                        'phone_user_send' => optional($data->wallet)->sidUser->phone ?? null,
                                        'date_created' =>  $data->created_at,
                                        'type'  =>  $data->type_transaction,
                                        'type_received'  => $data->type_received,
                                        'phone'  => $data->phone ?? null,
                                        'date_treatment' =>  optional($data->transaction)->created_at,
                                        'Reference'  => optional($data->transaction)->transaction_id,
                                        'name' =>  $data->name,
                                        'description'  =>  $data->description,
                                        'payment_cost_percent' => number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'number_decoder' =>  $data->number_decoder,
                                        'num_wallet' =>  $data->num_wallet,
                                        'id_invoice' =>  $data->id_invoice,
                                        'offer' => optional($data->offer)->name,
                                        'number_contract' =>  $data->number_contract,
                                        'amount_received' => Wallet::where('wallet_id',  $data->num_wallet)->first() != null ? number_format((float)$data->amount_received, 2, '.', ' ')  . ' ' . optional($wallet_send->currencie)->code : number_format((float)$data->amount_received, 2, '.', ' '),
                                        'number_phone' =>  $data->number_phone,
                                        'paiementChoix_phone' =>  $data->paiementChoix_phone,
                                        'factureChoix_type' =>  $data->factureChoix_type,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                        'amount_solde_drawal' =>  $data->amount_solde_drawal .  ' ' . optional($data->wallet->currencie)->code ?? null
                                ];
                        })
                ];
        }
}
