<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class VersementUnderCollection extends ResourceCollection
{
    use \App\Http\Resources\Concerns\BatchesLazyLoads;
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
                                        'type' =>  'Virement',
                                        'date_treatment' =>  optional($data->paymentBank)->created_at,
                                        'Reference'  => optional($data->paymentBank)->transaction_id,
                                        'description'  =>  $data->description,
                                        'bank_cost_percent' => number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'number_compte' =>  $data->number_compte,
                                        'client' => [
                                                'firstname' =>  $data->firstname ?? null,
                                                'lastname' =>  $data->lastname ?? null,
                                                'phone' =>  $data->phone ?? null,
                                        ],

                                        'name_user_desti' =>  null,
                                        'phone_user_desti' => null,
                                        'name_user_send' => null,
                                        'phone_user_send' => null,
                                        'type_received'  => 'Virement',
                                        'phone'  => $data->phone ?? null,
                                        'name' => null,
                                        'description'  =>  $data->description ?? null,
                                        'master_cost_percent' =>  number_format((float)$data->commission_masters, 2, '.', '') ?? null,
                                        'payment_cost_percent' => number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'number_decoder' =>  null,
                                        'num_wallet' => null,
                                        'id_invoice' =>  null,
                                        'offer' => null,
                                        'number_contract' =>  null,
                                        'amount_received' =>  null,
                                        'number_phone' =>  null,
                                        'paiementChoix_phone' =>  null,
                                        'factureChoix_type' =>  null,
                                        'amount_solde_drawal' =>  null,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                ];
                        })
                ];
        }
}
