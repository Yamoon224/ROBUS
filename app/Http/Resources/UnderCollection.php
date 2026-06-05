<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class UnderCollection extends ResourceCollection
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
                                $is_custumer = $data->is_custumers;
                                $is_under_distributer = $data->is_under_distributer;
                                $is_company = $data->is_company;
                                $item = [
                                        'id'      => $data->id,
                                        'firstname' => $data->firstname,
                                        'lastname' => $data->lastname,
                                        'email' => $data->email,
                                        'phone' => $data->phone,
                                        'status' => $data->status,
                                        'image' => $data->image,
                                        'type_users' => $is_custumer ? "Client" : ($is_under_distributer ? "Sous distributeur" : ($is_company ? "Compagnie" : "Master")),
                                        'wallet' => optional($data->wallet)->amount .  ' ' . optional($data?->wallet?->currencie)->code ?? null,
                                        'wallet_status' => optional($data->wallet)->status,
                                        'wallet_id' => optional($data->wallet)->wallet_id ?? null,
                                        'country' => optional($data->country)->name,
                                        'city' => optional($data->city)->name,
                                        'is_custumers' => $data->is_custumers,
                                        'is_accepted' => $data->is_accepted,
                                        'is_active' => $data->is_active,
                                        'is_distributer' => $data->is_distributer,
                                        'is_under_distributer' => $data->is_under_distributer,
                                        'is_company' => $data->is_company,
                                        'is_master' => $data->is_master,
                                        'screen' => [
                                                'screen_front' => optional($data->kyc)->screen,
                                                'screen_back' => optional($data->kyc)->screen_back,
                                                'image' => optional($data->kyc)->image,
                                                '       ' => optional($data->kyc)->motif,
                                        ],
                                ];

                                if ($data->transactions_by_type !== null) {
                                        $item['transactions_by_type'] = $data->transactions_by_type;
                                }

                                // if ($data->transactions_details_by_type !== null) {
                                //         $item['transactions_details_by_type'] = $data->transactions_details_by_type;
                                // }

                                if ($data->transactions_total !== null) {
                                        $item['transactions_total'] = $data->transactions_total;
                                }

                                return $item;
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
