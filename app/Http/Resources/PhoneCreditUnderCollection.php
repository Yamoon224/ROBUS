<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PhoneCreditUnderCollection extends ResourceCollection
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
                                        'type' =>  'Credit téléphonique',
                                        'date_created' =>  $data->created_at,
                                        'date_treatment' =>  optional($data->paymentPhone)->created_at,
                                        'Reference'  => optional($data->paymentPhone)->transaction_id,
                                        'phone' =>  $data->phone,
                                        'description'  =>  $data->description,
                                        'phone_cost_percent' =>  number_format((float)$data->commissions, 2, '.', '') ?? null,
                                        'frais' => number_format((float)$data->costs_amount, 2, '.', ' '),
                                        'devise'  => optional($data->wallet->currencie)->code,
                                        'status'  =>  $data->status,
                                        'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                                ];
                        })
                ];
        }
}
