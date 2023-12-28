<?php

namespace App\Http\Services;


use App\Models\Custumers\Country;

class ConvertionService
{

  
    public function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    
     
    public function convertAdminCommissionOutCiv($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    public function convertAdminCommissionInvoice($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $robus_currency;
        }
    }

    

    public function convertAdminCommissionSendAmount($amount , $countrie)
    {
        $country_civ = Country::find(107);

        $default_valeur = 2000000;

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount / $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    // public function convertAdminCommissionReload($amount)
    // {
    //     $country_civ = Country::find(107);

    //     $robus_currency = robus()->country->currencie->value;

    //     if ($country_civ->currencie->value > robus()->country->currencie->value) {
    //         return $amount * $robus_currency;
    //     } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
    //         return $amount;
    //     } else {
    //         return   $amount * $robus_currency;
    //     }
    // }

    public function convertAdminCommissionPayment($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value > robus()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == robus()->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $robus_currency;
        }
    }

}
