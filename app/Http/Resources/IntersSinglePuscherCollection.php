<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IntersSinglePuscherCollection extends JsonResource
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
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  $this->amount,
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->transactionInter)->created_at,
            'Reference'  => optional($this->transactionInter)->transaction_id,
            'description'  =>  $this->description,
            'inter_cost_percent' =>   $this->costs_amount ?? null,
            'number_compte' =>  $this->number_compte,
            'phone' =>  $this->phone,
            'country'  =>  optional($this->country)->code,
            'status'  =>  $this->status,
        ];
    }
}