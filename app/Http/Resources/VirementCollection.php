<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VirementCollection extends ResourceCollection
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
                    'id'      => $data->id,
                    'provider'  =>  optional($data->provider)->name,
                    'amount' => $data->amount,
                    'description'  => $data->description,
                    'bank_cost_percent' => $data->costs_amount ?? null,
                    'number_compte' => $data->number_compte,
                    'firstname' => $data->firstname,
                    'lastname' => $data->lastname,
                    'phone' => $data->phone,
                    'date_created'   => $data->created_at ?? null,
                    'date_treatment' =>  optional($data->paymentBank)->created_at,
                    'status'  => $data->status,
                    'commissions'  => $data->commissions,
                    'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
                ];
            })
        ];
    }
}
