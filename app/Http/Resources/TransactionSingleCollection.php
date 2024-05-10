<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionSingleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $wallet_send = $this->wallet_id == 0 ? Wallet::where('wallet_id',  $this->num_wallet)->first() :  Wallet::where('wallet_id',  $this->num_wallet)->first();

        if($wallet_send != null)
        {
            $wallet_send = Wallet::where('wallet_id',  $this->num_wallet)->first();

        }else {

            $wallet_send = null;

        }

        return [
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  number_format((double)$this->amount, 2, '.', ' '),
            // 'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'name_user_desti' =>  Wallet::where('wallet_id',  $this->num_wallet)->first() != null ? (optional($wallet_send)->sidUser->firstname) . ' ' . (optional($wallet_send)->sidUser->lastname) : null,
            // 'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname). ' ' . (optional($wallet_send)->sidUser->lastname)  : null,
            'phone_user_desti' => Wallet::where('wallet_id',  $this->num_wallet)->first() != null ?  optional($wallet_send)->sidUser->phone : $this->phone ,
            'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname) : null,
            // 'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname)  : null,
            'phone_user_send' => optional($this->wallet)->sidUser->phone ?? null,
            'date_created' =>  $this->created_at,
            'type'  =>  $this->type_transaction,
            'type_received'  => $this->type_received,
            'phone'  => $this->phone ?? null,
            'date_treatment' =>  optional($this->transaction)->created_at,
            'Reference'  => optional($this->transaction)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' => number_format((double)$this->commissions, 2, '.', '') ?? null,
            'number_decoder' =>  $this->number_decoder,
            'num_wallet' =>  $this->num_wallet,
            'id_invoice' =>  $this->id_invoice,
            'offer' => optional($this->offer)->name,
            'number_contract' =>  $this->number_contract,
            'amount_received' => Wallet::where('wallet_id',  $this->num_wallet)->first() != null ? number_format((double)$this->amount_received, 2, '.', ' ')  . ' ' . optional($wallet_send->currencie)->code : number_format((double)$this->amount_received, 2, '.', ' ') ,
            'number_phone' =>  $this->number_phone,
            'paiementChoix_phone' =>  $this->paiementChoix_phone,
            'factureChoix_type' =>  $this->factureChoix_type,
            'frais' => number_format((double)$this->costs_amount, 2, '.', ' ') ,
            'devise'  =>  optional(optional($this->wallet)->currencie)->code ?? "CFA",
            // optional($this->currencie)->code
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA"),
            'amount_solde_drawal' => $this->amount_solde_drawal . ' ' . (optional(optional($this->wallet)->currencie)->code ?? "CFA")
            
        ];
    }
}