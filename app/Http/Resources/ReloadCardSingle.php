<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReloadCardSingle extends JsonResource
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
            'type' =>  'Rechargement de carte',
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->deposit)->created_at,
            'Reference'  => optional($this->deposit)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'deposit_cost_percent' =>  number_format((double)$this->commissions, 2, '.', '') ?? null,
            'num_carte' => $this->num_carte,
            'devise'  => optional($this->wallet->currencie)->code,
            'frais' => number_format((double)$this->costs_amount, 2, '.', ' ') ,
            'status'  =>  $this->status,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,

        ];
    }
}