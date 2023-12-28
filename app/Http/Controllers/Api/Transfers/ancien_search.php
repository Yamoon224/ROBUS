<?php

namespace App\Http\Controllers\Api\Transfers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\DeletedService;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use App\Repositories\TransactionRepository;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\NotificationSendService;
use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\RobusTransactionService;
use App\Http\Services\SearchTransactionService;
use App\Http\Services\ValidateDailyLimitService;
use App\Http\Services\MobileWalletTransactionService;
use App\Http\Services\CreateRobusWalletWithDrawalService;

class TransactionController extends Controller
{

     if ($amount_paiement_robus != '') {
    
    
                        if ($wallet_user->country->code == 'CF') {
                            //Valuer inter  transaction
                            $commission_inter = TransactionCost::whereId(4)->first();
    
                            $valeur_amount = $amount_paiement_robus;
    
                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $commission_inter->levelTransactionIvoires->last();
    
                            //  Conversion valeur
                            $conversion_valeur_end  = $valeur_end->amount_end;
    
                            if ($conversion_valeur_end  < $amount_paiement_robus) {
                                $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            } else {
    
                                foreach ($commission_inter->levelTransactionIvoires  as $amount_comission) {
                                    if (
                                        $valeur_amount >= $amount_comission->amount_begin &&
                                        $valeur_amount <= $amount_comission->amount_end
                                    ) {
    
                                        $commission_transaction_val_inter_pay =  $amount_comission->amount;
    
                                        $valeur_verify =   $valeur_amount  -  $commission_transaction_val_inter_pay;
    
                                        if ($valeur_verify < 0) {
                                            $commission_transaction_val_inter_receive =   'La valeur SVP';
                                        } else {
    
                                            $commission_transaction_val_inter_receive =   $valeur_verify;
                                        }
    
                                        return response()->json([
                                            'success' => $commission_inter != null ? true : false,
                                            'data' =>  [
                                                'amount_pay' => $commission_transaction_val_inter_pay,
                                                'amount_receive' => $commission_transaction_val_inter_receive,
                                            ],
                                        ], 200);
                                    }
                                }
                            }
                        } elseif ($wallet_user->country->code == 'GH') {
                            //Countries GHANA 
                            //  Conversion valeur
                            $conversion_valeur_end  =  $default_valeur  /  $wallet_user->wallet->currencie->value;
    
                            if ($conversion_valeur_end  < $amount_paiement_robus) {
    
                                $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            } else {
    
                                $commission_inter = TransactionCost::whereId(14)->first();
    
                                $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_robus;
    
                                $valeur_verify =   $amount_paiement_robus  -   $commission_transaction_val_inter_pay;
    
    
                                if ($valeur_verify < 0) {
                                    $commission_transaction_val_inter_receive =  "";
                                } else {
    
                                    $commission_transaction_val_inter_receive = $valeur_verify;
                                }
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            }
                        } elseif ($wallet_user->country->code == 'MA') {
                            // Countries Maroc
                            //  Conversion valeur
                            $conversion_valeur_end  =  $default_valeur  /  $wallet_user->wallet->currencie->value;
    
                            if ($conversion_valeur_end  < $amount_paiement_robus) {
    
                                $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            } else {
    
                                //Valuer inter  transaction
                                $commission_inter = TransactionCost::whereId(19)->first();
    
                                $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_robus;
    
                                $valeur_verify =   $amount_paiement_robus  -  $commission_transaction_val_inter_pay;
    
    
                                if ($valeur_verify < 0) {
                                    $commission_transaction_val_inter_receive =  "";
                                } else {
    
                                    $commission_transaction_val_inter_receive = $valeur_verify;
                                }
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            }
                        } elseif ($wallet_user->country->code == 'FR') {
    
                            //Valuer inter  transaction
                            $commission_inter = TransactionCost::whereId(9)->first();
    
                            $valeur_amount =  $amount_paiement_robus;
    
                            // Verify valeur amount max in Landing the ROBUS
                            $valeur_end = $commission_inter->levelTransactionIvoires->last();
    
                            $conversion_valeur_end =  $valeur_end->amount_end;
                            //Verify amount in transactions 
                            if ($conversion_valeur_end  < $valeur_amount) {
                                $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            } else {
    
                                foreach ($commission_inter->levelTransactionIvoires as $amount_comission) {
                                    if ($valeur_amount >= $amount_comission->amount_begin && $valeur_amount <= $amount_comission->amount_end) {
    
                                        if ($amount_comission->percent == null) {
    
                                            $valeur_verify =  $valeur_amount - $amount_comission->amount;
    
                                            $commission_transaction_val_inter_pay = $amount_comission->amount;
    
                                            if ($valeur_verify < 0) {
                                                $commission_transaction_val_inter_receive =   "Montant negatif";
                                            } else {
    
                                                $commission_transaction_val_inter_receive = $valeur_verify;
                                            }
    
                                            return response()->json([
                                                'success' => $commission_inter != null ? true : false,
                                                'data' =>  [
                                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                                ],
                                            ], 200);
                                        } else {
    
    
                                            $commission_transaction_val_inter_pay =   $valeur_amount  * $amount_comission->percent;
    
                                            $valeur_verify = $valeur_amount -  $commission_transaction_val_inter_pay;
    
                                            if ($valeur_verify < 0) {
                                                $commission_transaction_val_inter_receive =   "Montant negatif";
                                            } else {
    
                                                $commission_transaction_val_inter_receive = $valeur_verify;
                                            }
    
                                            return response()->json([
                                                'success' => $commission_inter != null ? true : false,
                                                'data' =>  [
                                                    'amount_pay' => $commission_transaction_val_inter_pay,
                                                    'amount_receive' => $commission_transaction_val_inter_receive,
                                                ],
                                            ], 200);
                                        }
                                    }
                                }
                            }
                        } elseif ($wallet_user->country->code == 'TN') {
                            // Countries TUnisie
    
                            //  Conversion valeur
                            $conversion_valeur_end  =  $default_valeur  /  $wallet_user->wallet->currencie->value;
    
                            if ($conversion_valeur_end  < $amount_paiement_robus) {
    
                                $commission_transaction_val_inter_pay = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                $commission_transaction_val_inter_receive = "Montant maxi est  : " .  $conversion_valeur_end;
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            } else {
    
                                //Valuer inter  transaction
                                $commission_inter = TransactionCost::whereId(24)->first();
    
                                $commission_transaction_val_inter_pay = $commission_inter->percent  *  $amount_paiement_robus;
    
                                $valeur_verify =   $amount_paiement_robus  -  $commission_transaction_val_inter_pay;
    
    
                                if ($valeur_verify < 0) {
                                    $commission_transaction_val_inter_receive =  "Montant negatif";
                                } else {
    
                                    $commission_transaction_val_inter_receive = $valeur_verify;
                                }
    
                                return response()->json([
                                    'success' => $commission_inter != null ? true : false,
                                    'data' =>  [
                                        'amount_pay' => $commission_transaction_val_inter_pay,
                                        'amount_receive' => $commission_transaction_val_inter_receive,
                                    ],
                                ], 200);
                            }
                        }
                    }
}
