<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KycCollection extends ResourceCollection
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
                    'motif'  =>  $data->motif,
                    'users'   => optional($data->sidUser)->firstname . ' ' . optional($data->sidUser)->lastname,
                    'screen'  => $data->screen,
                    'screen_back'  => $data->screen_back,
                    'files' => $data->files,
                    'image' => $data->image,
                    'country_id' => optional($data->country)->name,
                    'phone' => $data->phone,
                    'status'  => $data->status,
                    'bank_card' => optional($data->walletCard)->firstname . optional($data->walletCard)->lastname,
                ];
            })
        ];
    }
}