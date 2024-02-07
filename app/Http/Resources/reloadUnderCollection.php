<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\ResourceCollection;


class reloadUnderCollection extends ResourceCollection
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

                                $wallet_send = Wallet::where('wallet_id',  $data->account_wallet)->first();

                                if($wallet_send != null)
                                {
                                    $wallet_send = Wallet::where('wallet_id',  $data->account_wallet)->first();
                        
                                }else {
                        
                                    $wallet_send = null;
                        
                                }
                        
                                return [
                                        'id' => (int) $data->id,
                                        'provider'  =>  optional($data->provider)->name,
                                        'amount' =>  number_format((float)$data->amount, 2, '.', ' '),
                                        'name_user_desti' =>  null,
                                        'phone_user_desti' => null,
                                        'name_user_send' => isset(optional($data->wallet)->sidUser->firstname) ? (optional($data->wallet)->sidUser->firstname) . ' ' . (optional($data->wallet)->sidUser->lastname) : null,
                                        'phone_user_send' => optional($data->wallet)->sidUser->phone ?? null,
                                        'date_created' =>  $data->created_at,
                                        'type'  =>  $data->type_transaction,
                                        'type_received'  => $data->type_received,
                                        'phone'  => null,
                                        'date_treatment' =>  optional($data->paymentReload)->created_at,
                                        'Reference'  => optional($data->paymentReload)->transaction_id,
                                        'name' =>  $data->name,
                                        'description'  =>  $data->description,
                                        'payment_cost_percent' => number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'number_decoder' => null,
                                        'num_wallet' =>  $data->account_wallet,
                                        'id_invoice' =>  null,
                                        'offer' => null,
                                        'number_contract' =>  null,
                                        'amount_received' => number_format((float)$data->amount_received, 2, '.', ' ')  . ' ' . optional($wallet_send->currencie)->code ?? null,
                                        'number_phone' => null,
                                        'paiementChoix_phone' =>  null,
                                        'factureChoix_type' =>  null,
                                        'frais' => null,
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        // optional($data->currencie)->code
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                        'amount_solde_drawal' =>  $data->amount_solde_drawal .  ' ' . optional($this->wallet->currencie)->code ?? null
                                ];
                        })
                ];
        }
}
