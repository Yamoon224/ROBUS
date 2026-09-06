<?php

namespace App\Http\Controllers;

use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\PixelInnovService;
use App\Models\Costs\ReloadCost;
use App\Models\Custumers\Country;
use App\Models\LedgerEntry;
use App\Models\Payments\PaymentReload;
use App\Models\PixelInnoveService;
use App\Models\PixelMobileZoneTransfer;
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
use App\Models\Wallets\WalletCommission;

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
        'GN' => ['min' => 1_000, 'max' => 15_000_000, 'currency' => 'GNF'],
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

        // show_all=true : désactive le filtrage automatique par pays (pour l'envoi international)
        $showAll = $request->boolean('show_all', false);

        if (!$showAll && !$countryId && $user && $user->country_id) {
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
                'amount_min'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['min'] ?? 100,
                'amount_max'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['max'] ?? 1_500_000,
                'fixed_fee_pct'    => (float) $s->fixed_fee,
                'commission_pct'   => (float) $s->commission_percent,
                'is_xof'   =>  $s->is_xof,
                'is_xaf'   =>  $s->is_xaf,
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
    // SERVICES TRANSFERT MOBILE ZONE (XOF / XAF) — groupés par pays
    // =========================================================================

    public function listZoneServices(Request $request)
    {
        $user = robus();

        // Déterminer la zone de l'utilisateur depuis son code pays
        $userCountryCode = self::countryCode($user?->country);
        $userZone = self::AMOUNT_LIMITS[$userCountryCode ?? '']['currency'] ?? null;

        // Zone inconnue → pas de transfert zone disponible
        if (!in_array($userZone, ['XOF', 'XAF'])) {
            return response()->json([
                'status'       => true,
                'zone'         => null,
                'user_country' => ['code' => $userCountryCode, 'name' => $user?->country?->name],
                'countries'    => [],
            ]);
        }

        $isXof = $userZone === 'XOF';

        // Récupérer les services actifs de la zone, en excluant le pays de l'utilisateur
        $userCountryId = $user?->country_id;
        $services = PixelInnoveService::with(['country', 'provider'])
            ->where('status', true)
            ->where($isXof ? 'is_xof' : 'is_xaf', true)
            ->when($userCountryId, fn($q) => $q->where('country_id', '!=', $userCountryId))
            ->when($request->operation_type, fn($q) => $q->where('operation_type', $request->operation_type))
            ->orderBy('name')
            ->get();

        // Grouper par pays
        $grouped = $services->groupBy(fn($s) => $s->country_id);

        $countries = $grouped->map(function ($items) use ($userCountryCode) {
            $first = $items->first();
            $iso2  = self::countryCode($first->country);
            $limits = self::AMOUNT_LIMITS[$iso2 ?? ''] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];

            $providers = $items->map(fn($s) => [
                'id'             => $s->id,
                'name'           => $s->name,
                'service_id'     => $s->service_id,
                'operation_type' => $s->operation_type,
                'provider'       => $s->provider->name ?? null,
                'image'          => $s->provider->image ?? null,
                'fixed_fee_pct'  => (float) $s->fixed_fee,
                'commission_pct' => (float) $s->commission_percent,
                'total_fee_pct'  => (float) $s->fixed_fee + (float) $s->commission_percent,
            ])->values();

            return [
                'country_id'   => $first->country_id,
                'country'      => $first->country->name ?? null,
                'country_iso2' => $iso2,
                'currency'     => $limits['currency'],
                'amount_min'   => $limits['min'],
                'amount_max'   => $limits['max'],
                'providers'    => $providers,
            ];
        })->values();

        return response()->json([
            'status'       => true,
            'zone'         => $userZone,
            'user_country' => [
                'code' => $userCountryCode,
                'name' => $user?->country?->name,
            ],
            'countries' => $countries,
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
                'status' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
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
        $iso2          = self::countryCode($pixelConfig->country) ?? '';
        $limits        = self::AMOUNT_LIMITS[$iso2] ?? ['min' => 200, 'max' => 1_500_000];
        $amount        = (float) $request->amount;
        $operationType = $pixelConfig->operation_type; // 'Cash In' ou 'Cash Out'

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
        $amountPay = $amount + $fees;

        // ── Cash Out (Robus → Mobile money) : vérifier le solde avant tout ──
        if ($operationType === 'Cash In') {
            if ((float) $wallet->amount < $amountPay) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Solde insuffisant pour effectuer ce Cash Out.',
                ], 422);
            }
        }


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
                'user_id'            => $user->id,
                'pixel_service_id'   => $pixelConfig->id,
                'pixel_service_name' => $pixelConfig->name,
                'service_id'         => $pixelConfig->service_id,
                'provider'           => $pixelConfig->provider->name ?? null,
                'country'            => $pixelConfig->country->name ?? null,
                'country_iso2'       => $iso2,
                'operation_type'     => $operationType,
                'destination'        => $request->destination,
                'custom_data'        => $customData,
            ]),
        ]);

        // ── Cash In : débiter le wallet immédiatement avant l'appel Pixel Innov ──
        if ($operationType === 'Cash In') {
            DB::beginTransaction();
            try {
                $wallet->decrement('amount', $amountPay);
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $reload->update(['status' => 'ERROR']);
                Log::error('Pixel Innov — Cash In : erreur débit wallet', ['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'message' => 'Erreur lors du débit du wallet.'], 500);
            }
        }

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

            // ── Cash Out : rembourser le wallet si Pixel Innov a refusé dès l'init ──
            if ($operationType === 'Cash in') {
                $wallet->increment('amount', $amountPay);
                Log::info('Pixel Innov — Cash in init échouée, wallet remboursé', [
                    'reload_id'       => $reload->id,
                    'amount_refunded' => $amountPay,
                ]);
            }

            Log::warning('Pixel Innov — init échouée', [
                'response'       => $response,
                'reload_id'      => $reload->id,
                'transaction_id' => $pixelTxId,
                'state'          => $response['data']['state'] ?? null,
                'operation_type' => $operationType,
            ]);

            $pixelMsg     = trim((string) ($response['message'] ?? ''));
            $dataMsg      = trim((string) ($response['data']['response'] ?? ''));
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
        // Cherche le lien de paiement dans tous les champs possibles de la réponse
        $smsLink = $response['data']['sms_link']
            ?? $response['data']['sms_link1']
            ?? $response['data']['sms_link2']
            ?? $response['data']['payment_link']
            ?? $response['data']['redirect_url']
            ?? null;

        Log::info('Pixel Innov — paiement initié', [
            'transaction_id' => $transactionId,
            'provider'       => $pixelConfig->provider->name ?? null,
            'country'        => $iso2,
            'user_id'        => $user->id,
            'amount'         => $amount,
            'sms_link'       => $smsLink,
            'response_data'  => $response['data'] ?? [],
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
            'amount_received' => $amountReceived,
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
                'status' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
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
        $limits = self::AMOUNT_LIMITS[$iso2] ?? ['min' => 100, 'max' => 1_500_000, 'currency' => 'XOF'];
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

        $remoteState = strtoupper($remote['data']['state'] ?? '');
        $synced      = false;

        // Synchronisation automatique si la transaction n'est pas encore SUCCESS localement
        // mais que Pixel Innov confirme SUCCESS ou SUCCESSFUL (IPN manqué ou mal traité).
        if (!in_array($reload->status, ['SUCCESS'])) {
            if (in_array($remoteState, ['SUCCESS', 'SUCCESSFUL'])) {
                DB::beginTransaction();
                try {
                    $this->handleSuccess($reload, $remote['data'] ?? []);
                    DB::commit();
                    $synced = true;
                    Log::info('Pixel Innov — statut synchronisé manuellement (IPN manqué)', [
                        'transaction_id' => $request->transaction_id,
                        'remote_state'   => $remoteState,
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error('Pixel Innov — erreur sync manuelle', [
                        'transaction_id' => $request->transaction_id,
                        'error'          => $e->getMessage(),
                    ]);
                }
            } elseif (in_array($remoteState, ['FAILED', 'FAIL', 'ERROR', 'CANCELLED']) && $reload->status !== 'ERROR') {
                $reload->update(['status' => 'ERROR']);
                $synced = true;
            }
            $reload->refresh();
        }

        return response()->json([
            'status'         => true,
            'local_status'   => $reload->status,
            'remote_state'   => $remoteState ?: null,
            'synced'         => $synced,
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
            Log::warning('Pixel Innov — hash invalide', [
                'transaction_id' => $transactionId,
                'hash_received'  => $hash,
                'hash_expected'  => hash('sha256', config('services.pixel_innov.api_key') . $transactionId),
            ]);
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

            // Idempotence : ne pas re-traiter une transaction déjà créditée (SUCCESS).
            // On ne bloque pas sur ERROR pour pouvoir récupérer si un SUCCESSFUL arrive après un PENDING mal traité.
            if (strtoupper($reload->status) === 'SUCCESS') {
                DB::rollBack();
                return response()->json(['status' => 'already_processed'], 200);
            }

            $stateUpper = strtoupper($state);

            if (in_array($stateUpper, ['SUCCESS', 'SUCCESSFUL'])) {
                $this->handleSuccess($reload, $request->all());
            } elseif (in_array($stateUpper, ['FAILED', 'FAIL', 'ERROR', 'CANCELLED'])) {
                $this->handleFailure($reload, $request->all());
            } else {
                // État intermédiaire (PENDING, PENDING1, PROCESSING…) : on attend le prochain IPN.
                DB::rollBack();
                Log::info('Pixel Innov — IPN état intermédiaire ignoré', [
                    'transaction_id' => $transactionId,
                    'state'          => $state,
                ]);
                return response()->json(['status' => 'pending'], 200);
            }

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
        $metadata      = json_decode($reload->metadata ?? '{}', true);
        $operationType = $metadata['operation_type'] ?? 'Cash In';

        if ($operationType === 'Cash Out') {
            // Mobile débité par Pixel → créditer le wallet Robus
            $this->creditWalletFromMobilePayment($reload, $ipnData);
        } else {
            // Wallet Robus déjà débité à l'init → juste marquer SUCCESS
            $this->markCashInCompleted($reload, $ipnData);
        }
    }

    // Cash Out (Robus → Mobile) : wallet déjà débité à l'init, on enregistre les frais et on marque SUCCESS
    private function markCashInCompleted(WalletReload $reload, array $ipnData): void
    {
        $wallet = $reload->wallet;

        // Récupérer pixelConfig depuis les métadonnées (enregistrées à l'init)
        $metadata      = json_decode($reload->metadata ?? '{}', true);
        $pixelConfigId = $metadata['pixel_service_id'] ?? null;
        $pixelConfig   = $pixelConfigId ? PixelInnoveService::find($pixelConfigId) : null;

        if (!$pixelConfig) {
            throw new \Exception('Configuration Pixel Innov introuvable (pixel_service_id absent des métadonnées)');
        }

        // benefice = montant + frais Pixel côté IPN
        $pixelFee = (float) ($ipnData['fee'] ?? $ipnData['comission'] ?? 0);
        $benefice  = (float) ($ipnData['benefice'] ?? ((float) $reload->amount + $pixelFee));

        // Frais depuis la config du service Pixel Innov (identiques à ceux calculés à l'init)
        $fixedFeePct     = (float) $pixelConfig->fixed_fee;
        $commissionPct   = (float) $pixelConfig->commission_percent;
        $totalFeePct     = $fixedFeePct + $commissionPct;
        $totalCommission = round((float) $reload->amount * $totalFeePct / 100, 2);
        $totalAdminCommission = round((float) $reload->amount * $commissionPct / 100, 2);
        $totalBanqueCommission = round((float) $reload->amount * $fixedFeePct / 100, 2);
        $amountReceived  = (float) $reload->amount - $totalCommission;

        $countryCiv    = Country::find(107);
        $robusCurrency = $wallet->country->currencie->value ?? 1;
        $walletAdmin      = WalletAdmin::find(1);
        $adminSingle      = SidUser::whereIsAdmin(true)->where('country_id', 107)->first();
        $walletCommission = WalletCommission::find(1);

        if ($countryCiv && $countryCiv->currencie->value == $wallet->country->currencie->value) {
            $amountTotalConversion      = $benefice;
            $amountCommissionConversion = $totalCommission;
            $amountCommissionAdminConversion = $totalAdminCommission;
            $amountCommissionBanqueConversion = $totalBanqueCommission;
        } else {
            $amountTotalConversion      = $benefice * (float) $robusCurrency;
            $amountCommissionConversion = $totalCommission * (float) $robusCurrency;
            $amountCommissionAdminConversion = $totalAdminCommission * (float) $robusCurrency;
            $amountCommissionBanqueConversion = $totalBanqueCommission * (float) $robusCurrency;
        }

        $reload->update([
            'status'           => 'SUCCESS',
            'gateway_response' => $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
            'paid_at'          => !empty($ipnData['created_at']) ? Carbon::parse($ipnData['created_at']) : now(),
            'description'      => 'Cash In Pixel Innov — mobile money crédité',
            'type_transaction' => 'Cash In',
            'fees'             => $totalCommission,
            'costs_amount'     => $totalCommission,
            'cost_conversion'  => $amountCommissionConversion,
            'amount_conversion' => $amountTotalConversion,
            'amount_received'  => $amountReceived,
            'reload_cost_id'   => $reload->reload_cost_id,
        ]);

        PaymentReload::create([
            'wallet_reload_id'          => $reload->id,
            'sid_user_id'               => $wallet->sid_user_id,
            'type'                      => 'debit',
            'transaction_id'            => $ipnData['transaction_id'],
            'pay_amount'                => $reload->amount,
            'fee_amount'                => $totalCommission,
            'received_amount'           => $amountReceived,
            'balance_before'            => $wallet->amount,
            'balance_after'             => $wallet->amount,
            'description'               => 'Cash In Pixel Innov — ' . $ipnData['transaction_id'],
            'reference'                 => $ipnData['transaction_id'],
            'operator_transaction_name' => 'Pixel Innov',
            'status'                    => 'completed',
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        if ($walletCommission) {
            $walletAdmin->increment('amount', $amountTotalConversion);
            LedgerEntry::create([
                'transaction_id' => (string) Str::uuid(),
                'admin_com_id'   => null,
                'debit'          => 0,
                'credit'         => $amountCommissionAdminConversion,
                'description'    => 'Commission administrateur',
                'is_uv'          => true,
            ]);

            $walletCommission->decrement('amount', $amountTotalConversion);

            LedgerEntry::create([
                'transaction_id' => (string) Str::uuid(),
                'admin_com_id' => null,
                'debit' => $amountCommissionBanqueConversion,
                'credit' => 0,
                'description' => "Commission banque",
                'is_comission' => true,
            ]);
        } else {
            $adminSingle->wallet->increment('amount', $amountTotalConversion);
        }


        Log::info('Pixel Innov — Cash In SUCCESS (mobile money crédité)', [
            'transaction_id' => $ipnData['transaction_id'] ?? null,
            'wallet_id'      => $reload->wallet_id,
            'amount_sent'    => $reload->amount,
            'commission'     => $totalCommission,
            'amount_received' => $amountReceived,
        ]);
    }

    // Cash In (Mobile → Robus) : mobile débité par Pixel → créditer le wallet Robus avec commissions
    private function creditWalletFromMobilePayment(WalletReload $reload, array $ipnData): void
    {
        $wallet = $reload->wallet;

        // Récupérer pixelConfig depuis les métadonnées (enregistrées à l'init)
        $metadata      = json_decode($reload->metadata ?? '{}', true);
        $pixelConfigId = $metadata['pixel_service_id'] ?? null;
        $pixelConfig   = $pixelConfigId ? PixelInnoveService::find($pixelConfigId) : null;

        if (!$pixelConfig) {
            throw new \Exception('Configuration Pixel Innov introuvable (pixel_service_id absent des métadonnées)');
        }

        // benefice = montant + frais Pixel (ce que le client a réellement payé depuis son mobile)
        $pixelFee = (float) ($ipnData['fee'] ?? $ipnData['comission'] ?? 0);
        $benefice  = (float) ($ipnData['benefice'] ?? ((float) $reload->amount + $pixelFee));

        // Frais depuis la config du service Pixel Innov (identiques à ceux calculés à l'init)
        $fixedFeePct     = (float) $pixelConfig->fixed_fee;
        $commissionPct   = (float) $pixelConfig->commission_percent;
        $totalFeePct     = $fixedFeePct + $commissionPct;
        $totalCommission = round((float) $reload->amount * $totalFeePct / 100, 2);
        $amountReceived  = (float) $reload->amount - $totalCommission;

        $walletAdmin      = WalletAdmin::find(1);
        $walletCommission = Wallet::where('wallet_id', 'CI44RTZU')->first();
        $adminSingle      = SidUser::whereIsAdmin(true)->where('country_id', 107)->first();

        if (!$wallet) {
            throw new \Exception('Wallet client introuvable');
        }

        if (!$walletAdmin) {
            throw new \Exception('Wallet admin introuvable');
        }

        if (!$walletCommission && !$adminSingle?->wallet) {
            throw new \Exception('Wallet commission introuvable');
        }

        $countryCiv    = Country::find(107);
        $robusCurrency = $wallet->country->currencie->value ?? 1;

        // La vérification du solde utilise benefice (montant + frais Pixel)
        // car c'est le montant total engagé dans la transaction côté client
        if ($countryCiv && $countryCiv->currencie->value == $wallet->country->currencie->value) {
            $amountTotalConversion      = $benefice;
            $amountCommissionConversion = $totalCommission;
        } else {
            $amountTotalConversion      = $benefice * (float) $robusCurrency;
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
            'reload_cost_id'   => $reload->reload_cost_id,
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
            'amount_conversion' => $amountTotalConversion,
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

        Log::info('Pixel Innov — Cash In SUCCESS, wallet Robus crédité', [
            'transaction_id'  => $ipnData['transaction_id'],
            'wallet_id'       => $reload->wallet_id,
            'amount_credited' => $amountReceived,
            'commission'      => $totalCommission,
            'provider_ref'    => $ipnData['provider_id'] ?? null,
        ]);
    }

    private function handleFailure(WalletReload $reload, array $ipnData): void
    {
        $metadata      = json_decode($reload->metadata ?? '{}', true);
        $operationType = $metadata['operation_type'] ?? 'Cash Out';

        $reload->update([
            'status'           => 'ERROR',
            'gateway_response' => $ipnData['error'] ?? $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
        ]);

        // Cash Out (Robus → Mobile) : rembourser le wallet car Pixel Innov n'a pas crédité le mobile
        if ($operationType === 'Cash Out') {
            $wallet = $reload->wallet;
            if ($wallet) {
                $wallet->increment('amount', $reload->amount);
                Log::info('Pixel Innov — Cash Out FAILED, wallet remboursé', [
                    'transaction_id'  => $ipnData['transaction_id'] ?? null,
                    'wallet_id'       => $reload->wallet_id,
                    'amount_refunded' => $reload->amount,
                ]);
            }
        }
        // Cash In (Mobile → Robus) : wallet jamais touché, rien à rembourser

        Log::info('Pixel Innov — FAILED', [
            'transaction_id' => $ipnData['transaction_id'] ?? null,
            'error'          => $ipnData['error'] ?? null,
            'operation_type' => $operationType,
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE — services disponibles (tous pays, non filtré)
    // =========================================================================

    public function listMobileTransferServices(Request $request)
    {
        $services = PixelInnoveService::with(['country', 'provider'])
            ->where('status', true)
            ->when($request->operation_type, fn($q) => $q->where('operation_type', $request->operation_type))
            ->orderBy('name')
            ->get();

        $grouped = $services->groupBy(fn($s) => $s->country_id);

        $countries = $grouped->map(function ($items) {
            $first = $items->first();
            $iso2  = self::countryCode($first->country);
            $limits = self::AMOUNT_LIMITS[$iso2 ?? ''] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];

            $providers = $items->map(fn($s) => [
                'id'             => $s->id,
                'service_id'     => $s->service_id,
                'operation_type' => $s->operation_type,
                'provider'       => $s->provider->name ?? null,
                'image'          => $s->provider->image ?? null,
                'fixed_fee_pct'  => (float) $s->fixed_fee,
                'commission_pct' => (float) $s->commission_percent,
                'total_fee_pct'  => (float) $s->fixed_fee + (float) $s->commission_percent,
            ])->values();

            return [
                'country_id'   => $first->country_id,
                'country'      => $first->country->name ?? null,
                'country_iso2' => $iso2,
                'currency'     => $limits['currency'],
                'amount_min'   => $limits['min'],
                'amount_max'   => $limits['max'],
                'providers'    => $providers,
            ];
        })->values();

        return response()->json([
            'status'    => true,
            'countries' => $countries,
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE — calcul des frais (deux legs)
    // =========================================================================

    public function calculateMobileZoneTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_service_id'    => 'required|integer|exists:pixel_innove_services,service_id',
            'recipient_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'               => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Données invalides',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $senderService = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->sender_service_id)
            ->where('status', true)
            ->where('operation_type', 'Cash Out')
            ->first();

        $recipientService = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->recipient_service_id)
            ->where('status', true)
            ->where('operation_type', 'Cash In')
            ->first();

        if (!$senderService) {
            return response()->json(['status' => false, 'message' => 'Service expéditeur introuvable ou inactif (Cash Out requis)'], 404);
        }
        if (!$recipientService) {
            return response()->json(['status' => false, 'message' => 'Service destinataire introuvable ou inactif (Cash In requis)'], 404);
        }

        $amount = (float) $request->amount;

        $senderFeePct  = (float) $senderService->fixed_fee + (float) $senderService->commission_percent;
        $senderFee     = round($amount * $senderFeePct / 100, 2);
        $amountToSend  = round($amount - $senderFee, 2); // ce que Robus envoie au destinataire

        $recipientFeePct = (float) $recipientService->fixed_fee + (float) $recipientService->commission_percent;
        $recipientFee    = round($amountToSend * $recipientFeePct / 100, 2);
        $amountReceived  = round($amountToSend - $recipientFee, 2);

        $totalFee = round($senderFee + $recipientFee, 2);

        return response()->json([
            'status'           => true,
            'amount'           => $amount,
            'sender_fee_pct'   => $senderFeePct,
            'sender_fee'       => $senderFee,
            'recipient_fee_pct'=> $recipientFeePct,
            'recipient_fee'    => $recipientFee,
            'total_fee_pct'    => round($totalFee / $amount * 100, 2),
            'fees'             => $totalFee,
            'amount_to_send'   => $amountToSend,
            'amount_received'  => $amountReceived,
            'currency'         => self::AMOUNT_LIMITS[self::countryCode($senderService->country) ?? '']['currency'] ?? 'XOF',
            'sender_provider'    => $senderService->provider->name ?? null,
            'recipient_provider' => $recipientService->provider->name ?? null,
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE — initialisation (deux legs : Cash Out + Cash In)
    // =========================================================================

    public function initializeMobileZoneTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sender_service_id'    => 'required|integer|exists:pixel_innove_services,service_id',
            'sender_phone'         => 'required|string|min:8|max:20',
            'recipient_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'recipient_phone'      => 'required|string|min:8|max:20',
            'amount'               => 'required|numeric|min:1',
            'om_otp'               => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Données invalides',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifié'], 401);
        }

        $senderService = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->sender_service_id)
            ->where('status', true)
            ->where('operation_type', 'Cash Out')
            ->first();

        $recipientService = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->recipient_service_id)
            ->where('status', true)
            ->where('operation_type', 'Cash In')
            ->first();

        if (!$senderService) {
            return response()->json(['status' => false, 'message' => 'Service expéditeur introuvable (Cash Out requis)'], 404);
        }
        if (!$recipientService) {
            return response()->json(['status' => false, 'message' => 'Service destinataire introuvable (Cash In requis)'], 404);
        }

        $senderIso2    = self::countryCode($senderService->country) ?? '';
        $recipientIso2 = self::countryCode($recipientService->country) ?? '';
        $senderLimits  = self::AMOUNT_LIMITS[$senderIso2] ?? ['min' => 200, 'max' => 1_500_000];

        $amount = (float) $request->amount;

        if ($amount < $senderLimits['min'] || $amount > $senderLimits['max']) {
            return response()->json([
                'status'  => false,
                'message' => "Montant invalide pour {$senderService->country->name}. Min : {$senderLimits['min']}, Max : {$senderLimits['max']}.",
            ], 422);
        }

        // Calcul des frais
        $senderFeePct  = (float) $senderService->fixed_fee + (float) $senderService->commission_percent;
        $senderFee     = round($amount * $senderFeePct / 100, 2);
        $amountToSend  = round($amount - $senderFee, 2);

        $recipientFeePct = (float) $recipientService->fixed_fee + (float) $recipientService->commission_percent;
        $recipientFee    = round($amountToSend * $recipientFeePct / 100, 2);
        $amountReceived  = round($amountToSend - $recipientFee, 2);
        $totalFee        = round($senderFee + $recipientFee, 2);

        $currency  = self::AMOUNT_LIMITS[$senderIso2]['currency'] ?? 'XOF';
        $customRef = 'ROBUS_MZ_' . $user->id . '_' . now()->timestamp;

        // Enregistrement du transfert AVANT l'appel API
        $transfer = PixelMobileZoneTransfer::create([
            'sender_pixel_service_id'    => $senderService->id,
            'sender_phone'               => $request->sender_phone,
            'sender_country_iso2'        => $senderIso2,
            'recipient_pixel_service_id' => $recipientService->id,
            'recipient_phone'            => $request->recipient_phone,
            'recipient_country_iso2'     => $recipientIso2,
            'amount'                     => $amount,
            'sender_fee'                 => $senderFee,
            'recipient_fee'              => $recipientFee,
            'total_fee'                  => $totalFee,
            'amount_to_send'             => $amountToSend,
            'amount_received'            => $amountReceived,
            'currency'                   => $currency,
            'sender_status'              => 'PENDING',
            'recipient_status'           => 'PENDING',
            'status'                     => 'PENDING',
            'wallet_id'                  => optional(Wallet::where('sid_user_id', $user->id)->first())->id,
            'metadata'                   => [
                'user_id'          => $user->id,
                'custom_ref'       => $customRef,
                'sender_service'   => $senderService->name,
                'recipient_service'=> $recipientService->name,
            ],
        ]);

        // Appel Pixel Innov — leg 1 : Cash Out depuis le mobile de l'expéditeur
        $pixelService = app(PixelInnovService::class);
        $publicBase   = rtrim(config('services.pixel_innov.public_url'), '/');

        $cashOutPayload = [
            'amount'             => (int) $amount,
            'destination'        => $request->sender_phone,
            'service_id'         => $senderService->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
            'custom_data'        => $customRef . '_CASHOUT',
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];
        if ($request->filled('om_otp')) {
            $cashOutPayload['om_otp'] = $request->om_otp;
        }

        $cashOutResponse = $pixelService->initializeTransaction($cashOutPayload);

        $cashOutTxId = $cashOutResponse['data']['transaction_id'] ?? null;
        $cashOutFailed = ($cashOutResponse['statut_code'] ?? null) !== 200
            || empty($cashOutTxId)
            || ($cashOutResponse['data']['state'] ?? null) === 'FAILED';

        if ($cashOutFailed) {
            $transfer->update([
                'sender_status'       => 'FAILED',
                'status'              => 'FAILED',
                'raw_response_sender' => $cashOutResponse,
            ]);

            $pixelMsg  = trim((string) ($cashOutResponse['message'] ?? ''));
            $dataMsg   = trim((string) ($cashOutResponse['data']['response'] ?? ''));
            $reason    = $pixelMsg ?: ($dataMsg ?: 'Échec du débit chez l\'expéditeur. Veuillez réessayer.');

            Log::warning('Mobile Zone Transfer — Cash Out échoué', [
                'transfer_id' => $transfer->id,
                'response'    => $cashOutResponse,
            ]);

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        // Cash Out initié avec succès — on enregistre le transaction_id
        $transfer->update([
            'sender_transaction_id' => $cashOutTxId,
            'sender_status'         => 'PENDING',
            'status'                => 'PENDING',
            'raw_response_sender'   => $cashOutResponse,
        ]);

        $smsLink = $cashOutResponse['data']['sms_link']
            ?? $cashOutResponse['data']['sms_link1']
            ?? $cashOutResponse['data']['sms_link2']
            ?? null;

        Log::info('Mobile Zone Transfer — Cash Out initié', [
            'transfer_id'    => $transfer->id,
            'transaction_id' => $cashOutTxId,
            'sender_phone'   => $request->sender_phone,
            'amount'         => $amount,
        ]);

        return response()->json([
            'status'          => true,
            'message'         => $this->initMessage($senderService->provider->name ?? '', $smsLink)
                . ' Une fois confirmé, le montant sera envoyé automatiquement au destinataire.',
            'transaction_id'  => (string) $transfer->id,
            'sms_link'        => $smsLink,
            'state'           => $cashOutResponse['data']['state'] ?? 'PENDING',
            'fees'            => $totalFee,
            'amount_received' => $amountReceived,
            'sender'          => [
                'phone'    => $request->sender_phone,
                'operator' => $senderService->provider->name ?? null,
                'country'  => $senderService->country->name ?? null,
            ],
            'recipient'       => [
                'phone'    => $request->recipient_phone,
                'operator' => $recipientService->provider->name ?? null,
                'country'  => $recipientService->country->name ?? null,
            ],
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE — IPN callback (Pixel Innov → Robus)
    // =========================================================================

    public function mobileTransferCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $state         = $request->input('state');
        $hash          = $request->input('hash');
        $customData    = $request->input('custom_data', '');

        Log::info('Mobile Zone Transfer — IPN reçu', $request->all());

        if (!$transactionId || !$state || !$hash) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        $pixelService = app(PixelInnovService::class);
        if (!$pixelService->verifyIpnHash($transactionId, $hash)) {
            return response()->json(['status' => 'invalid_hash'], 401);
        }

        $stateUpper = strtoupper($state);
        $isCashOut  = str_ends_with($customData, '_CASHOUT');
        $isCashIn   = str_ends_with($customData, '_CASHIN');

        // Trouver le transfert par le transaction_id
        $transfer = null;
        if ($isCashOut) {
            $transfer = PixelMobileZoneTransfer::where('sender_transaction_id', $transactionId)->first();
        } elseif ($isCashIn) {
            $transfer = PixelMobileZoneTransfer::where('recipient_transaction_id', $transactionId)->first();
        }

        // Fallback : chercher dans les deux colonnes
        if (!$transfer) {
            $transfer = PixelMobileZoneTransfer::where('sender_transaction_id', $transactionId)
                ->orWhere('recipient_transaction_id', $transactionId)
                ->first();
            if ($transfer) {
                $isCashOut = $transfer->sender_transaction_id === $transactionId;
                $isCashIn  = $transfer->recipient_transaction_id === $transactionId;
            }
        }

        if (!$transfer) {
            Log::error('Mobile Zone Transfer — transfert introuvable pour IPN', ['transaction_id' => $transactionId]);
            return response()->json(['status' => 'not_found'], 404);
        }

        DB::beginTransaction();
        try {
            if ($isCashOut) {
                $this->handleMobileTransferCashOutIpn($transfer, $transactionId, $stateUpper, $request->all(), $pixelService);
            } elseif ($isCashIn) {
                $this->handleMobileTransferCashInIpn($transfer, $transactionId, $stateUpper, $request->all());
            }

            DB::commit();
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Mobile Zone Transfer — erreur IPN', [
                'transfer_id'    => $transfer->id,
                'transaction_id' => $transactionId,
                'error'          => $e->getMessage(),
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function handleMobileTransferCashOutIpn(
        PixelMobileZoneTransfer $transfer,
        string $transactionId,
        string $state,
        array $ipnData,
        PixelInnovService $pixelService
    ): void {
        if ($transfer->sender_status === 'SUCCESS') {
            return; // idempotence
        }

        if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
            $transfer->update([
                'sender_status'       => 'SUCCESS',
                'status'              => 'PROCESSING',
                'raw_response_sender' => $ipnData,
            ]);

            // Déclencher immédiatement le leg Cash In vers le destinataire
            $recipientService = PixelInnoveService::with(['country', 'provider'])
                ->find($transfer->recipient_pixel_service_id);

            if (!$recipientService) {
                Log::error('Mobile Zone Transfer — service destinataire introuvable', ['transfer_id' => $transfer->id]);
                $transfer->update(['status' => 'FAILED', 'recipient_status' => 'FAILED']);
                return;
            }

            $publicBase   = rtrim(config('services.pixel_innov.public_url'), '/');
            $customRef    = $transfer->metadata['custom_ref'] ?? ('ROBUS_MZ_' . $transfer->id);

            $cashInPayload = [
                'amount'             => (int) $transfer->amount_to_send,
                'destination'        => $transfer->recipient_phone,
                'service_id'         => $recipientService->service_id,
                'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
                'custom_data'        => $customRef . '_CASHIN',
                'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
                'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
            ];

            $cashInResponse = $pixelService->initializeTransaction($cashInPayload);
            $cashInTxId     = $cashInResponse['data']['transaction_id'] ?? null;
            $cashInFailed   = ($cashInResponse['statut_code'] ?? null) !== 200
                || empty($cashInTxId)
                || ($cashInResponse['data']['state'] ?? null) === 'FAILED';

            if ($cashInFailed) {
                $transfer->update([
                    'recipient_status'         => 'FAILED',
                    'status'                   => 'FAILED',
                    'raw_response_recipient'   => $cashInResponse,
                ]);
                Log::error('Mobile Zone Transfer — Cash In échoué après Cash Out OK', [
                    'transfer_id' => $transfer->id,
                    'response'    => $cashInResponse,
                ]);
                return;
            }

            $transfer->update([
                'recipient_transaction_id' => $cashInTxId,
                'recipient_status'         => 'PENDING',
                'raw_response_recipient'   => $cashInResponse,
            ]);

            Log::info('Mobile Zone Transfer — Cash In initié après Cash Out OK', [
                'transfer_id'        => $transfer->id,
                'cash_in_tx_id'      => $cashInTxId,
                'recipient_phone'    => $transfer->recipient_phone,
                'amount_to_send'     => $transfer->amount_to_send,
            ]);
        } elseif (in_array($state, ['FAILED', 'FAIL', 'ERROR', 'CANCELLED'])) {
            $transfer->update([
                'sender_status'       => 'FAILED',
                'status'              => 'FAILED',
                'raw_response_sender' => $ipnData,
            ]);
            Log::warning('Mobile Zone Transfer — Cash Out FAILED', [
                'transfer_id'    => $transfer->id,
                'transaction_id' => $transactionId,
            ]);
        }
    }

    private function handleMobileTransferCashInIpn(
        PixelMobileZoneTransfer $transfer,
        string $transactionId,
        string $state,
        array $ipnData
    ): void {
        if ($transfer->recipient_status === 'SUCCESS') {
            return; // idempotence
        }

        if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
            $transfer->update([
                'recipient_status'       => 'SUCCESS',
                'status'                 => 'SUCCESS',
                'raw_response_recipient' => $ipnData,
            ]);

            // Créditer la commission sur le wallet admin
            $this->creditMobileTransferCommission($transfer);

            Log::info('Mobile Zone Transfer — SUCCESS complet', [
                'transfer_id'     => $transfer->id,
                'sender_phone'    => $transfer->sender_phone,
                'recipient_phone' => $transfer->recipient_phone,
                'amount'          => $transfer->amount,
                'amount_received' => $transfer->amount_received,
            ]);
        } elseif (in_array($state, ['FAILED', 'FAIL', 'ERROR', 'CANCELLED'])) {
            $transfer->update([
                'recipient_status'       => 'FAILED',
                'status'                 => 'FAILED',
                'raw_response_recipient' => $ipnData,
            ]);
            Log::warning('Mobile Zone Transfer — Cash In FAILED', [
                'transfer_id'    => $transfer->id,
                'transaction_id' => $transactionId,
            ]);
        }
    }

    private function creditMobileTransferCommission(PixelMobileZoneTransfer $transfer): void
    {
        try {
            $walletAdmin = WalletAdmin::find(1);
            if ($walletAdmin && $transfer->total_fee > 0) {
                $walletAdmin->increment('amount', $transfer->total_fee);
                LedgerEntry::create([
                    'transaction_id' => (string) Str::uuid(),
                    'admin_com_id'   => null,
                    'debit'          => 0,
                    'credit'         => $transfer->total_fee,
                    'description'    => 'Commission Transfert Mobile Zone #' . $transfer->id,
                    'is_uv'          => true,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Mobile Zone Transfer — erreur commission', [
                'transfer_id' => $transfer->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE — vérification du statut
    // =========================================================================

    public function checkMobileZoneTransferStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|integer|exists:pixel_mobile_zone_transfers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $transfer = PixelMobileZoneTransfer::find($request->transfer_id);

        return response()->json([
            'status'              => true,
            'local_status'        => $transfer->status,
            'sender_status'       => $transfer->sender_status,
            'recipient_status'    => $transfer->recipient_status,
            'transfer_id'         => $transfer->id,
            'amount'              => $transfer->amount,
            'fees'                => $transfer->total_fee,
            'amount_received'     => $transfer->amount_received,
            'created_at'          => $transfer->created_at,
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
            return $smsLink
                ? 'Appuyez sur le lien ci-dessous pour ouvrir l\'application Wave et confirmer votre paiement.'
                : 'Ouvrez l\'application Wave et confirmez le paiement en attente.';
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
