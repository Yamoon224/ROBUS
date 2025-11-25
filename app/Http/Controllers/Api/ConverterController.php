<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\ValidateDailyLimitService;
use App\Models\Currency;
use App\Models\Custumers\Country;
use App\Models\Faq;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Get(
 *      path="/converters",
 *      tags={"converters"},
 *      summary="Get list of converters of currency",
 *      description="Returns all currency converters",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="name", type="string"),
 *                  @OA\Property(property="rate", type="number", format="float")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found"
 *      )
 * )
 */

class ConverterController extends Controller
{
    protected $validatedamountDays;


    public function __construct(
        ValidateDailyLimitService $validatedamountDays
    ) {
        $this->validatedamountDays = $validatedamountDays;
    }

    public function converter_browse($amount, $currency)
    {

        $wallet_user = Auth::guard('api')->user();

        $currency_valeur = Currency::whereCode($currency)->first();

        $country_civ = Country::find(107);


        $reel_amount = $this->validatedamountDays->search_amount($amount);


        if ($wallet_user->wallet->currencie->value >  $currency_valeur->value) {


            if ($currency_valeur->code == 'CFA') {

                $valeur_converter = (float)  $reel_amount *  $wallet_user->wallet->currencie->value;
            } elseif ($country_civ->currencie->value > $currency_valeur->value) {

                // Convertion in cfa
                $valeur_converter_cfa =  (float)  $reel_amount *  $wallet_user->wallet->currencie->value;

                // Convertion in cfa
                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            } else {

                $valeur_converter_cfa = (float)  $reel_amount *  $wallet_user->wallet->currencie->value;

                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            }

            return response()->json([
                'success' => $wallet_user != null ? true : false,
                'data' =>  [
                    'valeur_converter' => $valeur_converter,
                ],
            ], 200);
        } elseif ($wallet_user->wallet->currencie->value == $currency_valeur->value) {


            $valeur_converter = $reel_amount;

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
                $valeur_converter = (float) $reel_amount *  $wallet_user->wallet->currencie->value;
            } elseif ($country_civ->currencie->value > $currency_valeur->value) {

                // Convertion in cfa
                $valeur_converter_cfa = (float) $reel_amount *  $wallet_user->wallet->currencie->value;

                // Convertion in cfa
                $valeur_converter = $valeur_converter_cfa /  $currency_valeur->value;
            } else {

                // Convertion in cfa
                $valeur_converter_cfa = (float)  $reel_amount *  $wallet_user->wallet->currencie->value;

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
