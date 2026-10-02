<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionSinglePuscherCollection extends JsonResource
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
            'type'  =>  $this->type_transaction,
            'type_received'  => $this->type_received,
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->transaction)->created_at,
            'Reference'  => optional($this->transaction)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' =>   number_format((float)$this->commissions, 2, '.', '') ?? null,
            'number_decoder' =>  $this->number_decoder,
            'id_invoice' =>  $this->id_invoice,
            'number_decoder' =>  $this->number_decoder,
            'offer' => optional($this->offer)->name,
            'number_contract' =>  $this->number_contract,
            'number_phone' =>  $this->number_phone,
            'paiementChoix_phone' =>  $this->paiementChoix_phone,
            'factureChoix_type' =>  $this->factureChoix_type,
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'amount_solde_drawal' => $this->amount_solde_drawal . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA")
        ];
    }
}
