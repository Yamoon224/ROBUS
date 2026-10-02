<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PhoneCreditCollection extends ResourceCollection
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
                    'id'      => (int)$data->id,
                    'provider'  =>  $data->provider->name,
                    'amount' => $data->amount,
                    'description'  => $data->description,
                    'phone_credit_cost_percent' => number_format((float)$data->commissions, 2, '.', '') ?? null,
                    'phone' => $data->phone,
                    'date_created' =>  $data->created_at,
                    'date_treatment' =>  optional($data->paymentPhone)->created_at,
                    'status'  => $data->status,
                    'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,

                ];
            })
        ];
    }
}
