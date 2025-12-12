<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemonteSingleCollection extends JsonResource
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
            'id' => (int) $this->id,
            'provider'  => "Robus",
            'amount_with_draw' =>  number_format((float)$this->amount_with_draw	, 2, '.', ' '),
            'date_created' =>  $this->date_removed,
            'date_treatment' =>  $this->date_removed,
            'type'  =>  $this->type_transaction,
            'status'  =>  $this->status,
            'devise'  =>optional($this->sidUser->wallet->currencie)->code,
            'solde_account'  => $this->amount_solde .  ' ' . optional($this->sidUser->wallet->currencie)->code ?? null,
        ];
    }
}
