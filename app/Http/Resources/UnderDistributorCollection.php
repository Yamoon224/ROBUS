<?php

namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\ResourceCollection;

class UnderDistributorCollection extends ResourceCollection
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
                                $is_custumer = $data->is_custumers;
                                $is_under_distributer = $data->is_under_distributer;
                                return [
                                        'id'      => $data->id,
                                        'firstname' => $data->firstname,
                                        'lastname' => $data->lastname,
                                        'email' => $data->email,
                                        'phone' => $data->phone,
                                        'zone' => $data->zone,
                                        'status' => $data->status,
                                        'longitude' => $data->longitude,
                                        'latitude' => $data->latitude,
                                        'image' => $data->image,
                                        'type_users' => $is_custumer ? "Client" : ($is_under_distributer ? "Sous distributeur" : "Master"),
                                        'wallet_id' => optional($data->wallet)->wallet_id ?? null,
                                        'country' => optional($data->country)->name,
                                        'city' => optional($data->city)->name,
                                        'is_custumers' => $data->is_custumers,
                                        'is_under_distributer' => $data->is_under_distributer,
                                ];
                        })
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
