<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymenentCollection extends ResourceCollection
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
                    'id'      => (int) $data->id,
                    'provider'  =>  $data->provider->name,
                    'amount' => $data->amount,
                    'description'  => $data->description,
                    'name'  => $data->name,
                    'payment_cost_percent' =>  $data->costs_amount ?? null,
                    'number_phone' => $data->number_phone,
                    'number_decoder' => $data->number_decoder,
                    'id_invoice' => $data->id_invoice,
                    'number_contract' => $data->number_contract,
                    'offers' => optional($data->offer)->name,
                    'paiementChoix_phone' => $data->paiementChoix_phone,
                    'factureChoix_type' => $data->factureChoix_type,
                    'date_created' =>  $data->created_at,
                    'date_treatment' =>  optional($data->payment)->created_at,
                    'status'  => $data->status,
                    'commissions'  => $data->commissions,
                    'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
                ];
            })
        ];
    }
}
