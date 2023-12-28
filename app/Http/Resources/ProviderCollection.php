<?php

namespace App\Http\Resources;

use App\Models\Costs\BankCost;
use App\Models\Costs\InterCost;
use App\Models\Costs\ReloadCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\OrderCardCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollection extends ResourceCollection
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
                $costTransaction =  TransactionCost::whereStatus(true)->first();
                $costPayment =  PaymentCost::whereStatus(true)->first();
                $costPhoneCredit =  PhoneCreditCost::whereStatus(true)->first();
                $costReloadUv =  ReloadCost::whereStatus(true)->first();
                $costInter =  InterCost::whereStatus(true)->first();
                $costBank =  BankCost::whereStatus(true)->first();
                $costReloadCard =  DepositCost::whereStatus(true)->first();
                $costOrderCard =  OrderCardCost::whereStatus(true)->first();
                return [
                    'id'  => (int) $data->id,
                    'name' => $data->name,
                    'country' =>  optional($data->country)->name,
                    'provider_type' =>  optional($data->providerType)->name,
                    'image' => $data->image,
                    'amount' => $data->amount ?? null,
                    'cost_transaction' =>  $costTransaction,
                    'cost_payment' =>  $costPayment,
                    'cost_phone_credit' =>  $costPhoneCredit,
                    'cost_reload_uv' =>  $costReloadUv,
                    'cost_inter' =>  $costInter,
                    'cost_bank' =>  $costBank,
                    'cost_reload_card' =>  $costReloadCard,
                    'cost_order_card' =>  $costOrderCard,
                ];
            })
        ];
    }
}
