<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OneXbetSingleCollection extends JsonResource
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
            'type' => $this->choose_type_transaction == 1 ? 'Depot 1Xbet' : 'Retrait 1Xbet',
            'date_treatment' =>  optional($this->transaction)->created_at,
            'Reference'  => optional($this->transaction)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'full_name'  =>  $this->full_name ?? null,
            'code_drawal'  =>  $this->code_drawal  ?? null,
            'num_acompte'  =>  $this->num_acompte  ?? null,
            'countrie'  =>  $this->countrie  ?? null,
            'choose_transaction' => $this->choose_type_transaction == 1 ? 'Transfert' : 'Retrait',
            'citie'  =>  $this->citie  ?? null,
            'payment_cost_percent' =>  number_format((float)$this->commissions, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'number_phone' =>  $this->phone ?? null,
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ,
            'devise'  => optional($this->wallet->currencie)->code,
            'status'  =>  $this->status,
            'amount_received' =>  number_format((float)$this->amount_received, 2, '.', ' '),
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
        ];
    }
}
