<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PaymentUnderCollection extends ResourceCollection
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

                                return [
                                        'id' => (int) $data->id,
                                        'provider'  =>  optional($data->provider)->name,
                                        'amount' =>  number_format((float)$data->amount, 2, '.', ''),
                                        'amount_wallet' =>  optional($data->wallet)->amount . ' ' . optional($data->wallet->currencie)->code,
                                        'date_created' =>  $data->created_at,
                                        'type' =>  'Paiement facture',
                                        'date_treatment' =>  optional($data->payment)->created_at,
                                        'Reference'  => optional($data->payment)->transaction_id,
                                        'name' =>  $data->name,
                                        'description'  =>  $data->description,
                                        'payment_cost_percent' =>  number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'number_decoder' =>  $data->number_decoder,
                                        'id_invoice' =>  $data->id_invoice,
                                        'offer' => optional($data->offer)->name,
                                        'number_contract' =>  $data->number_contract,
                                        'number_phone' =>  $data->number_phone,
                                        'paiementChoix_phone' =>  $data->paiementChoix_phone,
                                        'master_cost_percent' =>  number_format((float)$data->commission_masters, 2, '.', '') ?? null,
                                        'factureChoix_type' =>  $data->factureChoix_type,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                ];
                        })
                ];
        }
}
