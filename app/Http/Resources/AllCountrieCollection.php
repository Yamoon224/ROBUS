<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AllCountrieCollection extends ResourceCollection
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
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'name' => $data->name,
                    'currencie' => optional($data->currencie)->code,
                    'indecative' => $data->indecative ?? null,
                    'image' => $data->image ?? null,
                ];
            })
        ];
    }
}