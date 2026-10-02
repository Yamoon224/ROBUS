<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class relaoadCardUnderCollection extends ResourceCollection
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
                                        'type' =>  'Rechargement de carte',
                                        'date_created' =>  $data->created_at,
                                        'date_treatment' =>  optional($data->deposit)->created_at,
                                        'Reference'  => optional($data->deposit)->transaction_id,
                                        'name' =>  $data->name,
                                        'description'  =>  $data->description,
                                        'deposit_cost_percent' =>  number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'num_carte' => $data->num_carte,
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'master_cost_percent' =>  number_format((float)$data->commission_masters, 2, '.', '') ?? null,
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                ];
                        })
                ];
        }
}
