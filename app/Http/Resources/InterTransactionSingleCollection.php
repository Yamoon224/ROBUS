<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;

class InterTransactionSingleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $wallet_send = Wallet::where('wallet_id',  $this->number_compte)->first();

        if ($wallet_send != null) {
            $wallet_send = Wallet::where('wallet_id',  $this->number_compte)->first();
        } else {

            $wallet_send = null;
        }

        return [
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  number_format((float)$this->amount, 2, '.', ''),
            'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname)  . ' ' . (optional($wallet_send)->sidUser->lastname) : null,
            // 'name_user_desti' => isset(optional($wallet_send)->sidUser->lastname) ? (optional($wallet_send)->sidUser->lastname) : null,
            'phone_user_desti' => optional($wallet_send)->sidUser->phone ?? null,
            'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname) : null,
            // 'name_user_send' => isset(optional($this->wallet)->sidUser->lastname) ? (optional($this->wallet)->sidUser->lastname) : null,
            'phone_user_send' => optional($this->wallet)->sidUser->phone ?? null,
            'date_created' =>  $this->created_at,
            'type' =>  'Transfert internationnal',
            'date_treatment' =>  optional($this->transactionInter)->created_at,
            'Reference'  => optional($this->transactionInter)->transaction_id,
            'description'  =>  $this->description,
            'inter_cost_percent' =>   number_format((float)$this->commissions, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'num_wallet' =>  $this->number_compte ?? null,
            'num_carte' =>  $this->number_card ?? null,
            'type_invoice'  =>  $this->type_invoice ?? null,
            'number_decoder'  =>  $this->number_decoder ?? null,
            'id_invoice'  =>  $this->id_invoice ?? null,
            'number_contract'  =>  $this->number_contract ?? null,
            'id_invoice_pre'  =>  $this->id_invoice_pre ?? null,
            'name'  =>  $this->name ?? $this->name_complet ?? null,
            'number_phone'  =>  $this->number_phone ?? null,
            'master_cost_percent' =>  number_format((float)$this->commission_masters, 2, '.', '') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ?? null,
            'amount_received' => number_format((float)$this->amount_received, 2, '.', ' ') . ' ' . optional($this->country->currencie)->code ?? null,
            'offer'  =>  optional($this->offer)->name ?? null,
            'phone' =>  $this->phone ?? null,
            'phone_invoice' =>  $this->phone_invoice ?? null,
            'client' => [
                    'firstname' => optional($wallet_send)->sidUser->firstname  ??  $this->name_complet ??  optional($this->wallet->sidUser)->firstname ?? null,
                    'lastname' => optional($wallet_send)->sidUser->lastname ?? $this->name ?? $this->name_complet  ??   optional($this->wallet->sidUser)->lastname ?? null,
                    'phone' =>   optional($wallet_send)->sidUser->phone ??  $this->phone ??  optional($this->wallet->sidUser)->phone ?? null,
                ],
            'country_send'  =>  optional($this->wallet->country)->name ?? null,
            'country_desti'  =>  optional($this->country)->name ?? null,
            'devise'  =>  optional(optional($this->wallet)->currencie)->code ?? "CFA",
            'frais' => number_format((float)$this->costs_amount, 2, '.', ' ') . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA") ,
            'status'  =>  $this->status,
            // 'amount_received'  => $this->amount_received ?? null,
            'balance_before'  => $this->balance_before . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'solde_account'  => $this->amount_solde . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'amount_solde_drawal' =>  $this->amount_solde_drawal .  ' ' . (optional(optional($this->country->currencie))->code ?? "CFA"),
        ];
    }
}
