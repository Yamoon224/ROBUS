<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class OneXbetUnderCollection extends ResourceCollection
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
                                        'type' => $data->choose_type_transaction == 1 ? 'Depot 1Xbet ' : ' Retrait 1Xbet',
                                        'date_treatment' =>  optional($data->transaction)->created_at,
                                        'Reference'  => optional($data->transaction)->transaction_id,
                                        'name' =>  $data->name,
                                        'description'  =>  $data->description,
                                        'full_name'  =>  $data->full_name ?? null,
                                        'code_drawal'  =>  $data->code_drawal  ?? null,
                                        'num_acompte'  =>  $data->num_acompte  ?? null,
                                        'countrie'  =>  $data->countrie  ?? null,
                                        'citie'  =>  $data->citie  ?? null,
                                        'payment_cost_percent' =>  number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'master_cost_percent' =>  number_format((float)$data->commission_masters, 2, '.', '') ?? null,
                                        'number_phone' =>  $data->phone ?? null,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'status'  =>  $data->status,
                                        'amount_received' =>  number_format((float)$data->amount_received, 2, '.', ' '),
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                ];
                        })
                ];
        }
}
