<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Custumers\Country;

/**
 * @OA\Get(
 *      path="/converters",
 *      tags={"Faqs"},
 *      summary="Get List converters of currency,
 *      description="Returns all CUrrency converters",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 * @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 * @OA\Response(
 *      response=404,
 *      description="not found"
 *   ),
 *  )
 */
class ConverterController extends Controller
{
    public function converter_browse($amount, $currency)
    {

        $wallet_user = Auth::guard('api')->user();

        $currency_valeur = Currency::whereCode($currency)->first();

        $country_civ = Country::find(107);


        if ($wallet_user->wallet->currencie->value >  $currency_valeur->value) {


            if ($currency_valeur->code == 'CFA') {

                $valeur_converter = $amount *  $wallet_user->wallet->currencie->value;
            } elseif ($country_civ->currencie->value > $currency_valeur->value) {

                // Convertion in cfa
                $valeur_converter_cfa = $amount *  $wallet_user->wallet->currencie->value;

                // Convertion in cfa
                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            } else {

                $valeur_converter_cfa = $amount *  $wallet_user->wallet->currencie->value;

                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            }

            return response()->json([
                'success' => $wallet_user != null ? true : false,
                'data' =>  [
                    'valeur_converter' => $valeur_converter,
                ],
            ], 200);
        } elseif ($wallet_user->wallet->currencie->value == $currency_valeur->value) {


            $valeur_converter = $amount;

            return response()->json([
                'success' => $wallet_user != null ? true : false,
                'data' =>  [
                    'valeur_converter' => $valeur_converter,
                ],
            ], 200);
        } elseif ($wallet_user->wallet->currencie->value <  $currency_valeur->value) {
            
            // Convertion in CFA
            if ($currency_valeur->code == 'CFA') {

                // Convertion in cfa
                $valeur_converter = $amount *  $wallet_user->wallet->currencie->value;

            } elseif ($country_civ->currencie->value > $currency_valeur->value) {

                // Convertion in cfa
                $valeur_converter_cfa = $amount *  $wallet_user->wallet->currencie->value;

                // Convertion in cfa
                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            } else {

                // Convertion in cfa
                $valeur_converter_cfa = $amount *  $wallet_user->wallet->currencie->value;

                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            }


            return response()->json([
                'success' => $wallet_user != null ? true : false,
                'data' =>  [
                    'valeur_converter' => $valeur_converter,
                ],
            ], 200);
        }
    }
}
