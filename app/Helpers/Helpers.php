<?php

use App\Models\AppCloseCom;
use App\Models\AppCloseMaster;
use App\Models\AppMasterStatus;
use App\Models\CloseApp;
use App\Models\Banks\BankCard;
use App\Models\CloseAppWeb;
use App\Models\Costs\DepositCost;
use App\Models\Custumers\Country;
use App\Models\OperatorService;
use App\Models\Payments\PaymentReload;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\IntegrationClient;
use App\Http\Services\InterHomeService;
use App\Models\Sid\SidUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use App\Http\Services\AuthenticationService;
use App\Models\AppStatus;

if (!function_exists('formatDisplayAmount')) {
    /** Format a monetary value for display without changing its stored precision. */
    function formatDisplayAmount($amount): string
    {
        if ($amount === null || $amount === '') {
            return '0,00';
        }

        // Keep explicit placeholders used by transaction summaries.
        return is_numeric($amount) ? number_format((float) $amount, 2, ',', ' ') : (string) $amount;
    }
}


if (!function_exists('getAppStatus')) {
    function getAppStatus()
    {
        return app(AuthenticationService::class)->appDispoRobus(AppStatus::class);
    }
}

if (!function_exists('walletByNumber')) {
    // Wallet par numéro de compte, mémorisé pendant la requête (évite les N+1 dans les tableaux)
    function walletByNumber($number)
    {
        return app(\App\Support\WalletLookup::class)->find('wallet_id', $number);
    }
}

if (!function_exists('walletById')) {
    function walletById($id)
    {
        return app(\App\Support\WalletLookup::class)->find('id', $id);
    }
}

if (!function_exists('primeWallets')) {
    // Charge en une requête les wallets de toutes les lignes d'une liste avant la boucle d'affichage
    function primeWallets($items, string $attribute, string $column = 'wallet_id'): void
    {
        if (is_iterable($items)) {
            app(\App\Support\WalletLookup::class)->prime($items, $attribute, $column);
        }
    }
}

if (!function_exists('countryById')) {
    // Pays par id, mémorisé pendant la requête : les formulaires le relisent plusieurs fois par rendu
    function countryById($id)
    {
        if ($id === null || $id === '') {
            return null;
        }

        return app(\App\Support\RequestMemo::class)->remember('country:' . $id, function () use ($id) {
            return Country::where('id', $id)->first();
        });
    }
}

if (!function_exists('safeNotifyMany')) {
    function safeNotifyMany($users, Notification $notification): void
    {
        try {

            if (empty($users) || count($users) === 0) {
                return;
            }

            NotificationFacade::send($users, $notification);
        } catch (\Throwable $e) {

            Log::warning('Notification multiple ignorée', [
                'users_count' => is_countable($users) ? count($users) : null,
                'notification' => get_class($notification),
                'error' => $e->getMessage()
            ]);
        }
    }
}

if (!function_exists('safeEvent')) {

    /**
     * Diffuse un événement sans jamais faire échouer la requête : une panne de diffusion (Pusher lent,
     * quota, payload trop gros…) ne doit pas transformer une opération déjà enregistrée en erreur 500.
     */
    function safeEvent($event): void
    {
        try {
            event($event);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Diffusion ignorée (événement)', [
                'event' => is_object($event) ? get_class($event) : (string) $event,
                'exception' => get_class($e),
                'error' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);
        }
    }
}

if (!function_exists('safeNotify')) {

    function safeNotify($user, Notification $notification): void
    {
        try {

            if (!$user) {
                return;
            }

            $user->notify($notification);
        } catch (\Throwable $e) {

            Log::warning('Notification ignorée (quota ou erreur)', [
                'user_id' => optional($user)->id,
                'notification' => get_class($notification),
                'error' => $e->getMessage()
            ]);
        }
    }
}


if (! function_exists('sendTryCashMessage')) {
    /**
     * Envoie un message via TryCash
     */
    function sendTryCashMessage($phone, $message)
    {
        $encode = substr($phone, -10);


        try {

            Http::get("https://app.smspro.africa/api/http/sms/send", [
                'recipient' => "225$encode",
                'sender_id' => "ROBUS",
                'message' => \App\Support\SmsMessage::normalize($message),
                'api_token' => "20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af",
            ]);

        } catch (\Exception $e) {
            // Ici tu peux logger l'erreur pour analyse
            Log::error("Erreur TryCash: " . $e->getMessage());
            return false;
        }
    }
}

if (! function_exists('applyRestrictionMap')) {
    function applyRestrictionMap($operators, &$component)
    {
        $restrictionMapTransaction = [
            'TRANSFERT CREDIT' => 'showCreditButton',
            'PAIEMENT DE FACTURE' => 'showPaiementButton',
            'TRANSFERT INTERNATIONNAL MOBILE MONEY' => 'showInterButton',
            'RECHARGEMENT DE CARTE' => 'showRechargementButton',
            'VERSEMENT BANCAIRE' => 'showVirementButton',
            'RETRAIT' => 'showRetraitButton',
            'DEPOTS' => 'showDepotButton',
            'MOBILE MONEY' => 'showMobileButton',
            'DEPOT ET TRANSFERT MOBILE MONEY' => 'showMoneyButton',
            'TRANSFERT A INTER' => 'showTransacInterButton',
            'CREDIT TELEPHONIQUE A INTER' => 'showTelInterButton',
            'VERSEMENT BANCAIRE A INTER' => 'showBancInterButton',
            'RECHARGEMENT DE CARTE A INTER' => 'showCarteInterButton',
            'PAIMENT FACTURE A INTER' => 'showFacInterButton',
        ];

        foreach ($operators as $operator) {
            $restrictions = explode(',', $operator->name);
            foreach ($restrictionMapTransaction as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $component->{$buttonVar} = true;
                }
            }
        }
    }
}


if (!function_exists('appDispoRobus')) {
    function appDispoRobus($model)
    {

        Artisan::call('app:close-status');

        // Récupérer le statut de l'application
        $appStatus = $model::latest()->first();

        // Vérifier si l'application est fermée
        if ($appStatus && $appStatus->status_app === 'closed') {

            $robus_hour_begin = $appStatus->time_start;
            $robus_hour_end = $appStatus->time_end;
            $robus_hour_description = $appStatus->description;

            return [
                'success' => false,
                'message' => "L'application ROBUS Sous distributeurs était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
            ];
        }

        return ['success' => true];
    }
}


if (!function_exists('getCloseMasterRobusData')) {
    /**
     * Récupère et retourne les données de AppCloseMaster triées par ID.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getCloseMasterRobusData()
    {
        return AppCloseMaster::orderBy('id', 'asc')->get();
    }
}

if (!function_exists('sidUserInfo')) {
    function sidUserInfo($id)
    {
        // Le même opérateur revient sur de nombreuses lignes : une seule lecture par requête
        return app(\App\Support\RequestMemo::class)->remember('sidUserInfo:' . $id, function () use ($id) {
            $user = SidUser::find($id);
            if ($user) {
                return $user->firstname . ' ' . $user->lastname;
            }
            return null;
        });
    }
}


function adjustFeesWithRest($fees, $amount)
{


    $fees = round($fees);
    $lastes = $fees % 100;
    $adjustfees = $fees;
    $rest = $fees - $lastes;
    if ($rest > 0) {

        if ($rest == 1) {
            $adjustfees += 5;
        } elseif ($rest >= 4 && $rest <= 6) {
            $adjustfees += 5;
        } elseif ($rest >= 10 && $rest < 30) {
            $adjustfees += 10;
        } elseif ($rest >= 30 && $rest < 50) {
            $adjustfees += 20;
        } elseif ($rest == 50) {
            $adjustfees += 50;
        } elseif ($rest >= 60 && $rest <= 80) {
            $adjustfees += 70;
        } elseif ($rest > 80) {
            $adjustfees += 100;
        } elseif ($adjustfees == 0) {
            $adjustfees =  $fees;
        }
    } elseif ($rest == 0 && $adjustfees == 0) {
        $adjustfees = 0;
        $amount = 0;
    } else {
        $adjustfees = $adjustfees;
    }

    $amountReal = $amount - $fees;

    return [
        'adjust' => $adjustfees,
        'rest' => $rest,
        'amountSend' => $amountReal,
    ];
}

function cumulOrderCard()
{
    $interHomeService = app(InterHomeService::class);

    // Récupérer tous les counts
    $counts = [
        'bankCard' =>  $interHomeService->getCountByStatus(BankCard::class, 'wallet_payments', 0),
    ];

    // Calculer le cumul total
    $total = array_sum($counts);

    return $total;
}

function cumulTransaction()
{
    // Le menu et le menu mobile appellent ce cumul sur chaque page : calculé une seule fois par requête
    return app(\App\Support\RequestMemo::class)->remember('cumulTransaction:' . optional(sabdy())->id, function () {
        return cumulTransactionCount();
    });
}

function cumulTransactionCount()
{
    $interHomeService = app(InterHomeService::class);

    // Récupérer tous les counts
    $counts = [
        'push' => $interHomeService->getPushCountByStatus('PENDING'),
        'transaction' => $interHomeService->getTransactionCountByStatus('PENDING'),
        'payment' => $interHomeService->getPaymentCountByStatus('PENDING'),
        'bank' => $interHomeService->getVirementCountByStatus('PENDING'),
        'inter' => $interHomeService->getInterCountByStatus('PENDING'),
        'card' => $interHomeService->getDepositCountByStatus('PENDING'),
        'phone' => $interHomeService->getPhoneCreditCountByStatus('PENDING'),
        'xbet' => $interHomeService->getXbetCountByStatus('PENDING'),
        'djamo' => $interHomeService->getDjamoCountByStatus('PENDING'),
        'bank_send' => $interHomeService->getBankSendCountByStatus('PENDING'),
        'versement' => $interHomeService->getVersementCountByStatus('PENDING'),
        'tresortMoney' => $interHomeService->getTresortMoneyCountByStatus('PENDING'),
    ];

    // Calculer le cumul total
    $total = array_sum($counts);

    return $total; // Retourne le cumul total
}


if (!function_exists('asset_link_url')) {
    function asset_link_url($path)
    {

        if (strpos($path, 'public/') === 0) {
            return asset($path);
        }

        if (strpos($path, 'image_inters') !== false) {
            return asset('public/' . $path);
        }

        return asset($path);
    }
}

function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
{

    $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
        ->whereBetween($transaction_days, [Carbon::today(), Carbon::today()->endOfDay()])
        ->where($wallet_status, $status);

    if (sabdy()->is_distributer) {
        $query->where($walletMy, sabdy()->id);
    } elseif (sabdy()->is_under_distributer) {
        $query->where(function ($query) use ($wallet_transactions) {
            $query->where($wallet_transactions, sabdy()->wallet->id);
        });
    } else {

        $query->where(function ($query) use ($walletMy) {
            $query->where($walletMy, sabdy()->id)
                ->orWhere($walletMy, '!=', sabdy()->id);
        })
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                $query->where('wallets.country_id', '!=', $countryId)
                    ->orWhere('wallets.country_id', '=', $countryId);
            });
    }

    return $query->count();
}

function reloadCardPercent()
{

    return DepositCost::whereId(2)->first();
}

function SearchWalletRobus($num_wallet)
{
    return   Wallet::where('wallet_id',  $num_wallet)->first();
}

function provideId($id)
{
    return Provider::find($id);
}

function walletId($id)
{
    return Wallet::find($id);
}

function fielValidated()
{
    return IntegrationClient::where('sid_user_id', propect()->id)->first();
}
function paymentId($id)
{
    return WalletReload::find($id);
}

function firm(): ?SidUser
{
    return Auth::guard('company')->user();
}

function propect()
{
    return Auth::guard('integration')->user();
}


function integrate()
{
    return Auth::guard('api_client')->user();
}

if (!function_exists('auth_user')) {

    function auth_user()
    {
        if (Auth::guard('api')->check()) {
            return Auth::guard('api')->user();
        }

        if (Auth::guard('api_client')->check()) {
            return Auth::guard('api_client')->user();
        }

        return null;
    }
}


function sabdy()
{

    return Auth::guard('web')->user();
}


function sabdyVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur', 'com_zone'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

function getCountriesForUser($user)
{
    // Appelé par le menu et le menu mobile de chaque page : une seule lecture par requête
    return app(\App\Support\RequestMemo::class)->remember('countriesForUser:' . $user->id, function () use ($user) {
        $isDistributor = $user->wallet->sidUser->is_distributor;

        $countries = Country::whereRobus(true)
            ->orderBy('name', 'asc');

        if ($isDistributor) {
            $countries->where('id', $user->wallet->country->id);
        }

        return $countries->get();
    });
}

function getCountriesForVisitorsUser($user)
{
    // Appelé par le menu et le menu mobile de chaque page : une seule lecture par requête
    return app(\App\Support\RequestMemo::class)->remember('countriesForVisitorsUser', function () {
        return Country::whereRobus(true)->whereId(107)->first();
    });
}

if (!function_exists('appDispoRobus')) {
    function appDispoRobus($model)
    {
        Artisan::call('app:close-company-status');

        $appStatus = $model::latest()->first();

        if ($appStatus && $appStatus->status_app === 'closed') {
            $robus_hour_begin = $appStatus->time_start;
            $robus_hour_end = $appStatus->time_end;
            $robus_hour_description = $appStatus->description;

            return [
                'success' => false,
                'message' => "L'application ROBUS Compagnie était ouverte aujourd'hui de {$robus_hour_begin} à {$robus_hour_end} et est fermée pour cause de {$robus_hour_description}.",
            ];
        }

        return ['success' => true];
    }
}


function getOperators($userId)
{

    return OperatorService::where('sid_user_id', $userId)
        ->pluck('operator_id')
        ->toArray();
}

function getReloadCartes()
{

    return Provider::whereStatus(true)->where('provider_type_id',  5)
        ->orderBy('name', 'asc')->get();
}

function getReloadMasterCards()
{

    return Provider::whereStatus(true)->where('provider_type_id',  5)->where('id', '!=', 91)
        ->orderBy('name', 'asc')->get();
}

function getProviderInters()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->get();
}

function getReloadPushs()
{

    return Provider::whereStatus(true)->whereId(92)->first();
}

function getAppCloseWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

    return CloseAppWeb::whereStatus(true)->whereId($currentDaySearch)->first();
}

function getAppCloseMasterWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

    return AppCloseMaster::whereStatus(true)->whereId($currentDaySearch)->first();
}

function getAppCloseZoneWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;

    return AppCloseCom::whereStatus(true)->whereId($currentDaySearch)->first();
}


function getAppCloseTomorowWeb()
{

    $currentDayOfWeek = Carbon::now()->dayOfWeek;

    $currentDaySearch = $currentDayOfWeek == 0 ? 7 : $currentDayOfWeek;


    $add_days =   $currentDaySearch + 1;

    $days_decremente =   $add_days == 8 ? 1 : $add_days;

    return CloseAppWeb::whereStatus(true)->whereId($days_decremente)->first();
}



function getReloadPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->get();
}

function getReloadLimitPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  8)->where('id', '!=', 51)
        ->orderBy('name', 'asc')->limit(9)->get();
}


function getReloadMoneysPaiements()
{

    return Provider::whereStatus(true)->where('provider_type_id',  1)->where('id', '!=', 9)->where('id', '!=', 6)
        ->orderBy('name', 'asc')->get();
}

function getMobileTransferServicesGrouped()
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

    return \App\Models\PixelInnoveService::with(['country', 'provider'])
        ->where('status', true)
        ->orderBy('name')
        ->get()
        ->groupBy('country_id')
        ->map(function ($items) use ($providerImageUrl) {
            $first   = $items->first();
            $country = optional($first->country);
            $iso2    = $country->code ? strtoupper($country->code) : '';

            $formatProvider = function ($s) use ($providerImageUrl) {
                return [
                    'id'                   => $s->id,
                    'provider_id'          => $s->provider_id,
                    'name'                 => optional($s->provider)->name ?? $s->name,
                    'image'                => $providerImageUrl(optional($s->provider)->image),
                    'service_id'           => $s->service_id,
                    'sender_service_id'    => $s->service_id,
                    'recipient_service_id' => $s->service_id,
                    'operation_type'       => $s->operation_type,
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
                'country_id'          => $first->country_id,
                'country'             => $country->name,
                'country_iso2'        => $iso2,
                'country_indicatif'   => $country->indecative ?? '',
                'providers'           => $cashOutProviders,
                'cash_out_providers'  => $cashOutProviders,
                'cash_in_providers'   => $cashInProviders,
            ];
        })
        ->filter(fn($country) => $country['cash_out_providers']->isNotEmpty() || $country['cash_in_providers']->isNotEmpty())
        ->values();
}

function sabdyHomeVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur' , 'com_zone'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}

function sabdyWebVerify()
{
    $allowedRoles = ['root', 'Distributeur', 'superviseur',  'sous-distributeur' , 'com_zone'];

    $user = Auth::guard('web')->user();

    if ($user && $user->hasAnyRole($allowedRoles)) {
        return true;
    } else {
        return false;
    }
}


function sabdyMasterVerify()
{


    $user = Auth::guard('master')->user();

    if ($user && $user->hasRole('master')) {
        return true;
    } else {
        return false;
    }
}

function sabdyIntegrationVerify()
{

    $user = Auth::guard('integration')->user();

    if ($user && $user->hasRole('integration')) {
        return true;
    } else {
        return false;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path)
    {
        // Le préfixe public/ est nécessaire uniquement avec le point d'entrée à la racine.
        $path = ltrim((string) $path, '/');
        $script = request()->server('SCRIPT_FILENAME');
        $usesRootEntry = $script && realpath($script) === realpath(base_path('index.php'));
        $url = asset(($usesRootEntry ? 'public/' : '') . $path);

        // ?v=<date de modification> : le navigateur garde le fichier en cache (voir
        // public/.htaccess) et le recharge dès qu'il change.
        if ($path !== '' && ! str_contains($path, '?') && is_file($file = public_path($path))) {
            $url .= '?v=' . filemtime($file);
        }

        return $url;
    }
}

if (!function_exists('vite_asset')) {
    function vite_asset($entry)
    {
        // Le nom des fichiers compilés change à chaque build : on le lit dans le manifeste
        static $manifest = null;

        if ($manifest === null) {
            $path = public_path('build/manifest.json');
            $manifest = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
        }

        return isset($manifest[$entry]['file']) ? asset_url('build/' . $manifest[$entry]['file']) : '';
    }
}


function sabdyCompanyVerify()
{

    $user = Auth::guard('company')->user();

    if ($user && $user->hasRole('societe')) {
        return true;
    } else {
        return false;
    }
}

function master()
{
    return Auth::guard('master')->user();
}

if (!function_exists('sendOtpSms')) {
    function sendOtpSms($phone_user, $otpSend)
    {

        $response = Http::get("https://app.smspro.africa/api/http/sms/send", [
            'recipient' => "225$phone_user",
            'sender_id' => "Bonjour doc",
            'message' => \App\Support\SmsMessage::normalize($otpSend),
            'api_token' => "21|cDXu8PK2lD3MaANrDc8qzeyMoG9ZohPVmSwphAQwe7e6eaac",
        ]);

        return $response;
    }
}


function comzone()
{
    return Auth::guard('web')->user();
}

// function robus()
// {
//     return Auth::guard('api')->user();
// }

if (!function_exists('robus')) {

    function robus()
    {

        $guards = ['api', 'api_client'];

        foreach ($guards as $guard) {
            try {
                if (Auth::guard($guard)->check()) {
                    return Auth::guard($guard)->user();
                }
            } catch (\Throwable $e) {
                // Log en cas d’erreur de guard manquant ou cassé
                // \Log::warning("Guard '$guard' non valide : " . $e->getMessage());
            }
        }

        return null;
    }
}


function getOpenRobus()
{
    return CloseApp::orderBy('id', 'asc')->get();
}
function json_message_response($status, $message, $status_code)
{
    return response()->json(['success' => $status, 'message' => $message, 'status_code' => $status_code], $status_code);
}

function json_custom_response($response, $status_code = 200)
{
    return response()->json($response, $status_code);
}

function json_list_response($data)
{
    return response()->json(['data' => $data]);
}
