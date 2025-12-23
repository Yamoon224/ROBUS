<?php

namespace App\Http\Services;


use App\Models\Wallets\Wallet;
use App\Models\Custumers\Country;
use App\Models\Moneys\LevelMoney;
use App\Models\Costs\TransactionCost;
use App\Repositories\TransactionRepository;

class SearchTransactionService
{

    /** @var  TransactionRepository */
    public $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }


    public function searchTransaction($search)
    {

        $data = Wallet::where('wallet_id', $search)
            ->where('country_id',  robus()->country->id)
            ->whereStatus(true)
            ->first();

        return response()->json([
            'success' => $data != null ? true : false,
            'data' =>  [
                'walletId' => $data->wallet_id ?? null,
                'name' => $data->sidUser->firstname . ' ' . $data->sidUser->lastname,
                'user' => $data->sidUser->firstname . ' ' . $data->sidUser->lastname,
                'countrie' => $data->country->name ?? null,
                'currencie' => $data->currencie->currency ?? null,
                'image' => $data->sidUser->image ?? null,
            ],
        ], 200);
    }


    public function searchTransactionWallet($search)
    {

        $data = Wallet::where('wallet_id', $search)
            ->where('country_id',  robus()->country->id)
            // ->whereStatus(true)
            ->first();

        return  $data;
    }

    public function searchTransactionWalletInter($country_robus, $search)
    {

        // Search Wallet Send
        $wallet_search = Wallet::where('wallet_id', $search)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        return $wallet_search;
    }


    public function getCommissionTransactionMobileForCountryGroup($countryGroup, $underDistributer)
    {
        $commissionTransaction = null;

        if ($countryGroup == 'CIV') {
            $commissionTransaction = ($underDistributer == true) ? TransactionCost::whereId(27)->first() :  TransactionCost::whereId(29)->first();
        } elseif ($countryGroup == 'MAG') {
            $commissionTransaction = ($underDistributer == true) ? TransactionCost::whereId(44)->first() :  null;
        } else {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Providers erreur',
                ],
                333
            );
        }

        return $commissionTransaction;
    }


    public function getCommissionTransactionForCountryGroup($countryGroup, $underDistributer)
    {
        $commissionTransaction = null;

        if ($countryGroup == 'CIV') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(26)->first() :  TransactionCost::whereId(28)->first();
        } elseif ($countryGroup == 'AFL') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(32)->first() : TransactionCost::whereId(37)->first();
        } elseif ($countryGroup == 'AFC') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(33)->first() : TransactionCost::whereId(38)->first();
        } elseif ($countryGroup == 'USA') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(34)->first() : TransactionCost::whereId(39)->first();
        } elseif ($countryGroup == 'URP') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(35)->first() : TransactionCost::whereId(40)->first();
        } elseif ($countryGroup == 'MAG') {
            $commissionTransaction = ($underDistributer == 1) ? TransactionCost::whereId(36)->first() : TransactionCost::whereId(41)->first();
        }

        return $commissionTransaction;
    }

    // Fonction pour calculer la conversion de la valeur en fonction des devises
    public function calculateConversionValeurEnd($defaultValeur)
    {

        $country_civ = Country::whereId(107)
            ->first();
        if (robus()->wallet->currencie->value != $country_civ->currencie->value) {
            return $defaultValeur / robus()->wallet->currencie->value;
        } else {
            return $defaultValeur;
        }
    }
}
