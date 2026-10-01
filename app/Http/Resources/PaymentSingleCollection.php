<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentSingleCollection extends JsonResource
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
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  number_format((float)$this->amount, 2, '.', ''),
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'date_created' =>  $this->created_at,
            'type' =>  'Paiement facture',
            'date_treatment' =>  optional($this->payment)->created_at,
            'Reference'  => optional($this->payment)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' =>  number_format((float)$this->commissions, 2, '.', ''). ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA")  ?? null,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'number_decoder' =>  $this->number_decoder,
            'amount_received'  => number_format((double)$this->amount_received, 2, '.', ' ') ,
            'id_invoice' =>  $this->id_invoice,
            'offer' => optional($this->offer)->name,
            'number_contract' =>  $this->number_contract,
            'number_phone' =>  $this->number_phone,
            'paiementChoix_phone' =>  $this->paiementChoix_phone,
            'factureChoix_type' =>  $this->factureChoix_type,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ,
            'devise'  => optional($this->wallet->currencie)->code,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
        ];
    }
}
