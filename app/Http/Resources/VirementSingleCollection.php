<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VirementSingleCollection extends JsonResource
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
            'type' =>  'Virement',
            'date_treatment' =>  optional($this->paymentBank)->created_at,
            'Reference'  => optional($this->paymentBank)->transaction_id,
            'description'  =>  $this->description,
            'bank_cost_percent' => number_format((float)$this->commissions, 2, '.', '') ?? null,
            'number_compte' =>  $this->number_compte,
            'client' => [
                'firstname' =>  $this->firstname ?? null,
                'lastname' =>  $this->lastname ?? null,
                'phone' =>  $this->phone ?? null,
            ],

            // 'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'name_user_desti' =>  null,
            // 'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname). ' ' . (optional($wallet_send)->sidUser->lastname)  : null,
            'phone_user_desti' => null,
            'name_user_send' => null,
            // 'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname)  : null,
            'phone_user_send' => null,
            'type_received'  => 'Virement',
            'phone'  => $this->phone ?? null,
            'name' => null,
            'payment_cost_percent' => number_format((float)$this->commissions, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'number_decoder' =>  null,
            'num_wallet' => null,
            'id_invoice' =>  null,
            'offer' => null,
            'number_contract' =>  null,
            'amount_received'  => number_format((double)$this->amount_received, 2, '.', ' ') ,
            'number_phone' =>  null,
            'paiementChoix_phone' =>  null,
            'factureChoix_type' =>  null,
            // optional($this->currencie)->code
            'amount_solde_drawal' =>  null,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ,
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,

        ];
    }
}
