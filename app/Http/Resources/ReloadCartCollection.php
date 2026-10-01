<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReloadCartCollection extends JsonResource
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
            'date_created' =>  $this->created_at,
            'type' =>  'Commande de carte',
            'date_treatment' =>  $this->updated_at ?? null,
            'percent_order_card'  => number_format((double)$this->commissions, 2, '.', '') ?? null,
            'number_card' =>  $this->number_card,
            'ref_card' =>  $this->ref_card,
            'identity_doc'  => $this->identity_doc,
            'phone'  => $this->phone,
            'identity_id'  => $this->identity_id,
            'email'  => $this->email,
            'address'  => $this->address,
            'devise'  => optional($this->wallet->currencie)->code,
            'email'  => $this->email,
            'amount_received'  => number_format((float)$this->amount_received, 2, '.', ' '),
            'frais' => number_format((double)$this->costs_amount, 2, '.', ' ') ,
            'status'  =>  $this->status == 0  ? "PENDING" : "SUCCESS",
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
        ];
    }
}