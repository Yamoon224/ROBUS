<?php

namespace App\Http\Resources;

use App\Http\Resources\InterCollection;
use App\Http\Resources\ReloadCollection;
use App\Http\Resources\VirementCollection;
use App\Http\Resources\PaymenentCollection;
use App\Http\Resources\ReloadCardCollection;
use App\Http\Resources\PhoneCreditCollection;
use App\Http\Resources\TransactionCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletSingleCollection extends JsonResource
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
            'wallet_id' => $this->wallet_id,
            'amount' => number_format($this->amount , 2, ',', ' '),
            'reload_by' => $this->reload_by,
            'status' => $this->status,
            'user' => [
                'firstname' =>  optional($this->sidUser)->firstname,
                'id' =>  optional($this->sidUser)->id,
                'lastname' => optional($this->sidUser)->lastname,
                'phone' => optional($this->sidUser)->phone,
                'image' => optional($this->sidUser)->image,
                'email' => optional($this->sidUser)->email,
                'country' => optional($this->country)->name,
                'country_image' => optional($this->country)->image,
                'indicative' => optional($this->country)->indecative,
                'citie' => optional($this->sidUser->city)->name,
                'currencie' => optional($this->currencie)->code,
                'is_active' => optional($this->sidUser)->is_active,
                'status'  => optional($this->sidUser)->status,

            ],
          
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}