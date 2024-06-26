<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneCreditSingleCollection extends JsonResource
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
            'amount' =>  number_format((double)$this->amount, 2, '.', ''),
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'type' =>  'Credit téléphonique',
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->paymentPhone)->created_at,
            'Reference'  => optional($this->paymentPhone)->transaction_id,
            'phone' =>  $this->phone,
            'description'  =>  $this->description,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') ?? null,
            'phone_cost_percent' =>  number_format((double)$this->commissions, 2, '.', '') ?? null,
            'frais' => number_format((double)$this->costs_amount, 2, '.', ' ') ,
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,

        ];
    }
}