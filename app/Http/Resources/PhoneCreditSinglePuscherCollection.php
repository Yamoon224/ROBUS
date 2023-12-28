<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneCreditSinglePuscherCollection extends JsonResource
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
            'Reference'  => optional($this->paymentPhone)->transaction_id,
            'phone' =>  $this->phone,
            'description'  =>  $this->description,
            'phone_cost_percent' =>  number_format((double)$this->commissions, 2, '.', '') ?? null,
            'status'  =>  $this->status,
        ];
    }
}