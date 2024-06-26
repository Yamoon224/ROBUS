<?php

namespace App\Http\Resources;

use App\Models\Wallets\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;

class ReloadSinglePuscherCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $wallet_send = Wallet::where('wallet_id',  $this->account_wallet)->first();

        if($wallet_send != null)
        {
            $wallet_send = Wallet::where('wallet_id',  $this->account_wallet)->first();

        }else {

            $wallet_send = null;

        }
        return [
            'id' => (int) $this->id,
            'provider'  =>  optional($this->provider)->name,
            'amount' =>  number_format((float)$this->amount, 2, '.', ' '),
            // 'amount_wallet' =>  optional($this->wallet)->amount . ' ' . optional($this->wallet->currencie)->code,
            'name_user_desti' =>  null,
            // 'name_user_desti' => isset(optional($wallet_send)->sidUser->firstname) ? (optional($wallet_send)->sidUser->firstname). ' ' . (optional($wallet_send)->sidUser->lastname)  : null,
            'phone_user_desti' => null,
            'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname) : null,
            // 'name_user_send' => isset(optional($this->wallet)->sidUser->firstname) ? (optional($this->wallet)->sidUser->firstname) . ' ' . (optional($this->wallet)->sidUser->lastname)  : null,
            'phone_user_send' => optional($this->wallet)->sidUser->phone ?? null,
            'date_created' =>  $this->created_at,
            'type'  =>  $this->type_transaction,
            'type_received'  => $this->type_received,
            'phone'  => null,
            'date_treatment' =>  optional($this->paymentReload)->created_at,
            'Reference'  => optional($this->paymentReload)->transaction_id,
            'name' =>  $this->name,
            'description'  =>  $this->description,
            'payment_cost_percent' => number_format((float)$this->commissions, 2, '.', '') ?? null,
            'number_decoder' => null,
            'num_wallet' =>  $this->account_wallet,
            'id_invoice' =>  null,
            'offer' => null,
            'number_contract' =>  null,
            'amount_received' => number_format((float)$this->amount_received, 2, '.', ' ')  . ' ' . optional($wallet_send->currencie)->code ?? null,
            'number_phone' => null,
            'paiementChoix_phone' =>  null,
            'factureChoix_type' =>  null,
            'frais' => null,
            'devise'  => optional($this->wallet->currencie)->code,
            // optional($this->currencie)->code
            'status'  =>  $this->status,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->wallet->currencie)->code ?? null,
            'amount_solde_drawal' =>  $this->amount_solde_drawal .  ' ' . optional($this->wallet->currencie)->code ?? null
        ];
    }
}
