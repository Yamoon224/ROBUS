<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VirementTransactionSingleCollection extends JsonResource
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
            'type' => "Virement" ,
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->transaction)->created_at,
            'Reference'  => optional($this->transaction)->transaction_id,
            'phone' =>  $this->phone,
            'full_name' =>  $this->full_name,
            'description'  =>  $this->description,
            'num_accompte'  =>  $this->num_accompte,
            'bank_code'  =>  $this->bank_code,
            'num_authers'  =>  $this->num_authers,
            'guichet_code'  =>  $this->guichet_code,
            'amount_received'  => number_format((double)$this->amount_received, 2, '.', ' '),
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') ?? null,
            'with_cost_percent' =>  number_format((double)$this->commissions, 2, '.', '') ?? null,
            'frais' => number_format((double)$this->costs_amount, 2, '.', ' ') ,
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,

        ];
    }
}