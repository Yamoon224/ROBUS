<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithBankSendSingleCollection extends JsonResource
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
            'name_user_desti' =>   null,
            'phone_user_desti' =>  $this->phone,
            'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname) : null,
            'phone_user_send' => optional($this->wallet)->sidUser->phone ?? null,
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'type' => 'Bank Send',
            'date_created' =>  $this->created_at,
            'date_treatment' =>  optional($this->invoiceBankSend)->created_at,
            'Reference'  => optional($this->invoiceBankSend)->transaction_id,
            'phone' =>  $this->phone,
            'full_name' =>  $this->full_name,
            'description'  =>  $this->description,
            'number_compte'  =>  $this->number_compte,
            'name_complet'  =>  $this->name_complet,
            'num_piece'  =>  $this->num_piece,
            'name_benef'  =>  $this->name_benef,
            'validated_date'  =>  $this->validated_date,
            'provider_type'  =>  $this->provider_type,
            'choose_type_transaction'  =>  $this->choose_type_transaction,
            'identity_number'  =>  $this->identity_number,
            'country_send'  =>  optional($this->wallet->country)->name ?? null,
            'country_desti'  =>  optional($this->country)->name ?? null,
            'phone_inter_expe'  =>  $this->phone_inter_expe,
            'swit_code'  =>  $this->swit_code,
            'name'  =>  $this->name,
            'image'  =>  $this->image,
            'type_piece'  =>  $this->type_piece  ?? null,
            'choose_transaction' => $this->choose_transaction == 1 ? 'Transfert' : 'Retrait',
            'amount_received' => number_format((float)$this->amount_received, 2, '.', ' ') . ' ' . optional($this->country->currencie)->code ?? null,
            'number_phone' =>  $this->number_phone,
            'paiementChoix_phone' =>  $this->paiementChoix_phone,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'with_cost_percent' =>  number_format((float)$this->commissions, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'amount_solde_drawal' => $this->amount_solde_drawal . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA")

        ];
    }
}
