<?php

namespace App\Http\Services;


use App\Models\Costs\TransactionCost;

class CommissionServicePercentBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */
     public function getRobusPercentageCustumers()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(28)->first();
            case 'AFL':
                return TransactionCost::whereId(37)->first();
            case 'AFC':
                return TransactionCost::whereId(38)->first();
            case 'USA':
                return TransactionCost::whereId(39)->first();
            case 'URP':
                return TransactionCost::whereId(40)->first();
            case 'MAG':
                return TransactionCost::whereId(41)->first();
            case 'ASIE':
                return TransactionCost::whereId(46)->first();
            case 'AFE':
                return TransactionCost::whereId(48)->first();
            default:
                return null;
        }
    }

     public function getRobusPercentageSDistributers()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(26)->first();
            case 'AFL':
                return TransactionCost::whereId(32)->first();
            case 'AFC':
                return TransactionCost::whereId(33)->first();
            case 'USA':
                return TransactionCost::whereId(34)->first();
            case 'URP':
                return TransactionCost::whereId(35)->first();
            case 'MAG':
                return TransactionCost::whereId(36)->first();
            case 'ASIE':
                return TransactionCost::whereId(44)->first();
            case 'AFE':
                return TransactionCost::whereId(45)->first();
            default:
                return null;
        }
    }


    public function getRobusPercentageMobile()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(27)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageMobileMaroc()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'MAG':
                return TransactionCost::whereId(44)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageMobileTwo()
    {
        $countryNameGroup = sabdy()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(29)->first();
            default:
                return null;
        }
    }

}
