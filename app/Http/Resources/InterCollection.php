<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InterCollection extends ResourceCollection
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
                    'inter_cost_percent' => $data->costs_amount ?? null,
                    'number_compte' => $data->number_compte,
                    'country_id' => optional($data->country)->name,
                    'phone' => $data->phone,
                    'date_created' =>  $data->created_at,
                    'date_treatment' =>  optional($data->transactionInter)->created_at,         
                    'status'  => $data->status,
                    'commissions'  => $data->commissions,
                ];
            })
        ];
    }
}