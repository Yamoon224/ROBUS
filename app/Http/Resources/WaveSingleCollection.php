<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaveSingleCollection extends JsonResource
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
            'amount' =>  number_format((float)$this->amount, 2, '.', ''),
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'date_created' =>  $this->created_at,
            'type' =>  'Achat UV par Wave',
            'date_treatment' =>  optional($this->transactionWave)->created_at,
            'Reference'  => optional($this->transactionWave)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'full_name'  =>  $this->full_name ?? null,
            'payment_cost_percent' =>  number_format((float)$this->commissions, 2, '.', '') ?? null,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') ?? null,
            'number_phone' =>  $this->phone ?? null,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' '),
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'amount_received' =>  number_format((double)$this->amount_received, 2, '.', ' ') ,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
        ];
    }
}
