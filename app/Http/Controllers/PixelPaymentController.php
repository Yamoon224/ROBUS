<?php

namespace App\Http\Controllers;

use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\PixelInnovService;
use App\Models\Costs\ReloadCost;
use App\Models\Custumers\Country;
use App\Models\LedgerEntry;
use App\Models\Payments\PaymentReload;
use App\Models\PixelInnoveService;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Pixel Innov",
 *     description="Paiements mobile money multi-pays via l'API Pixel Innov (Orange, MTN, Moov, Wave…)"
 * )
 */
class PixelPaymentController extends Controller
{
    // Limites par code pays ISO2 (source : docs.pixpay.sn)
    private const AMOUNT_LIMITS = [
        'CI' => ['min' => 200,   'max' => 1_500_000, 'currency' => 'XOF'],
        'SN' => ['min' => 200,   'max' => 2_000_000, 'currency' => 'XOF'],
        'ML' => ['min' => 200,   'max' => 1_500_000, 'currency' => 'XOF'],
        'BF' => ['min' => 200,   'max' => 1_500_000, 'currency' => 'XOF'],
        'BJ' => ['min' => 200,   'max' => 1_500_000, 'currency' => 'XOF'],
        'TG' => ['min' => 500,   'max' => 1_500_000, 'currency' => 'XOF'],
        'CM' => ['min' => 500,   'max' => 1_500_000, 'currency' => 'XAF'],
        'GN' => ['min' => 1_000, 'max' => 15_000_000,'currency' => 'GNF'],
    ];

    protected PercentRobusAuthService $percentRobus;

    public function __construct(PercentRobusAuthService $percentRobus)
    {
        $this->percentRobus = $percentRobus;
    }

    private static function countryCode($country): ?string
    {
        return $country && $country->code ? strtoupper($country->code) : null;
    }

    // =========================================================================
    // LISTE DES SERVICES DISPONIBLES
    // =========================================================================

    /**
     * @OA\Get(
     *     path="/pixelInnov/services",
     *     tags={"Pixel Innov"},
     *     summary="Lister les moyens de paiement disponibles",
     *     description="Retourne les services Pixel Innov actifs filtrés par le pays du client authentifié. Le filtre pays est automatique : si `country_id` n'est pas précisé, le pays du compte connecté est utilisé. Vous pouvez surcharger avec `country_id` ou `country_iso2`.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         required=false,
     *         description="ID interne du pays (surcharge la détection automatique du pays du client)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="country_iso2",
     *         in="query",
     *         required=false,
     *         description="Code ISO2 du pays (ex: SN, CI, ML, BF, BJ, TG, CM, GN) — utilisé si `country_id` n'est pas fourni",
     *         @OA\Schema(type="string", example="SN", enum={"SN","CI","ML","BF","BJ","TG","CM","GN"})
     *     ),
     *     @OA\Parameter(
     *         name="operation_type",
     *         in="query",
     *         required=false,
     *         description="Type d'opération à filtrer",
     *         @OA\Schema(type="string", enum={"Cash In","Cash Out"}, example="Cash In")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des services disponibles pour le pays du client",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="country_filter", type="string", example="SN",
     *                 description="Code ISO2 du pays utilisé pour le filtrage (auto-détecté ou manuel)"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Orange Money Sénégal"),
     *                     @OA\Property(property="service_id", type="integer", example=12),
     *                     @OA\Property(property="operation_type", type="string", example="Cash In"),
     *                     @OA\Property(property="provider", type="string", example="Orange"),
     *                     @OA\Property(property="country", type="string", example="Sénégal"),
     *                     @OA\Property(property="country_iso2", type="string", example="SN"),
     *                     @OA\Property(property="currency", type="string", example="XOF"),
     *                     @OA\Property(property="amount_min", type="integer", example=200),
     *                     @OA\Property(property="amount_max", type="integer", example=2000000),
     *                     @OA\Property(property="fixed_fee_pct", type="number", format="float", example=0.5),
     *                     @OA\Property(property="commission_pct", type="number", format="float", example=1.0),
     *                     @OA\Property(property="total_fee_pct", type="number", format="float", example=1.5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function listServices(Request $request)
    {
        $user = robus();

        // Résoudre le country_id effectif :
        // 1. Priorité au paramètre explicite country_id
        // 2. Sinon résoudre via country_iso2 passé en paramètre
        // 3. Sinon utiliser le pays du compte authentifié (auto-détection)
        $countryId = $request->country_id;

        if (!$countryId && $request->country_iso2) {
            $countryId = Country::where('code', strtoupper($request->country_iso2))->value('id');
        }

        if (!$countryId && $user && $user->country_id) {
            $countryId = $user->country_id;
        }

        $services = PixelInnoveService::with(['country', 'provider', 'providerType'])
            ->where('status', true)
            ->when($countryId, fn($q) => $q->where('country_id', $countryId))
            ->when($request->operation_type, fn($q) => $q->where('operation_type', $request->operation_type))
            ->orderBy('name')
            ->get()
            ->map(fn($s) => [
                'id'               => $s->id,
                'name'             => $s->name,
                'service_id'       => $s->service_id,
                'operation_type'   => $s->operation_type,
                'provider'         => $s->provider->name ?? null,
                'image'         => $s->provider->image ?? null,
                'country'          => $s->country->name ?? null,
                'country_iso2'     => self::countryCode($s->country),
                'currency'         => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['currency'] ?? 'XOF',
                'amount_min'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['min'] ?? 200,
                'amount_max'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['max'] ?? 1_500_000,
                'fixed_fee_pct'    => (float) $s->fixed_fee,
                'commission_pct'   => (float) $s->commission_percent,
                'total_fee_pct'    => (float) $s->fixed_fee + (float) $s->commission_percent,
            ]);

        // Déterminer le code ISO2 effectivement utilisé pour le filtrage
        $isoUsed = null;
        if ($countryId) {
            $isoUsed = Country::where('id', $countryId)->value('code');
        }

        return response()->json([
            'status'         => true,
            'country_filter' => $isoUsed,
            'data'           => $services,
        ]);
    }

    // =========================================================================
    // INITIALISATION DU PAIEMENT
    // =========================================================================

    /**
     * @OA\Post(
     *     path="/pixelInnov/initialize",
     *     tags={"Pixel Innov"},
     *     summary="Initialiser un paiement mobile money",
     *     description="Démarre une transaction de dépôt (Cash In) ou de retrait (Cash Out) via Pixel Innov. Le `pixel_service_id` doit correspondre à un service actif disponible dans le pays du client (voir `GET /pixelInnov/services`).",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pixel_service_id","amount","destination"},
     *             @OA\Property(property="pixel_service_id", type="integer", example=3,
     *                 description="ID interne du service Pixel Innov (obtenu via GET /pixelInnov/services)"),
     *             @OA\Property(property="amount", type="integer", example=5000,
     *                 description="Montant en devise locale (XOF, XAF ou GNF selon le pays)"),
     *             @OA\Property(property="destination", type="string", example="221771234567",
     *                 description="Numéro de téléphone du client au format international (sans +)"),
     *             @OA\Property(property="custom_data", type="string", example="REF-2024-001",
     *                 description="Référence libre optionnelle (facture, commande…)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paiement initié — le client doit valider sur son téléphone",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Un lien de validation a été envoyé par SMS au client."),
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123"),
     *             @OA\Property(property="sms_link", type="string", nullable=true, example="https://pay.orange.sn/…"),
     *             @OA\Property(property="state", type="string", example="PENDING1"),
     *             @OA\Property(property="provider", type="string", example="Orange"),
     *             @OA\Property(property="country", type="string", example="Sénégal"),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="fees", type="number", example=75),
     *             @OA\Property(property="amount_received", type="number", example=4925)
     *         )
     *     ),
     *     @OA\Response(response=400, description="Échec de l'initialisation côté Pixel Innov"),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Données invalides ou montant hors limites")
     * )
     */
    public function initializePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pixel_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'           => 'required|numeric|min:1',
            'destination'      => 'required|string|min:8|max:15',
            'om_otp'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'message' => 'Données invalides', 'errors' => $validator->errors(),
            ], 422);
        }

        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifié'], 401);
        }

        $wallet = Wallet::where('sid_user_id', $user->id)->first();
        if (!$wallet) {
            return response()->json(['status' => false, 'message' => 'Portefeuille introuvable'], 404);
        }

        // Récupérer la config du service (provider + pays + frais)
        $pixelConfig = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->pixel_service_id)
            ->where('status', true)
            ->first();

        if (!$pixelConfig) {
            return response()->json(['status' => false, 'message' => 'Service introuvable ou inactif'], 404);
        }

        // Valider le montant selon les limites du pays
        $iso2   = self::countryCode($pixelConfig->country) ?? '';
        $limits = self::AMOUNT_LIMITS[$iso2] ?? ['min' => 200, 'max' => 1_500_000];
        $amount = (float) $request->amount;

        if ($amount < $limits['min'] || $amount > $limits['max']) {
            return response()->json([
                'status'  => false,
                'message' => "Montant invalide pour {$pixelConfig->country->name}. Min : {$limits['min']}, Max : {$limits['max']}.",
            ], 422);
        }

        // Calcul des frais depuis la config du service
        $totalFeePct    = (float) $pixelConfig->fixed_fee + (float) $pixelConfig->commission_percent;
        $fees           = round($amount * $totalFeePct / 100, 2);
        $amountReceived = $amount - $fees;
        $customData     = $request->custom_data ?? ('ROBUS_' . $user->id . '_' . now()->timestamp);

        // Enregistrement en base AVANT l'appel API (idempotence)
        $reload = WalletReload::create([
            'wallet_id'       => $wallet->id,
            'provider_id'     => $pixelConfig->provider_id,
            'reload_cost_id'  => 1,
            'amount'          => $amount,
            'fees'            => $fees,
            'costs_amount'    => $fees,
            'amount_received' => $amountReceived,
            'amount_solde'    => $wallet->amount,
            'balance_before'  => $wallet->amount,
            'currency'        => $limits['currency'] ?? 'XOF',
            'status'          => 'PENDING',
            'payment_method'  => 'pixel_innov_' . strtolower($pixelConfig->provider->name ?? 'unknown'),
            'metadata'        => json_encode([
                'user_id'          => $user->id,
                'pixel_service_id' => $pixelConfig->id,
                'pixel_service_name' => $pixelConfig->name,
                'service_id'       => $pixelConfig->service_id,
                'provider'         => $pixelConfig->provider->name ?? null,
                'country'          => $pixelConfig->country->name ?? null,
                'country_iso2'     => $iso2,
                'operation_type'   => $pixelConfig->operation_type,
                'destination'      => $request->destination,
                'custom_data'      => $customData,
            ]),
        ]);

        // Appel Pixel Innov
        $pixelService = app(PixelInnovService::class);

        $publicBase = rtrim(config('services.pixel_innov.public_url'), '/');
        $pixelPayload = [
            'amount'             => (int) $amount,
            'destination'        => $request->destination,
            'service_id'         => $pixelConfig->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/callback',
            'custom_data'        => $customData,
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];

        // Orange Money Cash Out exige un OTP côté Pixel Innov
        if ($request->filled('om_otp')) {
            $pixelPayload['om_otp'] = $request->om_otp;
        }

        $response = $pixelService->initializeTransaction($pixelPayload);

        // Conserver le transaction_id même en cas d'échec (pour réconciliation)
        $pixelTxId = $response['data']['transaction_id'] ?? null;
        if ($pixelTxId) {
            $reload->update(['reference' => $pixelTxId]);
        }

        $isFailed = ($response['statut_code'] ?? null) !== 200
            || empty($pixelTxId)
            || ($response['data']['state'] ?? null) === 'FAILED';

        if ($isFailed) {
            $reload->update(['status' => 'ERROR']);
            Log::warning('Pixel Innov — init échouée', [
                'response'       => $response,
                'reload_id'      => $reload->id,
                'transaction_id' => $pixelTxId,
                'state'          => $response['data']['state'] ?? null,
                'wallet_before'  => $response['data']['p_last_wallet_amount'] ?? null,
            ]);

            // Chercher un message utile dans la réponse Pixel Innov
            $pixelMsg    = trim((string) ($response['message'] ?? ''));
            $dataMsg     = trim((string) ($response['data']['response'] ?? ''));
            $walletBefore = $response['data']['p_last_wallet_amount'] ?? null;
            $state        = $response['data']['state'] ?? null;

            if ($pixelMsg !== '') {
                $reason = $pixelMsg;
            } elseif ($dataMsg !== '') {
                $reason = $dataMsg;
            } elseif ($state === 'FAILED' && $walletBefore === 0) {
                $reason = 'Solde marchand PixPay insuffisant. Veuillez contacter Robus.';
            } elseif ($state === 'FAILED') {
                $reason = 'Transaction rejetée par l\'opérateur mobile.';
            } else {
                $reason = 'Échec de l\'initialisation. Veuillez réessayer.';
            }

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        $transactionId = $pixelTxId;
        $smsLink       = $response['data']['sms_link'] ?? null;

        Log::info('Pixel Innov — paiement initié', [
            'transaction_id' => $transactionId,
            'provider'       => $pixelConfig->provider->name ?? null,
            'country'        => $iso2,
            'user_id'        => $user->id,
            'amount'         => $amount,
        ]);

        return response()->json([
            'status'         => true,
            'message'        => $this->initMessage($pixelConfig->provider->name ?? '', $smsLink),
            'transaction_id' => $transactionId,
            'sms_link'       => $smsLink,
            'state'          => $response['data']['state'] ?? 'PENDING',
            'provider'       => $pixelConfig->provider->name ?? null,
            'country'        => $pixelConfig->country->name ?? null,
            'amount'         => $amount,
            'fees'           => $fees,
            'amount_received'=> $amountReceived,
        ], 200);
    }

    // =========================================================================
    // CALCUL DES FRAIS AVANT INITIALISATION
    // =========================================================================

    /**
     * @OA\Post(
     *     path="/pixelInnov/calculate",
     *     tags={"Pixel Innov"},
     *     summary="Calculer les frais et le montant reçu pour un service donné",
     *     description="Retourne les frais et le montant net qui sera crédité/envoyé pour un montant et un service Pixel Innov donnés, sans initier de transaction.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pixel_service_id","amount"},
     *             @OA\Property(property="pixel_service_id", type="integer", example=6,
     *                 description="service_id du service Pixel Innov (obtenu via GET /pixelInnov/services)"),
     *             @OA\Property(property="amount", type="integer", example=5000,
     *                 description="Montant saisi par l'utilisateur en devise locale")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Calcul des frais",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="fixed_fee_pct", type="number", example=0.5),
     *             @OA\Property(property="commission_pct", type="number", example=1.9),
     *             @OA\Property(property="total_fee_pct", type="number", example=2.4),
     *             @OA\Property(property="fees", type="number", example=120),
     *             @OA\Property(property="amount_received", type="number", example=4880),
     *             @OA\Property(property="currency", type="string", example="XOF"),
     *             @OA\Property(property="provider", type="string", example="Mtn"),
     *             @OA\Property(property="operation_type", type="string", example="Cash In")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Données invalides ou montant hors limites"),
     *     @OA\Response(response=404, description="Service introuvable")
     * )
     */
    public function calculateAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pixel_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'           => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 'message' => 'Données invalides', 'errors' => $validator->errors(),
            ], 422);
        }

        $pixelConfig = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->pixel_service_id)
            ->where('status', true)
            ->first();

        if (!$pixelConfig) {
            return response()->json(['status' => false, 'message' => 'Service introuvable ou inactif'], 404);
        }

        $iso2   = self::countryCode($pixelConfig->country) ?? '';
        $limits = self::AMOUNT_LIMITS[$iso2] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];
        $amount = (float) $request->amount;

        if ($amount < $limits['min'] || $amount > $limits['max']) {
            return response()->json([
                'status'  => false,
                'message' => "Montant invalide. Min : {$limits['min']}, Max : {$limits['max']} {$limits['currency']}.",
                'amount_min' => $limits['min'],
                'amount_max' => $limits['max'],
                'currency'   => $limits['currency'],
            ], 422);
        }

        $fixedFeePct    = (float) $pixelConfig->fixed_fee;
        $commissionPct  = (float) $pixelConfig->commission_percent;
        $totalFeePct    = $fixedFeePct + $commissionPct;
        $fees           = round($amount * $totalFeePct / 100, 2);
        $amountReceived = round($amount - $fees, 2);

        return response()->json([
            'status'          => true,
            'amount'          => $amount,
            'fixed_fee_pct'   => $fixedFeePct,
            'commission_pct'  => $commissionPct,
            'total_fee_pct'   => $totalFeePct,
            'fees'            => $fees,
            'amount_received' => $amountReceived,
            'currency'        => $limits['currency'],
            'provider'        => $pixelConfig->provider->name ?? null,
            'operation_type'  => $pixelConfig->operation_type,
        ]);
    }

    // =========================================================================
    // VÉRIFICATION DE STATUT
    // =========================================================================

    /**
     * @OA\Post(
     *     path="/pixelInnov/status",
     *     tags={"Pixel Innov"},
     *     summary="Vérifier le statut d'une transaction",
     *     description="Interroge à la fois la base locale et l'API Pixel Innov pour retourner l'état en temps réel d'une transaction initiée.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaction_id"},
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123",
     *                 description="Identifiant de transaction retourné par /pixelInnov/initialize")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de la transaction",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="local_status", type="string", example="SUCCESS",
     *                 description="Statut en base : PENDING | SUCCESS | ERROR"),
     *             @OA\Property(property="remote_state", type="string", nullable=true, example="SUCCESS",
     *                 description="État retourné en temps réel par Pixel Innov"),
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123"),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="fees", type="number", example=75),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Transaction introuvable"),
     *     @OA\Response(response=422, description="transaction_id manquant")
     * )
     */
    public function checkStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $reload = WalletReload::where('reference', $request->transaction_id)->first();

        if (!$reload) {
            return response()->json(['status' => false, 'message' => 'Transaction introuvable'], 404);
        }

        // Vérification distante via Pixel Innov
        $pixelService = app(PixelInnovService::class);
        $remote = $pixelService->checkTransactionStatus($request->transaction_id);

        return response()->json([
            'status'         => true,
            'local_status'   => $reload->status,
            'remote_state'   => $remote['data']['state'] ?? null,
            'transaction_id' => $request->transaction_id,
            'amount'         => $reload->amount,
            'fees'           => $reload->fees,
            'created_at'     => $reload->created_at,
        ]);
    }

    // =========================================================================
    // IPN WEBHOOK — appelé serveur→serveur par Pixel Innov
    // =========================================================================

    /**
     * POST /payment_pixelInnov/callback
     *
     * Payload Pixel Innov :
     *   transaction_id, amount, state (SUCCESS|FAILED), error, response,
     *   provider_id, hash (SHA256(api_key + transaction_id)), created_at
     */
    public function paymentCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $state         = $request->input('state');
        $hash          = $request->input('hash');

        Log::info('Pixel Innov — IPN reçu', $request->all());

        if (!$transactionId || !$state || !$hash) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        // Vérification du hash SHA256(api_key + transaction_id)
        $pixelService = app(PixelInnovService::class);
        if (!$pixelService->verifyIpnHash($transactionId, $hash)) {
            Log::warning('Pixel Innov — hash invalide', ['transaction_id' => $transactionId]);
            return response()->json(['status' => 'invalid_hash'], 401);
        }

        DB::beginTransaction();
        try {
            $reload = WalletReload::where('reference', $transactionId)->lockForUpdate()->first();

            if (!$reload) {
                DB::rollBack();
                Log::error('Pixel Innov — WalletReload introuvable', ['transaction_id' => $transactionId]);
                return response()->json(['status' => 'not_found'], 404);
            }

            // Idempotence sous verrou SQL pour éviter un double crédit si Pixel renvoie deux IPN.
            if (in_array(strtoupper($reload->status), ['SUCCESS', 'ERROR'])) {
                DB::rollBack();
                return response()->json(['status' => 'already_processed'], 200);
            }

            strtoupper($state) === 'SUCCESS'
                ? $this->handleSuccess($reload, $request->all())
                : $this->handleFailure($reload, $request->all());

            DB::commit();
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Pixel Innov — erreur IPN', [
                'transaction_id' => $transactionId,
                'error'          => $e->getMessage(),
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    // =========================================================================
    // REDIRECTIONS NAVIGATEUR
    // =========================================================================

    public function redirectSuccess(Request $request)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Paiement effectué avec succès. Votre compte sera crédité sous peu.',
        ]);
    }

    public function redirectError(Request $request)
    {
        return response()->json([
            'status'  => false,
            'message' => 'Le paiement a échoué ou a été annulé.',
        ]);
    }

    // =========================================================================
    // MÉTHODES PRIVÉES
    // =========================================================================

    private function handleSuccess(WalletReload $reload, array $ipnData): void
    {
        $wallet = $reload->wallet;
        $walletDetails = Wallet::whereId($reload->wallet_id)->first();
        $countryNameGroup = $walletDetails->country->name_groupe ?? null;

        if (in_array($countryNameGroup, ['CIV', 'AFL', 'AFC', 'AFE'])) {
            $commissionRobus = ReloadCost::whereId(2)->first();
        } elseif (in_array($countryNameGroup, ['URP', 'USA', 'MAG', 'ASIE'])) {
            $commissionRobus = ReloadCost::whereId(3)->first();
        } else {
            $commissionRobus = ReloadCost::whereId($reload->reload_cost_id)->first();
        }

        if (!$commissionRobus) {
            throw new \Exception('Coût Robus introuvable');
        }

        $totalPercent = (float) $commissionRobus->percent + (float) $commissionRobus->percent_admin;
        $totalCommission = $totalPercent * (float) $reload->amount;
        $amountReceived = (float) $reload->amount - (float) $totalCommission;

        $walletAdmin = WalletAdmin::find(1);
        $walletCommission = Wallet::where('wallet_id', 'CI44RTZU')->first();
        $adminSingle = SidUser::whereIsAdmin(true)->where('country_id', 107)->first();

        if (!$wallet) {
            throw new \Exception('Wallet client introuvable');
        }

        if (!$walletAdmin) {
            throw new \Exception('Wallet admin introuvable');
        }

        if (!$walletCommission && !$adminSingle?->wallet) {
            throw new \Exception('Wallet commission introuvable');
        }

        $countryCiv = Country::find(107);
        $robusCurrency = $wallet->country->currencie->value ?? 1;

        if ($countryCiv && $countryCiv->currencie->value == $wallet->country->currencie->value) {
            $amountTotalConversion = (float) $reload->amount;
            $amountCommissionConversion = $totalCommission;
        } else {
            $amountTotalConversion = (float) $reload->amount * (float) $robusCurrency;
            $amountCommissionConversion = $totalCommission * (float) $robusCurrency;
        }

        if ($amountTotalConversion <= 0) {
            throw new \Exception('Montant final invalide');
        }

        $reload->update([
            'status'           => 'SUCCESS',
            'gateway_response' => $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
            'paid_at'          => !empty($ipnData['created_at']) ? Carbon::parse($ipnData['created_at']) : now(),
            'payment_method'   => 'ACHAT D\'UV',
            'description'      => 'ACHAT D\'UV Pixel Innov',
            'balance_before'   => $wallet->amount,
            'amount_solde'     => (float) $wallet->amount + (float) $amountReceived,
            'amount_received'  => $amountReceived,
            'reload_cost_id'   => $commissionRobus->id,
            'amount_solde_drawal' => $walletCommission->amount ?? null,
            'amount_solde_before_drawal' => $walletCommission
                ? (float) $walletCommission->amount - (float) $amountTotalConversion
                : null,
            'account_wallet'   => 'CI44RTZU',
            'type_transaction' => 'Achat UV',
            'type_received'    => 'UV',
            'fees'             => $totalCommission,
            'costs_amount'     => $totalCommission,
            'cost_conversion'  => $amountCommissionConversion,
            'amount_conversion'=> $amountTotalConversion,
        ]);

        PaymentReload::create([
            'wallet_reload_id' => $reload->id,
            'sid_user_id' => $wallet->sid_user_id,
            'type' => 'credit',
            'transaction_id' => $ipnData['transaction_id'],
            'pay_amount' => $reload->amount,
            'fee_amount' => $totalCommission,
            'received_amount' => $amountReceived,
            'balance_before' => $wallet->amount,
            'balance_after' => (float) $wallet->amount + (float) $amountReceived,
            'description' => 'Recharge via achat UV Pixel Innov - ' . $ipnData['transaction_id'],
            'reference' => $ipnData['transaction_id'],
            'operator_transaction_name' => 'Pixel Innov',
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($walletCommission && $walletCommission->amount >= $amountTotalConversion) {
            $walletCommission->decrement('amount', $amountTotalConversion);
        } else {
            $adminSingle->wallet->decrement('amount', $amountTotalConversion);
        }

        LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id' => null,
            'debit' => 0,
            'credit' => $amountCommissionConversion,
            'description' => 'Commission adminstrateur',
            'is_uv' => true,
        ]);

        $walletAdmin->increment('amount', $amountCommissionConversion);

        LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id' => null,
            'debit' => $amountTotalConversion,
            'credit' => 0,
            'description' => 'Wallet retrait',
            'is_wallet' => true,
        ]);

        $wallet->update([
            'reload_by' => $reload->user_id,
            'sid_user_relod' => $reload->user_id,
            'amount' => DB::raw('amount+' . $amountReceived),
        ]);

        Log::info('Pixel Innov — SUCCESS, wallet crédité', [
            'transaction_id'  => $ipnData['transaction_id'],
            'wallet_id'       => $reload->wallet_id,
            'amount_credited' => $amountReceived,
            'commission'      => $totalCommission,
            'provider_ref'    => $ipnData['provider_id'] ?? null,
        ]);
    }

    private function handleFailure(WalletReload $reload, array $ipnData): void
    {
        $reload->update([
            'status'           => 'ERROR',
            'gateway_response' => $ipnData['error'] ?? $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
        ]);

        Log::info('Pixel Innov — FAILED', [
            'transaction_id' => $ipnData['transaction_id'],
            'error'          => $ipnData['error'] ?? null,
        ]);
    }

    /**
     * Message de confirmation adapté selon le provider
     * Orange CI → le client valide via un lien SMS (pas d'OTP)
     * Autres    → le client valide via USSD ou OTP selon le provider
     */
    private function initMessage(string $providerName, ?string $smsLink): string
    {
        $name = strtolower($providerName);

        if (str_contains($name, 'orange') && $smsLink) {
            return 'Un lien de validation a été envoyé par SMS au client. Il doit cliquer dessus pour confirmer.';
        }
        if (str_contains($name, 'wave')) {
            return 'Une notification Wave a été envoyée au client pour validation.';
        }
        if (str_contains($name, 'mtn')) {
            return 'Le client doit valider la transaction via son application MTN MoMo ou l\'invite USSD.';
        }
        if (str_contains($name, 'moov')) {
            return 'Le client doit valider via l\'invite USSD Moov Money.';
        }

        return 'Transaction initiée. Le client doit la valider sur son téléphone.';
    }
}
