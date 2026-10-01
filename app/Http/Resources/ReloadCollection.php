<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReloadCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($data) {
                return [
                    'id'      => (int) $data->id,
                    'provider'  =>  optional($data->provider)->name,
                    'amount' => $data->amount,
                    'description'  => $data->description,
                    'account_wallet' => $data->account_wallet,
                    'phone' => $data->phone,
                    'date_created' =>  $data->created_at,
                    'type'  =>  $data->type_transaction,
                    'type_received'  => $data->type_received,
                    'date_treatment' =>  optional($data->paymentReload)->created_at,
                    'status'  => $data->status,
                    'commissions'  => $data->commissions,
                    'solde_account'  => $data->amount_solde .  ' ' . optional($data->wallet->currencie)->code ?? null,
                    'amount_solde_drawal' =>  $data->amount_solde_drawal .  ' ' . optional($data->wallet->currencie)->code ?? null
                ];
            })
        ];
    }
}
