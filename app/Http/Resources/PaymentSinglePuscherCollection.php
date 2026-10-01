<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentSinglePuscherCollection extends JsonResource
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
            'amount' =>  $this->amount,
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->payment)->created_at,
            'Reference'  => optional($this->payment)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' =>   number_format((double)$this->commissions, 2, '.', '') ?? null,
            'number_decoder' =>  $this->number_decoder,
            'id_invoice' =>  $this->id_invoice,
            'offer' => optional($this->offer)->name,
            'number_contract' =>  $this->number_contract,
            'number_phone' =>  $this->number_phone,
            'paiementChoix_phone' =>  $this->paiementChoix_phone,
            'factureChoix_type' =>  $this->factureChoix_type,
            'status'  =>  $this->status,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
        ];
    }
}