<?php

namespace App\Http\Resources;

use App\Http\Resources\KycCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSingleCollection extends JsonResource
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
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'image' => $this->image,
            'wallet' => optional($this->wallet)->amount,
            'wallet_status' => optional($this->wallet)->status,
            'country' => optional($this->country)->name,
            'city' => optional($this->city)->name,
            'is_custumers' => $this->is_custumers,
            'is_active' => $this->is_active,
            'is_distributer' => $this->is_distributer,
            'is_under_distributer' => $this->is_under_distributer,
            'screen' => [
                'screen_front' => optional($this->kyc)->screen,
                'screen_back' => optional($this->kyc)->screen_back,
                'image' => optional($this->kyc)->image,
                'motif' => optional($this->kyc)->motif,
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