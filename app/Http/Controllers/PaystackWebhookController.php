<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Wallets\WalletReload;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Costs\ReloadCost;
use App\Models\Payments\PaymentReload;
use App\Models\Providers\Provider;
use App\Http\Services\PaystackService;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Events\PaymentPending;
use App\Models\Invoices\InvoiceReload;
use Carbon\Carbon;
use App\Models\Wallets\WalletAdmin;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\PercentRobusAuthService;

class PaystackWebhookController extends Controller
{
    /**
     * Clé secrète Paystack
     */
    private $paystackSecret;
    protected $percentRobus;

    /**
     * Constructeur
     */
    public function __construct(
        PercentRobusAuthService $percentRobus
    ) {
        $this->percentRobus = $percentRobus;
        $this->paystackSecret = config('services.paystack.secret_key');
    }

    /**
     * Initialiser un paiement Paystack
     */
    public function initializePayment(Request $request)
    {
        // Valider les données d'entrée
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100|max:1000000',
            'email' => 'required|email',
            'reference' => 'required|string|unique:wallet_reloads,reference',
            'metadata' => 'nullable|array',
            'callback_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Vérifier que l'utilisateur est authentifié
            $user = robus();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            // Vérifier que l'email correspond à l'utilisateur authentifié
            if ($user->email !== $request->email) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email ne correspond pas à l\'utilisateur authentifié'
                ], 403);
            }

            $paystackService = new PaystackService();

            // Convertir le montant en kobo/centimes
            $amountInKobo = $request->amount * 100;

            $data = [
                'email' => $request->email,
                'amount' => $amountInKobo,
                'reference' => $request->reference,
                'currency' => 'XOF',
                'channels' => ['card', 'mobile_money', 'bank'],
                'metadata' => $request->metadata ?? [],
            ];

            // Ajouter l'URL de callback si fournie
            if ($request->callback_url) {
                $data['callback_url'] = $request->callback_url;
            } else {
                // URL de callback par défaut
                $data['callback_url'] = route('payment.callback');
            }

            $response = $paystackService->initializeTransaction($data);

            if ($response['status'] == true) {
                // Récupérer le fournisseur Paystack
                $provider = Provider::where('name', 'Paystack')->first();

                if (!$provider) {
                    // Créer le fournisseur s'il n'existe pas
                    $provider = Provider::create([
                        'name' => 'Paystack',
                        'slug' => 'paystack',
                        'status' => 'active',
                        'type' => 'payment_gateway',
                    ]);
                }

                // Trouver le portefeuille de l'utilisateur
                $wallet = Wallet::where('sid_user_id', $user->id)->first();

                if (!$wallet) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Portefeuille utilisateur non trouvé'
                    ], 404);
                }

                // Créer la recharge en attente
                WalletReload::create([
                    'wallet_id' => $wallet->id,
                    'provider_id' => $provider->id,
                    'amount' => $request->amount,
                    'fees' => 0,
                    'currency' => 'XOF',
                    'reference' => $request->reference,
                    'status' => 'pending',
                    'metadata' => json_encode(array_merge(
                        $request->metadata ?? [],
                        ['user_id' => $user->id, 'wallet_id' => $wallet->id]
                    )),
                    'payment_method' => 'paystack',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'payment_link' => $response['data']['authorization_url'] ?? null
                ]);

                Log::info('Transaction Paystack initialisée', [
                    'reference' => $request->reference,
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'authorization_url' => $response['data']['authorization_url'] ?? null
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Paiement initialisé avec succès',
                    'data' => $response['data']
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => $response['message'] ?? 'Échec de l\'initialisation du paiement'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Erreur initialisation Paystack', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erreur interne du serveur'
            ], 500);
        }
    }


    /**
     * Gérer les webhooks Paystack
     */
    public function handleWebhook(Request $request)
    {
        // Valider la signature du webhook
        if (!$this->validatePaystackSignature($request)) {
            Log::warning('Signature Paystack invalide', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all()
            ]);
            return response()->json(['error' => 'Signature invalide'], 401);
        }

        // Récupérer l'événement
        $event = $request->input('event');
        $data = $request->input('data');

        Log::info('Webhook Paystack reçu', [
            'event' => $event,
            'reference' => $data['reference'] ?? null,
            'data' => $data
        ]);

        // Traiter selon le type d'événement
        switch ($event) {
            case 'charge.success':
                return $this->handleSuccessfulCharge($data);

            case 'charge.failed':
                return $this->handleFailedCharge($data);

            case 'transfer.success':
                return $this->handleSuccessfulTransfer($data);

            case 'transfer.failed':
                return $this->handleFailedTransfer($data);

            case 'transfer.reversed':
                return $this->handleReversedTransfer($data);

            default:
                Log::warning('Événement Paystack non traité', ['event' => $event]);
                return response()->json(['status' => 'ignored'], 200);
        }
    }

    /**
     * Gérer un paiement réussi
     */
    private function handleSuccessfulCharge(array $data)
    {
        DB::beginTransaction();

        try {
            // Récupérer la transaction
            $transaction = WalletReload::where('reference', $data['reference'])->first();

            if (!$transaction) {
                Log::error('Transaction non trouvée', ['reference' => $data['reference']]);
                DB::rollBack();
                return response()->json(['error' => 'Transaction non trouvée'], 404);
            }

            // Vérifier si la transaction a déjà été traitée
            if ($transaction->status === 'success') {
                Log::info('Transaction déjà traitée', ['reference' => $data['reference']]);
                DB::rollBack();
                return response()->json(['status' => 'already_processed'], 200);
            }

            // Valider le montant
            $expectedAmount = $transaction->amount * 100; // Convertir en kobo/centimes
            if ($data['amount'] != $expectedAmount) {
                Log::error('Montant incorrect', [
                    'expected' => $expectedAmount,
                    'received' => $data['amount'],
                    'reference' => $data['reference']
                ]);
                DB::rollBack();
                return response()->json(['error' => 'Montant incorrect'], 400);
            }

            $wallet = $transaction->wallet;
            $wallet_details = Wallet::whereId($transaction->wallet_id)->first();
            $countryNameGroup = $wallet_details->country->name_groupe;
            if ($countryNameGroup == 'CIV' || $countryNameGroup == 'AFL' || $countryNameGroup == 'AFC' || $countryNameGroup == 'AFE') {
                $commission_robus = ReloadCost::whereId(2)->first();
            } elseif ($countryNameGroup == 'URP' || $countryNameGroup == 'USA' || $countryNameGroup == 'MAG' || $countryNameGroup == 'ASIE') {
                $commission_robus = ReloadCost::whereId(3)->first();
            }

            $total_percent = (float) $commission_robus->percent + (float) $commission_robus->percent_admin;
            $total_commission = $total_percent * $transaction->amount;
            $amountReceived = (float) $transaction->amount - (float)$total_commission;
            // Mettre à jour la transaction
            $transaction->update([
                'status' => 'success',
                'gateway_response' => $data['gateway_response'],
                'paid_at' => Carbon::parse($data['paid_at']),
                'payment_method' => $data['channel'],
                'currency' =>  $data['currency'] ?? 'XOF',
                'balance_before' =>  $wallet->amount,
                'amount_solde' => (float) $wallet->amount + (float) $amountReceived,
                'amount_received' => $amountReceived,
                'ip_address' => $data['ip_address'] ?? null,
                'fees' => $total_commission ?? 0,
                'costs_amount' => $total_commission ?? 0,
                'authorization' => json_encode($data['authorization'] ?? []),
                'customer' => json_encode($data['customer'] ?? []),
                'metadata' => json_encode(array_merge(
                    json_decode($transaction->metadata, true) ?? [],
                    $data['metadata'] ?? []
                )),
                'verified' => true,
                'verified_at' => now(),
                'raw_response' => json_encode($data),
            ]);

            // Mettre à jour le solde du portefeuille
            if ($wallet) {
                $oldBalance = $wallet->amount;
                $newBalance = $oldBalance + $transaction->amount_received;

                // Créer une transaction dans l'historique du portefeuille
                PaymentReload::create([
                    'wallet_reload_id' => $transaction->id,
                    'sid_user_id' => $wallet->sid_user_id,
                    'type' => 'credit',
                    'transaction_id' => $data['reference'],
                    'pay_amount' => $transaction->amount,
                    'fee_amount' => $total_commission ?? 0,
                    'received_amount' => $transaction->amount_received,
                    'balance_before' => $oldBalance,
                    'balance_after' => $newBalance,
                    'description' => 'Recharge via Paystack - ' . $data['reference'],
                    'reference' => $data['reference'],
                    'operator_transaction_name' =>  "Paystack",
                    'status' => 'completed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


                $admin_single =  SidUser::whereIsAdmin(true)
                    ->where('country_id', 107)
                    ->first();

                $walletAdmin = WalletAdmin::find(1);
                $wallet_commission = Wallet::where('wallet_id', "CI44RTZU")->first();

                if (!$commission_robus) {
                    throw new \Exception('Coût Robus introuvable');
                }

                if (!$wallet_commission) {
                    throw new \Exception("Wallet commission introuvable");
                }

                $totalAmount = $transaction->amount;

                $total_admin_commission = $transaction->costs_amount;

                if ($totalAmount <= 0) {
                    throw new \Exception('Montant final invalide');
                }

                if ($walletAdmin && $walletAdmin->amount >= $totalAmount) {
                    $wallet_commission->decrement('amount', $totalAmount);
                    $walletAdmin->increment('amount', $total_admin_commission);
                } else {
                    $admin_single->wallet->decrement('amount', $totalAmount);
                }
                // Update wallet Transactions
                $transaction->wallet->update([
                    'reload_by' => $transaction->user_id,
                    'sid_user_relod' => $transaction->user_id,
                    'amount' => DB::raw('amount+' . $amountReceived),
                ]);
            }

            // Déclencher l'événement de paiement réussi
            event(new PaymentSuccessful($transaction, $data));

            // Envoyer une notification
            $this->sendSuccessNotification($transaction);

            DB::commit();

            Log::info('Paiement traité avec succès', [
                'reference' => $data['reference'],
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Paiement traité avec succès',
                'transaction' => $transaction->fresh(),
                'wallet' => $wallet->fresh(),
                'paystack_data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement du paiement', [
                'reference' => $data['reference'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }

    private function handleSuccessWebfulCharge(array $data)
    {
        DB::beginTransaction();


        try {
            // Récupérer la transaction
            $transaction = WalletReload::where('reference', $data['reference'])->first();

            if (!$transaction) {
                Log::error('Transaction non trouvée', ['reference' => $data['reference']]);
                DB::rollBack();
            }

            // Vérifier si la transaction a déjà été traitée
            if ($transaction->status === 'success') {
                Log::info('Transaction déjà traitée', ['reference' => $data['reference']]);
            }

            // Valider le montant
            $expectedAmount = $transaction->amount * 100; // Convertir en kobo/centimes
            if ($data['amount'] != $expectedAmount) {
                Log::error('Montant incorrect', [
                    'expected' => $expectedAmount,
                    'received' => $data['amount'],
                    'reference' => $data['reference']
                ]);
                DB::rollBack();
            }

            $wallet = $transaction->wallet;
            $wallet_details = Wallet::whereId($transaction->wallet_id)->first();
            $countryNameGroup = $wallet_details->country->name_groupe;
            if ($countryNameGroup == 'CIV' || $countryNameGroup == 'AFL' || $countryNameGroup == 'AFC' || $countryNameGroup == 'AFE') {
                $commission_robus = ReloadCost::whereId(2)->first();
            } elseif ($countryNameGroup == 'URP' || $countryNameGroup == 'USA' || $countryNameGroup == 'MAG' || $countryNameGroup == 'ASIE') {
                $commission_robus = ReloadCost::whereId(3)->first();
            }

            $total_percent = (float) $commission_robus->percent + (float) $commission_robus->percent_admin;
            $total_commission = $total_percent * $transaction->amount;
            $amountReceived = (float) $transaction->amount - (float)$total_commission;
            // Mettre à jour la transaction
            $transaction->update([
                'status' => 'success',
                'gateway_response' => $data['gateway_response'],
                'paid_at' => Carbon::parse($data['paid_at']),
                'payment_method' => $data['channel'],
                'currency' =>  $data['currency'] ?? 'XOF',
                'balance_before' =>  $wallet->amount,
                'amount_solde' => (float) $wallet->amount + (float) $amountReceived,
                'amount_received' => $amountReceived,
                'ip_address' => $data['ip_address'] ?? null,
                'fees' => $total_commission ?? 0,
                'costs_amount' => $total_commission ?? 0,
                'authorization' => json_encode($data['authorization'] ?? []),
                'customer' => json_encode($data['customer'] ?? []),
                'metadata' => json_encode(array_merge(
                    json_decode($transaction->metadata, true) ?? [],
                    $data['metadata'] ?? []
                )),
                'verified' => true,
                'verified_at' => now(),
                'raw_response' => json_encode($data),
            ]);

            // Mettre à jour le solde du portefeuille
            if ($wallet) {
                $oldBalance = $wallet->amount;
                $newBalance = $oldBalance + $transaction->amount_received;

                // Créer une transaction dans l'historique du portefeuille
                PaymentReload::create([
                    'wallet_reload_id' => $transaction->id,
                    'sid_user_id' => $wallet->sid_user_id,
                    'type' => 'credit',
                    'transaction_id' => $data['reference'],
                    'pay_amount' => $transaction->amount,
                    'fee_amount' => $total_commission ?? 0,
                    'received_amount' => $transaction->amount_received,
                    'balance_before' => $oldBalance,
                    'balance_after' => $newBalance,
                    'description' => 'Recharge via Paystack - ' . $data['reference'],
                    'reference' => $data['reference'],
                    'operator_transaction_name' =>  "Paystack",
                    'status' => 'completed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);


                $admin_single =  SidUser::whereIsAdmin(true)
                    ->where('country_id', 107)
                    ->first();

                $walletAdmin = WalletAdmin::find(1);
                $wallet_commission = Wallet::where('wallet_id', "CI44RTZU")->first();

                if (!$commission_robus) {
                    throw new \Exception('Coût Robus introuvable');
                }

                if (!$wallet_commission) {
                    throw new \Exception("Wallet commission introuvable");
                }

                $totalAmount = $transaction->amount;

                $total_admin_commission = $transaction->costs_amount;

                if ($totalAmount <= 0) {
                    throw new \Exception('Montant final invalide');
                }

                if ($walletAdmin && $walletAdmin->amount >= $totalAmount) {
                    $wallet_commission->decrement('amount', $totalAmount);
                    $walletAdmin->increment('amount', $total_admin_commission);
                } else {
                    $admin_single->wallet->decrement('amount', $totalAmount);
                }
                // Update wallet Transactions
                $transaction->wallet->update([
                    'reload_by' => $transaction->user_id,
                    'sid_user_relod' => $transaction->user_id,
                    'amount' => DB::raw('amount+' . $amountReceived),
                ]);
            }


            // Déclencher l'événement de paiement réussi
            event(new PaymentSuccessful($transaction, $data));

            // Envoyer une notification
            $this->sendSuccessNotification($transaction);

            DB::commit();

            Log::info('Paiement traité avec succès', [
                'reference' => $data['reference'],
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount
            ]);

            if ($transaction->status === 'success') {
                return redirect()->route('payment.success', ['reference' => $data['reference']]);
            } else {
                return redirect()->route('payment.failed', ['reference' => $data['reference']]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement du paiement', [
                'reference' => $data['reference'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('payment.failed', ['reference' => "robus_null"]);
        }
    }

    private function handleCancelledWebCharge(array $data)
    {
        DB::beginTransaction();

        try {
            $transaction = WalletReload::where('reference', $data['reference'])->first();

            if (!$transaction) {
                DB::rollBack();
                Log::error('Transaction non trouvée', ['reference' => $data['reference']]);
                return response()->json(['status' => 'error', 'message' => 'Transaction non trouvée'], 404);
            }

            if ($transaction->status === 'success') {
                DB::rollBack();
                Log::info('Transaction déjà traitée', ['reference' => $data['reference']]);
                return response()->json(['status' => 'already_processed'], 200);
            }

            $wallet = $transaction->wallet;

            if (!$wallet) {
                DB::rollBack();
                Log::error('Wallet introuvable', ['reference' => $data['reference']]);
                return response()->json(['status' => 'error', 'message' => 'Wallet introuvable'], 400);
            }

            $statusMap = [
                'success' => 'success',
                'failed' => 'failed',
                'abandoned' => 'cancelled',
                'cancelled' => 'cancelled'
            ];

            $paymentStatus = $statusMap[$data['status']] ?? 'failed';

            // Mettre à jour la transaction
            $transaction->update([
                'status' => $paymentStatus,
                'gateway_response' => $data['gateway_response'] ?? null,
                'paid_at' => isset($data['paid_at']) ? Carbon::parse($data['paid_at']) : null,
                'payment_method' => $data['channel'] ?? null,
                'currency' => $data['currency'] ?? 'XOF',
                'balance_before' => $wallet->amount,
                'amount_solde' => $wallet->amount ,
                'fees' => isset($data['fees']) ? $data['fees'] / 100 : 0,
                'authorization' => json_encode($data['authorization'] ?? []),
                'customer' => json_encode($data['customer'] ?? []),
                'metadata' => json_encode(array_merge(
                    json_decode($transaction->metadata, true) ?? [],
                    $data['metadata'] ?? []
                )),
                'verified' => $paymentStatus === 'success',
                'verified_at' => $paymentStatus === 'success' ? now() : null,
                'raw_response' => json_encode($data),
            ]);

            // Créditer wallet seulement si succès
            $oldBalance = $wallet->amount;
            $newBalance = $oldBalance + $transaction->amount;

            PaymentReload::create([
                'wallet_reload_id' => $transaction->id,
                'sid_user_id' => $wallet->sid_user_id,
                'type' => 'credit',
                'transaction_id' => $transaction->reference,
                'pay_amount' => $transaction->amount,
                'fee_amount' => isset($data['fees']) ? $data['fees'] / 100 : 0,
                'received_amount' => $transaction->amount,
                'balance_before' => $oldBalance,
                'balance_after' => $newBalance,
                'description' => 'Recharge via Paystack - ' . $transaction->reference,
                'reference' => $transaction->reference,
                'operator_transaction_name' => 'Paystack',
                'status' => 'completed',
            ]);
            // Déclencher l'événement succès et notification
            event(new PaymentSuccessful($transaction, $data));
            $this->sendSuccessNotification($transaction);

            DB::commit();

            return redirect()->route('payment.failed', ['reference' => $data['reference']]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur traitement paiement', [
                'reference' => $data['reference'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Erreur interne'], 500);
        }
    }

    private function handleCancelledCharge(array $data)
    {
        DB::beginTransaction();

        try {
            $transaction = WalletReload::where('reference', $data['reference'])->first();

            if (!$transaction) {
                DB::rollBack();
                Log::error('Transaction non trouvée', ['reference' => $data['reference']]);
                return response()->json(['status' => 'error', 'message' => 'Transaction non trouvée'], 404);
            }

            if ($transaction->status === 'success') {
                DB::rollBack();
                Log::info('Transaction déjà traitée', ['reference' => $data['reference']]);
                return response()->json(['status' => 'already_processed'], 200);
            }

            $wallet = $transaction->wallet;

            if (!$wallet) {
                DB::rollBack();
                Log::error('Wallet introuvable', ['reference' => $data['reference']]);
                return response()->json(['status' => 'error', 'message' => 'Wallet introuvable'], 400);
            }

            $statusMap = [
                'success' => 'success',
                'failed' => 'failed',
                'abandoned' => 'cancelled',
                'cancelled' => 'cancelled'
            ];

            $paymentStatus = $statusMap[$data['status']] ?? 'failed';

            // Mettre à jour la transaction
            $transaction->update([
                'status' => $paymentStatus,
                'gateway_response' => $data['gateway_response'] ?? null,
                'paid_at' => isset($data['paid_at']) ? Carbon::parse($data['paid_at']) : null,
                'payment_method' => $data['channel'] ?? null,
                'currency' => $data['currency'] ?? 'XOF',
                'balance_before' => $wallet->amount,
                'amount_solde' => $wallet->amount,
                'fees' => isset($data['fees']) ? $data['fees'] / 100 : 0,
                'authorization' => json_encode($data['authorization'] ?? []),
                'customer' => json_encode($data['customer'] ?? []),
                'metadata' => json_encode(array_merge(
                    json_decode($transaction->metadata, true) ?? [],
                    $data['metadata'] ?? []
                )),
                'verified' => $paymentStatus === 'success',
                'verified_at' => $paymentStatus === 'success' ? now() : null,
                'raw_response' => json_encode($data),
            ]);

            // Créditer wallet seulement si succès
            $oldBalance = $wallet->amount;
            $newBalance = $oldBalance + $transaction->amount;

            PaymentReload::create([
                'wallet_reload_id' => $transaction->id,
                'sid_user_id' => $wallet->sid_user_id,
                'type' => 'credit',
                'transaction_id' => $transaction->reference,
                'pay_amount' => $transaction->amount,
                'fee_amount' => isset($data['fees']) ? $data['fees'] / 100 : 0,
                'received_amount' => $transaction->amount,
                'balance_before' => $oldBalance,
                'balance_after' => $newBalance,
                'description' => 'Recharge via Paystack - ' . $transaction->reference,
                'reference' => $transaction->reference,
                'operator_transaction_name' => 'Paystack',
                'status' => 'completed',
            ]);
            // Déclencher l'événement succès et notification
            event(new PaymentSuccessful($transaction, $data));
            $this->sendSuccessNotification($transaction);

            DB::commit();

            return response()->json([
                'status' => $paymentStatus,
                'message' => match ($paymentStatus) {
                    'success' => 'Paiement traité avec succès',
                    'cancelled' => 'Paiement annulé',
                    'failed' => 'Paiement échoué',
                    default => 'Erreur de paiement',
                },
                'transaction' => $transaction->fresh(),
                'wallet' => $wallet->fresh(),
                'paystack_data' => $data
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur traitement paiement', [
                'reference' => $data['reference'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Erreur interne'], 500);
        }
    }


    /**
     * Gérer un paiement échoué
     */
    private function handleFailedCharge(array $data)
    {
        try {
            $transaction = WalletReload::where('reference', $data['reference'])->first();

            if ($transaction) {
                $transaction->update([
                    'status' => 'failed',
                    'gateway_response' => $data['gateway_response'],
                    'failure_reason' => $data['message'] ?? 'Paiement échoué',
                    'verified' => true,
                    'verified_at' => now(),
                    'raw_response' => json_encode($data),
                ]);

                // Déclencher l'événement de paiement échoué
                event(new PaymentFailed($transaction, $data));

                // Envoyer une notification d'échec
                $this->sendFailureNotification($transaction);
            }

            return response()->json(['status' => 'failed_processed'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du paiement échoué', [
                'reference' => $data['reference'],
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }

    /**
     * Gérer un transfert réussi
     */
    private function handleSuccessfulTransfer(array $data)
    {
        try {
            Log::info('Transfert Paystack réussi', $data);

            // TODO: Implémenter la logique pour les transferts sortants
            return response()->json(['status' => 'transfer_success'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du transfert réussi', [
                'reference' => $data['reference'],
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }

    /**
     * Gérer un transfert échoué
     */
    private function handleFailedTransfer(array $data)
    {
        try {
            Log::info('Transfert Paystack échoué', $data);

            // TODO: Implémenter la logique pour les transferts échoués
            return response()->json(['status' => 'transfer_failed'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du transfert échoué', [
                'reference' => $data['reference'],
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }

    /**
     * Gérer un transfert annulé
     */
    private function handleReversedTransfer(array $data)
    {
        try {
            Log::info('Transfert Paystack annulé', $data);

            // TODO: Implémenter la logique pour les transferts annulés
            return response()->json(['status' => 'transfer_reversed'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du transfert annulé', [
                'reference' => $data['reference'],
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur interne'], 500);
        }
    }

    /**
     * Vérifier manuellement une transaction (pour le frontend)
     */
    public function verifyTransaction(Request $request, $reference = null)
    {
        // Support pour GET avec paramètre dans l'URL ou POST avec body
        $reference = $reference ?: $request->input('reference');

        if (!$reference) {
            return response()->json([
                'success' => false,
                'message' => 'Référence invalide'
            ], 400);
        }

        try {

            $paystackService = new PaystackService();
            $verification = $paystackService->verifyTransaction($reference);

            if ($verification['status']) {
                // Transaction trouvée, vérifier si elle est déjà enregistrée
                $transaction = WalletReload::where('reference', $reference)->first();

                if ($transaction) {
                    $data = $verification['data'];

                    // Mettre à jour la transaction selon le statut
                    if ($data['status'] === 'success' && $transaction->status !== 'success') {
                        return $this->handleSuccessfulCharge($data);
                    } elseif (in_array($data['status'], ['failed', 'abandoned']) && $transaction->status === 'pending') {

                        $amountPaid = $data['amount'] / 100;
                        $paystackFees = ($data['fees'] ?? 0) / 100;
                        $amountReceived = $amountPaid - $paystackFees;
                        $wallet = $transaction->wallet;

                        $transaction->update([
                            'status' => 'failed',
                            'gateway_response' => $data['gateway_response'],
                            'verified' => true,
                            'verified_at' => now(),
                            'balance_before' =>  $wallet->amount,
                            'amount_solde' => $wallet->amount,
                            'fees' => $paystackFees,
                            'amount_received' => $amountReceived,
                            'raw_response' => json_encode($data),
                        ]);

                        return $this->handleCancelledCharge($data);

                        return response()->json([
                            'success' => false,
                            'message' => 'Paiement échoué',
                            'transaction' => $transaction
                        ]);
                    }

                    // Retourner le statut actuel
                    return response()->json([
                        'success' => true,
                        'message' => 'Transaction vérifiée',
                        'status' => $transaction->status,
                        'transaction' => $transaction
                    ]);
                }

                // Transaction non trouvée localement mais existe sur Paystack
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction vérifiée sur Paystack mais non enregistrée localement',
                    'data' => $verification
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Transaction non trouvée sur Paystack'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification manuelle', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification'
            ], 500);
        }
    }


    /**
     * Endpoint pour le callback de redirection
     */
    public function paymentCallback(Request $request)
    {
        $reference = $request->query('reference');
        $trxref = $request->query('trxref');
        $status = $request->query('status');


        $reference = $reference ?: $trxref;


        if (!$reference) {
            return response()->json([
                'success' => false,
                'message' => 'Référence invalide'
            ], 400);
        }

        $paystackService = new PaystackService();
        $verification = $paystackService->verifyTransaction($reference);


        if (!$verification || !$verification['status']) {
            return response()->json(['message' => 'Verification failed'], 200);
        }



        if ($verification['status']) {

            // Transaction trouvée, vérifier si elle est déjà enregistrée
            $transaction = WalletReload::where('reference', $reference)->first();


            if ($transaction) {
                $data = $verification['data'];

                // Mettre à jour la transaction selon le statut
                if ($data['status'] === 'success' && $transaction->status !== 'success') {

                    return $this->handleSuccessWebfulCharge($data);
                } elseif (in_array($data['status'], ['failed', 'abandoned']) && $transaction->status === 'pending') {

                    $amountPaid = $data['amount'] / 100;
                    $paystackFees = ($data['fees'] ?? 0) / 100;
                    $amountReceived = $amountPaid - $paystackFees;
                    $wallet = $transaction->wallet;

                    $transaction->update([
                        'status' => 'failed',
                        'gateway_response' => $data['gateway_response'],
                        'verified' => true,
                        'verified_at' => now(),
                        'balance_before' =>  $wallet->amount,
                        'amount_solde' => $wallet->amount,
                        'fees' => $paystackFees,
                        'amount_received' => $amountReceived,
                        'raw_response' => json_encode($data),
                    ]);

                    return $this->handleCancelledWebCharge($data);
                } else {
                    return redirect()->route('payment.failed', ['reference' => "Je crois que vous avez deja utiliser ce retour de paiement "]);
                }
            }
        } else {
            return redirect()->route('payment.failed', ['reference' => "Paiment impossible"]);
        }
    }

    /**
     * Page de succès de paiement
     */
    public function paymentSuccess($reference)
    {

        if (!$reference) {
            return redirect()->route('payment.failed', ['reference' => 'unknown'])
                ->with('error', 'Référence manquante');
        }

        $transaction = WalletReload::where('reference', $reference)->first();

        if (!$transaction) {
            return redirect()->route('payment.failed', ['reference' => $reference])
                ->with('error', 'Transaction non trouvée');
        }

        if ($transaction->status !== 'success') {
            return redirect()->route('payment.failed', ['reference' => $reference]);
        }

        return view('pages.payment_success', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Page d'échec de paiement
     */
    public function paymentFailed($reference)
    {

        if (!$reference) {
            return redirect()->route('payment.failed', ['reference' => 'unknown'])
                ->with('error', 'Référence manquante');
        }

        $transaction = WalletReload::where('reference', $reference)->first();

        if (!$transaction) {
            return redirect()->route('payment.failed', ['reference' => $reference])
                ->with('error', 'Transaction non trouvée');
        }

        if ($transaction->status !== 'success') {
            return redirect()->route('payment.failed', ['reference' => $reference]);
        }

        return view('pages.payment_failed', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Générer une référence de transaction
     */
    public function generateReference(Request $request)
    {
        $prefix = 'ROBUS_';
        $timestamp = now()->timestamp;
        $random = strtoupper(bin2hex(random_bytes(4)));

        $reference = $prefix . $timestamp . '_' . $random;

        return response()->json([
            'success' => true,
            'reference' => $reference,
            'timestamp' => $timestamp
        ]);
    }

    /**
     * Valider la signature Paystack
     */
    private function validatePaystackSignature(Request $request): bool
    {
        $signature = $request->header('x-paystack-signature');

        if (!$signature) {
            Log::warning('Signature Paystack manquante');
            return false;
        }

        $payload = $request->getContent();
        $computedSignature = hash_hmac('sha512', $payload, $this->paystackSecret);

        $isValid = hash_equals($computedSignature, $signature);

        if (!$isValid) {
            Log::warning('Signature Paystack invalide', [
                'computed' => $computedSignature,
                'received' => $signature
            ]);
        }

        return $isValid;
    }

    /**
     * Envoyer une notification de succès
     */
    private function sendSuccessNotification(WalletReload $transaction)
    {
        try {
            $user = $transaction->user;

            if ($user) {
                // Envoyer un email
                if ($user->email) {
                    $mailController = new MailController();
                    $mailController->newReload($transaction);
                }

                // Envoyer un SMS pour la Côte d'Ivoire
                if ($user->country && $user->country->code == "CI" && $user->phone) {
                    $message = sprintf(
                        'Vous avez rechargé votre compte Robus de %s FCFA via Paystack. Référence: %s. Nouveau solde: %s FCFA. Robus vous remercie!',
                        number_format($transaction->amount, 0, ',', ' '),
                        $transaction->reference,
                        number_format($transaction->wallet->balance, 0, ',', ' ')
                    );

                    $phoneNumber = '225' . substr($user->phone, -10);

                    // Http::get('https://app.smspro.africa/api/http/sms/send', [
                    //     'recipient' => $phoneNumber,
                    //     'sender_id' => 'ROBUS',
                    //     'message' => $message,
                    //     'api_token' => config('services.smspro.api_token')
                    // ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer une notification d'échec
     */
    private function sendFailureNotification(WalletReload $transaction)
    {
        try {
            $user = $transaction->user;

            if ($user && $user->email) {
                // Envoyer un email d'échec
                // TODO: Implémenter un email spécifique pour les échecs
                Log::info('Notification d\'échec pour la transaction', [
                    'transaction_id' => $transaction->id,
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification d\'échec', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
