<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\ResourceCollection;


class InterTransactionUnderCollection extends ResourceCollection
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


                                $wallet_send = Wallet::where('wallet_id',  $data->number_compte)->first();

                                if ($wallet_send != null) {
                                        $wallet_send = Wallet::where('wallet_id',  $data->number_compte)->first();
                                } else {

                                        $wallet_send = null;
                                }


                                return [
                                        'id' => (int) $data->id,
                                        'provider'  =>  optional($data->provider)->name,
                                        'amount' =>  number_format((float)$data->amount, 2, '.', ''),
                                        'amount_wallet' =>  optional($data->wallet)->amount . ' ' . optional($data->wallet->currencie)->code,
                                        'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname)  . ' ' . (optional($wallet_send)->sidUser->lastname) : null,
                                        // 'name_user_desti' => isset(optional($wallet_send)->sidUser->lastname) ? (optional($wallet_send)->sidUser->lastname) : null,
                                        'phone_user_desti' => optional($wallet_send)->sidUser->phone ?? null,
                                        'name_user_send' => isset(optional($data->wallet)->sidUser->firstname) ? (optional($data->wallet)->sidUser->firstname) . ' ' . (optional($data->wallet)->sidUser->lastname) : null,
                                        // 'name_user_send' => isset(optional($data->wallet)->sidUser->lastname) ? (optional($data->wallet)->sidUser->lastname) : null,
                                        'phone_user_send' => optional($data->wallet)->sidUser->phone ?? null,
                                        'date_created' =>  $data->created_at,
                                        'type' =>  'Transfert internationnal',
                                        'date_treatment' =>  optional($data->transactionInter)->created_at,
                                        'Reference'  => optional($data->transactionInter)->transaction_id,
                                        'description'  =>  $data->description,
                                        'inter_cost_percent' =>   number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'num_wallet' =>  $data->number_compte ?? null,
                                        'num_carte' =>  $data->number_card ?? null,
                                        'type_invoice'  =>  $data->type_invoice ?? null,
                                        'number_decoder'  =>  $data->number_decoder ?? null,
                                        'id_invoice'  =>  $data->id_invoice ?? null,
                                        'number_contract'  =>  $data->number_contract ?? null,
                                        'id_invoice_pre'  =>  $data->id_invoice_pre ?? null,
                                        'name'  =>  $data->name ?? $data->name_complet ?? null,
                                        'number_phone'  =>  $data->number_phone ?? null,
                                        'master_cost_percent' =>  number_format((float)$data->commission_masters, 2, '.', '') ?? null,
                                        'amount_received' => number_format((float)$data->amount_received, 2, '.', ' ') . ' ' . optional($data->country->currencie)->code ?? null,
                                        'offer'  =>  optional($data->offer)->name ?? null,
                                        'phone' =>  $data->phone ?? null,
                                        'phone_invoice' =>  $data->phone_invoice ?? null,
                                        'client' => [
                                                'firstname' => optional($wallet_send)->sidUser->firstname  ??  $data->name_complet ??  optional($data->wallet->sidUser)->firstname ?? null,
                                                'lastname' => optional($wallet_send)->sidUser->lastname ?? $data->name ?? $data->name_complet  ??   optional($data->wallet->sidUser)->lastname ?? null,
                                                'phone' =>   optional($wallet_send)->sidUser->phone ??  $data->phone ??  optional($data->wallet->sidUser)->phone ?? null,
                                        ],
                                        'country_send'  =>  optional($data->wallet->country)->name ?? null,
                                        'country_desti'  =>  optional($data->country)->name ?? null,
                                        'devise'  =>  optional(optional($this->wallet)->currencie)->code ?? "CFA",
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'status'  =>  $data->status,
                                        // 'amount_received'  => $data->amount_received ?? null,
                                        'solde_account'  => $this->amount_solde . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
                                        'amount_solde_drawal' => $this->amount_solde_drawal . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") 
                                ];
                        })
                ];
        }
}
