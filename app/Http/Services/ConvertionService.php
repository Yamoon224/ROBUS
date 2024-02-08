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



    public function convertAdminCommissionSendAmount($amount, $countrie)
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


    public function convertAdminCommissionReload($amount, $wallet)
    {
        $country_civ = Country::find(107);

        $robus_currency = robus()->country->currencie->value;

        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount / $wallet->country->currencie->value;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $wallet->country->currencie->value;
        }
    }

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

    public function convertAdminCommissionInter($amount, $walletSend, $walletReceveid)
    {

        $country_civ = Country::find(107);

        if ($walletSend->country->id == 107) {

            if ($walletSend->currencie->value >  $walletReceveid->currencie->value) {
                return $amount / $walletReceveid->currencie->value;
            } elseif ($walletSend->currencie->value ==  $walletReceveid->currencie->value) {
                return $amount;
            } else {
                return $amount * $walletReceveid->currencie->value;
            }
        } else {

            if ($walletReceveid->country->id == 107) {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    $amount = $amount / $walletSend->currencie->value;

                    return $amount;
                }
            } else {

                if ($walletReceveid->currencie->value >  $walletSend->currencie->value) {
                    // Convertion in FCA
                    if ($country_civ->currencie->value >  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                } elseif ($walletReceveid->currencie->value ==  $walletSend->currencie->value) {
                    return $amount;
                } else {

                    if ($country_civ->currencie->value <  $walletSend->currencie->value) {
                        $amount_cfa = $amount * $walletSend->currencie->value;
                        // convertion in valeurs
                        $amount = $amount_cfa / $walletReceveid->currencie->value;
                    } else {
                        // convertion in valeurs
                        $amount = $amount / $walletReceveid->currencie->value;
                    }
                    return $amount;
                }
            }
        }
    }
}
