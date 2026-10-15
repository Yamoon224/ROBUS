<?php

namespace App\Http\Controllers;

use App\Http\Services\PercentRobusAuthService;
use App\Http\Services\PixelInnovService;
use App\Http\Services\OperatorRestrictionService;
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
 *     description="Paiements mobile money multi-pays via l'API Pixel Innov (Orange, MTN, Moov, Waveâ€¦)"
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
        'CD' => ['min' => 500,   'max' => 1_500_000, 'currency' => 'CDF'],
        'GM' => ['min' => 100,   'max' => 1_500_000, 'currency' => 'GMD'],
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
     *     description="Retourne les services Pixel Innov actifs filtrÃ©s par le pays du client authentifiÃ©. Le filtre pays est automatique : si `country_id` n'est pas prÃ©cisÃ©, le pays du compte connectÃ© est utilisÃ©. Vous pouvez surcharger avec `country_id` ou `country_iso2`.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="country_id",
     *         in="query",
     *         required=false,
     *         description="ID interne du pays (surcharge la dÃ©tection automatique du pays du client)",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="country_iso2",
     *         in="query",
     *         required=false,
     *         description="Code ISO2 du pays (ex: SN, CI, ML, BF, BJ, TG, CM, GN) â€” utilisÃ© si `country_id` n'est pas fourni",
     *         @OA\Schema(type="string", example="SN", enum={"SN","CI","ML","BF","BJ","TG","CM","GN"})
     *     ),
     *     @OA\Parameter(
     *         name="operation_type",
     *         in="query",
     *         required=false,
     *         description="Type d'opÃ©ration Ã  filtrer",
     *         @OA\Schema(type="string", enum={"Cash In","Cash Out"}, example="Cash In")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des services disponibles pour le pays du client",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="country_filter", type="string", example="SN",
     *                 description="Code ISO2 du pays utilisÃ© pour le filtrage (auto-dÃ©tectÃ© ou manuel)"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Orange Money SÃ©nÃ©gal"),
     *                     @OA\Property(property="service_id", type="integer", example=12),
     *                     @OA\Property(property="operation_type", type="string", example="Cash In"),
     *                     @OA\Property(property="provider", type="string", example="Orange"),
     *                     @OA\Property(property="country", type="string", example="SÃ©nÃ©gal"),
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
     *     @OA\Response(response=401, description="Non authentifiÃ©")
     * )
     */
    public function listServices(Request $request)
    {
        $user = robus();

        // RÃ©soudre le country_id effectif :
        // 1. PrioritÃ© au paramÃ¨tre explicite country_id
        // 2. Sinon rÃ©soudre via country_iso2 passÃ© en paramÃ¨tre
        // 3. Sinon utiliser le pays du compte authentifiÃ© (auto-dÃ©tection)
        $countryId = $request->country_id;

        if (!$countryId && $request->country_iso2) {
            $countryId = Country::where('code', strtoupper($request->country_iso2))->value('id');
        }

        // show_all=true : dÃ©sactive le filtrage automatique par pays (pour l'envoi international)
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
                'country_indicatif' => optional($s->country)->indecative,
                'currency'         => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['currency'] ?? 'XOF',
                'amount_min'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['min'] ?? 100,
                'amount_max'       => self::AMOUNT_LIMITS[self::countryCode($s->country) ?? '']['max'] ?? 1_500_000,
                'fixed_fee_pct'    => (float) $s->fixed_fee,
                'commission_pct'   => (float) $s->commission_percent,
                'is_xof'   =>  $s->is_xof,
                'is_xaf'   =>  $s->is_xaf,
                'is_cdf'   =>  $s->is_cdf,
                'is_gmd'   =>  $s->is_gmd,
                'total_fee_pct'    => (float) $s->fixed_fee + (float) $s->commission_percent,
            ]);

        // DÃ©terminer le code ISO2 effectivement utilisÃ© pour le filtrage
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

    /**
     * Services de dÃ©pÃ´t mobile disponibles dans le pays du client.
     * Cette liste est volontairement limitÃ©e aux opÃ©rateurs pris en charge
     * par l'Ã©cran Â« DÃ©pÃ´t zone Â» de l'application customers.
     */
    public function listDepositZoneServices(Request $request)
    {
        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifiÃ©'], 401);
        }

        $countryId = $user->country_id;
        $services = PixelInnoveService::with(['country', 'provider'])
            ->where('country_id', $countryId)
            ->where('operation_type', 'Cash In')
            ->where('status', true)
            ->whereHas('provider', function ($query) {
                $query->where('status', true)
                    ->where(function ($operators) {
                        $operators->whereRaw('LOWER(name) LIKE ?', ['orange%'])
                            ->orWhereRaw('LOWER(name) LIKE ?', ['mtn%'])
                            ->orWhereRaw('LOWER(name) LIKE ?', ['moov%'])
                            ->orWhereRaw('LOWER(name) LIKE ?', ['wave%']);
                    });
            })
            ->orderBy('name')
            ->get()
            ->map(fn($service) => [
                'id'                => $service->id,
                'name'              => $service->name,
                'service_id'        => $service->service_id,
                'operation_type'    => $service->operation_type,
                'provider'          => $service->provider->name,
                'image'             => $service->provider->image,
                'country'           => $service->country->name ?? null,
                'country_iso2'      => self::countryCode($service->country),
                'country_indicatif' => optional($service->country)->indecative,
                'currency'          => self::AMOUNT_LIMITS[self::countryCode($service->country) ?? '']['currency'] ?? 'XOF',
                'amount_min'        => self::AMOUNT_LIMITS[self::countryCode($service->country) ?? '']['min'] ?? 100,
                'amount_max'        => self::AMOUNT_LIMITS[self::countryCode($service->country) ?? '']['max'] ?? 1_500_000,
                'fixed_fee_pct'     => (float) $service->fixed_fee,
                'commission_pct'    => (float) $service->commission_percent,
                'total_fee_pct'     => (float) $service->fixed_fee + (float) $service->commission_percent,
            ])->values();

        return response()->json([
            'status' => true,
            'country_filter' => self::countryCode($user->country),
            'data' => $services,
        ]);
    }

    // =========================================================================
    // SERVICES TRANSFERT MOBILE ZONE (XOF / XAF) â€” groupÃ©s par pays
    // =========================================================================

    public function listZoneServices(Request $request)
    {
        $user = robus();

        // DÃ©terminer la zone de l'utilisateur depuis son code pays
        $userCountryCode = self::countryCode($user?->country);
        $userZone = self::AMOUNT_LIMITS[$userCountryCode ?? '']['currency'] ?? null;

        $zoneColumn = $this->currencyZoneColumn($userZone);

        // Zone inconnue â†’ pas de transfert zone disponible
        if (!$zoneColumn) {
            return response()->json([
                'status'       => true,
                'zone'         => null,
                'user_country' => ['code' => $userCountryCode, 'name' => $user?->country?->name],
                'countries'    => [],
            ]);
        }

        // RÃ©cupÃ©rer tous les services actifs (toutes zones), en excluant le pays de l'utilisateur
        // Les transferts cross-zone (XAFâ†’XOF, XOFâ†’XAFâ€¦) sont autorisÃ©s :
        // l'API key correcte est sÃ©lectionnÃ©e dynamiquement via setXaf() lors de l'initiation.
        $userCountryId = $user?->country_id;
        $services = PixelInnoveService::with(['country', 'provider'])
            ->where('status', true)
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

            $providers = $items->map(function ($s) {
                $providerName = $s->provider->name ?? $s->name;

                return [
                    'id'                       => $s->id,
                    'name'                     => $providerName,
                    'provider'                 => $providerName,
                    'operator'                 => $providerName,
                    'service_id'               => $s->service_id,
                    'pixel_service_id'         => $s->id,
                    'pixel_gateway_service_id' => $s->service_id,
                    'operation_type'           => $s->operation_type,
                    'pixel_operation_type'     => $s->operation_type,
                    'country_iso2'             => self::countryCode($s->country),
                    'country_indicatif'        => optional($s->country)->indecative,
                    'image'                    => $s->provider->image ?? null,
                    'fixed_fee_pct'            => (float) $s->fixed_fee,
                    'commission_pct'           => (float) $s->commission_percent,
                    'total_fee_pct'            => (float) $s->fixed_fee + (float) $s->commission_percent,
                    'is_xof'                   => (bool) $s->is_xof,
                    'is_xaf'                   => (bool) $s->is_xaf,
                    'is_cdf'                   => (bool) $s->is_cdf,
                    'is_gmd'                   => (bool) $s->is_gmd,
                ];
            })->values();

            return [
                'country_id'   => $first->country_id,
                'country'      => $first->country->name ?? null,
                'country_iso2' => $iso2,
                'country_indicatif' => optional($first->country)->indecative,
                'currency'     => $limits['currency'],
                'amount_min'   => $limits['min'],
                'amount_max'   => $limits['max'],
                'providers'    => $providers,
                'operators'    => $providers,
                'operateurs'   => $providers,
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
     *     description="DÃ©marre une transaction de dÃ©pÃ´t (Cash In) ou de retrait (Cash Out) via Pixel Innov. Le `pixel_service_id` doit correspondre Ã  un service actif disponible dans le pays du client (voir `GET /pixelInnov/services`).",
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
     *                 description="NumÃ©ro de tÃ©lÃ©phone du client au format international (sans +)"),
     *             @OA\Property(property="custom_data", type="string", example="REF-2024-001",
     *                 description="RÃ©fÃ©rence libre optionnelle (facture, commandeâ€¦)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paiement initiÃ© â€” le client doit valider sur son tÃ©lÃ©phone",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Un lien de validation a Ã©tÃ© envoyÃ© par SMS au client."),
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123"),
     *             @OA\Property(property="sms_link", type="string", nullable=true, example="https://pay.orange.sn/â€¦"),
     *             @OA\Property(property="state", type="string", example="PENDING1"),
     *             @OA\Property(property="provider", type="string", example="Orange"),
     *             @OA\Property(property="country", type="string", example="SÃ©nÃ©gal"),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="fees", type="number", example=75),
     *             @OA\Property(property="amount_received", type="number", example=4925)
     *         )
     *     ),
     *     @OA\Response(response=400, description="Ã‰chec de l'initialisation cÃ´tÃ© Pixel Innov"),
     *     @OA\Response(response=401, description="Non authentifiÃ©"),
     *     @OA\Response(response=422, description="DonnÃ©es invalides ou montant hors limites")
     * )
     */
    public function initializePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pixel_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'           => 'required|numeric|min:1',
            'destination'      => 'required|string|min:8|max:15',
            'debit_number'     => 'nullable|string|min:8|max:15',
            'om_otp'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'DonnÃ©es invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifiÃ©'], 401);
        }

        $wallet = Wallet::where('sid_user_id', $user->id)->first();
        if (!$wallet) {
            return response()->json(['status' => false, 'message' => 'Portefeuille introuvable'], 404);
        }

        // RÃ©cupÃ©rer la config du service (provider + pays + frais)
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
        $totalFeeAdmin    = (float) $pixelConfig->fixed_fee + (float) $pixelConfig->commission_percent;
        $fees           = round($amount * $totalFeePct / 100, 2);
        $amountReceived = round($amount - $fees, 2);
        $amountPay = $amount + $fees;
        // Pour un dÃ©pÃ´t vers un mobile, Pixel Innov doit crÃ©diter le net aprÃ¨s frais.
        // Le montant brut reste enregistrÃ© pour l'historique et le calcul du dÃ©bit.
        $amountToCredit = $operationType === 'Cash In' ? $amountReceived : $amount;

        // VÃ©rifier que le montant reÃ§u aprÃ¨s frais est >= au minimum requis
        if ($operationType === 'Cash In' && $amountReceived < $limits['min']) {
            $currency    = $limits['currency'] ?? 'XOF';
            $minRequired = (int) ceil($limits['min'] / (1 - $totalFeePct / 100));
            return response()->json([
                'status'  => false,
                'message' => "AprÃ¨s dÃ©duction des frais ({$totalFeePct}%), le destinataire recevrait " . number_format($amountReceived, 0) . " {$currency}, en dessous du minimum autorisÃ© de {$limits['min']} {$currency}. Veuillez saisir au moins {$minRequired} {$currency}.",
            ], 422);
        }

        // â”€â”€ Cash Out (Robus â†’ Mobile money) : vÃ©rifier le solde avant tout â”€â”€
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
                'debit_number'       => $request->debit_number ?? null,
                'custom_data'        => $customData,
            ]),
        ]);

        // â”€â”€ Cash In : dÃ©biter le wallet immÃ©diatement avant l'appel Pixel Innov â”€â”€
        if ($operationType === 'Cash In') {
            DB::beginTransaction();
            try {
                $wallet->decrement('amount', $amountPay);
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $reload->update(['status' => 'ERROR']);
                Log::error('Pixel Innov â€” Cash In : erreur dÃ©bit wallet', ['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'message' => 'Erreur lors du dÃ©bit du wallet.'], 500);
            }
        }

        // Appel Pixel Innov â€” clÃ© sÃ©lectionnÃ©e selon is_xaf du service
        $pixelService = app(PixelInnovService::class)->setXaf($pixelConfig->is_xaf ?? false);

        $publicBase = rtrim(config('services.pixel_innov.public_url'), '/');
        $pixelPayload = [
            'amount'             => $amountToCredit,
            'destination'        => $request->destination,
            'service_id'         => $pixelConfig->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/callback',
            'custom_data'        => $customData,
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];


        if ($request->filled('debit_number')) {
            $pixelPayload['debit_number'] = $request->debit_number;
        }
        if ($request->filled('om_otp')) {
            $pixelPayload['om_otp'] = $request->om_otp;
        }
        $pixelPayload = $this->withWaveBusinessNameId($pixelPayload, $pixelConfig);

        $response = $pixelService->initializeTransaction($pixelPayload);

        // Conserver le transaction_id mÃªme en cas d'Ã©chec (pour rÃ©conciliation)
        $pixelTxId = $response['data']['transaction_id'] ?? null;
        if ($pixelTxId) {
            $reload->update(['reference' => $pixelTxId]);
        }

        $isFailed = ($response['statut_code'] ?? null) !== 200
            || empty($pixelTxId)
            || ($response['data']['state'] ?? null) === 'FAILED';

        if ($isFailed) {
            $reload->update(['status' => 'ERROR']);

            // â”€â”€ Cash In : rembourser le wallet si Pixel Innov a refusÃ© dÃ¨s l'init â”€â”€
            if ($operationType === 'Cash In') {
                $wallet->increment('amount', $amountPay);
                Log::info('Pixel Innov â€” Cash In init Ã©chouÃ©e, wallet remboursÃ©', [
                    'reload_id'       => $reload->id,
                    'amount_refunded' => $amountPay,
                ]);
            }

            Log::warning('Pixel Innov â€” init Ã©chouÃ©e', [
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
                $reason = 'Transaction rejetÃ©e par l\'opÃ©rateur mobile.';
            } else {
                $reason = 'Ã‰chec de l\'initialisation. Veuillez rÃ©essayer.';
            }

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        $transactionId = $pixelTxId;
        // Cherche le lien de paiement dans tous les champs possibles de la rÃ©ponse
        $smsLink = $this->extractPixelPaymentLink($response);

        Log::info('Pixel Innov â€” paiement initiÃ©', [
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

    /**
     * Paiement direct via Wallet Robus : dÃ©bite le wallet du client et initialise
     * la transaction Pixel Innov (Cash In vers le destinataire) en son nom.
     * Pas d'action requise par le client sur son tÃ©lÃ©phone mobile.
     */
    public function initializeWalletPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pixel_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'           => 'required|numeric|min:1',
            'destination'      => 'required|string|min:8|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'DonnÃ©es invalides',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifiÃ©'], 401);
        }

        $wallet = Wallet::where('sid_user_id', $user->id)->first();
        if (!$wallet) {
            return response()->json(['status' => false, 'message' => 'Portefeuille introuvable'], 404);
        }

        $pixelConfig = PixelInnoveService::with(['country', 'provider'])
            ->where('service_id', $request->pixel_service_id)
            ->where('operation_type', 'Cash In')
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
                'message' => "Montant invalide pour {$pixelConfig->country->name}. Min : {$limits['min']}, Max : {$limits['max']}.",
            ], 422);
        }

        // Frais directs (commission_percent) â€” structure tarifaire directe Robus
        $commissionPct  = (float) $pixelConfig->commission_percent;
        $fixedFeePct    = (float) $pixelConfig->fixed_fee;
        $totalFeePct    = $commissionPct + $fixedFeePct;
        $fees           = round($amount * $totalFeePct / 100, 2);
        $totalDebit     = $amount + $fees;
        $amountReceived = round($amount - $fees, 2);
        // Ce parcours est exclusivement un Cash In : le mobile reÃ§oit le net.
        $amountToCredit = $amountReceived;

        if ((float) $wallet->amount < $totalDebit) {
            return response()->json([
                'status'  => false,
                'message' => "Solde Robus insuffisant. Disponible : " . number_format($wallet->amount, 0) . " {$limits['currency']}, requis : " . number_format($totalDebit, 0) . " {$limits['currency']}.",
            ], 422);
        }

        $customData = 'ROBUS_WALLET_' . $user->id . '_' . now()->timestamp;

        // Enregistrement optimiste AVANT appel API
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
            'payment_method'  => 'robus_wallet',
            'type_transaction' => 'Cash In',
            'metadata'        => json_encode([
                'user_id'            => $user->id,
                'pixel_service_id'   => $pixelConfig->id,
                'pixel_service_name' => $pixelConfig->name,
                'service_id'         => $pixelConfig->service_id,
                'provider'           => $pixelConfig->provider->name ?? null,
                'country'            => $pixelConfig->country->name ?? null,
                'country_iso2'       => $iso2,
                'operation_type'     => 'Cash In',
                'destination'        => $request->destination,
                'custom_data'        => $customData,
                'source'             => 'robus_wallet',
            ]),
        ]);

        // DÃ©bit immÃ©diat du wallet
        DB::beginTransaction();
        try {
            $wallet->decrement('amount', $totalDebit);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $reload->update(['status' => 'ERROR']);
            Log::error('Pixel Innov Wallet â€” erreur dÃ©bit', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Erreur lors du dÃ©bit du wallet.'], 500);
        }

        // Appel Pixel Innov
        $pixelService = app(PixelInnovService::class)->setXaf($pixelConfig->is_xaf ?? false);
        $publicBase   = rtrim(config('services.pixel_innov.public_url'), '/');

        $pixelPayload = [
            'amount'             => $amountToCredit,
            'destination'        => $request->destination,
            'service_id'         => $pixelConfig->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/callback',
            'custom_data'        => $customData,
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];
        $pixelPayload = $this->withWaveBusinessNameId($pixelPayload, $pixelConfig);

        $response    = $pixelService->initializeTransaction($pixelPayload);
        $pixelTxId   = $response['data']['transaction_id'] ?? null;

        if ($pixelTxId) {
            $reload->update(['reference' => $pixelTxId]);
        }

        $isFailed = ($response['statut_code'] ?? null) !== 200
            || empty($pixelTxId)
            || ($response['data']['state'] ?? null) === 'FAILED';

        if ($isFailed) {
            $reload->update(['status' => 'ERROR']);
            // Rembourser le wallet immÃ©diatement
            $wallet->increment('amount', $totalDebit);
            Log::warning('Pixel Innov Wallet â€” init Ã©chouÃ©e, wallet remboursÃ©', [
                'reload_id'      => $reload->id,
                'amount_refunded' => $totalDebit,
                'response'       => $response,
            ]);

            $reason = trim((string) ($response['message'] ?? ''))
                ?: trim((string) ($response['data']['response'] ?? ''))
                ?: 'Ã‰chec de l\'initialisation. Wallet remboursÃ©.';

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        Log::info('Pixel Innov Wallet â€” Cash In initiÃ© depuis wallet Robus', [
            'transaction_id' => $pixelTxId,
            'user_id'        => $user->id,
            'amount'         => $amount,
            'fees'           => $fees,
            'destination'    => $request->destination,
        ]);

        return response()->json([
            'status'          => true,
            'message'         => 'Transaction initiÃ©e depuis votre wallet Robus. Le destinataire recevra le montant sur son mobile.',
            'transaction_id'  => $pixelTxId,
            'source'          => 'robus_wallet',
            'amount'          => $amount,
            'fees'            => $fees,
            'total_debited'   => $totalDebit,
            'amount_received' => $amountReceived,
            'provider'        => $pixelConfig->provider->name ?? null,
            'country'         => $pixelConfig->country->name ?? null,
            'sms_link'        => $this->extractPixelPaymentLink($response),
            'state'           => $response['data']['state'] ?? 'PENDING',
        ], 200);
    }

    public function initializeWebPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'robus_id'         => 'required|string|exists:wallets,wallet_id',
            'pixel_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'amount'           => 'required|numeric|min:1',
            'destination'      => 'required|string|min:8|max:15',
            'om_otp'           => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'DonnÃ©es invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $wallet = Wallet::where('wallet_id', $request->robus_id)->with('sidUser')->first();
        if (!$wallet || !$wallet->sidUser) {
            return response()->json(['status' => false, 'message' => 'Portefeuille introuvable'], 404);
        }

        // RÃ©cupÃ©rer la config du service (provider + pays + frais)
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
        $totalFeeAdmin    = (float) $pixelConfig->fixed_fee + (float) $pixelConfig->commission_percent;
        $fees           = round($amount * $totalFeePct / 100, 2);
        $amountReceived = round($amount - $fees, 2);
        $amountPay = $amount + $fees;
        // Le montant transmis Ã  l'opÃ©rateur est celui que le bÃ©nÃ©ficiaire reÃ§oit.
        $amountToCredit = $operationType === 'Cash In' ? $amountReceived : $amount;

        // VÃ©rifier que le montant reÃ§u aprÃ¨s frais est >= au minimum requis
        if ($operationType === 'Cash In' && $amountReceived < $limits['min']) {
            $currency    = $limits['currency'] ?? 'XOF';
            $minRequired = (int) ceil($limits['min'] / (1 - $totalFeePct / 100));
            return response()->json([
                'status'  => false,
                'message' => "AprÃ¨s dÃ©duction des frais ({$totalFeePct}%), le destinataire recevrait " . number_format($amountReceived, 0) . " {$currency}, en dessous du minimum autorisÃ© de {$limits['min']} {$currency}. Veuillez saisir au moins {$minRequired} {$currency}.",
            ], 422);
        }

        // â”€â”€ Cash Out (Robus â†’ Mobile money) : vÃ©rifier le solde avant tout â”€â”€
        if ($operationType === 'Cash In') {
            if ((float) $wallet->amount < $amountPay) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Solde insuffisant pour effectuer ce Cash Out.',
                ], 422);
            }
        }


        $customData     = $request->custom_data ?? ('ROBUS_' . $wallet->sidUser->id . '_' . now()->timestamp);

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
                'user_id'            => $wallet->sidUser->id,
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

        // â”€â”€ Cash In : dÃ©biter le wallet immÃ©diatement avant l'appel Pixel Innov â”€â”€
        if ($operationType === 'Cash In') {
            DB::beginTransaction();
            try {
                $wallet->decrement('amount', $amountPay);
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $reload->update(['status' => 'ERROR']);
                Log::error('Pixel Innov â€” Cash In : erreur dÃ©bit wallet', ['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'message' => 'Erreur lors du dÃ©bit du wallet.'], 500);
            }
        }

        // Appel Pixel Innov â€” clÃ© sÃ©lectionnÃ©e selon is_xaf du service
        $pixelService = app(PixelInnovService::class)->setXaf($pixelConfig->is_xaf ?? false);

        $publicBase = rtrim(config('services.pixel_innov.public_url'), '/');
        $pixelPayload = [
            'amount'             => $amountToCredit,
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
        $pixelPayload = $this->withWaveBusinessNameId($pixelPayload, $pixelConfig);

        $response = $pixelService->initializeTransaction($pixelPayload);

        // Conserver le transaction_id mÃªme en cas d'Ã©chec (pour rÃ©conciliation)
        $pixelTxId = $response['data']['transaction_id'] ?? null;
        if ($pixelTxId) {
            $reload->update(['reference' => $pixelTxId]);
        }

        $isFailed = ($response['statut_code'] ?? null) !== 200
            || empty($pixelTxId)
            || ($response['data']['state'] ?? null) === 'FAILED';

        if ($isFailed) {
            $reload->update(['status' => 'ERROR']);

            // â”€â”€ Cash In : rembourser le wallet si Pixel Innov a refusÃ© dÃ¨s l'init â”€â”€
            if ($operationType === 'Cash In') {
                $wallet->increment('amount', $amountPay);
                Log::info('Pixel Innov â€” Cash In init Ã©chouÃ©e, wallet remboursÃ©', [
                    'reload_id'       => $reload->id,
                    'amount_refunded' => $amountPay,
                ]);
            }

            Log::warning('Pixel Innov â€” init Ã©chouÃ©e', [
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
                $reason = 'Transaction rejetÃ©e par l\'opÃ©rateur mobile.';
            } else {
                $reason = 'Ã‰chec de l\'initialisation. Veuillez rÃ©essayer.';
            }

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        $transactionId = $pixelTxId;
        // Cherche le lien de paiement dans tous les champs possibles de la rÃ©ponse
        $smsLink = $this->extractPixelPaymentLink($response);

        Log::info('Pixel Innov â€” paiement initiÃ©', [
            'transaction_id' => $transactionId,
            'provider'       => $pixelConfig->provider->name ?? null,
            'country'        => $iso2,
            'user_id'        => $wallet->sidUser->id,
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
     *     summary="Calculer les frais et le montant reÃ§u pour un service donnÃ©",
     *     description="Retourne les frais et le montant net qui sera crÃ©ditÃ©/envoyÃ© pour un montant et un service Pixel Innov donnÃ©s, sans initier de transaction.",
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
     *     @OA\Response(response=422, description="DonnÃ©es invalides ou montant hors limites"),
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
                'message' => 'DonnÃ©es invalides',
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
    // VÃ‰RIFICATION DE STATUT
    // =========================================================================

    /**
     * @OA\Post(
     *     path="/pixelInnov/status",
     *     tags={"Pixel Innov"},
     *     summary="VÃ©rifier le statut d'une transaction",
     *     description="Interroge Ã  la fois la base locale et l'API Pixel Innov pour retourner l'Ã©tat en temps rÃ©el d'une transaction initiÃ©e.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"transaction_id"},
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123",
     *                 description="Identifiant de transaction retournÃ© par /pixelInnov/initialize")
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
     *                 description="Ã‰tat retournÃ© en temps rÃ©el par Pixel Innov"),
     *             @OA\Property(property="transaction_id", type="string", example="PIX_abc123"),
     *             @OA\Property(property="amount", type="number", example=5000),
     *             @OA\Property(property="fees", type="number", example=75),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifiÃ©"),
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

        // RÃ©soudre is_xaf depuis les mÃ©tadonnÃ©es du reload (mÃªme logique que paymentCallback)
        $reloadMeta      = $this->reloadMetadata($reload);
        $reloadServiceId = $reloadMeta['pixel_service_id'] ?? null;
        $reloadService   = $reloadServiceId ? PixelInnoveService::find($reloadServiceId) : null;

        // VÃ©rification distante via Pixel Innov â€” clÃ© XAF ou XOF selon le service
        $pixelService = app(PixelInnovService::class)->setXaf($reloadService->is_xaf ?? false);
        $remote = $pixelService->checkTransactionStatus($request->transaction_id);

        $remoteState = strtoupper($remote['data']['state'] ?? '');
        $synced      = false;

        // Synchronisation automatique si la transaction n'est pas encore SUCCESS localement
        // mais que Pixel Innov confirme SUCCESS ou SUCCESSFUL (IPN manquÃ© ou mal traitÃ©).
        if (!in_array($reload->status, ['SUCCESS'])) {
            if (in_array($remoteState, ['SUCCESS', 'SUCCESSFUL'])) {
                DB::beginTransaction();
                try {
                    $this->handleSuccess($reload, $remote['data'] ?? []);
                    DB::commit();
                    $synced = true;
                    Log::info('Pixel Innov â€” statut synchronisÃ© manuellement (IPN manquÃ©)', [
                        'transaction_id' => $request->transaction_id,
                        'remote_state'   => $remoteState,
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error('Pixel Innov â€” erreur sync manuelle', [
                        'transaction_id' => $request->transaction_id,
                        'error'          => $e->getMessage(),
                    ]);
                }
            } elseif (in_array($remoteState, ['FAILED', 'FAIL', 'ERROR', 'CANCELLED']) && $reload->status !== 'ERROR') {
                DB::beginTransaction();
                try {
                    $this->handleFailure($reload, $remote['data'] ?? []);
                    DB::commit();
                    $synced = true;
                    Log::info('Pixel Innov â€” FAILED synchronisÃ© manuellement (IPN manquÃ©), wallet remboursÃ© si Cash In', [
                        'transaction_id' => $request->transaction_id,
                        'remote_state'   => $remoteState,
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error('Pixel Innov â€” erreur sync manuelle FAILED', [
                        'transaction_id' => $request->transaction_id,
                        'error'          => $e->getMessage(),
                    ]);
                }
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
    // IPN WEBHOOK â€” appelÃ© serveurâ†’serveur par Pixel Innov
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

        Log::info('Pixel Innov â€” IPN reÃ§u', $request->all());

        if (!$transactionId || !$state || !$hash) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        // VÃ©rification du hash â€” on teste les deux clÃ©s (XOF et XAF) car le service
        // n'est pas encore chargÃ© Ã  ce stade.
        $pixelService = app(PixelInnovService::class);
        $hashValid = $pixelService->setXaf(false)->verifyIpnHash($transactionId, $hash)
            || $pixelService->setXaf(true)->verifyIpnHash($transactionId, $hash);
        if (!$hashValid) {
            Log::warning('Pixel Innov â€” hash invalide', [
                'transaction_id' => $transactionId,
                'hash_received'  => $hash,
            ]);
            return response()->json(['status' => 'invalid_hash'], 401);
        }

        DB::beginTransaction();
        try {

            $reload = WalletReload::where('reference', $transactionId)->lockForUpdate()->first();

            if (!$reload) {
                DB::rollBack();
                Log::error('Pixel Innov â€” WalletReload introuvable', ['transaction_id' => $transactionId]);
                return response()->json(['status' => 'not_found'], 404);
            }

            // Re-sÃ©lectionner la clÃ© correcte maintenant qu'on connaÃ®t le service
            $reloadMeta      = $this->reloadMetadata($reload);
            $reloadServiceId = $reloadMeta['pixel_service_id'] ?? null;
            $reloadService   = $reloadServiceId ? \App\Models\PixelInnoveService::find($reloadServiceId) : null;
            $pixelService->setXaf($reloadService->is_xaf ?? false);

            // Idempotence : ne pas re-traiter une transaction dÃ©jÃ  crÃ©ditÃ©e (SUCCESS).
            // On ne bloque pas sur ERROR pour pouvoir rÃ©cupÃ©rer si un SUCCESSFUL arrive aprÃ¨s un PENDING mal traitÃ©.
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
                // Ã‰tat intermÃ©diaire (PENDING, PENDING1, PROCESSINGâ€¦) : on attend le prochain IPN.
                DB::rollBack();
                Log::info('Pixel Innov â€” IPN Ã©tat intermÃ©diaire ignorÃ©', [
                    'transaction_id' => $transactionId,
                    'state'          => $state,
                ]);
                return response()->json(['status' => 'pending'], 200);
            }

            DB::commit();
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Pixel Innov â€” erreur IPN', [
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
            'message' => 'Paiement effectuÃ© avec succÃ¨s. Votre compte sera crÃ©ditÃ© sous peu.',
        ]);
    }

    public function redirectError(Request $request)
    {
        return response()->json([
            'status'  => false,
            'message' => 'Le paiement a Ã©chouÃ© ou a Ã©tÃ© annulÃ©.',
        ]);
    }

    // =========================================================================
    // MÃ‰THODES PRIVÃ‰ES
    // =========================================================================

    private function reloadMetadata(WalletReload $reload): array
    {
        if (is_array($reload->metadata)) {
            return $reload->metadata;
        }

        $metadata = json_decode($reload->metadata ?? '{}', true);

        return is_array($metadata) ? $metadata : [];
    }

    private function pixelInnovUnderCommission(WalletReload $reload, PixelInnoveService $pixelConfig, float $conversionRate = 1): array
    {
        $walletOwner = $reload->wallet?->sid_user_id
            ? SidUser::with('wallet')->find($reload->wallet->sid_user_id)
            : null;

        if (!$walletOwner || !($walletOwner->is_under_distributer || $walletOwner->is_api)) {
            return [null, 0.0, 0.0];
        }

        $activeCButtons = app(OperatorRestrictionService::class)->getActiveCButtons($walletOwner->id);
        if (empty($activeCButtons['showRemonterPixelInnovCButton'])) {
            return [$walletOwner, 0.0, 0.0];
        }

        $commissionPct = (float) ($pixelConfig->commission_under_pct ?? 0);
        if ($commissionPct <= 0) {
            return [$walletOwner, 0.0, 0.0];
        }

        $commission = round((float) $reload->amount * $commissionPct / 100, 2);

        return [$walletOwner, $commission, round($commission * $conversionRate, 2)];
    }

    private function creditPixelInnovUnderCommission(
        WalletReload $reload,
        ?SidUser $walletOwner,
        float $commission,
        float $commissionConversion,
        string $description
    ): void {
        if ($commission <= 0 || !$walletOwner?->wallet) {
            return;
        }

        $reload->update(['commissions' => $commission]);

        LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id'   => null,
            'credit'          => 0,
            'debit'         => $commissionConversion,
            'description'    => $description . ' #' . $reload->id,
            'is_uv'          => true,
        ]);

        Log::info('Pixel Innov â€” commission sous-distributeur versÃ©e', [
            'wallet_reload_id' => $reload->id,
            'user_id'          => $walletOwner->id,
            'amount'           => $commission,
            'amount_conv'      => $commissionConversion,
        ]);
    }


    private function handleSuccess(WalletReload $reload, array $ipnData): void
    {
        $metadata      = $this->reloadMetadata($reload);
        $operationType = $metadata['operation_type'] ?? 'Cash In';

        if ($operationType === 'Cash Out') {
            // Mobile dÃ©bitÃ© par Pixel â†’ crÃ©diter le wallet Robus
            $this->creditWalletFromMobilePayment($reload, $ipnData);
        } else {
            // Wallet Robus dÃ©jÃ  dÃ©bitÃ© Ã  l'init â†’ juste marquer SUCCESS
            $this->markCashInCompleted($reload, $ipnData);
        }
    }

    // Cash Out (Robus â†’ Mobile) : wallet dÃ©jÃ  dÃ©bitÃ© Ã  l'init, on enregistre les frais et on marque SUCCESS
    private function markCashInCompleted(WalletReload $reload, array $ipnData): void
    {
        $wallet = $reload->wallet;

        // RÃ©cupÃ©rer pixelConfig depuis les mÃ©tadonnÃ©es (enregistrÃ©es Ã  l'init)
        $metadata      = $this->reloadMetadata($reload);
        $pixelConfigId = $metadata['pixel_service_id'] ?? null;
        $pixelConfig   = $pixelConfigId ? PixelInnoveService::find($pixelConfigId) : null;

        if (!$pixelConfig) {
            throw new \Exception('Configuration Pixel Innov introuvable (pixel_service_id absent des mÃ©tadonnÃ©es)');
        }

        // benefice = montant + frais Pixel cÃ´tÃ© IPN
        $pixelFee = (float) ($ipnData['fee'] ?? $ipnData['comission'] ?? 0);
        $benefice  = (float) ($ipnData['benefice'] ?? ((float) $reload->amount + $pixelFee));

        // Frais depuis la config du service Pixel Innov (identiques Ã  ceux calculÃ©s Ã  l'init)
        $fixedFeePct     = (float) $pixelConfig->fixed_fee;
        $commissionPct   = (float) $pixelConfig->commission_percent;
        $totalFeePct     = $fixedFeePct + $commissionPct;
        $totalCommission = round((float) $reload->amount * $totalFeePct / 100, 2);
        $totalAdminCommission = round((float) $reload->amount * $fixedFeePct / 100, 2);
        $amountReceived  = (float) $reload->amount - $totalCommission;

        $countryCiv    = Country::find(107);
        $robusCurrency = $wallet->country->currencie->value ?? 1;
        $walletPixel      = WalletAdmin::find(1);
        $adminSingle      = SidUser::whereIsAdmin(true)->where('country_id', 107)->first();
        $walletPixelCommission = WalletCommission::find(1);

        if ($countryCiv && $countryCiv->currencie->value == $wallet->country->currencie->value) {
            $amountTotalConversion      = $benefice;
            $amountCommissionConversion = $totalCommission;
            $amountCommissionAdminConversion = $totalAdminCommission;
            $conversionRate = 1;
        } else {
            $amountTotalConversion      = $benefice * (float) $robusCurrency;
            $amountCommissionConversion = $totalCommission * (float) $robusCurrency;
            $amountCommissionAdminConversion = $totalAdminCommission * (float) $robusCurrency;
            $conversionRate = (float) $robusCurrency;
        }

        [$walletOwner, $underCommission, $underCommissionConversion] = $this->pixelInnovUnderCommission($reload, $pixelConfig, $conversionRate);
        $amountCommissionAdminConversion = max(0, $amountCommissionAdminConversion - $underCommissionConversion);

        $reload->update([
            'status'           => 'SUCCESS',
            'gateway_response' => $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
            'paid_at'          => !empty($ipnData['created_at']) ? Carbon::parse($ipnData['created_at']) : now(),
            'description'      => 'DÃ©pot â€” mobile money crÃ©ditÃ©',
            'type_transaction' => 'Cash In',
            'fees'             => $totalCommission,
            'costs_amount'     => $totalCommission,
            'cost_conversion'  => $amountCommissionConversion,
            'amount_conversion' => $amountTotalConversion,
            'amount_received'  => $amountReceived,
            'commissions'      => $underCommission,
            'balance_before'  => $reload->amount_solde - ($reload->amount + $reload->fees),
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
            'description'               => 'DÃ©pot â€” ' . $ipnData['transaction_id'],
            'reference'                 => $ipnData['transaction_id'],
            'operator_transaction_name' => 'Pixel Innov',
            'status'                    => 'completed',
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        if ($walletPixelCommission) {
            $walletPixel->increment('amount', $amountCommissionAdminConversion);

            LedgerEntry::create([
                'transaction_id' => (string) Str::uuid(),
                'admin_com_id'   => null,
                'debit'          => 0,
                'credit'         => $amountCommissionAdminConversion,
                'description'    => 'Commission administrateur Pixel',
                'is_uv'          => false,
                'is_pixel'          => true,
            ]);

            $walletPixelCommission->decrement('amount', $amountCommissionConversion);
        } else {
            $adminSingle->wallet->increment('amount', $amountCommissionAdminConversion);
        }

        $this->creditPixelInnovUnderCommission(
            $reload,
            $walletOwner,
            $underCommission,
            $underCommissionConversion,
            'Commission sous-distributeur Pixel Innov Cash In'
        );

        Log::info('Pixel Innov â€” Cash In SUCCESS (mobile money crÃ©ditÃ©)', [
            'transaction_id' => $ipnData['transaction_id'] ?? null,
            'wallet_id'      => $reload->wallet_id,
            'amount_sent'    => $reload->amount,
            'commission'     => $totalCommission,
            'amount_received' => $amountReceived,
        ]);
    }

    // Cash In (Mobile â†’ Robus) : mobile dÃ©bitÃ© par Pixel â†’ crÃ©diter le wallet Robus avec commissions
    private function creditWalletFromMobilePayment(WalletReload $reload, array $ipnData): void
    {
        $wallet = $reload->wallet;

        // RÃ©cupÃ©rer pixelConfig depuis les mÃ©tadonnÃ©es (enregistrÃ©es Ã  l'init)
        $metadata      = $this->reloadMetadata($reload);
        $pixelConfigId = $metadata['pixel_service_id'] ?? null;
        $pixelConfig   = $pixelConfigId ? PixelInnoveService::find($pixelConfigId) : null;

        if (!$pixelConfig) {
            throw new \Exception('Configuration Pixel Innov introuvable (pixel_service_id absent des mÃ©tadonnÃ©es)');
        }

        // benefice = montant + frais Pixel (ce que le client a rÃ©ellement payÃ© depuis son mobile)
        $pixelFee = (float) ($ipnData['fee'] ?? $ipnData['comission'] ?? 0);
        $benefice  = (float) ($ipnData['benefice'] ?? ((float) $reload->amount + $pixelFee));

        // Frais depuis la config du service Pixel Innov (identiques Ã  ceux calculÃ©s Ã  l'init)
        $fixedFeePct     = (float) $pixelConfig->fixed_fee;
        $commissionPct   = (float) $pixelConfig->commission_percent;
        $totalFeePct     = $fixedFeePct + $commissionPct;
        $totalCommission = round((float) $reload->amount * $totalFeePct / 100, 2);
        $amountReceived  = (float) $reload->amount - $totalFeePct;
        $totalAdminCommission  = round((float) $reload->amount * $fixedFeePct / 100, 2);;

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

        // La vÃ©rification du solde utilise benefice (montant + frais Pixel)
        // car c'est le montant total engagÃ© dans la transaction cÃ´tÃ© client
        if ($countryCiv && $countryCiv->currencie->value == $wallet->country->currencie->value) {
            $amountTotalConversion      = $benefice;
            $amountCommissionConversion = $totalCommission;
            $amountAdminCommissionConversion = $totalAdminCommission;
            $conversionRate = 1;
        } else {
            $amountTotalConversion      = $benefice * (float) $robusCurrency;
            $amountCommissionConversion = $totalCommission * (float) $robusCurrency;
            $amountAdminCommissionConversion = $totalAdminCommission * (float) $robusCurrency;
            $conversionRate = (float) $robusCurrency;
        }

        if ($amountTotalConversion <= 0) {
            throw new \Exception('Montant final invalide');
        }

        [$walletOwner, $underCommission, $underCommissionConversion] = $this->pixelInnovUnderCommission($reload, $pixelConfig, $conversionRate);
        $amountCommissionConversion = max(0, $amountCommissionConversion - $underCommissionConversion);

        $reload->update([
            'status'           => 'SUCCESS',
            'gateway_response' => $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
            'paid_at'          => !empty($ipnData['created_at']) ? Carbon::parse($ipnData['created_at']) : now(),
            'payment_method'   => 'ACHAT D\'UV',
            'description'      => 'ACHAT D\'UV',
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
            'commissions'      => $underCommission,
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
            'description' => 'Recharge via achat UV  - ' . $ipnData['transaction_id'],
            'reference' => $ipnData['transaction_id'],
            'operator_transaction_name' => 'achat UV',
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
            'credit' => $amountAdminCommissionConversion,
            'description' => 'Commission adminstrateur',
            'is_uv' => false,
            'is_pixel' => true,
        ]);

        $walletAdmin->increment('amount', $amountAdminCommissionConversion);

        $this->creditPixelInnovUnderCommission(
            $reload,
            $walletOwner,
            $underCommission,
            $underCommissionConversion,
            'Commission sous-distributeur Pixel Innov Achat UV'
        );

        LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id' => null,
            'debit' => $amountTotalConversion,
            'credit' => 0,
            'description' => 'Wallet retrait Pixel Innov',
            'is_wallet' => false,
            'is_pixel' => true,
        ]);

        $wallet->update([
            'reload_by' => $reload->user_id,
            'sid_user_relod' => $reload->user_id,
            'amount' => DB::raw('amount+' . $amountReceived),
        ]);

        Log::info('Pixel Innov â€” Cash In SUCCESS, wallet Robus crÃ©ditÃ©', [
            'transaction_id'  => $ipnData['transaction_id'],
            'wallet_id'       => $reload->wallet_id,
            'amount_credited' => $amountReceived,
            'commission'      => $totalCommission,
            'provider_ref'    => $ipnData['provider_id'] ?? null,
        ]);
    }

    private function handleFailure(WalletReload $reload, array $ipnData): void
    {
        $metadata      = $this->reloadMetadata($reload);
        $operationType = $metadata['operation_type'] ?? 'Cash Out';

        $reload->update([
            'status'           => 'ERROR',
            'gateway_response' => $ipnData['error'] ?? $ipnData['response'] ?? null,
            'raw_response'     => json_encode($ipnData),
            'verified'         => true,
            'verified_at'      => now(),
        ]);

        // Cash Out (Mobile â†’ Robus = DÃ©pÃ´t) : wallet jamais dÃ©bitÃ©, rien Ã  rembourser.
        // Les LedgerEntry ne sont crÃ©Ã©es que sur SUCCESS (markCashInCompleted / creditWalletFromMobilePayment),
        // donc aucune Ã©criture comptable Ã  inverser ici.

        // Cash In (Robus â†’ Mobile = Retrait) : wallet dÃ©bitÃ© Ã  l'init, rembourser maintenant
        if ($operationType === 'Cash In') {
            $wallet = $reload->wallet;
            if ($wallet) {
                $refundAmount = (float) $reload->amount + (float) ($reload->fees ?? $reload->costs_amount ?? 0);
                $wallet->increment('amount', $refundAmount);
                Log::info('Pixel Innov â€” Cash In IPN FAILED, wallet remboursÃ©', [
                    'transaction_id'  => $ipnData['transaction_id'] ?? null,
                    'wallet_id'       => $reload->wallet_id,
                    'amount_refunded' => $refundAmount,
                ]);
            }
        }

        Log::info('Pixel Innov â€” FAILED', [
            'transaction_id' => $ipnData['transaction_id'] ?? null,
            'error'          => $ipnData['error'] ?? null,
            'operation_type' => $operationType,
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE â€” services disponibles (tous pays, non filtrÃ©)
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

            $providers = $items->map(function ($s) {
                $providerName = $s->provider->name ?? $s->name;

                return [
                    'id'                       => $s->id,
                    'name'                     => $providerName,
                    'provider'                 => $providerName,
                    'operator'                 => $providerName,
                    'service_id'               => $s->service_id,
                    'pixel_service_id'         => $s->id,
                    'pixel_gateway_service_id' => $s->service_id,
                    'operation_type'           => $s->operation_type,
                    'pixel_operation_type'     => $s->operation_type,
                    'country_iso2'             => self::countryCode($s->country),
                    'country_indicatif'        => optional($s->country)->indecative,
                    'image'                    => $s->provider->image ?? null,
                    'fixed_fee_pct'            => (float) $s->fixed_fee,
                    'commission_pct'           => (float) $s->commission_percent,
                    'total_fee_pct'            => (float) $s->fixed_fee + (float) $s->commission_percent,
                    'is_xof'                   => (bool) $s->is_xof,
                    'is_xaf'                   => (bool) $s->is_xaf,
                    'is_cdf'                   => (bool) $s->is_cdf,
                    'is_gmd'                   => (bool) $s->is_gmd,
                ];
            })->values();

            return [
                'country_id'   => $first->country_id,
                'country'      => $first->country->name ?? null,
                'country_iso2' => $iso2,
                'country_indicatif' => optional($first->country)->indecative,
                'currency'     => $limits['currency'],
                'amount_min'   => $limits['min'],
                'amount_max'   => $limits['max'],
                'providers'    => $providers,
                'operators'    => $providers,
                'operateurs'   => $providers,
            ];
        })->values();

        return response()->json([
            'status'    => true,
            'countries' => $countries,
        ]);
    }

    public function listMobileTransferServicesWeb(Request $request)
    {
        $providerImageUrl = function (?string $image): ?string {
            if (!$image) {
                return null;
            }

            if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/')) {
                return $image;
            }

            return asset_url('dist/providers/' . $image);
        };

        $services = PixelInnoveService::with(['country', 'provider'])
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $grouped = $services->groupBy(fn($s) => $s->country_id);

        $countries = $grouped->map(function ($items) use ($providerImageUrl) {
            $first = $items->first();
            $iso2  = self::countryCode($first->country);
            $limits = self::AMOUNT_LIMITS[$iso2 ?? ''] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];

            $formatProvider = function ($s) use ($providerImageUrl) {
                $providerName = $s->provider->name ?? $s->name;

                return [
                    'id'                       => $s->id,
                    'name'                     => $providerName,
                    'provider'                 => $providerName,
                    'operator'                 => $providerName,
                    'service_id'               => $s->service_id,
                    'sender_service_id'        => $s->service_id,
                    'recipient_service_id'     => $s->service_id,
                    'pixel_service_id'         => $s->id,
                    'pixel_gateway_service_id' => $s->service_id,
                    'operation_type'           => $s->operation_type,
                    'pixel_operation_type'     => $s->operation_type,
                    'country_iso2'             => self::countryCode($s->country),
                    'country_indicatif'        => optional($s->country)->indecative,
                    'image'                    => $providerImageUrl($s->provider->image ?? null),
                    'fixed_fee_pct'            => (float) $s->fixed_fee,
                    'commission_pct'           => (float) $s->commission_percent,
                    'total_fee_pct'            => (float) $s->fixed_fee + (float) $s->commission_percent,
                    'is_xof'                   => (bool) $s->is_xof,
                    'is_xaf'                   => (bool) $s->is_xaf,
                    'is_cdf'                   => (bool) $s->is_cdf,
                    'is_gmd'                   => (bool) $s->is_gmd,
                ];
            };

            $cashOutProviders = $items
                ->where('operation_type', 'Cash Out')
                ->map($formatProvider)
                ->values();

            $cashInProviders = $items
                ->where('operation_type', 'Cash In')
                ->map($formatProvider)
                ->values();

            return [
                'country_id'         => $first->country_id,
                'country'            => $first->country->name ?? null,
                'country_iso2'       => $iso2,
                'country_indicatif'  => optional($first->country)->indecative,
                'currency'           => $limits['currency'],
                'amount_min'         => $limits['min'],
                'amount_max'         => $limits['max'],
                'providers'          => $cashOutProviders,
                'cash_out_providers' => $cashOutProviders,
                'cash_in_providers'  => $cashInProviders,
                'operators'          => $cashOutProviders,
                'operateurs'         => $cashOutProviders,
            ];
        })->filter(fn($country) => $country['cash_out_providers']->isNotEmpty() || $country['cash_in_providers']->isNotEmpty())->values();

        return response()->json([
            'status'    => true,
            'countries' => $countries,
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE â€” calcul des frais (deux legs)
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
                'message' => 'DonnÃ©es invalides',
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
            return response()->json(['status' => false, 'message' => 'Service expÃ©diteur introuvable ou inactif (Cash Out requis)'], 404);
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
            'recipient_fee_pct' => $recipientFeePct,
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

    public function calculateMobileZoneTransferWeb(Request $request)
    {
        return $this->calculateMobileZoneTransfer($request);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE â€” initialisation (deux legs : Cash Out + Cash In)
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
                'message' => 'DonnÃ©es invalides',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = robus();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Non authentifiÃ©'], 401);
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
            return response()->json(['status' => false, 'message' => 'Service expÃ©diteur introuvable (Cash Out requis)'], 404);
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

        $recipientLimits = self::AMOUNT_LIMITS[$recipientIso2] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];

        // VÃ©rifier que le montant reÃ§u par le destinataire aprÃ¨s frais est >= au minimum requis
        if ($amountReceived < $recipientLimits['min']) {
            $senderCurrency    = self::AMOUNT_LIMITS[$senderIso2]['currency'] ?? 'XOF';
            $recipientCurrency = $recipientLimits['currency'] ?? 'XOF';
            // RÃ©soudre : amount * (1 - senderFeePct/100) * (1 - recipientFeePct/100) >= recipientLimits['min']
            $factor      = (1 - $senderFeePct / 100) * (1 - $recipientFeePct / 100);
            $minRequired = (int) ceil($recipientLimits['min'] / $factor);
            return response()->json([
                'status'  => false,
                'message' => "AprÃ¨s dÃ©duction des frais, le destinataire recevrait " . number_format($amountReceived, 0) . " {$recipientCurrency}, en dessous du minimum autorisÃ© de {$recipientLimits['min']} {$recipientCurrency}. Veuillez saisir au moins {$minRequired} {$senderCurrency}.",
            ], 422);
        }

        $senderCurrencyCode    = self::AMOUNT_LIMITS[$senderIso2]['currency'] ?? 'XOF';
        $recipientCurrencyCode = self::AMOUNT_LIMITS[$recipientIso2]['currency'] ?? 'XOF';
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
            'currency'                   => $senderCurrencyCode,
            'sender_currency'            => $senderCurrencyCode,
            'recipient_currency'         => $recipientCurrencyCode,
            'sender_status'              => 'PENDING',
            'recipient_status'           => 'PENDING',
            'status'                     => 'PENDING',
            'wallet_id'                  => optional(Wallet::where('sid_user_id', $user->id)->first())->id,
            'metadata'                   => [
                'user_id'          => $user->id,
                'custom_ref'       => $customRef,
                'sender_service'   => $senderService->name,
                'recipient_service' => $recipientService->name,
            ],
        ]);

        // Appel Pixel Innov â€” leg 1 : Cash Out depuis le mobile de l'expÃ©diteur
        // ClÃ© sÃ©lectionnÃ©e selon is_xaf du service expÃ©diteur
        $pixelService = app(PixelInnovService::class)->setXaf($senderService->is_xaf ?? false);
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
        $cashOutPayload = $this->withWaveBusinessNameId($cashOutPayload, $senderService);

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
            $reason    = $pixelMsg ?: ($dataMsg ?: 'Ã‰chec du dÃ©bit chez l\'expÃ©diteur. Veuillez rÃ©essayer.');

            Log::warning('Mobile Zone Transfer â€” Cash Out Ã©chouÃ©', [
                'transfer_id' => $transfer->id,
                'response'    => $cashOutResponse,
            ]);

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        // Cash Out initiÃ© avec succÃ¨s â€” on enregistre le transaction_id + solde avant
        $pixelWalletBefore = $cashOutResponse['data']['p_last_wallet_amount'] ?? null;

        $transfer->update([
            'sender_transaction_id' => $cashOutTxId,
            'sender_status'         => 'PENDING',
            'status'                => 'PENDING',
            'raw_response_sender'   => $cashOutResponse,
            'pixel_wallet_before'   => $pixelWalletBefore,
        ]);

        $smsLink = $this->extractPixelPaymentLink($cashOutResponse);

        Log::info('Mobile Zone Transfer â€” Cash Out initiÃ©', [
            'transfer_id'    => $transfer->id,
            'transaction_id' => $cashOutTxId,
            'sender_phone'   => $request->sender_phone,
            'amount'         => $amount,
        ]);

        // â”€â”€ DÃ©clencher le Cash In immÃ©diatement â€” switcher vers la clÃ© du service destinataire â”€â”€
        $pixelService->setXaf($recipientService->is_xaf ?? false);
        $cashInPayload = [
            'amount'             => (int) $transfer->amount_received,
            'destination'        => $request->recipient_phone,
            'service_id'         => $recipientService->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
            'custom_data'        => $customRef . '_CASHIN',
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];
        $cashInPayload = $this->withWaveBusinessNameId($cashInPayload, $recipientService);

        $cashInResponse  = $pixelService->initializeTransaction($cashInPayload);
        $cashInTxId      = $cashInResponse['data']['transaction_id'] ?? null;
        $cashInStatut    = $cashInResponse['statut_code'] ?? null;
        $cashInState     = $cashInResponse['data']['state'] ?? null;
        $cashInFailed    = $cashInStatut !== 200 || empty($cashInTxId) || $cashInState === 'FAILED';

        if ($cashInFailed) {
            $cashInErrMsg = trim((string) ($cashInResponse['message'] ?? ''))
                ?: trim((string) ($cashInResponse['data']['response'] ?? ''))
                ?: 'Ã‰chec de l\'envoi vers le destinataire';

            $transfer->update([
                'recipient_status'       => 'FAILED',
                'status'                 => 'FAILED',
                'raw_response_recipient' => $cashInResponse,
            ]);

            Log::error('Mobile Zone Transfer â€” Cash In direct Ã©chouÃ©', [
                'transfer_id'      => $transfer->id,
                'cash_in_response' => $cashInResponse,
                'recipient_phone'  => $request->recipient_phone,
                'amount_sent'      => $transfer->amount_received,
                'cash_out_tx_id'   => $cashOutTxId,
            ]);

            // Tenter le remboursement automatique vers l'expÃ©diteur
            $refundResult = $this->refundSender($transfer, $pixelService, $publicBase);

            return response()->json([
                'status'          => false,
                'message'         => 'DÃ©bit expÃ©diteur initiÃ© mais envoi au destinataire Ã©chouÃ© : ' . $cashInErrMsg
                    . ($refundResult['initiated'] ? ' Un remboursement est en cours vers votre numÃ©ro.' : ' Contactez le support pour remboursement.'),
                'transaction_id'  => (string) $transfer->id,
                'cash_out_state'  => $cashOutResponse['data']['state'] ?? 'PENDING',
                'cash_in_error'   => $cashInErrMsg,
                'refund_initiated' => $refundResult['initiated'],
                'refund_error'    => $refundResult['error'] ?? null,
            ], 400);
        }

        $pixelWalletAfter = $cashInResponse['data']['p_last_wallet_amount'] ?? null;

        $transfer->update([
            'recipient_transaction_id' => $cashInTxId,
            'recipient_status'         => 'PENDING',
            'status'                   => 'PROCESSING',
            'raw_response_recipient'   => $cashInResponse,
            'pixel_wallet_after'       => $pixelWalletAfter,
        ]);

        Log::info('Mobile Zone Transfer â€” Cash In direct initiÃ©', [
            'transfer_id'     => $transfer->id,
            'cash_in_tx_id'   => $cashInTxId,
            'recipient_phone' => $request->recipient_phone,
            'amount_to_send'  => $transfer->amount_to_send,
            'cash_in_state'   => $cashInState,
        ]);

        return response()->json([
            'status'          => true,
            'message'         => $this->initMessage($senderService->provider->name ?? '', $smsLink)
                . ' Le montant est en cours d\'envoi au destinataire.',
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
                'phone'      => $request->recipient_phone,
                'operator'   => $recipientService->provider->name ?? null,
                'country'    => $recipientService->country->name ?? null,
                'cash_in_id' => $cashInTxId,
                'state'      => $cashInState,
            ],
        ]);
    }

    public function initializeMobileZoneTransferWeb(Request $request)
    {
        $request->merge([
            'sender_phone'    => $request->input('sender_phone', $request->input('my_phone')),
            'recipient_phone' => $request->input('recipient_phone', $request->input('phone_benef')),
        ]);

        $validator = Validator::make($request->all(), [
            'sender_service_id'    => 'required|integer|exists:pixel_innove_services,service_id',
            'sender_phone'         => 'required|string|min:8|max:20',
            'recipient_service_id' => 'required|integer|exists:pixel_innove_services,service_id',
            'recipient_phone'      => 'required|string|min:8|max:20',
            'amount'               => 'required|numeric|min:1',
            'name_expeditor'       => 'nullable|string|max:120',
            'om_otp'               => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'DonnÃ©es invalides',
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
            return response()->json(['status' => false, 'message' => 'Service expÃ©diteur introuvable (Cash Out requis)'], 404);
        }
        if (!$recipientService) {
            return response()->json(['status' => false, 'message' => 'Service destinataire introuvable (Cash In requis)'], 404);
        }

        $senderIso2    = self::countryCode($senderService->country) ?? '';
        $recipientIso2 = self::countryCode($recipientService->country) ?? '';
        $senderLimits  = self::AMOUNT_LIMITS[$senderIso2] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];

        $amount = (float) $request->amount;

        if ($amount < $senderLimits['min'] || $amount > $senderLimits['max']) {
            return response()->json([
                'status'  => false,
                'message' => "Montant invalide pour {$senderService->country->name}. Min : {$senderLimits['min']}, Max : {$senderLimits['max']}.",
            ], 422);
        }

        $senderFeePct  = (float) $senderService->fixed_fee + (float) $senderService->commission_percent;
        $senderFee     = round($amount * $senderFeePct / 100, 2);
        $amountToSend  = round($amount - $senderFee, 2);

        $recipientFeePct = (float) $recipientService->fixed_fee + (float) $recipientService->commission_percent;
        $recipientFee    = round($amountToSend * $recipientFeePct / 100, 2);
        $amountReceived  = round($amountToSend - $recipientFee, 2);
        $totalFee        = round($senderFee + $recipientFee, 2);

        $recipientLimits = self::AMOUNT_LIMITS[$recipientIso2] ?? ['min' => 200, 'max' => 1_500_000, 'currency' => 'XOF'];
        if ($amountReceived < $recipientLimits['min']) {
            $senderCurrency    = $senderLimits['currency'] ?? 'XOF';
            $recipientCurrency = $recipientLimits['currency'] ?? 'XOF';
            $factor            = (1 - $senderFeePct / 100) * (1 - $recipientFeePct / 100);
            $minRequired       = (int) ceil($recipientLimits['min'] / $factor);

            return response()->json([
                'status'  => false,
                'message' => "AprÃ¨s dÃ©duction des frais, le destinataire recevrait " . number_format($amountReceived, 0) . " {$recipientCurrency}, en dessous du minimum autorisÃ© de {$recipientLimits['min']} {$recipientCurrency}. Veuillez saisir au moins {$minRequired} {$senderCurrency}.",
            ], 422);
        }

        $senderCurrencyCode    = $senderLimits['currency'] ?? 'XOF';
        $recipientCurrencyCode = $recipientLimits['currency'] ?? 'XOF';
        $customRef             = 'ROBUS_WEB_MZ_' . now()->timestamp . '_' . Str::upper(Str::random(6));

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
            'currency'                   => $senderCurrencyCode,
            'sender_currency'            => $senderCurrencyCode,
            'recipient_currency'         => $recipientCurrencyCode,
            'sender_status'              => 'PENDING',
            'recipient_status'           => 'PENDING',
            'status'                     => 'PENDING',
            'wallet_id'                  => null,
            'metadata'                   => [
                'source'            => 'web',
                'custom_ref'        => $customRef,
                'name_expeditor'    => $request->name_expeditor,
                'sender_service'    => $senderService->name,
                'recipient_service' => $recipientService->name,
            ],
        ]);

        $pixelService = app(PixelInnovService::class)->setXaf($senderService->is_xaf ?? false);
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
        $cashOutPayload = $this->withWaveBusinessNameId($cashOutPayload, $senderService);

        $cashOutResponse = $pixelService->initializeTransaction($cashOutPayload);
        $cashOutTxId     = $cashOutResponse['data']['transaction_id'] ?? null;
        $cashOutFailed   = ($cashOutResponse['statut_code'] ?? null) !== 200
            || empty($cashOutTxId)
            || ($cashOutResponse['data']['state'] ?? null) === 'FAILED';

        if ($cashOutFailed) {
            $transfer->update([
                'sender_status'       => 'FAILED',
                'status'              => 'FAILED',
                'raw_response_sender' => $cashOutResponse,
            ]);

            $reason = trim((string) ($cashOutResponse['message'] ?? ''))
                ?: trim((string) ($cashOutResponse['data']['response'] ?? ''))
                ?: 'Ã‰chec du dÃ©bit chez l\'expÃ©diteur. Veuillez rÃ©essayer.';

            Log::warning('Mobile Zone Transfer Web â€” Cash Out Ã©chouÃ©', [
                'transfer_id' => $transfer->id,
                'response'    => $cashOutResponse,
            ]);

            return response()->json(['status' => false, 'message' => $reason], 400);
        }

        $transfer->update([
            'sender_transaction_id' => $cashOutTxId,
            'sender_status'         => 'PENDING',
            'status'                => 'PENDING',
            'raw_response_sender'   => $cashOutResponse,
            'pixel_wallet_before'   => $cashOutResponse['data']['p_last_wallet_amount'] ?? null,
        ]);

        $smsLink = $this->extractPixelPaymentLink($cashOutResponse);

        $pixelService->setXaf($recipientService->is_xaf ?? false);
        $cashInPayload = [
            'amount'             => (int) $transfer->amount_received,
            'destination'        => $request->recipient_phone,
            'service_id'         => $recipientService->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
            'custom_data'        => $customRef . '_CASHIN',
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];
        $cashInPayload = $this->withWaveBusinessNameId($cashInPayload, $recipientService);

        $cashInResponse = $pixelService->initializeTransaction($cashInPayload);
        $cashInTxId     = $cashInResponse['data']['transaction_id'] ?? null;
        $cashInState    = $cashInResponse['data']['state'] ?? null;
        $cashInFailed   = ($cashInResponse['statut_code'] ?? null) !== 200
            || empty($cashInTxId)
            || $cashInState === 'FAILED';

        if ($cashInFailed) {
            $cashInErrMsg = trim((string) ($cashInResponse['message'] ?? ''))
                ?: trim((string) ($cashInResponse['data']['response'] ?? ''))
                ?: 'Ã‰chec de l\'envoi vers le destinataire';

            $transfer->update([
                'recipient_status'       => 'FAILED',
                'status'                 => 'FAILED',
                'raw_response_recipient' => $cashInResponse,
            ]);

            Log::error('Mobile Zone Transfer Web â€” Cash In direct Ã©chouÃ©', [
                'transfer_id'      => $transfer->id,
                'cash_in_response' => $cashInResponse,
                'recipient_phone'  => $request->recipient_phone,
                'amount_sent'      => $transfer->amount_received,
                'cash_out_tx_id'   => $cashOutTxId,
            ]);

            $refundResult = $this->refundSender($transfer, $pixelService, $publicBase);

            return response()->json([
                'status'           => false,
                'message'          => 'DÃ©bit expÃ©diteur initiÃ© mais envoi au destinataire Ã©chouÃ© : ' . $cashInErrMsg
                    . ($refundResult['initiated'] ? ' Un remboursement est en cours vers votre numÃ©ro.' : ' Contactez le support pour remboursement.'),
                'transaction_id'   => (string) $transfer->id,
                'cash_out_state'   => $cashOutResponse['data']['state'] ?? 'PENDING',
                'cash_in_error'    => $cashInErrMsg,
                'refund_initiated' => $refundResult['initiated'],
                'refund_error'     => $refundResult['error'] ?? null,
            ], 400);
        }

        $transfer->update([
            'recipient_transaction_id' => $cashInTxId,
            'recipient_status'         => 'PENDING',
            'status'                   => 'PROCESSING',
            'raw_response_recipient'   => $cashInResponse,
            'pixel_wallet_after'       => $cashInResponse['data']['p_last_wallet_amount'] ?? null,
        ]);

        Log::info('Mobile Zone Transfer Web â€” initiÃ©', [
            'transfer_id'     => $transfer->id,
            'cash_out_tx_id'  => $cashOutTxId,
            'cash_in_tx_id'   => $cashInTxId,
            'sender_phone'    => $request->sender_phone,
            'recipient_phone' => $request->recipient_phone,
            'amount'          => $amount,
        ]);

        return response()->json([
            'status'          => true,
            'message'         => $this->initMessage($senderService->provider->name ?? '', $smsLink)
                . ' Le montant est en cours d\'envoi au destinataire.',
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
                'phone'      => $request->recipient_phone,
                'operator'   => $recipientService->provider->name ?? null,
                'country'    => $recipientService->country->name ?? null,
                'cash_in_id' => $cashInTxId,
                'state'      => $cashInState,
            ],
        ]);
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE â€” IPN callback (Pixel Innov â†’ Robus)
    // =========================================================================

    public function mobileTransferCallback(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $state         = $request->input('state');
        $hash          = $request->input('hash');
        $customData    = $request->input('custom_data', '');

        Log::info('Mobile Zone Transfer â€” IPN reÃ§u', $request->all());

        if (!$transactionId || !$state || !$hash) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }

        // Tester les deux clÃ©s (XOF et XAF) â€” le service n'est pas encore connu
        $pixelService = app(PixelInnovService::class);
        $hashValid = $pixelService->setXaf(false)->verifyIpnHash($transactionId, $hash)
            || $pixelService->setXaf(true)->verifyIpnHash($transactionId, $hash);
        if (!$hashValid) {
            return response()->json(['status' => 'invalid_hash'], 401);
        }

        $stateUpper = strtoupper($state);
        $isCashOut  = str_ends_with($customData, '_CASHOUT');
        $isCashIn   = str_ends_with($customData, '_CASHIN');
        $isRefund   = str_ends_with($customData, '_REFUND');

        // Trouver le transfert par le transaction_id
        $transfer = null;
        if ($isCashOut) {
            $transfer = PixelMobileZoneTransfer::where('sender_transaction_id', $transactionId)->first();
        } elseif ($isCashIn) {
            $transfer = PixelMobileZoneTransfer::where('recipient_transaction_id', $transactionId)->first();
        } elseif ($isRefund) {
            $transfer = PixelMobileZoneTransfer::where('refund_transaction_id', $transactionId)->first();
        }

        // Fallback : chercher dans les deux colonnes principales
        if (!$transfer) {
            $transfer = PixelMobileZoneTransfer::where('sender_transaction_id', $transactionId)
                ->orWhere('recipient_transaction_id', $transactionId)
                ->orWhere('refund_transaction_id', $transactionId)
                ->first();
            if ($transfer) {
                $isCashOut = $transfer->sender_transaction_id === $transactionId;
                $isCashIn  = $transfer->recipient_transaction_id === $transactionId;
                $isRefund  = $transfer->refund_transaction_id === $transactionId;
            }
        }

        if (!$transfer) {
            Log::error('Mobile Zone Transfer â€” transfert introuvable pour IPN', ['transaction_id' => $transactionId]);
            return response()->json(['status' => 'not_found'], 404);
        }

        DB::beginTransaction();
        try {
            if ($isCashOut) {
                $this->handleMobileTransferCashOutIpn($transfer, $transactionId, $stateUpper, $request->all(), $pixelService);
            } elseif ($isCashIn) {
                $publicBase = rtrim(config('services.pixel_innov.public_url'), '/');
                $this->handleMobileTransferCashInIpn($transfer, $transactionId, $stateUpper, $request->all(), $pixelService, $publicBase);
            } elseif ($isRefund) {
                $refundState = in_array($stateUpper, ['SUCCESS', 'SUCCESSFUL']) ? 'SUCCESS' : 'FAILED';
                $transfer->update(['refund_status' => $refundState]);
                Log::info('Mobile Zone Transfer â€” remboursement IPN reÃ§u', [
                    'transfer_id'    => $transfer->id,
                    'transaction_id' => $transactionId,
                    'refund_status'  => $refundState,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Mobile Zone Transfer â€” erreur IPN', [
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
        // Re-fetch avec lock pour bloquer les IPN concurrents
        $transfer = PixelMobileZoneTransfer::lockForUpdate()->find($transfer->id);
        if (!$transfer || $transfer->sender_status === 'SUCCESS') {
            return; // idempotence
        }

        if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
            $transfer->update([
                'sender_status'       => 'SUCCESS',
                'status'              => 'PROCESSING',
                'raw_response_sender' => $ipnData,
            ]);

            // Cash In dÃ©jÃ  dÃ©clenchÃ© lors de l'initialisation â€” ne pas envoyer une 2e fois
            if (!empty($transfer->recipient_transaction_id)) {
                Log::info('Mobile Zone Transfer â€” Cash In dÃ©jÃ  initiÃ© (skip IPN)', [
                    'transfer_id'              => $transfer->id,
                    'recipient_transaction_id' => $transfer->recipient_transaction_id,
                ]);
                return;
            }

            // DÃ©clencher le leg Cash In (uniquement si pas encore initiÃ©)
            $recipientService = PixelInnoveService::with(['country', 'provider'])
                ->find($transfer->recipient_pixel_service_id);

            if (!$recipientService) {
                Log::error('Mobile Zone Transfer â€” service destinataire introuvable', ['transfer_id' => $transfer->id]);
                $transfer->update(['status' => 'FAILED', 'recipient_status' => 'FAILED']);
                return;
            }

            // Utiliser la clÃ© du service destinataire pour le Cash In
            $pixelService->setXaf($recipientService->is_xaf ?? false);
            $publicBase   = rtrim(config('services.pixel_innov.public_url'), '/');
            $customRef    = $transfer->metadata['custom_ref'] ?? ('ROBUS_MZ_' . $transfer->id);

            $cashInPayload = [
                'amount'             => (int) $transfer->amount,
                'destination'        => $transfer->recipient_phone,
                'service_id'         => $recipientService->service_id,
                'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
                'custom_data'        => $customRef . '_CASHIN',
                'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
                'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
            ];
            $cashInPayload = $this->withWaveBusinessNameId($cashInPayload, $recipientService);

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
                Log::error('Mobile Zone Transfer â€” Cash In Ã©chouÃ© aprÃ¨s Cash Out OK', [
                    'transfer_id' => $transfer->id,
                    'response'    => $cashInResponse,
                ]);
                // Remboursement automatique vers l'expÃ©diteur
                $this->refundSender($transfer, $pixelService, $publicBase);
                return;
            }

            $transfer->update([
                'recipient_transaction_id' => $cashInTxId,
                'recipient_status'         => 'PENDING',
                'raw_response_recipient'   => $cashInResponse,
                'pixel_wallet_after'       => $cashInResponse['data']['p_last_wallet_amount'] ?? null,
            ]);

            Log::info('Mobile Zone Transfer â€” Cash In initiÃ© aprÃ¨s Cash Out OK', [
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
            Log::warning('Mobile Zone Transfer â€” Cash Out FAILED', [
                'transfer_id'    => $transfer->id,
                'transaction_id' => $transactionId,
            ]);
        }
    }

    private function handleMobileTransferCashInIpn(
        PixelMobileZoneTransfer $transfer,
        string $transactionId,
        string $state,
        array $ipnData,
        PixelInnovService $pixelService,
        string $publicBase
    ): void {
        // Re-fetch avec lock pour bloquer les IPN concurrents
        $transfer = PixelMobileZoneTransfer::lockForUpdate()->find($transfer->id);
        if (!$transfer || $transfer->recipient_status === 'SUCCESS') {
            return; // idempotence
        }
        // Si dÃ©jÃ  FAILED et remboursement dÃ©jÃ  initiÃ©, ne pas re-dÃ©clencher
        if ($transfer->recipient_status === 'FAILED' && !empty($transfer->refund_transaction_id)) {
            return;
        }

        if (in_array($state, ['SUCCESS', 'SUCCESSFUL'])) {
            $transfer->update([
                'recipient_status'       => 'SUCCESS',
                'status'                 => 'SUCCESS',
                'raw_response_recipient' => $ipnData,
            ]);

            // CrÃ©diter la commission sur le wallet Pixel Innov dÃ©diÃ©
            $this->creditMobileTransferCommission($transfer);

            Log::info('Mobile Zone Transfer â€” SUCCESS complet', [
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

            Log::warning('Mobile Zone Transfer â€” Cash In FAILED via IPN, remboursement automatique', [
                'transfer_id'    => $transfer->id,
                'transaction_id' => $transactionId,
            ]);

            // Rembourser l'expÃ©diteur car Cash In Ã©chouÃ© aprÃ¨s Cash Out rÃ©ussi
            $this->refundSender($transfer, $pixelService, $publicBase);
        }
    }

    private function creditMobileTransferCommission(PixelMobileZoneTransfer $transfer): void
    {
        if ((float) $transfer->total_fee <= 0) {
            return;
        }

        $walletPixel           = WalletAdmin::find(1);
        $walletPixelCommission = WalletCommission::find(1);;

        $transfer->loadMissing(['senderService', 'recipientService', 'wallet.country.currencie']);

        $senderService    = $transfer->senderService;
        $recipientService = $transfer->recipientService;

        if (!$senderService || !$recipientService) {
            throw new \Exception('Services Pixel Innov Mobile Zone introuvables pour la commission');
        }

        // â”€â”€ Commissions admin + banque â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $senderAdminCommission    = round((float) $transfer->amount      * (float) $senderService->fixed_fee    / 100, 2);
        $senderBankCommission     = round((float) $transfer->amount      * (float) $senderService->commission_percent             / 100, 2);
        $recipientAdminCommission = round((float) $transfer->amount_to_send * (float) $recipientService->commission_percent / 100, 2);
        $recipientBankCommission  = round((float) $transfer->amount_to_send * (float) $recipientService->fixed_fee          / 100, 2);

        $adminCommission = round($senderAdminCommission, 2);
        $bankCommission  = round($senderBankCommission,  2);
        $totalCommission = round($adminCommission + $bankCommission, 2);

        if ($totalCommission <= 0) {
            $totalCommission = (float) $transfer->total_fee;
            $adminCommission = $totalCommission;
            $bankCommission  = 0;
        }

        // â”€â”€ Conversion monÃ©taire â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $countryCiv       = Country::find(107);
        $walletCurrValue  = $transfer->wallet?->country?->currencie?->value ?? 1;
        $needsConversion  = $countryCiv
            && $transfer->wallet?->country?->currencie
            && (float) $countryCiv->currencie->value !== (float) $transfer->wallet->country->currencie->value;
        $conversionRate   = $needsConversion ? (float) $walletCurrValue : 1;

        // â”€â”€ Commissions sous-distributeur et compagnie â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $walletOwner      = $transfer->wallet?->sid_user_id
            ? \App\Models\Sid\SidUser::with('wallet')->find($transfer->wallet->sid_user_id)
            : null;

        $isUnder = $walletOwner && ($walletOwner->is_under_distributer || $walletOwner->is_api);

        $underCommission    = 0.0;
        $companieCommission = 0.0;
        $activeButtons      = [];
        $activeCButtons     = [];

        if ($isUnder) {
            $restrictionSvc = app(OperatorRestrictionService::class);
            $activeButtons  = $restrictionSvc->getActiveButtons($walletOwner->id);

            // RÃ©soudre la compagnie liÃ©e Ã  ce sous-distributeur
            $accountAccepted    = $walletOwner->is_accepted_companie ?? false;
            $companyId          = $walletOwner->company_id ?? null;
            $compagnieSidUserId = null;
            if ($companyId) {
                $compagnie = SidUser::whereIsCompany(true)->where('company_id', $companyId)->first();
                if ($accountAccepted && $compagnie) {
                    $compagnieSidUserId = $compagnie->id;
                }
            }
            if ($compagnieSidUserId) {
                $activeCButtons = $restrictionSvc->getActiveCButtons($compagnieSidUserId);
            }
        }

        // Commission sous-distributeur : uniquement si showRemonterPixelButton est actif
        if (
            $isUnder
            && !empty($activeButtons['showRemonterPixelButton'])
            && (float) ($senderService->commission_under_pct ?? 0) > 0
        ) {
            $underCommission = round((float) $transfer->amount * (float) $senderService->commission_under_pct / 100, 2);
        }

        // Commission compagnie : uniquement si showRemonterPixelInnovCButton est actif
        $isCompanie = $walletOwner
            && ($walletOwner->is_accepted_companie ?? false)
            && !empty($activeCButtons['showRemonterPixelInnovCButton']);

        if ($isCompanie && (float) ($senderService->commission_companie_pct ?? 0) > 0) {
            $companieCommission = round((float) $transfer->amount * (float) $senderService->commission_companie_pct / 100, 2);
        }

        // Les commissions sous-dist et compagnie sont prÃ©levÃ©es sur la part admin
        $adminCommissionConversion   = round($adminCommission   * $conversionRate, 2);
        $bankCommissionConversion    = round($bankCommission        * $conversionRate, 2);
        $underCommissionConversion   = round($underCommission       * $conversionRate, 2);
        $companieCommissionConversion = round($companieCommission   * $conversionRate, 2);
        $totalCommissionConversion   = round($totalCommission       * $conversionRate, 2);

        // â”€â”€ CrÃ©dit wallet Pixel Innov (part admin nette) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $walletPixel->increment('amount', $adminCommissionConversion);

        LedgerEntry::create([
            'transaction_id' => (string) Str::uuid(),
            'admin_com_id'   => null,
            'debit'          => 0,
            'credit'         => $adminCommissionConversion,
            'description'    => 'Commission administrateur Transfert Mobile Zone #' . $transfer->id,
            'is_uv'          => false,
            'is_pixel'       => true,
        ]);

        // â”€â”€ DÃ©bit wallet commission Pixel Innov â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        $walletPixelCommission->decrement('amount', $totalCommissionConversion);

        // â”€â”€ Commission sous-distributeur â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if ($underCommission > 0 && $walletOwner?->wallet) {

            LedgerEntry::create([
                'transaction_id' => (string) Str::uuid(),
                'admin_com_id'   => null,
                'debit'          => $underCommissionConversion,
                'credit'         => 0,
                'description'    => 'Commission sous-distributeur Transfert Mobile Zone #' . $transfer->id,
                'is_uv'          => true,
            ]);

            $transfer->update(['commissions' => $underCommission]);

            Log::info('Mobile Zone Transfer â€” commission sous-distributeur versÃ©e', [
                'transfer_id'   => $transfer->id,
                'user_id'       => $walletOwner->id,
                'amount'        => $underCommission,
                'amount_conv'   => $underCommissionConversion,
            ]);
        }

        // â”€â”€ Commission compagnie â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if ($companieCommission > 0) {
            // La commission compagnie est tracÃ©e sur le transfert ; le versement
            // effectif se fait via le tableau de bord compagnie (comme pour les
            // autres services : wallet_with->commission_companie, etc.)
            $transfer->update(['commission_companie' => $companieCommission]);

            LedgerEntry::create([
                'transaction_id' => (string) Str::uuid(),
                'admin_com_id'   => null,
                'credit'          => 0,
                'debit'         => $companieCommissionConversion,
                'description'    => 'Commission compagnie Transfert Mobile Zone #' . $transfer->id,
                'is_uv'          => true,
            ]);

            Log::info('Mobile Zone Transfer â€” commission compagnie enregistrÃ©e', [
                'transfer_id'    => $transfer->id,
                'company_id'     => $walletOwner->company_id ?? null,
                'amount'         => $companieCommission,
                'amount_conv'    => $companieCommissionConversion,
            ]);
        }
    }

    // =========================================================================
    // TRANSFERT MOBILE ZONE â€” vÃ©rification du statut
    // =========================================================================

    public function checkMobileZoneTransferStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transfer_id' => 'required|integer|exists:pixel_mobile_zone_transfers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $recovered   = false;
        $cashInError = null;
        $publicBase  = rtrim(config('services.pixel_innov.public_url'), '/');

        DB::beginTransaction();
        try {
            // lockForUpdate : empÃªche deux appels simultanÃ©s de dÃ©clencher deux Cash In
            $transfer = PixelMobileZoneTransfer::lockForUpdate()->find($request->transfer_id);

            $senderServiceForRecovery = PixelInnoveService::find($transfer->sender_pixel_service_id);
            $pixelService = app(PixelInnovService::class)->setXaf($senderServiceForRecovery->is_xaf ?? false);

            // â”€â”€ RÃ©cupÃ©ration des transferts bloquÃ©s â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            // Cas 1 : Cash Out SUCCESS mais Cash In jamais initiÃ© (recipient_transaction_id null)
            $cashOutTxId = $transfer->sender_transaction_id;
            $needsCashIn = !in_array($transfer->recipient_status, ['SUCCESS', 'PROCESSING'])
                && empty($transfer->recipient_transaction_id)
                && $cashOutTxId;

            if ($needsCashIn) {
                // VÃ©rifier si Cash Out est bien SUCCESS cÃ´tÃ© Pixel Innov
                $remoteStatus = $pixelService->checkTransactionStatus($cashOutTxId);
                $remoteState  = strtoupper($remoteStatus['data']['state'] ?? '');

                if (in_array($remoteState, ['SUCCESS', 'SUCCESSFUL'])) {
                    if ($transfer->sender_status !== 'SUCCESS') {
                        $transfer->update(['sender_status' => 'SUCCESS', 'status' => 'PROCESSING']);
                    }

                    $recipientService = PixelInnoveService::with(['country', 'provider'])
                        ->find($transfer->recipient_pixel_service_id);

                    if ($recipientService) {
                        $pixelService->setXaf($recipientService->is_xaf ?? false);
                        $customRef   = $transfer->metadata['custom_ref'] ?? ('ROBUS_MZ_' . $transfer->id);
                        $cashInPayload = [
                            'amount'             => (int) $transfer->amount_received,
                            'destination'        => $transfer->recipient_phone,
                            'service_id'         => $recipientService->service_id,
                            'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
                            'custom_data'        => $customRef . '_CASHIN',
                            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
                            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
                        ];
                        $cashInPayload = $this->withWaveBusinessNameId($cashInPayload, $recipientService);

                        $cashInResponse = $pixelService->initializeTransaction($cashInPayload);
                        $cashInTxId     = $cashInResponse['data']['transaction_id'] ?? null;
                        $cashInFailed   = ($cashInResponse['statut_code'] ?? null) !== 200
                            || empty($cashInTxId)
                            || ($cashInResponse['data']['state'] ?? null) === 'FAILED';

                        if ($cashInFailed) {
                            $cashInError = trim((string) ($cashInResponse['message'] ?? ''))
                                ?: trim((string) ($cashInResponse['data']['response'] ?? ''))
                                ?: 'Ã‰chec Cash In';

                            $transfer->update([
                                'recipient_status'       => 'FAILED',
                                'status'                 => 'FAILED',
                                'raw_response_recipient' => $cashInResponse,
                            ]);

                            Log::error('Mobile Zone Transfer â€” rÃ©cupÃ©ration Cash In Ã©chouÃ©e', [
                                'transfer_id'     => $transfer->id,
                                'cash_in_error'   => $cashInError,
                                'recipient_phone' => $transfer->recipient_phone,
                                'amount_sent'     => $transfer->amount_received,
                            ]);

                            // Rembourser l'expÃ©diteur â€” Cash Out rÃ©ussi mais Cash In impossible
                            $this->refundSender($transfer, $pixelService, $publicBase);
                        } else {
                            $transfer->update([
                                'recipient_transaction_id' => $cashInTxId,
                                'recipient_status'         => 'PENDING',
                                'status'                   => 'PROCESSING',
                                'raw_response_recipient'   => $cashInResponse,
                            ]);
                            $recovered = true;

                            Log::info('Mobile Zone Transfer â€” Cash In dÃ©clenchÃ© en rÃ©cupÃ©ration', [
                                'transfer_id'   => $transfer->id,
                                'cash_in_tx_id' => $cashInTxId,
                                'amount_sent'   => $transfer->amount_received,
                            ]);
                        }
                    }
                }

                $transfer->refresh();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Mobile Zone Transfer â€” erreur rÃ©cupÃ©ration', [
                'transfer_id' => $request->transfer_id,
                'error'       => $e->getMessage(),
            ]);
            return response()->json(['status' => false, 'message' => 'Erreur lors de la rÃ©cupÃ©ration.'], 500);
        }

        return response()->json([
            'status'           => true,
            'local_status'     => $transfer->status,
            'sender_status'    => $transfer->sender_status,
            'recipient_status' => $transfer->recipient_status,
            'transfer_id'      => $transfer->id,
            'amount'           => $transfer->amount,
            'fees'             => $transfer->total_fee,
            'amount_received'  => $transfer->amount_received,
            'created_at'       => $transfer->created_at,
            'recovered'        => $recovered,
            'cash_in_error'    => $cashInError,
        ]);
    }

    public function checkMobileZoneTransferStatusWeb(Request $request)
    {
        return $this->checkMobileZoneTransferStatus($request);
    }

    /**
     * Message de confirmation adaptÃ© selon le provider
     * Orange CI â†’ le client valide via un lien SMS (pas d'OTP)
     * Autres    â†’ le client valide via USSD ou OTP selon le provider
     */
    private function initMessage(string $providerName, ?string $smsLink): string
    {
        $name = strtolower($providerName);

        if (str_contains($name, 'orange') && $smsLink) {
            return 'Un lien de validation a Ã©tÃ© envoyÃ© par SMS au client. Il doit cliquer dessus pour confirmer.';
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

        return 'Transaction initiÃ©e. Le client doit la valider sur son tÃ©lÃ©phone.';
    }

    private function isWaveService(?PixelInnoveService $service): bool
    {
        $providerName = strtolower((string) ($service?->provider?->name ?? ''));
        $serviceName = strtolower((string) ($service?->name ?? ''));

        return str_contains($providerName, 'wave') || str_contains($serviceName, 'wave');
    }

    private function withWaveBusinessNameId(array $payload, ?PixelInnoveService $service): array
    {
        if (!$this->isWaveService($service)) {
            return $payload;
        }

        $businessNameId = trim((string) config('services.pixel_innov.wave_business_name_id', ''));
        if ($businessNameId === '') {
            Log::warning('Pixel Innov â€” business_name_id Wave manquant', [
                'service_id' => $service?->service_id,
                'provider'   => $service?->provider?->name,
            ]);
            return $payload;
        }

        $payload['business_name_id'] = $businessNameId;
        return $payload;
    }

    private function extractPixelPaymentLink(array $response): ?string
    {
        $data = $response['data'] ?? [];
        if (!is_array($data)) {
            return null;
        }

        foreach (['sms_link', 'sms_link1', 'sms_link2', 'payment_link', 'payment_url', 'link', 'redirect_url'] as $field) {
            $value = $data[$field] ?? null;
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    private function currencyZoneColumn(?string $currency): ?string
    {
        return match ($currency) {
            'XOF' => 'is_xof',
            'XAF' => 'is_xaf',
            'CDF' => 'is_cdf',
            'GMD' => 'is_gmd',
            default => null,
        };
    }

    /**
     * Rembourse l'expÃ©diteur via un Cash In vers son numÃ©ro quand le Cash In destinataire a Ã©chouÃ©.
     * Trouve le service Cash In correspondant au mÃªme opÃ©rateur que le Cash Out expÃ©diteur.
     */
    private function refundSender(
        PixelMobileZoneTransfer $transfer,
        PixelInnovService $pixelService,
        string $publicBase
    ): array {
        $senderPixelService = PixelInnoveService::find($transfer->sender_pixel_service_id);

        if (!$senderPixelService) {
            Log::error('Mobile Zone Transfer â€” remboursement impossible : service expÃ©diteur introuvable', [
                'transfer_id' => $transfer->id,
            ]);
            return ['initiated' => false, 'error' => 'Service expÃ©diteur introuvable'];
        }

        // Chercher le service Cash In du mÃªme opÃ©rateur (mÃªme provider_id) pour le remboursement
        $refundService = PixelInnoveService::where('provider_id', $senderPixelService->provider_id)
            ->where('operation_type', 'Cash In')
            ->where('status', 1)
            ->first();

        if (!$refundService) {
            Log::error('Mobile Zone Transfer â€” remboursement impossible : aucun service Cash In pour cet opÃ©rateur', [
                'transfer_id' => $transfer->id,
                'provider_id' => $senderPixelService->provider_id,
            ]);
            $transfer->update(['refund_status' => 'FAILED']);
            return ['initiated' => false, 'error' => 'Service de remboursement introuvable'];
        }

        // Utiliser la clÃ© correspondant au service de remboursement
        $pixelService->setXaf($refundService->is_xaf ?? false);

        $customRef    = $transfer->metadata['custom_ref'] ?? ('ROBUS_MZ_' . $transfer->id);
        $refundPayload = [
            'amount'             => (int) $transfer->amount,
            'destination'        => $transfer->sender_phone,
            'service_id'         => $refundService->service_id,
            'ipn_url'            => $publicBase . '/payment_pixelInnov/mobile-transfer/callback',
            'custom_data'        => $customRef . '_REFUND',
            'redirect_url'       => $publicBase . '/payment_pixelInnov/success',
            'redirect_error_url' => $publicBase . '/payment_pixelInnov/error',
        ];
        $refundPayload = $this->withWaveBusinessNameId($refundPayload, $refundService);

        $refundResponse = $pixelService->initializeTransaction($refundPayload);
        $refundTxId     = $refundResponse['data']['transaction_id'] ?? null;
        $refundOk       = ($refundResponse['statut_code'] ?? null) === 200 && !empty($refundTxId);

        if ($refundOk) {
            $transfer->update([
                'status'               => 'REFUNDED',
                'refund_transaction_id' => $refundTxId,
                'refund_status'        => 'PENDING',
                'refunded_at'          => now(),
                'pixel_wallet_after'   => $refundResponse['data']['p_last_wallet_amount'] ?? null,
            ]);

            Log::info('Mobile Zone Transfer â€” remboursement initiÃ©', [
                'transfer_id'    => $transfer->id,
                'refund_tx_id'   => $refundTxId,
                'sender_phone'   => $transfer->sender_phone,
                'amount'         => $transfer->amount,
            ]);

            return ['initiated' => true];
        }

        $refundErr = trim((string) ($refundResponse['message'] ?? ''))
            ?: trim((string) ($refundResponse['data']['response'] ?? ''))
            ?: 'Ã‰chec du remboursement';

        $transfer->update(['refund_status' => 'FAILED']);

        Log::error('Mobile Zone Transfer â€” remboursement Ã©chouÃ©', [
            'transfer_id'     => $transfer->id,
            'refund_response' => $refundResponse,
            'sender_phone'    => $transfer->sender_phone,
        ]);

        return ['initiated' => false, 'error' => $refundErr];
    }
}