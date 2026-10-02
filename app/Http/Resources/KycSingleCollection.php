<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KycSingleCollection extends JsonResource
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
            // 'id' => (int) $this->id,
            'screen'  =>  $this->screen,
            'screen_back'  =>  $this->screen_back,
            'image' => $this->image,
            'status'  =>  $this->status,
        ];
    }
}